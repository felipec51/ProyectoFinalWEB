<?php

require_once 'conexion.php';

$mensaje = "";

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    if (isset($_POST['btn_eliminar'])) {
        
        $id_eliminar = $_POST['id_genero_eliminar']; 

        try {
            $conexion->beginTransaction();
            $sqlRelacion = "DELETE FROM pelicula_genero WHERE genero_id_genero = :id";
            $stmtRelacion = $conexion->prepare($sqlRelacion);
            $stmtRelacion->execute([':id' => $id_eliminar]);

            
            $sqlDelete = "DELETE FROM genero WHERE id_genero = :id";
            $stmtDelete = $conexion->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $id_eliminar);

            if ($stmtDelete->execute()) {
                
                $conexion->commit(); 
                $mensaje = "<div class='alert success'>Género ID #$id_eliminar y sus relaciones eliminados correctamente.</div>";
            } else {
                
                $conexion->rollBack(); 
                $mensaje = "<div class='alert error'>Error interno al intentar eliminar el género. La operación fue revertida.</div>";
            }
        } catch (Exception $e) {
            
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            $mensaje = "<div class='alert error'>Error de base de datos al eliminar. La operación fue cancelada. Error: " . $e->getMessage() . "</div>";
        }
    }

    
    $consulta = "SELECT id_genero, nombre FROM genero ORDER BY id_genero ASC";

    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    
    $generos = $sentencia->fetchAll(PDO::FETCH_ASSOC); 
    
} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Géneros - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/admintablas.css">
</head>

<body>

    <div class="admin-header">
        <h1>Panel de Administración de Géneros</h1>
        <div>
            <a href="paneladmin.php" class="btn btn-edit">Volver al Inicio</a>
            <a href="agregar_genero.php" class="btn btn-add">➕ Nuevo Género</a>
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
                <?php if (count($generos) > 0): ?>
                    <?php foreach ($generos as $genero): ?>
                        <tr>
                            <td>#<?php echo $genero['id_genero']; ?></td>
                        
                            <td style="font-weight: bold; font-size: 1.1rem;">
                                <?php echo htmlspecialchars($genero['nombre']); ?>
                            </td>
                           
                            <td class="actions-cell">
                                <a href="editar_genero.php?id=<?php echo $genero['id_genero']; ?>" class="btn btn-edit">
                                    Editar
                                </a>

                                <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar el género <?php echo htmlspecialchars($genero['nombre']); ?>? Se eliminarán también todas las relaciones con películas.');">
                                    <input type="hidden" name="id_genero_eliminar" value="<?php echo $genero['id_genero']; ?>">
                                    <button type="submit" name="btn_eliminar" class="btn btn-delete">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 40px;">No hay géneros registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>