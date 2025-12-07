<?php
include 'check_session.php'; 

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: login.php"); 
    exit;
}
$usuario_logueado_id = $_SESSION["id_usuario"];

require_once 'conexion.php';

$mensaje = "";

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    
    if (isset($_POST['btn_eliminar'])) {
        $id_eliminar = $_POST['id_actor_eliminar'];

        try {
            $conexion->beginTransaction();
            $sqlRelacionPeli = "DELETE FROM pelicula_actor WHERE actor_id_actor = :id";
            $stmtRelacionPeli = $conexion->prepare($sqlRelacionPeli);
            $stmtRelacionPeli->execute([':id' => $id_eliminar]);

            
            $sqlRelacionGusto = "DELETE FROM gusto_actor WHERE actor_id_actor = :id";
            $stmtRelacionGusto = $conexion->prepare($sqlRelacionGusto);
            $stmtRelacionGusto->execute([':id' => $id_eliminar]);

            
            $sqlDelete = "DELETE FROM actor WHERE id_actor = :id";
            $stmtDelete = $conexion->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $id_eliminar);

            if ($stmtDelete->execute()) {
                $conexion->commit();
                $mensaje = "<div class='alert success'>Actor ID #$id_eliminar y todas sus relaciones eliminadas correctamente.</div>";
            } else {
                $conexion->rollBack();
                $mensaje = "<div class='alert error'>Error interno al intentar eliminar el actor. La operación fue revertida.</div>";
            }
        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            $mensaje = "<div class='alert error'>Error de base de datos al eliminar. La operación fue cancelada. Error: " . $e->getMessage() . "</div>";
        }
    }

    
    $consulta = "SELECT id_actor, nombre FROM actor ORDER BY id_actor ASC";

    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $actores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Actores - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/admintablas.css">
</head>

<body>

    <div class="admin-header">
        <h1>Panel de Administración de Actores</h1>
        <div>
            <a href="paneladmin.php" class="btn btn-edit">Volver al Inicio</a>
            <a href="agregar_actor.php" class="btn btn-add">➕ Nuevo Actor</a>
        </div>
    </div>

    <?php echo $mensaje; ?>

    <div class="table-container_pequeña">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($actores) > 0): ?>
                    <?php foreach ($actores as $actor): ?>
                        <tr>
                            <td>#<?php echo $actor['id_actor']; ?></td>

                            <td style="font-weight: bold; font-size: 1.1rem;"><?php echo htmlspecialchars($actor['nombre']); ?></td>

                            <td class="actions-cell">
                                <a href="editar_actor.php?id=<?php echo $actor['id_actor']; ?>" class="btn btn-edit">
                                    Editar
                                </a>

                                <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar al actor <?php echo htmlspecialchars($actor['nombre']); ?>? Se eliminarán también todas sus relaciones (Película, Gustos).');">
                                    <input type="hidden" name="id_actor_eliminar" value="<?php echo $actor['id_actor']; ?>">
                                    <button type="submit" name="btn_eliminar" class="btn btn-delete">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px; color: var(--text-sec);">No hay actores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>