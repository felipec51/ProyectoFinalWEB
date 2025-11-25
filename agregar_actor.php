<?php
// agregar_actor.php
require_once 'conexion.php';

$mensaje = "";
$nombre_actor = "";

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // --- Lógica para INSERTAR un nuevo Actor ---
    if (isset($_POST['btn_guardar'])) {
        $nombre_actor = trim($_POST['nombre']);

        if (empty($nombre_actor)) {
            $mensaje = "<div class='alert error'>El nombre del actor no puede estar vacío.</div>";
        } else {
            // 1. Verificar si el actor ya existe
            $sqlCheck = "SELECT COUNT(*) FROM actor WHERE nombre = :nombre";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':nombre' => $nombre_actor]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                $mensaje = "<div class='alert error'>El actor '<b>" . htmlspecialchars($nombre_actor) . "</b>' ya existe.</div>";
            } else {
                // 2. Insertar el nuevo actor
                $sqlInsert = "INSERT INTO actor (nombre) VALUES (:nombre)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                
                if ($stmtInsert->execute([':nombre' => $nombre_actor])) {
                    $mensaje = "<div class='alert success'>Actor '<b>" . htmlspecialchars($nombre_actor) . "</b>' agregado correctamente.</div>";
                    $nombre_actor = ""; // Limpiar campo después de éxito
                } else {
                    $mensaje = "<div class='alert error'>Error al intentar agregar el actor.</div>";
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
    <title>Agregar Actor - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/addActorOgenero.css">
</head>

<body>

    <div class="admin-header">
        <h1>Agregar Nuevo Actor</h1>
        <a href="actores.php" class="btn btn-back">Volver a Actores</a>
    </div>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo del Actor:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre_actor); ?>" required>
            </div>
            
            <button type="submit" name="btn_guardar" class="btn btn-submit">
                Guardar Actor
            </button>
        </form>
    </div>

</body>

</html>