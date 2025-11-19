<?php
session_start();
// Incluye la clase de conexión a la base de datos
require_once 'conexion.php'; 

// Función de redirección para simplificar el código
function redirect($message, $type = 'error') {
    $_SESSION['notification'] = [
        'message' => $message,
        'type' => $type
    ];
    // Almacena los datos del formulario en caso de error para que el usuario no tenga que reescribir todo
    if ($type === 'error') {
        $_SESSION['form_data'] = [
            'user_identifier' => $_POST['user_identifier'] ?? '',
            'pregunta_seguridad' => $_POST['pregunta_seguridad'] ?? '',
            'respuesta_seguridad' => $_POST['respuesta_seguridad'] ?? ''
        ];
    }
    header('Location: recuperar.php');
    exit();
}

// 1. Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('Acceso no autorizado al script de procesamiento.');
}

// 2. Mapeo de valores del formulario a valores de la BD
// El campo 'pregunta_seguridad' en la BD almacena la pregunta completa.
$question_map = [
    'mascota' => '¿Cuál es el nombre de tu primera mascota?', 
    'madre' => 'Apodo de la infancia?',
    'ciudad' => 'Ciudad donde naciste?',
    'escuela' => 'Nombre de tu primera escuela?'
];

// 3. Capturar y validar datos del formulario
$user_identifier = trim($_POST['user_identifier'] ?? '');
$pregunta_key = $_POST['pregunta_seguridad'] ?? '';
$respuesta_seguridad = trim($_POST['respuesta_seguridad'] ?? '');
$nueva_password = $_POST['nueva_password'] ?? '';

// Verificar campos vacíos
if (empty($user_identifier) || empty($pregunta_key) || empty($respuesta_seguridad) || empty($nueva_password)) {
    redirect('Todos los campos son obligatorios para la recuperación.');
}

// Obtener el texto completo de la pregunta para la consulta SQL
$pregunta_seguridad_db = $question_map[$pregunta_key] ?? null;

if (is_null($pregunta_seguridad_db)) {
    redirect('La pregunta de seguridad seleccionada no es válida.');
}

// 4. Conectar a la base de datos
try {
    $conexion = Conexion::Conectar();
} catch (Exception $e) {
    redirect('Error de conexión a la base de datos.');
}

// 5. Consulta de verificación
// Se busca un usuario que coincida con el identificador (username O email), 
// la pregunta de seguridad Y la respuesta de seguridad.
$sql_select = "SELECT id_usuario FROM Usuario 
               WHERE (username = :identifier OR email = :identifier)
               AND pregunta_seguridad = :pregunta 
               AND respuesta_seguridad = :respuesta";

try {
    $consulta = $conexion->prepare($sql_select);
    $consulta->bindParam(':identifier', $user_identifier);
    $consulta->bindParam(':pregunta', $pregunta_seguridad_db);
    $consulta->bindParam(':respuesta', $respuesta_seguridad);
    $consulta->execute();
    
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    redirect('Error al verificar las credenciales de seguridad.');
}

// 6. Procesar resultado de la verificación
if ($usuario) {
    // Usuario verificado: Proceder con la actualización de la contraseña

    // **NOTA IMPORTANTE:** Se guarda la contraseña en texto plano según lo solicitado.
    $user_id = $usuario['id_usuario'];
    $password_plano = $nueva_password; // Usando el valor sin hashear

    // 7. Consulta de actualización
    $sql_update = "UPDATE Usuario SET password = :new_password WHERE id_usuario = :id_usuario";

    try {
        $consulta_update = $conexion->prepare($sql_update);
        $consulta_update->bindParam(':new_password', $password_plano); // Se enlaza la contraseña en texto plano
        $consulta_update->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
        $consulta_update->execute();

        // 8. Éxito
        redirect('¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.', 'success');

    } catch (PDOException $e) {
        redirect('Error interno al intentar actualizar la contraseña. Intente nuevamente.');
    }

} else {
    // 9. Error de verificación
    redirect('Los datos de identificación, pregunta de seguridad o respuesta no coinciden.');
}

// Cierra la conexión
$conexion = null;
?>