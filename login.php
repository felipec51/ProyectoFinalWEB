<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/inc/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // CONSULTAR usuario
    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE username = :u LIMIT 1");
    $stmt->execute([":u" => $username]);
    $row = $stmt->fetch();
   // echo '<pre>'; var_dump($row); echo '</pre>';
    //exit;


    if ($row) {

        // CONTRASEÑA SIN HASH
        if ($password === $row["password"]) {

            $_SESSION["id_usuario"] = $row["id_usuario"];
            $_SESSION["nombre"]     = $row["nombre"];
            $_SESSION["rol_id_rol"] = $row["rol_id_rol"];

            // 1 = admin
            if ($row["rol_id_rol"] == 1) {
                header("Location: paneladmin.php");
            } else {
                header("Location: peliculasMenu.php");
            }
            exit;
        }

    }

    $_SESSION["error"] = "Usuario o contraseña incorrectos.";
    header("Location: iniciarsesion.php");
    exit;
}
?>
