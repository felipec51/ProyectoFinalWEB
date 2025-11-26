<?php
// editar_genero.php
require_once 'conexion.php';

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: peliculasMenu.php"); 
    exit;
}



$mensaje = "";
$genero = null; 

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // 1. Obtener el ID del género a editar
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID de género no válido.");
    }
    $id_editar = $_GET['id'];

    // --- Lógica para ACTUALIZAR el Género ---
    if (isset($_POST['btn_actualizar'])) {
        $nuevo_nombre = trim($_POST['nombre']);
        $id_genero_form = $_POST['id_genero']; // Aseguramos el ID desde el formulario

        if (empty($nuevo_nombre)) {
            $mensaje = "<div class='alert error'>El nombre del género no puede estar vacío.</div>";
        } else {
             // Verificar si el nuevo nombre ya existe en otro género
            $sqlCheck = "SELECT COUNT(*) FROM genero WHERE nombre = :nombre AND id_genero != :id";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':nombre' => $nuevo_nombre, ':id' => $id_genero_form]);

            if ($stmtCheck->fetchColumn() > 0) {
                $mensaje = "<div class='alert error'>Ya existe otro género con el nombre '<b>" . htmlspecialchars($nuevo_nombre) . "</b>'.</div>";
            } else {
                // Actualizar el género
                $sqlUpdate = "UPDATE genero SET nombre = :nombre WHERE id_genero = :id";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                
                if ($stmtUpdate->execute([':nombre' => $nuevo_nombre, ':id' => $id_genero_form])) {
                    $mensaje = "<div class='alert success'>Género ID #$id_genero_form actualizado a '<b>" . htmlspecialchars($nuevo_nombre) . "</b>' correctamente.</div>";
                    // Recargar los datos actualizados para mostrarlos en el formulario
                    $id_editar = $id_genero_form; 
                } else {
                    $mensaje = "<div class='alert error'>Error al intentar actualizar el género.</div>";
                }
            }
        }
    }

    // 2. Consultar los datos actuales del género
    $consulta = "SELECT id_genero, nombre FROM genero WHERE id_genero = :id";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute([':id' => $id_editar]);
    $genero = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$genero) {
        die("Género no encontrado.");
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
    <title>Editar Género - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/addActorOgenero.css">
</head>

<body>

    <div class="admin-header">
        <h1>Editar Género: #<?php echo htmlspecialchars($genero['id_genero']); ?></h1>
        <a href="generos.php" class="btn-back btn">
            Volver a Géneros
        </a>
    </div>

    <?php echo $mensaje; ?>

    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="id_genero" value="<?php echo htmlspecialchars($genero['id_genero']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre del Género:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($genero['nombre']); ?>" required>
            </div>
            
            <button type="submit" name="btn_actualizar" class="btn btn-submit">
                Actualizar Género
            </button>
            
        </form>
    </div>

</body>

</html>