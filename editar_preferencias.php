<?php
include 'componentes/headermain.php';
require_once 'conexion.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_id = $_SESSION["id_usuario"];
$mensaje = "";
$tipo_mensaje = "";

try {
    $conexion = Conexion::Conectar();

    // 2. PROCESAR EL FORMULARIO (GUARDAR DATOS)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $actores_seleccionados = $_POST['actores'] ?? [];
        $generos_seleccionados = $_POST['generos'] ?? [];
        $directores_seleccionados = $_POST['directores'] ?? []; // NUEVO

        // Validación de servidor: Mínimo 5 de cada uno
        if (count($actores_seleccionados) < 5 || count($generos_seleccionados) < 5 || count($directores_seleccionados) < 5) {
            $mensaje = "Debes seleccionar al menos 5 actores, 5 géneros y 5 directores.";
            $tipo_mensaje = "error";
        } else {
            try {
                // Usamos una TRANSACCIÓN
                $conexion->beginTransaction();

                // --- A. ACTORES ---
                $sqlDelActor = "DELETE FROM gusto_actor WHERE Usuario_id_usuario = :uid";
                $stmtDelA = $conexion->prepare($sqlDelActor);
                $stmtDelA->execute([':uid' => $usuario_id]);

                $sqlInsActor = "INSERT INTO gusto_actor (Usuario_id_usuario, actor_id_actor) VALUES (:uid, :aid)";
                $stmtInsA = $conexion->prepare($sqlInsActor);
                foreach ($actores_seleccionados as $actor_id) {
                    $stmtInsA->execute([':uid' => $usuario_id, ':aid' => $actor_id]);
                }

                // --- B. GÉNEROS ---
                $sqlDelGen = "DELETE FROM gusto_genero WHERE Usuario_id_usuario = :uid";
                $stmtDelG = $conexion->prepare($sqlDelGen);
                $stmtDelG->execute([':uid' => $usuario_id]);

                $sqlInsGen = "INSERT INTO gusto_genero (Usuario_id_usuario, genero_id_genero) VALUES (:uid, :gid)";
                $stmtInsG = $conexion->prepare($sqlInsGen);
                foreach ($generos_seleccionados as $genero_id) {
                    $stmtInsG->execute([':uid' => $usuario_id, ':gid' => $genero_id]);
                }

                // --- C. DIRECTORES (NUEVO) ---
                $sqlDelDir = "DELETE FROM gusta_director WHERE Usuario_id_usuario = :uid";
                $stmtDelD = $conexion->prepare($sqlDelDir);
                $stmtDelD->execute([':uid' => $usuario_id]);

                $sqlInsDir = "INSERT INTO gusta_director (Usuario_id_usuario, director_id_director) VALUES (:uid, :did)";
                $stmtInsD = $conexion->prepare($sqlInsDir);
                foreach ($directores_seleccionados as $director_id) {
                    $stmtInsD->execute([':uid' => $usuario_id, ':did' => $director_id]);
                }

                $conexion->commit(); // Confirmar cambios
                $mensaje = "¡Todas tus preferencias han sido actualizadas!";
                $tipo_mensaje = "success";

            } catch (Exception $e) {
                $conexion->rollBack(); // Si falla algo, deshacer todo
                $mensaje = "Error al guardar: " . $e->getMessage();
                $tipo_mensaje = "error";
            }
        }
    }

    // 3. OBTENER DATOS PARA MOSTRAR EN PANTALLA
    
    // A. Obtener TODOS los items
    $stmtAllActores = $conexion->query("SELECT id_actor, nombre FROM actor ORDER BY nombre ASC");
    $todos_actores = $stmtAllActores->fetchAll(PDO::FETCH_ASSOC);

    $stmtAllGeneros = $conexion->query("SELECT id_genero, nombre FROM genero ORDER BY nombre ASC");
    $todos_generos = $stmtAllGeneros->fetchAll(PDO::FETCH_ASSOC);

    $stmtAllDirectores = $conexion->query("SELECT id_director, nombre FROM director ORDER BY nombre ASC"); // NUEVO
    $todos_directores = $stmtAllDirectores->fetchAll(PDO::FETCH_ASSOC);

    // B. Obtener lo que el usuario YA tiene seleccionado (IDs)
    $stmtMisActores = $conexion->prepare("SELECT actor_id_actor FROM gusto_actor WHERE Usuario_id_usuario = :uid");
    $stmtMisActores->execute([':uid' => $usuario_id]);
    $mis_actores_ids = $stmtMisActores->fetchAll(PDO::FETCH_COLUMN); 

    $stmtMisGeneros = $conexion->prepare("SELECT genero_id_genero FROM gusto_genero WHERE Usuario_id_usuario = :uid");
    $stmtMisGeneros->execute([':uid' => $usuario_id]);
    $mis_generos_ids = $stmtMisGeneros->fetchAll(PDO::FETCH_COLUMN);

    $stmtMisDirectores = $conexion->prepare("SELECT director_id_director FROM gusta_director WHERE Usuario_id_usuario = :uid"); // NUEVO
    $stmtMisDirectores->execute([':uid' => $usuario_id]);
    $mis_directores_ids = $stmtMisDirectores->fetchAll(PDO::FETCH_COLUMN);

} catch (Exception $e) {
    $mensaje = "Error de conexión: " . $e->getMessage();
    $tipo_mensaje = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Preferencias - CineMax</title>
    <link rel="stylesheet" href="./styles/perfil.css" />
    <link rel="stylesheet" href="styles/config.css" />
    
    <style>
        /* Estilos específicos para la selección múltiple */
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
            max-height: 250px; /* Un poco más pequeño para que quepan los 3 */
            overflow-y: auto; 
            padding: 10px;
            background: #1a1a1a;
            border-radius: 8px;
            border: 1px solid #333;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            background: #252525;
            border-radius: 5px;
            transition: background 0.2s;
            cursor: pointer;
        }

        .checkbox-wrapper:hover {
            background: #333;
        }

        .checkbox-wrapper input[type="checkbox"] {
            accent-color: #e50914; 
            transform: scale(1.2);
            cursor: pointer;
        }

        .checkbox-wrapper label {
            cursor: pointer;
            width: 100%;
            font-size: 0.95rem;
            color: #ddd;
        }

        .counter-badge {
            background-color: #333;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            color: #fff;
            margin-left: 10px;
            border: 1px solid #555;
        }
        
        .counter-badge.valid { border-color: #2ecc71; color: #2ecc71; }
        .counter-badge.invalid { border-color: #e50914; color: #e50914; }

        .btn-save {
            background-color: #e50914;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 20px;
            width: 100%;
        }
        .btn-save:disabled {
            background-color: #555;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .alert.success { background-color: #2ecc71; color: #fff; }
        .alert.error { background-color: #e74c3c; color: #fff; }
    </style>
</head>
<body>
    
    <?php navheader("Preferencias", $usuario_id)?>

    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container breadcrumb-content">
            <a href="perfil.php" class="crumb-link">Perfil</a>
            <span class="crumb-current">> Editar Preferencias</span>
        </div>
    </nav>

    <main class="main-content container">
        
        <section class="profile-header">
            <div class="user-details">
                <h1>Tus Gustos</h1>
                <p>Personaliza tus recomendaciones.</p>
            </div>
        </section>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="preferencesForm">
            <div class="info-grid">
                
                <article class="card" style="grid-column: span 2;">
                    <div class="card-header">
                        <div class="card-title">
                            <img class="icon-md" src="./imgs/icons/reproducir.svg" alt=""> 
                            <h2>Actores Favoritos</h2>
                            <span id="actor-counter" class="counter-badge invalid">0/5 Seleccionados</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="instruction-text">Selecciona al menos 5 actores:</p>
                        <div class="selection-grid">
                            <?php foreach ($todos_actores as $actor): ?>
                                <?php $isChecked = in_array($actor['id_actor'], $mis_actores_ids) ? 'checked' : ''; ?>
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="actores[]" id="actor_<?php echo $actor['id_actor']; ?>" 
                                           value="<?php echo $actor['id_actor']; ?>" class="actor-check" <?php echo $isChecked; ?>>
                                    <label for="actor_<?php echo $actor['id_actor']; ?>"><?php echo htmlspecialchars($actor['nombre']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>

                <article class="card" style="grid-column: span 2;">
                    <div class="card-header">
                        <div class="card-title">
                            <img class="icon-md" src="./imgs/icons/mas.svg" alt=""> 
                            <h2>Géneros Favoritos</h2>
                            <span id="genre-counter" class="counter-badge invalid">0/5 Seleccionados</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="instruction-text">Selecciona al menos 5 géneros:</p>
                        <div class="selection-grid">
                            <?php foreach ($todos_generos as $genero): ?>
                                <?php $isChecked = in_array($genero['id_genero'], $mis_generos_ids) ? 'checked' : ''; ?>
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="generos[]" id="genero_<?php echo $genero['id_genero']; ?>" 
                                           value="<?php echo $genero['id_genero']; ?>" class="genre-check" <?php echo $isChecked; ?>>
                                    <label for="genero_<?php echo $genero['id_genero']; ?>"><?php echo htmlspecialchars($genero['nombre']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>

                <article class="card" style="grid-column: span 2;">
                    <div class="card-header">
                        <div class="card-title">
                            <img class="icon-md" src="./imgs/icons/vector-22.svg" alt=""> <h2>Directores Favoritos</h2>
                            <span id="director-counter" class="counter-badge invalid">0/5 Seleccionados</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="instruction-text">Selecciona al menos 5 directores:</p>
                        <div class="selection-grid">
                            <?php foreach ($todos_directores as $director): ?>
                                <?php $isChecked = in_array($director['id_director'], $mis_directores_ids) ? 'checked' : ''; ?>
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" name="directores[]" id="director_<?php echo $director['id_director']; ?>" 
                                           value="<?php echo $director['id_director']; ?>" class="director-check" <?php echo $isChecked; ?>>
                                    <label for="director_<?php echo $director['id_director']; ?>"><?php echo htmlspecialchars($director['nombre']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </article>

            </div>

            <button type="submit" id="submitBtn" class="btn-save" disabled>Guardar Preferencias</button>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a los grupos de checkboxes
            const actorChecks = document.querySelectorAll('.actor-check');
            const genreChecks = document.querySelectorAll('.genre-check');
            const directorChecks = document.querySelectorAll('.director-check'); // NUEVO

            // Referencias a los contadores visuales
            const actorCounter = document.getElementById('actor-counter');
            const genreCounter = document.getElementById('genre-counter');
            const directorCounter = document.getElementById('director-counter'); // NUEVO
            
            const submitBtn = document.getElementById('submitBtn');

            function updateCounters() {
                // Contar cuántos hay seleccionados
                const actorCount = document.querySelectorAll('.actor-check:checked').length;
                const genreCount = document.querySelectorAll('.genre-check:checked').length;
                const directorCount = document.querySelectorAll('.director-check:checked').length; // NUEVO

                // --- ACTUALIZAR ACTORES ---
                actorCounter.textContent = `${actorCount}/5 Seleccionados`;
                if (actorCount >= 5) {
                    actorCounter.classList.remove('invalid'); actorCounter.classList.add('valid');
                } else {
                    actorCounter.classList.remove('valid'); actorCounter.classList.add('invalid');
                }

                // --- ACTUALIZAR GÉNEROS ---
                genreCounter.textContent = `${genreCount}/5 Seleccionados`;
                if (genreCount >= 5) {
                    genreCounter.classList.remove('invalid'); genreCounter.classList.add('valid');
                } else {
                    genreCounter.classList.remove('valid'); genreCounter.classList.add('invalid');
                }

                // --- ACTUALIZAR DIRECTORES ---
                directorCounter.textContent = `${directorCount}/5 Seleccionados`;
                if (directorCount >= 5) {
                    directorCounter.classList.remove('invalid'); directorCounter.classList.add('valid');
                } else {
                    directorCounter.classList.remove('valid'); directorCounter.classList.add('invalid');
                }

                // --- VALIDACIÓN FINAL ---
                // Solo activa el botón si los 3 cumplen el mínimo
                if (actorCount >= 5 && genreCount >= 5 && directorCount >= 5) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = "Guardar Preferencias";
                } else {
                    submitBtn.disabled = true;
                    submitBtn.textContent = "Debes completar todas las selecciones (min 5 c/u)";
                }
            }

            // Escuchar cambios en cualquier grupo
            actorChecks.forEach(ch => ch.addEventListener('change', updateCounters));
            genreChecks.forEach(ch => ch.addEventListener('change', updateCounters));
            directorChecks.forEach(ch => ch.addEventListener('change', updateCounters)); // NUEVO

            // Ejecutar al inicio para validar lo que viene de BD
            updateCounters();
        });
    </script>
</body>
</html>