<?php
require_once "../inc/db.php";

$sql = "SELECT * FROM cinta ORDER BY idCinta DESC";
$result = mysqli_query($conexion, $sql);
?>

<h1>Listado de Cintas</h1>
<a href="registrarCinta.php">Registrar Cinta</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Color</th>
        <th>Acciones</th>
    </tr>

    <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $fila['idCinta']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['color']; ?></td>

            <td>
                <a href="editarCinta.php?id=<?php echo $fila['idCinta']; ?>">Editar</a> |
                <a href="eliminarCinta.php?id=<?php echo $fila['idCinta']; ?>"
                   onclick="return confirm('¿Seguro que deseas eliminar esta cinta?');">
                   Eliminar
                </a>
            </td>
        </tr>
    <?php } ?>
</table>
