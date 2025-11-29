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

if ($prestamo_id <= 0) {
    $_SESSION['mensaje_alquiler_error'] = "ID de préstamo inválido.";
    header("Location: mis_alquileres.php");
    exit;
}

try {
    $conexion = Conexion::Conectar();
    $conexion->beginTransaction();

    
    
    $sql_update = "UPDATE prestamo SET estado_alquiler = 'finalizado' WHERE id_prestamo = :prestamo_id AND Usuario_id_usuario = :user_id AND estado_alquiler = 'en curso'";
    
    $stmt = $conexion->prepare($sql_update);
    $stmt->bindParam(':prestamo_id', $prestamo_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION["id_usuario"], PDO::PARAM_INT);
    $stmt->execute();
    
    $rows_affected = $stmt->rowCount();

    if ($rows_affected === 0) {
        
        $conexion->rollBack();
        
        $sql_title = "SELECT p.titulo FROM prestamo pr JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula WHERE pr.id_prestamo = :id";
        $stmt_title = $conexion->prepare($sql_title);
        $stmt_title->bindParam(':id', $prestamo_id, PDO::PARAM_INT);
        $stmt_title->execute();
        $movie_title = $stmt_title->fetchColumn() ?: "una película";

        $_SESSION['mensaje_alquiler_error'] = "La película '{$movie_title}' ya había sido devuelta o no es tuya.";
        header("Location: mis_alquileres.php");
        exit;
    }

    $conexion->commit();

    
    $sql_title = "SELECT p.titulo FROM prestamo pr JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula WHERE pr.id_prestamo = :id";
    $stmt_title = $conexion->prepare($sql_title);
    $stmt_title->bindParam(':id', $prestamo_id, PDO::PARAM_INT);
    $stmt_title->execute();
    $movie_title = $stmt_title->fetchColumn() ?: "una película";

    $_SESSION['mensaje_alquiler'] = "¡Película '{$movie_title}' devuelta con éxito! Gracias por tu devolución.";

} catch (Exception $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    
    $error_message = strpos($e->getMessage(), 'SQLSTATE') !== false ? "Error en la base de datos al devolver la película." : $e->getMessage();
    error_log("Error al devolver la película: " . $e->getMessage());
    $_SESSION['mensaje_alquiler_error'] = "Error al devolver la película: {$error_message}";
}


header("Location: mis_alquileres.php");
exit;
?>
