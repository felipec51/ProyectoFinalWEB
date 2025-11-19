<?php
// editar_pelicula.php
require_once 'conexion.php';

$id_pelicula = isset($_GET['id']) ? $_GET['id'] : null;
$mensaje = "";
$pelicula = null;

if (!$id_pelicula) {
    die("ID de película no especificado.");
}

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // 1. PROCESAR FORMULARIO (GUARDAR CAMBIOS)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recibir datos
        $titulo = $_POST['titulo'];
        $anio = $_POST['anio'];
        $calificacion = $_POST['calificacion'];
        $director_id = $_POST['director'];
        $poster = $_POST['poster'];
        $precio = $_POST['precio'];       // Nuevo campo
        $nuevas_copias = (int)$_POST['ncopias']; // Nuevo campo

        // --- LÓGICA DE COPIAS Y CINTAS ---
        
        // A. Obtener copias actuales de la BD para comparar
        $sqlCheck = "SELECT ncopias FROM pelicula WHERE id_pelicula = :id";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $id_pelicula]);
        $datosActuales = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        $copias_actuales = (int)$datosActuales['ncopias'];

        $puede_actualizar = true;

        // B. Validar Regla: No se puede reducir el número
        if ($nuevas_copias < $copias_actuales) {
            echo "<script>alert('ERROR: No puedes reducir el número de copias. El inventario físico no se puede eliminar desde aquí.'); window.history.back();</script>";
            $puede_actualizar = false;
            exit; // Detenemos el script
        }

        // C. Si aumentó el número, creamos las cintas faltantes
        if ($nuevas_copias > $copias_actuales && $puede_actualizar) {
            $diferencia = $nuevas_copias - $copias_actuales;
            
            // Insertamos X cantidad de cintas nuevas
            $sqlInsertCinta = "INSERT INTO cinta (estado, pelicula_id_pelicula) VALUES ('disponible', :id_peli)";
            $stmtCinta = $conexion->prepare($sqlInsertCinta);
            
            for ($i = 0; $i < $diferencia; $i++) {
                $stmtCinta->execute([':id_peli' => $id_pelicula]);
            }
        }

        // --- ACTUALIZACIÓN DE LA PELÍCULA ---
        if ($puede_actualizar) {
            $sqlUpdate = "UPDATE pelicula SET 
                            titulo = :titulo, 
                            anio = :anio, 
                            calificacion = :calif, 
                            director_id_director = :dir, 
                            poster_path = :poster,
                            precio_alquiler = :precio,
                            ncopias = :ncopias
                          WHERE id_pelicula = :id";
            
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $res = $stmtUpdate->execute([
                ':titulo' => $titulo,
                ':anio' => $anio,
                ':calif' => $calificacion,
                ':dir' => $director_id,
                ':poster' => $poster,
                ':precio' => $precio,
                ':ncopias' => $nuevas_copias,
                ':id' => $id_pelicula
            ]);

            if ($res) {
                echo "<script>alert('Película actualizada correctamente. Inventario ajustado.'); window.location.href='adminpeliculas.php';</script>";
            }
        }
    }

    // 2. OBTENER DATOS PARA MOSTRAR EN EL FORMULARIO
    $sql = "SELECT * FROM pelicula WHERE id_pelicula = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pelicula) {
        die("Película no encontrada.");
    }

    // 3. OBTENER LISTA DE DIRECTORES
    $sqlDir = "SELECT * FROM director";
    $stmtDir = $conexion->prepare($sqlDir);
    $stmtDir->execute();
    $directores = $stmtDir->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error del sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Editar Premium - <?php echo htmlspecialchars($pelicula['titulo']); ?></title>
        <link rel="stylesheet" href="./styles/editar_pelicula.css" />
    </head>
    <body>
        
        <div class="glass-container">
            <form method="POST">
                
                <div class="header">
                    <div>
                        <h1>Editar Película</h1>
                        <p>Actualiza la información y gestiona el inventario.</p>
                    </div>
                    <div class="id-badge">ID: #<?php echo $pelicula['id_pelicula']; ?></div>
                </div>

                <div class="form-grid">
                    
                    <div class="input-group col-12">
                        <label class="label">Título Original</label>
                        <input type="text" name="titulo" class="input-field" value="<?php echo htmlspecialchars($pelicula['titulo']); ?>" required>
                    </div>

                    <div class="input-group col-6">
                        <label class="label">Director</label>
                        <select name="director" class="input-field">
                            <?php foreach ($directores as $dir): ?>
                                <option value="<?php echo $dir['id_director']; ?>" 
                                    <?php echo ($dir['id_director'] == $pelicula['director_id_director']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dir['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group col-3">
                        <label class="label">Año</label>
                        <input type="number" name="anio" class="input-field" value="<?php echo $pelicula['anio']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
                    </div>

                    <div class="input-group col-3">
                        <label class="label">Clasificación</label>
                        <input type="text" name="calificacion" class="input-field" value="<?php echo htmlspecialchars($pelicula['calificacion']); ?>">
                    </div>

                    <div class="input-group col-6">
                        <label class="label">Precio de Alquiler ($)</label>
                        <input type="number" name="precio" class="input-field" value="<?php echo $pelicula['precio_alquiler']; ?>" required step="100">
                        <div class="helper-text">Valor monetario por alquiler.</div>
                    </div>

                    <div class="input-group col-6">
                        <label class="label">Copias Totales (Inventario)</label>
                        <input type="number" name="ncopias" class="input-field" value="<?php echo $pelicula['ncopias']; ?>" required min="<?php echo $pelicula['ncopias']; ?>">
                        <div class="helper-text" style="color: #e50914;">
                            ⚠️ Solo puedes aumentar el inventario. Las copias nuevas se marcarán como "disponibles".
                        </div>
                    </div>

                    <div class="input-group col-12">
                        <label class="label">URL del Póster</label>
                        <input type="url" name="poster" class="input-field" value="<?php echo htmlspecialchars($pelicula['poster_path']); ?>" placeholder="https://...">
                    </div>

                </div>

                <div class="footer">
                    <a href="adminpeliculas.php" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-save">Guardar Cambios</button>
                </div>

            </form>
        </div>

    </body>
</html>