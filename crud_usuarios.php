<?php

include 'componentes/sidebar.php';
include 'conexion.php'

// Conexión
$conexion = new mysqli("localhost", "root", "", "FinWeb");
$conexion->set_charset("utf8");

// --- ELIMINAR ---
if (isset($_GET['action']) && $_GET['action'] == 'eliminar') {
    $id = intval($_GET['id']);
    $conexion->query("DELETE FROM Usuario WHERE id_usuario = $id");
    header("Location: adminusuarios.php");
    exit;
}

// --- GUARDAR NUEVO ---
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $conexion->query("INSERT INTO Usuario(nombre, username, email, rol_id_rol, fecha_creacion)
                      VALUES ('$nombre', '$username', '$email', '$rol', NOW())");

    header("Location: adminusuarios.php");
    exit;
}

// --- ACTUALIZAR ---
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $conexion->query("UPDATE Usuario SET 
                        nombre='$nombre',
                        username='$username',
                        email='$email',
                        rol_id_rol='$rol'
                      WHERE id_usuario=$id");

    header("Location: adminusuarios.php");
    exit;
}

?>