<?php
// Archivo: login.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1. INCLUSIÓN DEL ARCHIVO DE CONEXIÓN
require_once 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 2. Recibir el valor de entrada (username o email) y la contraseña
    $input_user = trim($_POST["username"] ?? ""); 
    $password = $_POST["password"] ?? ""; 

    // 3. Verificar campos vacíos
    if (empty($input_user) || empty($password)) {
        $_SESSION["error"] = "Por favor, ingresa el usuario/email y la contraseña.";
        header("Location: iniciarsesion.php");
        exit;
    }

    try {
        // 4. OBTENER LA CONEXIÓN PDO
        $conn = Conexion::Conectar();

        // 5. CONSULTA UNIFICADA: Busca la fila por username O email
        // Esta consulta permite usar el username O el email.
        $sql = "SELECT id_usuario, username, nombre, password, rol_id_rol 
                FROM Usuario 
                WHERE username = :input_user OR email = :input_user 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':input_user', $input_user);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 6. Verificación de Credenciales
        if ($row) {
            
            // ✅ CORRECCIÓN APLICADA: 
            // Usa la comparación directa ya que las contraseñas están en texto plano en la DB.
            if ($password === $row["password"]) { 
                
                // Credenciales correctas: Crear variables de sesión
                $_SESSION["id_usuario"] = $row["id_usuario"];
                $_SESSION["nombre"]     = $row["nombre"];
                $_SESSION["username"]   = $row["username"];
                $_SESSION["rol_id_rol"] = $row["rol_id_rol"];
                
                // Redirigir según el rol
                if ($row["rol_id_rol"] == 1) { // 1 = admin
                    header("Location: paneladmin.php");
                } else { // 2 = socio
                    header("Location: peliculasMenu.php");
                }
                exit;
            
            } else {
                 // Contraseña incorrecta
                 $_SESSION["error"] = "Usuario o contraseña incorrectos.";
            }

        } else {
            // Usuario no encontrado
            $_SESSION["error"] = "Usuario o contraseña incorrectos.";
        }

    } catch (PDOException $e) {
        // Error de base de datos
        $_SESSION["error"] = "Error de servidor al intentar iniciar sesión. Inténtalo más tarde.";
        error_log("Login Error: " . $e->getMessage());
    }

    // 7. Redirigir si hubo cualquier error
    header("Location: iniciarsesion.php");
    exit;
}
?>