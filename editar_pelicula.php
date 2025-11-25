<?php

require_once 'conexion.php';

$id_pelicula = isset($_GET['id']) ? $_GET['id'] : null;
$mensaje = "";
$pelicula = null;
$generos_seleccionados_actuales = [];
$actores_seleccionados_actuales = [];
$trailer1_url = ''; 
$trailer2_url = '';
$trailer3_url = '';

if (!$id_pelicula) {
    die("ID de película no especificado.");
}

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $titulo = $_POST['titulo'];
        $anio = $_POST['anio'];
        $calificacion = $_POST['calificacion'];
        $director_id = $_POST['director'];
        $poster = $_POST['poster'];
        $precio = $_POST['precio'];
        $nuevas_copias = (int)$_POST['ncopias'];
        $descripcion = $_POST['descripcion']; 
        
        
        $generos_seleccionados = isset($_POST['generos']) ? (array)$_POST['generos'] : [];
        $actores_seleccionados = isset($_POST['actores']) ? (array)$_POST['actores'] : [];
        $trailer_urls = isset($_POST['trailer_url']) ? (array)$_POST['trailer_url'] : [];


        $conexion->beginTransaction();

        $sqlCheck = "SELECT ncopias FROM pelicula WHERE id_pelicula = :id";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $id_pelicula]);
        $datosActuales = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        $copias_actuales = (int)$datosActuales['ncopias'];

        $puede_actualizar = true;

        if ($nuevas_copias < $copias_actuales) {
            $conexion->rollBack();
            echo "<script>alert('ERROR: No puedes reducir el número de copias. El inventario físico no se puede eliminar desde aquí.'); window.history.back();</script>";
            $puede_actualizar = false;
            exit; 
        }

        
        if ($nuevas_copias > $copias_actuales && $puede_actualizar) {
            $diferencia = $nuevas_copias - $copias_actuales;
            
            $sqlInsertCinta = "INSERT INTO cinta (estado, pelicula_id_pelicula) VALUES ('disponible', :id_peli)";
            $stmtCinta = $conexion->prepare($sqlInsertCinta);
            
            for ($i = 0; $i < $diferencia; $i++) {
                $stmtCinta->execute([':id_peli' => $id_pelicula]);
            }
        }

        
        if ($puede_actualizar) {
            
            $sqlUpdate = "UPDATE pelicula SET 
                            titulo = :titulo, 
                            anio = :anio, 
                            calificacion = :calif, 
                            director_id_director = :dir, 
                            poster_path = :poster,
                            precio_alquiler = :precio,
                            ncopias = :ncopias,
                            descripcion = :desc
                          WHERE id_pelicula = :id";
            
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $resPelicula = $stmtUpdate->execute([
                ':titulo' => $titulo,
                ':anio' => $anio,
                ':calif' => $calificacion,
                ':dir' => $director_id,
                ':poster' => $poster,
                ':precio' => $precio,
                ':ncopias' => $nuevas_copias,
                ':desc' => $descripcion,
                ':id' => $id_pelicula
            ]);

            if (!$resPelicula) {
                throw new Exception("Error al actualizar la tabla 'pelicula'.");
            }

            
            $sqlDeleteGen = "DELETE FROM pelicula_genero WHERE pelicula_id_pelicula = :id";
            $stmtDeleteGen = $conexion->prepare($sqlDeleteGen);
            $stmtDeleteGen->execute([':id' => $id_pelicula]);

            $sqlInsertGen = "INSERT INTO pelicula_genero (pelicula_id_pelicula, genero_id_genero) VALUES (:id_peli, :id_genero)";
            $stmtInsertGen = $conexion->prepare($sqlInsertGen);

            foreach ($generos_seleccionados as $genero_id) {
                if (is_numeric($genero_id)) {
                    $stmtInsertGen->execute([':id_peli' => $id_pelicula, ':id_genero' => $genero_id]);
                }
            }
        
            $sqlDeleteActor = "DELETE FROM pelicula_actor WHERE pelicula_id_pelicula = :id";
            $stmtDeleteActor = $conexion->prepare($sqlDeleteActor);
            $stmtDeleteActor->execute([':id' => $id_pelicula]);

            $sqlInsertActor = "INSERT INTO pelicula_actor (pelicula_id_pelicula, actor_id_actor, rol_pelicula) VALUES (:id_peli, :id_actor, 'Protagonista')";
            $stmtInsertActor = $conexion->prepare($sqlInsertActor);

            foreach ($actores_seleccionados as $actor_id) {
                if (is_numeric($actor_id)) {
                    $stmtInsertActor->execute([':id_peli' => $id_pelicula, ':id_actor' => $actor_id]);
                }
            }
            
            $sqlDeleteTrailer = "DELETE FROM traileres WHERE pelicula_id_pelicula = :id";
            $stmtDeleteTrailer = $conexion->prepare($sqlDeleteTrailer);
            $stmtDeleteTrailer->execute([':id' => $id_pelicula]);

            $sqlInsertTrailer = "INSERT INTO traileres (ruta, pelicula_id_pelicula) VALUES (:ruta, :id_peli)";
            $stmtInsertTrailer = $conexion->prepare($sqlInsertTrailer);

            foreach ($trailer_urls as $ruta) {
                $ruta_limpia = trim($ruta);
                if (!empty($ruta_limpia)) {
                    if ($stmtInsertTrailer->rowCount() < 3) { 
                       $stmtInsertTrailer->execute([':ruta' => $ruta_limpia, ':id_peli' => $id_pelicula]);
                    }
                }
            }
            
            $conexion->commit();
            
            echo "<script>alert('Película, géneros, actores, trailers y descripción actualizados correctamente. Inventario ajustado.'); window.location.href='adminpeliculas.php';</script>";

        } else {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }
    }

    
    
    
    $sql = "SELECT * FROM pelicula WHERE id_pelicula = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pelicula) {
        die("Película no encontrada.");
    }

    
    $sqlDir = "SELECT id_director, nombre FROM director ORDER BY nombre ASC";
    $stmtDir = $conexion->prepare($sqlDir);
    $stmtDir->execute();
    $directores = $stmtDir->fetchAll(PDO::FETCH_ASSOC);

    
    $sqlGeneros = "SELECT id_genero, nombre FROM genero ORDER BY nombre ASC";
    $stmtGeneros = $conexion->prepare($sqlGeneros);
    $stmtGeneros->execute();
    $todos_los_generos = $stmtGeneros->fetchAll(PDO::FETCH_ASSOC);

    
    $sqlGenerosPeli = "SELECT genero_id_genero FROM pelicula_genero WHERE pelicula_id_pelicula = :id";
    $stmtGenerosPeli = $conexion->prepare($sqlGenerosPeli);
    $stmtGenerosPeli->execute([':id' => $id_pelicula]);
    $generos_seleccionados_actuales = $stmtGenerosPeli->fetchAll(PDO::FETCH_COLUMN, 0);

    
    $sqlActores = "SELECT id_actor, nombre FROM actor ORDER BY nombre ASC";
    $stmtActores = $conexion->prepare($sqlActores);
    $stmtActores->execute();
    $todos_los_actores = $stmtActores->fetchAll(PDO::FETCH_ASSOC);
    
    
    $sqlActoresPeli = "SELECT actor_id_actor FROM pelicula_actor WHERE pelicula_id_pelicula = :id AND rol_pelicula = 'Protagonista'";
    $stmtActoresPeli = $conexion->prepare($sqlActoresPeli);
    $stmtActoresPeli->execute([':id' => $id_pelicula]);
    $actores_seleccionados_actuales = $stmtActoresPeli->fetchAll(PDO::FETCH_COLUMN, 0);

    
    $sqlTrailers = "SELECT ruta FROM traileres WHERE pelicula_id_pelicula = :id ORDER BY id_traileres ASC LIMIT 3";
    $stmtTrailers = $conexion->prepare($sqlTrailers);
    $stmtTrailers->execute([':id' => $id_pelicula]);
    $trailers_actuales = $stmtTrailers->fetchAll(PDO::FETCH_COLUMN, 0);

    $trailer_urls = array_pad($trailers_actuales, 3, ''); 
    $trailer1_url = $trailer_urls[0];
    $trailer2_url = $trailer_urls[1];
    $trailer3_url = $trailer_urls[2];

} catch (Exception $e) {
    if ($conexion && $conexion->inTransaction()) {
        $conexion->rollBack();
    }
    die("Error del sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Editar Película - <?php echo htmlspecialchars($pelicula['titulo']); ?></title>
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
                    
                    <div class="input-group col-12">
                        <label class="label">Sinopsis / Descripción</label>
                        <textarea name="descripcion" class="input-field" required><?php echo htmlspecialchars($pelicula['descripcion']); ?></textarea>
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

                    <div class="input-group col-6">
                        <label class="label">Géneros (Selección Múltiple)</label>
                        <select name="generos[]" class="input-field" multiple required> 
                            <?php foreach ($todos_los_generos as $gen): ?>
                                <option value="<?php echo $gen['id_genero']; ?>" 
                                    <?php 
                                    echo in_array($gen['id_genero'], $generos_seleccionados_actuales) ? 'selected' : ''; 
                                    ?>>
                                    <?php echo htmlspecialchars($gen['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="helper-text">Mantén presionada la tecla **Ctrl (o Cmd)** para seleccionar varios.</div>
                    </div>
                    
                    <div class="input-group col-12">
                        <label class="label">Actores Protagonistas (Selección Múltiple)</label>
                        <select name="actores[]" class="input-field" multiple required> 
                            <?php foreach ($todos_los_actores as $actor): ?>
                                <option value="<?php echo $actor['id_actor']; ?>" 
                                    <?php 
                                    echo in_array($actor['id_actor'], $actores_seleccionados_actuales) ? 'selected' : ''; 
                                    ?>>
                                    <?php echo htmlspecialchars($actor['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="helper-text">Mantén presionada la tecla **Ctrl (o Cmd)** para seleccionar varios. Se registrarán como "Protagonista".</div>
                    </div>
             
                    <div class="col-12" style="margin-top: 10px;">
                        <label class="label">URLs de Trailers (Máximo 3)</label>
                    </div>
                    
                    <div class="input-group col-4">
                        <label class="label sub-label">Trailer 1 URL</label>
                        <input type="url" name="trailer_url[]" class="input-field" value="<?php echo htmlspecialchars($trailer1_url); ?>" placeholder="https:
                    </div>
                    
                    <div class="input-group col-4">
                        <label class="label sub-label">Trailer 2 URL</label>
                        <input type="url" name="trailer_url[]" class="input-field" value="<?php echo htmlspecialchars($trailer2_url); ?>" placeholder="https:
                    </div>
                    
                    <div class="input-group col-4">
                        <label class="label sub-label">Trailer 3 URL</label>
                        <input type="url" name="trailer_url[]" class="input-field" value="<?php echo htmlspecialchars($trailer3_url); ?>" placeholder="https:
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
                        <input type="url" name="poster" class="input-field" value="<?php echo htmlspecialchars($pelicula['poster_path']); ?>" placeholder="https:
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