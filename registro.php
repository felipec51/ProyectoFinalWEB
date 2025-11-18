<?php
// Archivo: registro.php
// ¡IMPORTANTE! Iniciar la sesión debe ser lo primero
session_start();

// Configuración de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php'; 

// Función para limpiar y sanitizar los datos de entrada
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Recolección de datos y sanitización
    // NOTA: Para el propósito de DEPURACIÓN, primero almacenamos los datos para revisar qué falta
    $direccion = sanitize_input($_POST['direccion'] ?? '');
    $username = sanitize_input($_POST['username'] ?? ''); 
    $nombre = sanitize_input($_POST['nombre'] ?? '');
    $apellido = sanitize_input($_POST['apellido'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? ''; 
    $pregunta_seguridad = sanitize_input($_POST['pregunta_seguridad'] ?? '');
    $respuesta_seguridad = sanitize_input($_POST['respuesta_seguridad'] ?? '');
    $telefono = sanitize_input($_POST['telefono'] ?? ''); 

    $nombre_completo = trim($nombre . ' ' . $apellido); 

    // Función de error que guarda datos en sesión y redirige al formulario
    $errorRedirect = function ($message) use ($direccion, $username, $nombre, $apellido, $email, $pregunta_seguridad, $respuesta_seguridad, $telefono) {
        
        // Guardar los datos del formulario en sesión
        $_SESSION['form_data'] = [
            'direccion' => $direccion,
            'username' => $username,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'pregunta_seguridad' => $pregunta_seguridad,
            'respuesta_seguridad' => $respuesta_seguridad,
            'telefono' => $telefono,
        ];
        
        // Guardar la notificación de error
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => $message
        ];
        header("Location: index.php"); 
        exit();
    };

    // =================================================================
    // 3. VALIDACIÓN DE CAMPOS VACÍOS (¡DEPURACIÓN MEJORADA!)
    // =================================================================
    
    // Creamos un mapa de todos los campos requeridos
    $campos_requeridos = [
        'Dirección' => $direccion, 
        'Username' => $username, 
        'Nombre' => $nombre, 
        'Apellido' => $apellido, 
        'Email' => $email, 
        'Contraseña' => $password_raw, // La contraseña raw (sin sanitizar) para la comprobación
        'Pregunta de Seguridad' => $pregunta_seguridad, // <-- ¡Revisa este campo si falla!
        'Respuesta de Seguridad' => $respuesta_seguridad, 
        'Teléfono' => $telefono,
    ];

    $campos_faltantes = [];
    foreach ($campos_requeridos as $nombre_campo => $valor_campo) {
        if (empty($valor_campo)) {
            $campos_faltantes[] = $nombre_campo;
        }
    }

    if (!empty($campos_faltantes)) {
        // 🚨 MENSAJE DE ERROR MEJORADO: Lista exactamente lo que falta.
        $mensaje_error = "Error: Faltan campos obligatorios. Debes completar: **" . implode(', ', $campos_faltantes) . "**.";
        $errorRedirect($mensaje_error);
    }
    
    // --- LÓGICA DE VALIDACIÓN DE TELÉFONO FLEXIBLE ---
    $telefono_cleaned = preg_replace('/[^0-9]/', '', $telefono); 

    if (strlen($telefono_cleaned) < 7 || strlen($telefono_cleaned) > 15) {
        $errorRedirect("El teléfono debe contener entre 7 y 15 dígitos numéricos en total.");
    }
    
    // Validación de longitud mínima de contraseña
    if (strlen($password_raw) < 6) {
        $errorRedirect("La contraseña debe tener al menos 6 caracteres.");
    }

    // Validación de caracteres (nombre completo)
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre_completo)) {
        $errorRedirect("El nombre y apellido solo deben contener letras y espacios.");
    }

    // Validación de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorRedirect("El formato del correo electrónico no es válido.");
    }

    // Validación de username
    if (!preg_match("/^[a-zA-Z0-9._]{3,20}$/", $username)) {
        $errorRedirect("El username debe tener entre 3 y 20 caracteres (solo letras, números, puntos y guiones bajos).");
    }

    try {
        $conn = Conexion::Conectar();
        
        // 4. VERIFICACIÓN DE DISPONIBILIDAD (USERNAME, EMAIL Y TELEFONO)
        $sql_check = "SELECT username, email, telefono FROM Usuario WHERE username = :username OR email = :email OR telefono = :telefono_cleaned";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->bindParam(':telefono_cleaned', $telefono_cleaned); 
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $conflicto = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if ($conflicto['username'] === $username) {
                $errorRedirect("El nombre de usuario **{$username}** ya está en uso.");
            } elseif ($conflicto['email'] === $email) {
                 $errorRedirect("El correo electrónico **{$email}** ya está registrado.");
            } else {
                 $errorRedirect("El número de teléfono **{$telefono_cleaned}** ya está registrado.");
            }
        }
        
        // 5. Valores por defecto
        $rol_id_rol = 2; // Socio

        // 6. Preparar la consulta SQL de INSERCIÓN 
        $sql = "INSERT INTO Usuario (
                    username, nombre, email, password, telefono, direccion, 
                    rol_id_rol, pregunta_seguridad, respuesta_seguridad, fecha_creacion
                ) VALUES (
                    :username, :nombre_completo, :email, :password_raw, :telefono, 
                    :direccion, :rol_id_rol, :pregunta_seguridad, :respuesta_seguridad, NOW()
                )";

        $stmt = $conn->prepare($sql);

        // Bind de los parámetros
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':nombre_completo', $nombre_completo); 
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_raw', $password_raw); // Contraseña en texto plano
        $stmt->bindParam(':telefono', $telefono); 
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':rol_id_rol', $rol_id_rol);
        $stmt->bindParam(':pregunta_seguridad', $pregunta_seguridad);
        $stmt->bindParam(':respuesta_seguridad', $respuesta_seguridad);

        // Ejecutar la inserción
        if ($stmt->execute()) {
            
            unset($_SESSION['form_data']); 
            
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => '¡Registro exitoso! Ya puedes iniciar sesión con tu cuenta.'
            ];
            header("Location: iniciarsesion.php"); 
            exit();
        } else {
            $errorRedirect("Error interno al registrar. Intenta de nuevo o contacta a soporte.");
        }

    } catch (PDOException $e) {
        $errorRedirect("Error de servidor. Contacta al administrador: " . $e->getMessage());
    }

} else {
    header("Location: index.php");
    exit();
}
?>