<?php
// inc/db.php
// CONFIGURA ESTO con tus datos
$DB_HOST = 'localhost';
$DB_NAME = 'FinWeb';    // o el nombre que uses
$DB_USER = 'root';
$DB_PASS = '';

// Opciones PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    $conn = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // En producción no mostrar error detallado
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

/**
 * Helper: intenta obtener usuario por username o email.
 * Maneja ambas variantes de esquema: tabla 'usuarios' o 'Usuario',
 * y columnas 'password_hash' o 'password'.
 *
 * Devuelve fila asociativa o false.
 */
function getUserByLogin($conn, string $login) {
    // Intentamos con tabla 'usuarios'
    $tables = ['usuarios', 'Usuario'];
    foreach ($tables as $table) {
        try {
            $sql = "SELECT * FROM {$table} WHERE username = :u OR email = :u LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':u' => $login]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            // tabla no existe o error -> ignoramos y probamos la siguiente
            continue;
        }
    }
    return false;
}
