<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$prestamo_id = isset($_POST['prestamo_id']) ? (int)$_POST['prestamo_id'] : 0;
$user_id = $_SESSION["id_usuario"];

if ($prestamo_id <= 0) {
    $_SESSION['mensaje_alquiler_error'] = "ID de préstamo inválido.";
    header("Location: mis_alquileres.php");
    exit;
}

$conexion = Conexion::Conectar();

try {
    $conexion->beginTransaction();

    // 1. Obtener el ID de la película y verificar que el préstamo es válido y pertenece al usuario
    $sql_get_info = "SELECT c.pelicula_id_pelicula, p.titulo 
                     FROM prestamo pr
                     JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta
                     JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula
                     WHERE pr.id_prestamo = :prestamo_id 
                       AND pr.Usuario_id_usuario = :user_id 
                       AND pr.estado_alquiler = 'en curso'";
    
    $stmt_get_info = $conexion->prepare($sql_get_info);
    $stmt_get_info->bindParam(':prestamo_id', $prestamo_id, PDO::PARAM_INT);
    $stmt_get_info->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_get_info->execute();
    $info = $stmt_get_info->fetch(PDO::FETCH_ASSOC);

    if (!$info) {
        // Si no se encuentra, es porque ya fue devuelto, no existe o no es del usuario.
        throw new Exception("Este préstamo no se puede devolver porque no está activo o no te pertenece.");
    }

    $pelicula_id = $info['pelicula_id_pelicula'];
    $movie_title = $info['titulo'];

    // 2. Actualizar el estado del préstamo a 'finalizado'
    $sql_update = "UPDATE prestamo SET estado_alquiler = 'finalizado' WHERE id_prestamo = :prestamo_id";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bindParam(':prestamo_id', $prestamo_id, PDO::PARAM_INT);
    $stmt_update->execute();

    // 3. Incrementar el contador de copias de la película
    $sql_increment = "UPDATE pelicula SET ncopias = ncopias + 1 WHERE id_pelicula = :pelicula_id";
    $stmt_increment = $conexion->prepare($sql_increment);
    $stmt_increment->bindParam(':pelicula_id', $pelicula_id, PDO::PARAM_INT);
    $stmt_increment->execute();

    // El trigger 'trg_notify_on_availability' que creamos en la BD se encargará
    // automáticamente de notificar a los usuarios en lista de espera si ncopias pasa de 0 a 1.

    $conexion->commit();

    $_SESSION['mensaje_alquiler'] = "¡Película '{$movie_title}' devuelta con éxito!";

} catch (Exception $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    
    error_log("Error al devolver la película: " . $e->getMessage());
    $_SESSION['mensaje_alquiler_error'] = "Error al devolver la película: " . $e->getMessage();
}

// Redirigir siempre al final
header("Location: mis_alquileres.php");
exit;
?>

