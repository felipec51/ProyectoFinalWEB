<?php
// ¡IMPORTANTE! Iniciar la sesión debe ser lo primero
session_start();

// Configuración de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Incluir el archivo de conexión a la base de datos
// ASEGÚRATE DE QUE ESTE ARCHIVO EXISTA Y FUNCIONE CORRECTAMENTE
require_once 'conexion.php'; 

// Función para limpiar y sanitizar los datos de entrada
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // Nota: htmlspecialchars se usa para prevenir XSS, pero no debe usarse en contraseñas
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Recolección y sanitización de datos (incluyendo datos sensibles para el formulario pegajoso)
    $direccion = sanitize_input($_POST['direccion'] ?? '');
    $username = sanitize_input($_POST['username'] ?? ''); 
    $nombre = sanitize_input($_POST['nombre'] ?? '');
    $apellido = sanitize_input($_POST['apellido'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? ''; 
    $pregunta_seguridad = sanitize_input($_POST['pregunta_seguridad'] ?? '');
    $respuesta_seguridad = sanitize_input($_POST['respuesta_seguridad'] ?? '');
    
    // Concatenar nombre y apellido para el campo 'nombre' en la base de datos
    $nombre_completo = trim($nombre . ' ' . $apellido); 

    // Función de error que guarda datos en sesión y redirige al formulario (Sticky Form)
    $errorRedirect = function ($message) use ($direccion, $username, $nombre, $apellido, $email, $pregunta_seguridad, $respuesta_seguridad) {
        
        // Guardar los datos del formulario en sesión (excepto la contraseña por seguridad)
        $_SESSION['form_data'] = [
            'direccion' => $direccion,
            'username' => $username,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'pregunta_seguridad' => $pregunta_seguridad,
            'respuesta_seguridad' => $respuesta_seguridad,
        ];
        
        // Guardar la notificación de error
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => $message
        ];
        // Redirigir al formulario principal (index.php)
        header("Location: index.php"); 
        exit();
    };

    // =================================================================
    // 3. VALIDACIÓN DE CAMPOS Y FORMATOS
    // =================================================================
    
    // Validación de campos vacíos (mínimos)
    if (empty($direccion) || empty($username) || empty($nombre) || empty($apellido) || empty($email) || empty($password_raw) || empty($pregunta_seguridad) || empty($respuesta_seguridad)) {
        $errorRedirect("Todos los campos son obligatorios.");
    }
    
    // Validación de longitud mínima de contraseña
    if (strlen($password_raw) < 6) {
        $errorRedirect("La contraseña debe tener al menos 6 caracteres.");
    }

    // Validación de caracteres (solo letras y espacios para el nombre completo)
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre_completo)) {
        $errorRedirect("El nombre y apellido solo deben contener letras y espacios.");
    }

    // Validación de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorRedirect("El formato del correo electrónico no es válido.");
    }

    // Validación de username (alfanuméricos, puntos y guiones bajos)
    if (!preg_match("/^[a-zA-Z0-9._]{3,20}$/", $username)) {
        $errorRedirect("El username debe tener entre 3 y 20 caracteres (solo letras, números, puntos y guiones bajos).");
    }

    try {
        $conn = Conexion::Conectar();
        
        // =================================================================
        // 4. VERIFICACIÓN DE DISPONIBILIDAD (USERNAME Y EMAIL)
        // =================================================================
        $sql_check = "SELECT username, email FROM Usuario WHERE username = :username OR email = :email";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $conflicto = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if ($conflicto['username'] === $username) {
                $errorRedirect("El nombre de usuario **{$username}** ya está en uso.");
            } else {
                 $errorRedirect("El correo electrónico **{$email}** ya está registrado.");
            }
        }
        
        // 5. Cifrado y valores por defecto
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
        $rol_id_rol = 2; // Asumimos rol 2 para usuario/socio
        $telefono_default = '0'; // Valor por defecto requerido por la base de datos (NOT NULL)

        // 6. Preparar la consulta SQL de INSERCIÓN
        // Nota: Se usan 'nombre' (concatenado), 'telefono'='0', y 'fecha_creacion'=NOW()
        $sql = "INSERT INTO Usuario (
                    username, nombre, email, password, telefono, direccion, 
                    rol_id_rol, pregunta_seguridad, respuesta_seguridad, fecha_creacion
                ) VALUES (
                    :username, :nombre_completo, :email, :password_hashed, :telefono_default, 
                    :direccion, :rol_id_rol, :pregunta_seguridad, :respuesta_seguridad, NOW()
                )";

        $stmt = $conn->prepare($sql);

        // Bind de los parámetros
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nombre_completo', $nombre_completo); 
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hashed', $password_hashed);
        $stmt->bindParam(':telefono_default', $telefono_default); 
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':rol_id_rol', $rol_id_rol);
        $stmt->bindParam(':pregunta_seguridad', $pregunta_seguridad);
        $stmt->bindParam(':respuesta_seguridad', $respuesta_seguridad);

        // Ejecutar la inserción
        if ($stmt->execute()) {
            
            // Limpiar datos de formularios previos de la sesión
            unset($_SESSION['form_data']); 
            
            // ÉXITO: USAR NOTIFICACIÓN Y REDIRECCIONAR a iniciar sesión
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => '¡Registro exitoso! Ya puedes iniciar sesión con tu cuenta.'
            ];
            header("Location: iniciarsesion.php"); 
            exit();
        } else {
            // Error de ejecución si la base de datos falló
            $errorRedirect("Error interno al registrar. Intenta de nuevo o contacta a soporte.");
        }

    } catch (PDOException $e) {
        // Error de base de datos (ej. si falla la conexión)
        $errorRedirect("Error de servidor. Contacta al administrador: " . $e->getMessage());
    }

} else {
    // Si se accede directamente sin POST, redirigir al formulario
    header("Location: index.php");
    exit();
}
?>