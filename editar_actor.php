<?php

require_once 'conexion.php';

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: peliculasMenu.php"); 
    exit;
}

$mensaje = "";
$actor = null;

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID de actor no válido.");
    }
    $id_editar = $_GET['id'];

    if (isset($_POST['btn_actualizar'])) {
        $nuevo_nombre = trim($_POST['nombre']);
        $id_actor_form = $_POST['id_actor']; 

        if (empty($nuevo_nombre)) {
            $mensaje = "<div class='alert error'>El nombre del actor no puede estar vacío.</div>";
        } else {
            
            $sqlCheck = "SELECT COUNT(*) FROM actor WHERE nombre = :nombre AND id_actor != :id";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':nombre' => $nuevo_nombre, ':id' => $id_actor_form]);

            if ($stmtCheck->fetchColumn() > 0) {
                $mensaje = "<div class='alert error'>Ya existe otro actor con el nombre '<b>" . htmlspecialchars($nuevo_nombre) . "</b>'.</div>";
            } else {
                
                $sqlUpdate = "UPDATE actor SET nombre = :nombre WHERE id_actor = :id";
                $stmtUpdate = $conexion->prepare($sqlUpdate);

                if ($stmtUpdate->execute([':nombre' => $nuevo_nombre, ':id' => $id_actor_form])) {
                    $mensaje = "<div class='alert success'>Actor ID #$id_actor_form actualizado a '<b>" . htmlspecialchars($nuevo_nombre) . "</b>' correctamente.</div>";
                    
                    $id_editar = $id_actor_form;
                } else {
                    $mensaje = "<div class='alert error'>Error al intentar actualizar el actor.</div>";
                }
            }
        }
    }
    
    $consulta = "SELECT id_actor, nombre FROM actor WHERE id_actor = :id";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute([':id' => $id_editar]);
    $actor = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$actor) {
        die("Actor no encontrado.");
    }
} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Actor - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/addActorOgenero.css">

</head>

<body>

    <div class="admin-header">
        <h1>Editar Actor: #<?php echo htmlspecialchars($actor['id_actor']); ?></h1>
        <a href="actores.php" class="btn-back btn">
            Volver a Actores
        </a>
    </div>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="id_actor" value="<?php echo htmlspecialchars($actor['id_actor']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre del Actor:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($actor['nombre']); ?>" required>
            </div>

            <button type="submit" name="btn_actualizar" class="btn btn-submit">
                Actualizar Actor
            </button>

        </form>
    </div>

</body>

</html>