<?php

$DB_HOST = 'localhost';
$DB_NAME = 'FinWeb';
$DB_USER = 'root';
$DB_PASS = ''; // Cambia si tu MySQL tiene contraseña
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    $conn = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        $options
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>