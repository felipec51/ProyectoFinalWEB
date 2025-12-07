<?php
// adminpeliculas.php
require_once 'conexion.php';
include 'check_session.php'; 

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: login.php"); 
    exit;
}
$usuario_logueado_id = $_SESSION["id_usuario"];



$mensaje = "";

try {
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();


    if (isset($_POST['btn_eliminar'])) {
        $id_eliminar = $_POST['id_pelicula_eliminar'];

        try {

            $conexion->beginTransaction();

            $sqlPrestamo = "DELETE FROM prestamo WHERE cinta_id_cinta IN (SELECT id_cinta FROM cinta WHERE pelicula_id_pelicula = :id_peli)";
            $stmtPrestamo = $conexion->prepare($sqlPrestamo);
            $stmtPrestamo->execute([':id_peli' => $id_eliminar]);


            $sqlCinta = "DELETE FROM cinta WHERE pelicula_id_pelicula = :id";
            $stmtCinta = $conexion->prepare($sqlCinta);
            $stmtCinta->execute([':id' => $id_eliminar]);


            $sqlGen = "DELETE FROM pelicula_genero WHERE pelicula_id_pelicula = :id";
            $stmtGen = $conexion->prepare($sqlGen);
            $stmtGen->execute([':id' => $id_eliminar]);


            $sqlActor = "DELETE FROM pelicula_actor WHERE pelicula_id_pelicula = :id";
            $stmtActor = $conexion->prepare($sqlActor);
            $stmtActor->execute([':id' => $id_eliminar]);


            $sqlTrailer = "DELETE FROM traileres WHERE pelicula_id_pelicula = :id";
            $stmtTrailer = $conexion->prepare($sqlTrailer);
            $stmtTrailer->execute([':id' => $id_eliminar]);


            $sqlEspera = "DELETE FROM lista_espera WHERE pelicula_id_pelicula = :id";
            $stmtEspera = $conexion->prepare($sqlEspera);
            $stmtEspera->execute([':id' => $id_eliminar]);


            $sqlDelete = "DELETE FROM pelicula WHERE id_pelicula = :id";
            $stmtDelete = $conexion->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $id_eliminar);

            if ($stmtDelete->execute()) {

                $conexion->commit();
                $mensaje = "<div class='alert success'>Película ID #$id_eliminar y todos sus registros asociados eliminados correctamente.</div>";
            } else {

                $conexion->rollBack();
                $mensaje = "<div class='alert error'>Error interno al intentar eliminar la película. La operación fue revertida.</div>";
            }
        } catch (Exception $e) {

            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            $mensaje = "<div class='alert error'>Error de base de datos al eliminar. La operación fue cancelada. Error: " . $e->getMessage() . "</div>";
        }
    }

    $consulta = "SELECT p.id_pelicula, p.titulo, p.anio, p.poster_path, p.precio_alquiler, p.ncopias, d.nombre as director_nombre 
                 FROM pelicula p 
                 JOIN director d ON p.director_id_director = d.id_director
                 ORDER BY p.id_pelicula DESC";

    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $peliculas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/admintablas.css">
</head>

<body>

    <div class="admin-header">
        <h1>Panel de Administración</h1>
        <div>
            <a href="paneladmin.php" class="btn btn-edit">Volver al Inicio</a>
            <a href="agregar_pelicula.php" class="btn btn-add">+ Nueva Película</a>
        </div>
    </div>

    <?php echo $mensaje; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Título</th>
                    <th>Año</th>
                    <th>Director</th>
                    <th>Copias</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($peliculas) > 0): ?>
                    <?php foreach ($peliculas as $peli): ?>
                        <tr>
                            <td>#<?php echo $peli['id_pelicula']; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($peli['poster_path']); ?>" class="poster-mini" alt="Poster">
                            </td>
                            <td style="font-weight: bold; font-size: 1.1rem;">
                                <?php echo htmlspecialchars($peli['titulo']); ?>
                            </td>
                            <td><?php echo $peli['anio']; ?></td>
                            <td><?php echo htmlspecialchars($peli['director_nombre']); ?></td>
                            <td><?php echo $peli['ncopias']; ?></td>
                            <td>$<?php echo number_format($peli['precio_alquiler'], 0); ?></td>
                            <td>
                                <div class="actions-cell">
                                <a href="editar_pelicula.php?id=<?php echo $peli['id_pelicula']; ?>" class="btn btn-edit">
                                    Editar
                                </a>

                                <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar <?php echo htmlspecialchars($peli['titulo']); ?>? Se eliminarán todos sus registros asociados (cintas, préstamos, géneros, etc.).');">
                                    <input type="hidden" name="id_pelicula_eliminar" value="<?php echo $peli['id_pelicula']; ?>">
                                    <button type="submit" name="btn_eliminar" class="btn btn-delete">
                                        Eliminar
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">No hay películas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>