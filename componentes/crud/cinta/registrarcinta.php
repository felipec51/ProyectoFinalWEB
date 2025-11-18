<?php
require_once "../inc/db.php";

if (!empty($_POST)) {
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];

    $sql = "INSERT INTO cinta (nombre, color) VALUES ('$nombre', '$color')";
    mysqli_query($conexion, $sql);

    header("Location: listarCinta.php");
    exit();
}
?>

<h1>Registrar Cinta</h1>

<form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Color:</label>
    <input type="text" name="color" required><br><br>

    <button type="submit">Guardar</button>
</form>

<a href="listarCinta.php">Volver</a>
