<?php
session_start();

require_once 'conexion.php'; 


function redirect($message, $type = 'error') {
    $_SESSION['notification'] = [
        'message' => $message,
        'type' => $type
    ];
    
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('Acceso no autorizado al script de procesamiento.');
}

$question_map = [
    'mascota' => '¿Cuál es el nombre de tu primera mascota?', 
    'madre' => 'Apodo de la infancia?',
    'ciudad' => 'Ciudad donde naciste?',
    'escuela' => 'Nombre de tu primera escuela?'
];
$user_identifier = trim($_POST['user_identifier'] ?? '');
$pregunta_key = $_POST['pregunta_seguridad'] ?? '';
$respuesta_seguridad = trim($_POST['respuesta_seguridad'] ?? '');
$nueva_password = $_POST['nueva_password'] ?? '';
if (empty($user_identifier) || empty($pregunta_key) || empty($respuesta_seguridad) || empty($nueva_password)) {
    redirect('Todos los campos son obligatorios para la recuperación.');
}

$pregunta_seguridad_db = $question_map[$pregunta_key] ?? null;

if (is_null($pregunta_seguridad_db)) {
    redirect('La pregunta de seguridad seleccionada no es válida.');
}

try {
    $conexion = Conexion::Conectar();
} catch (Exception $e) {
    redirect('Error de conexión a la base de datos.');
}

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

if ($usuario) {
    $user_id = $usuario['id_usuario'];
    $password_plano = $nueva_password; 

    
    $sql_update = "UPDATE Usuario SET password = :new_password WHERE id_usuario = :id_usuario";

    try {
        $consulta_update = $conexion->prepare($sql_update);
        $consulta_update->bindParam(':new_password', $password_plano); 
        $consulta_update->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
        $consulta_update->execute();
        
        redirect('¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.', 'success');

    } catch (PDOException $e) {
        redirect('Error interno al intentar actualizar la contraseña. Intente nuevamente.');
    }

} else {
    
    redirect('Los datos de identificación, pregunta de seguridad o respuesta no coinciden.');
}


$conexion = null;
?>