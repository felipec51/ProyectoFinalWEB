<?php
// agregar_genero.php
require_once 'conexion.php';

$mensaje = "";
$nombre_genero = "";

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // --- Lógica para INSERTAR un nuevo Género ---
    if (isset($_POST['btn_guardar'])) {
        $nombre_genero = trim($_POST['nombre']);

        if (empty($nombre_genero)) {
            $mensaje = "<div class='alert error'>El nombre del género no puede estar vacío.</div>";
        } else {
            // 1. Verificar si el género ya existe
            $sqlCheck = "SELECT COUNT(*) FROM genero WHERE nombre = :nombre";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':nombre' => $nombre_genero]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                $mensaje = "<div class='alert error'>El género '<b>" . htmlspecialchars($nombre_genero) . "</b>' ya existe.</div>";
            } else {
                // 2. Insertar el nuevo género
                $sqlInsert = "INSERT INTO genero (nombre) VALUES (:nombre)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                
                if ($stmtInsert->execute([':nombre' => $nombre_genero])) {
                    $mensaje = "<div class='alert success'>Género '<b>" . htmlspecialchars($nombre_genero) . "</b>' agregado correctamente.</div>";
                    $nombre_genero = ""; // Limpiar campo después de éxito
                } else {
                    $mensaje = "<div class='alert error'>Error al intentar agregar el género.</div>";
                }
            }
        }
    }
} catch (Exception $e) {
    $mensaje = "<div class='alert error'>Error de base de datos: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Género - RewindCodeFilm</title>
     <link rel="stylesheet" href="./styles/addActorOgenero.css">
</head>

<body>

    <div class="admin-header">
        <h1>Agregar Nuevo Género</h1>
        <a href="generos.php" class="btn btn-back">Volver a Géneros</a>
    </div>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Género:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre_genero); ?>" required>
            </div>
            
            <button type="submit" name="btn_guardar" class="btn btn-submit">
                Guardar Género
            </button>
        </form>
    </div>

</body>

</html>