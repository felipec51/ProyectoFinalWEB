<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: peliculasMenu.php"); 
    exit;
}
?>