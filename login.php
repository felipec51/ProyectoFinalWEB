<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


require_once 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $input_user = trim($_POST["username"] ?? ""); 
    $password = $_POST["password"] ?? ""; 
    if (empty($input_user) || empty($password)) {
        $_SESSION["error"] = "Por favor, ingresa el usuario/email y la contraseña.";
        header("Location: iniciarsesion.php");
        exit;
    }

    try {
        
        $conn = Conexion::Conectar();
        $sql = "SELECT id_usuario, username, nombre, password, rol_id_rol 
                FROM Usuario 
                WHERE username = :input_user OR email = :input_user 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':input_user', $input_user);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        if ($row) {
            
            if ($password === $row["password"]) {          
                $_SESSION["id_usuario"] = $row["id_usuario"];
                $_SESSION["nombre"]     = $row["nombre"];
                $_SESSION["username"]   = $row["username"];
                $_SESSION["rol_id_rol"] = $row["rol_id_rol"];
                if ($row["rol_id_rol"] == 1) { 
                    header("Location: paneladmin.php");
                } else { 
                    header("Location: peliculasMenu.php");
                }
                exit;
            
            } else {
                 
                 $_SESSION["error"] = "Usuario o contraseña incorrectos.";
            }

        } else {
            
            $_SESSION["error"] = "Usuario o contraseña incorrectos.";
        }

    } catch (PDOException $e) {
        
        $_SESSION["error"] = "Error de servidor al intentar iniciar sesión. Inténtalo más tarde.";
        error_log("Login Error: " . $e->getMessage());
    }

    
    header("Location: iniciarsesion.php");
    exit;
}
?>