<?php
session_start();
require_once __DIR__ . '/inc/db.php'; // AJUSTA A TU RUTA REAL

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Conexión
    $db = new Conexion();
    $conn = $db->conectar();

    $sql = "SELECT idUsuario, nombre, email, password, rol FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {

        $row = $resultado->fetch_assoc();

        if (password_verify($password, $row["password"])) {

            $_SESSION["idUsuario"] = $row["idUsuario"];
            $_SESSION["nombre"]    = $row["nombre"];
            $_SESSION["rol"]       = $row["rol"];

            // Redirección según rol
            if ($row["rol"] === "admin") {
                header("Location: paneladmin.php");
            } else {
                header("Location: peliculasMenu.php");
            }
            exit;
        }
    }

    // Error si falla
    $_SESSION["error"] = "Correo o contraseña incorrectos.";
    header("Location: iniciarsesion.php");
    exit;
}
?>
