<?php
// agregar_pelicula.php
require_once 'conexion.php';

$mensaje = "";
$directores = [];
$generos_db = [];

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // 1. PROCESAR FORMULARIO (INSERTAR)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recibir datos básicos
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

        // NUEVOS CAMPOS DE DIRECTOR
        $director_existente_id = $_POST['director_existente']; // ID del director seleccionado (puede ser vacío)
        $nuevo_director_nombre = trim($_POST['nuevo_director']); // Nombre escrito por el usuario (puede ser vacío)

        // Lógica de validación
        if (empty($generos)) {
            $mensaje = "ERROR: Debe seleccionar al menos un género para la película.";
        } elseif (empty($director_existente_id) && empty($nuevo_director_nombre)) {
            $mensaje = "ERROR: Debe seleccionar un Director existente O escribir el nombre de un Director nuevo.";
        } else {
            try {
                $conexion->beginTransaction();

                $director_id_final = null;

                // --- GESTIÓN DEL DIRECTOR (PASO CRUCIAL) ---
                if (!empty($nuevo_director_nombre)) {
                    // Si se proporcionó un nuevo nombre, lo insertamos.
                    $sqlInsertDir = "INSERT INTO director (nombre) VALUES (:nombre)";
                    $stmtDir = $conexion->prepare($sqlInsertDir);
                    $stmtDir->execute([':nombre' => $nuevo_director_nombre]);
                    $director_id_final = $conexion->lastInsertId();
                } else if (!empty($director_existente_id)) {
                    // Si se seleccionó uno existente.
                    $director_id_final = $director_existente_id;
                }

                // A. Insertar Película (Usando $director_id_final)
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
                    ':dir' => $director_id_final, // <-- USAMOS EL ID FINAL
                    ':idioma' => $idioma,
                    ':ncopias' => $ncopias
                ]);

                // B. Obtener el ID de la película recién creada
                $id_nueva_pelicula = $conexion->lastInsertId();

                // C. Insertar las cintas correspondientes (Inventario físico)
                if ($ncopias > 0) {
                    $sqlCinta = "INSERT INTO cinta (estado, pelicula_id_pelicula) VALUES ('disponible', :id_peli)";
                    $stmtCinta = $conexion->prepare($sqlCinta);
                    
                    for ($i = 0; $i < $ncopias; $i++) {
                        $stmtCinta->execute([':id_peli' => $id_nueva_pelicula]);
                    }
                }

                // D. Insertar Géneros en pelicula_genero
                $sqlPeliGen = "INSERT INTO pelicula_genero (pelicula_id_pelicula, genero_id_genero) VALUES (:id_peli, :id_gen)";
                $stmtPeliGen = $conexion->prepare($sqlPeliGen);

                foreach ($generos as $id_genero) {
                    $stmtPeliGen->execute([
                        ':id_peli' => $id_nueva_pelicula,
                        ':id_gen' => (int)$id_genero
                    ]);
                }

                $conexion->commit();
                
                echo "<script>alert('¡Película agregada con éxito!'); window.location.href='adminpeliculas.php';</script>";

            } catch (Exception $ex) {
                $conexion->rollBack();
                $mensaje = "Error al guardar: " . $ex->getMessage();
            }
        }
    }

    // 2. OBTENER LISTAS DE DATOS (DIRECTORES Y GÉNEROS)
    
    // Obtener Directores (Refrescamos la lista)
    $sqlDir = "SELECT * FROM director ORDER BY nombre ASC";
    $stmtDir = $conexion->prepare($sqlDir);
    $stmtDir->execute();
    $directores = $stmtDir->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener Géneros
    $sqlGen = "SELECT * FROM genero ORDER BY nombre ASC";
    $stmtGen = $conexion->prepare($sqlGen);
    $stmtGen->execute();
    $generos_db = $stmtGen->fetchAll(PDO::FETCH_ASSOC);

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
                    <div class="error-msg"><?php echo $mensaje; ?></div>
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
                        <input type="url" name="poster" class="input-field" placeholder="https://imagen..." required>
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