<?php

require_once 'conexion.php';

$mensaje = "";
$directores = [];
$generos_db = [];
$actores_db = []; 

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $titulo = $_POST['titulo'];
        $anio = (int)$_POST['anio'];
        $duracion = (int)$_POST['duracion'];
        $descripcion = $_POST['descripcion'];
        $poster = $_POST['poster'];
        $precio = $_POST['precio'];
        $calificacion = $_POST['calificacion'];
        $idioma = $_POST['idioma'];
        $ncopias = (int)$_POST['ncopias'];
        $generos = isset($_POST['generos']) ? $_POST['generos'] : [];

        
        $director_existente_id = $_POST['director_existente'];
        $nuevo_director_nombre = trim($_POST['nuevo_director']);

        
        $actores_existentes = isset($_POST['actores_existentes']) ? $_POST['actores_existentes'] : [];
        $nuevos_actores_str = trim($_POST['nuevos_actores']);

        
        if (empty($generos)) {
            $mensaje = "<div class='error-msg'>ERROR: Debe seleccionar al menos un género para la película.</div>";
        } elseif (empty($director_existente_id) && empty($nuevo_director_nombre)) {
            $mensaje = "<div class='error-msg'>ERROR: Debe seleccionar un Director existente O escribir el nombre de un Director nuevo.</div>";
        } elseif (empty($actores_existentes) && empty($nuevos_actores_str)) {
             $mensaje = "<div class='error-msg'>ERROR: Debe seleccionar o especificar al menos un actor protagonista.</div>";
        } else {
            try {
                $conexion->beginTransaction();

                $director_id_final = null;

                
                if (!empty($nuevo_director_nombre)) {
                    $sqlInsertDir = "INSERT INTO director (nombre) VALUES (:nombre)";
                    $stmtDir = $conexion->prepare($sqlInsertDir);
                    $stmtDir->execute([':nombre' => $nuevo_director_nombre]);
                    $director_id_final = $conexion->lastInsertId();
                } else if (!empty($director_existente_id)) {
                    $director_id_final = $director_existente_id;
                }

                
                $sqlInsert = "INSERT INTO pelicula (titulo, anio, duracion_min, descripcion, poster_path, precio_alquiler, calificacion, director_id_director, idioma, ncopias) 
                              VALUES (:titulo, :anio, :duracion, :desc, :poster, :precio, :calif, :dir, :idioma, :ncopias)";
                
                $stmt = $conexion->prepare($sqlInsert);
                $stmt->execute([
                    ':titulo' => $titulo,
                    ':anio' => $anio,
                    ':duracion' => $duracion,
                    ':desc' => $descripcion,
                    ':poster' => $poster,
                    ':precio' => $precio,
                    ':calif' => $calificacion,
                    ':dir' => $director_id_final,
                    ':idioma' => $idioma,
                    ':ncopias' => $ncopias
                ]);

                
                $id_nueva_pelicula = $conexion->lastInsertId();

                
                if ($ncopias > 0) {
                    $sqlCinta = "INSERT INTO cinta (estado, pelicula_id_pelicula) VALUES ('disponible', :id_peli)";
                    $stmtCinta = $conexion->prepare($sqlCinta);
                    for ($i = 0; $i < $ncopias; $i++) {
                        $stmtCinta->execute([':id_peli' => $id_nueva_pelicula]);
                    }
                }

                
                $sqlPeliGen = "INSERT INTO pelicula_genero (pelicula_id_pelicula, genero_id_genero) VALUES (:id_peli, :id_gen)";
                $stmtPeliGen = $conexion->prepare($sqlPeliGen);

                foreach ($generos as $id_genero) {
                    $stmtPeliGen->execute([
                        ':id_peli' => $id_nueva_pelicula,
                        ':id_gen' => (int)$id_genero
                    ]);
                }
                
                
                $actor_ids_a_relacionar = []; 

                
                if (!empty($actores_existentes)) {
                    $actor_ids_a_relacionar = array_merge($actor_ids_a_relacionar, array_map('intval', $actores_existentes));
                }

                
                if (!empty($nuevos_actores_str)) {
                    $nuevos_actores_arr = explode(',', $nuevos_actores_str);
                    
                    $sqlCheckActor = "SELECT id_actor FROM actor WHERE nombre = :nombre";
                    $stmtCheckActor = $conexion->prepare($sqlCheckActor);

                    $sqlInsertActor = "INSERT INTO actor (nombre) VALUES (:nombre)";
                    $stmtInsertActor = $conexion->prepare($sqlInsertActor);
                    
                    foreach ($nuevos_actores_arr as $nombre_actor) {
                        $nombre_actor_limpio = trim($nombre_actor);

                        if (!empty($nombre_actor_limpio)) {
                            
                            $stmtCheckActor->execute([':nombre' => $nombre_actor_limpio]);
                            $actor_existente = $stmtCheckActor->fetch(PDO::FETCH_ASSOC);

                            if ($actor_existente) {
                                $actor_id = $actor_existente['id_actor'];
                            } else {
                                
                                $stmtInsertActor->execute([':nombre' => $nombre_actor_limpio]);
                                $actor_id = $conexion->lastInsertId();
                            }
                            
                            
                            if ($actor_id) {
                                $actor_ids_a_relacionar[] = (int)$actor_id;
                            }
                        }
                    }
                }

                
                $actor_ids_unicos = array_unique($actor_ids_a_relacionar);

                if (!empty($actor_ids_unicos)) {
                    $sqlPeliActor = "INSERT INTO pelicula_actor (pelicula_id_pelicula, actor_id_actor, rol_pelicula) 
                                     VALUES (:id_peli, :id_actor, :rol)";
                    $stmtPeliActor = $conexion->prepare($sqlPeliActor);
                    $rol_protagonista = "Protagonista"; 
                    
                    foreach ($actor_ids_unicos as $id_actor) {
                        $stmtPeliActor->execute([
                            ':id_peli' => $id_nueva_pelicula,
                            ':id_actor' => $id_actor,
                            ':rol' => $rol_protagonista
                        ]);
                    }
                }
                
                
                // 8. Notificar a usuarios con preferencias coincidentes
                $all_user_ids_to_notify = [];

                // A. Encontrar usuarios por director
                if ($director_id_final) {
                    $sqlUsersDir = "SELECT Usuario_id_usuario FROM gusta_director WHERE director_id_director = :dir_id";
                    $stmtUsersDir = $conexion->prepare($sqlUsersDir);
                    $stmtUsersDir->execute([':dir_id' => $director_id_final]);
                    $usersByDir = $stmtUsersDir->fetchAll(PDO::FETCH_COLUMN);
                    $all_user_ids_to_notify = array_merge($all_user_ids_to_notify, $usersByDir);
                }

                // B. Encontrar usuarios por género(s)
                if (!empty($generos)) {
                    $placeholders = rtrim(str_repeat('?,', count($generos)), ',');
                    $sqlUsersGen = "SELECT Usuario_id_usuario FROM gusto_genero WHERE genero_id_genero IN ($placeholders)";
                    $stmtUsersGen = $conexion->prepare($sqlUsersGen);
                    $stmtUsersGen->execute($generos);
                    $usersByGen = $stmtUsersGen->fetchAll(PDO::FETCH_COLUMN);
                    $all_user_ids_to_notify = array_merge($all_user_ids_to_notify, $usersByGen);
                }

                // C. Encontrar usuarios por actor(es)
                if (!empty($actor_ids_unicos)) {
                    $placeholders = rtrim(str_repeat('?,', count($actor_ids_unicos)), ',');
                    $sqlUsersAct = "SELECT Usuario_id_usuario FROM gusto_actor WHERE actor_id_actor IN ($placeholders)";
                    $stmtUsersAct = $conexion->prepare($sqlUsersAct);
                    $stmtUsersAct->execute($actor_ids_unicos);
                    $usersByAct = $stmtUsersAct->fetchAll(PDO::FETCH_COLUMN);
                    $all_user_ids_to_notify = array_merge($all_user_ids_to_notify, $usersByAct);
                }

                // D. Insertar notificaciones (solo a usuarios únicos)
                $unique_user_ids = array_unique(array_map('intval', $all_user_ids_to_notify));

                if (!empty($unique_user_ids)) {
                    $mensajeNotif = "¡Nueva película que podría gustarte! Ya está disponible \"$titulo\".";
                    $sqlNotif = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (:id_usuario, :mensaje)";
                    $stmtNotif = $conexion->prepare($sqlNotif);
                    
                    foreach ($unique_user_ids as $user_id) {
                        // Evitar notificar al admin si es que tuviera gustos. Opcional.
                        if ($user_id > 0) { // Asumiendo que el admin no es el usuario 0
                             $stmtNotif->execute([
                                ':id_usuario' => $user_id,
                                ':mensaje' => $mensajeNotif
                            ]);
                        }
                    }
                }

                $conexion->commit();
                
                echo "<script>alert('¡Película agregada con éxito!'); window.location.href='adminpeliculas.php';</script>";

            } catch (Exception $ex) {
                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
                $mensaje = "<div class='error-msg'>Error al guardar: " . $ex->getMessage() . "</div>";
            }
        }
    }

    
    
    
    $sqlDir = "SELECT * FROM director ORDER BY nombre ASC";
    $stmtDir = $conexion->prepare($sqlDir);
    $stmtDir->execute();
    $directores = $stmtDir->fetchAll(PDO::FETCH_ASSOC);
    
    
    $sqlGen = "SELECT * FROM genero ORDER BY nombre ASC";
    $stmtGen = $conexion->prepare($sqlGen);
    $stmtGen->execute();
    $generos_db = $stmtGen->fetchAll(PDO::FETCH_ASSOC);

    
    $sqlActor = "SELECT * FROM actor ORDER BY nombre ASC";
    $stmtActor = $conexion->prepare($sqlActor);
    $stmtActor->execute();
    $actores_db = $stmtActor->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error del sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Nueva Película - RewindCodeFilm</title>
        <link rel="stylesheet" href="./styles/agregar_pelicula.css" />
    </head>
    <body>
        
        <div class="glass-container">
            <form method="POST">
                
                <div class="header">
                    <div>
                        <h1>Agregar Nueva Película</h1>
                        <p>Ingresa los detalles para registrar un nuevo título en el sistema.</p>
                    </div>
                    <div class="new-badge">NUEVO REGISTRO</div>
                </div>

                <?php if(!empty($mensaje)): ?>
                    <?php echo $mensaje; ?>
                <?php endif; ?>

                <div class="form-grid">
                    
                    <div class="input-group col-12">
                        <label class="label">Título de la Película</label>
                        <input type="text" name="titulo" class="input-field" placeholder="Ej: Interestelar" required>
                    </div>
                    
                    <div class="input-group col-12">
                        <label class="label">Director Existente</label>
                        <select name="director_existente" class="input-field">
                            <option value="" selected>--- Seleccione un director ---</option>
                            <?php foreach ($directores as $dir): ?>
                                <option value="<?php echo $dir['id_director']; ?>">
                                    <?php echo htmlspecialchars($dir['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="divider-or">O</div>
                    </div>

                    <div class="input-group col-12">
                        <label class="label">Agregar Nuevo Director (Escribir nombre)</label>
                        <input type="text" name="nuevo_director" class="input-field" placeholder="Ej: Christopher Nolan">
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Si escribe aquí, ignora la selección anterior.</div>
                    </div>
            

                    <div class="input-group col-12">
                        <label class="label">Actores Protagonistas (Existentes)</label>
                        <select name="actores_existentes[]" class="input-field" multiple size="5">
                            <option value="" disabled>--- Seleccione uno o más actores ---</option>
                            <?php foreach ($actores_db as $actor): ?>
                                <option value="<?php echo $actor['id_actor']; ?>">
                                    <?php echo htmlspecialchars($actor['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div style="font-size: 11px; color: #aaa; margin-top: 4px;">Mantén presionado **CTRL** (o **CMD** en Mac) para seleccionar varios.</div>
                    </div>
                    
                    <div class="col-12">
                        <div class="divider-or">O</div>
                    </div>

                    <div class="input-group col-12">
                        <label class="label">Agregar Nuevos Actores (Escribir, separados por coma)</label>
                        <input type="text" name="nuevos_actores" class="input-field" placeholder="Ej: Brad Pitt, Jennifer Aniston">
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Si escribe aquí, ignora la selección anterior. Se registrarán como **Protagonistas**.</div>
                    </div>
                    <hr class="separator"/>

                    <div class="input-group col-6">
                        <label class="label">Año</label>
                        <input type="number" name="anio" class="input-field" placeholder="2024" required min="1900" max="2100">
                    </div>

                    <div class="input-group col-6">
                        <label class="label">Duración (min)</label>
                        <input type="number" name="duracion" class="input-field" placeholder="120" required>
                    </div>
                    
                    <div class="input-group col-6">
                        <label class="label">Clasificación</label>
                        <input type="text" name="calificacion" class="input-field" placeholder="Ej: 13+, R, PG-13" required>
                    </div>

                     <div class="input-group col-6">
                        <label class="label">Idioma Audio</label>
                        <input type="text" name="idioma" class="input-field" placeholder="Ej: Inglés, Español Latino" value="Inglés" required>
                    </div>
                    
                    <div class="input-group col-12">
                        <label class="label">Géneros (Selección Múltiple)</label>
                        <select name="generos[]" class="input-field" multiple required size="5">
                            <?php foreach ($generos_db as $gen): ?>
                                <option value="<?php echo $gen['id_genero']; ?>">
                                    <?php echo htmlspecialchars($gen['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div style="font-size: 11px; color: #aaa; margin-top: 4px;">Mantén presionado **CTRL** (o **CMD** en Mac) para seleccionar varios géneros.</div>
                    </div>


                    <div class="input-group col-6">
                        <label class="label">Precio de Alquiler ($)</label>
                        <input type="number" name="precio" class="input-field" placeholder="Ej: 15000" required step="100">
                    </div>

                    <div class="input-group col-6">
                        <label class="label">Copias Iniciales (Cintas)</label>
                        <input type="number" name="ncopias" class="input-field" value="1" min="0" required>
                        <div style="font-size: 11px; color: #aaa; margin-top: 4px;">Se crearán registros individuales de cintas automáticamente.</div>
                    </div>

                    <div class="input-group col-12">
                        <label class="label">URL del Póster (Imagen)</label>
                        <input type="url" name="poster" class="input-field" placeholder="https:
                    </div>

                    <div class="input-group col-12">
                        <label class="label">Sinopsis / Descripción</label>
                        <textarea name="descripcion" class="input-field" placeholder="Escribe una breve reseña de la película..." required></textarea>
                    </div>

                </div>

                <div class="footer">
                    <a href="adminpeliculas.php" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-save">Crear Película</button>
                </div>

            </form>
        </div>

    </body>
</html>