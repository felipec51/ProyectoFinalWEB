<?php
require_once "../inc/db.php";

$id = $_GET['id'];

$sql = "DELETE FROM cinta WHERE idCinta = $id";
mysqli_query($conexion, $sql);

header("Location: listarCinta.php");
exit();
