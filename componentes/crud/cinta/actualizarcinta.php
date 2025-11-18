<?php
require_once "../inc/db.php";

$id     = $_POST['id'];
$nombre = $_POST['nombre'];
$color  = $_POST['color'];

$sql = "UPDATE cinta SET 
        nombre = '$nombre', 
        color = '$color'
        WHERE idCinta = $id";

mysqli_query($conexion, $sql);

header("Location: listarCinta.php");
exit();
