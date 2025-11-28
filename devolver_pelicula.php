<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prestamo_id'])) {
    $id_prestamo = $_POST['prestamo_id'];
    $id_usuario = $_SESSION['id_usuario'];

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    try {
        $conexion->beginTransaction();

        // 1. Obtener información del préstamo y la cinta, y bloquear la fila
        $sql_get_info = "
            SELECT pr.cinta_id_cinta, c.pelicula_id_pelicula, p.titulo
            FROM prestamo pr
            JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta
            JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula
            WHERE pr.id_prestamo = :id_prestamo AND pr.Usuario_id_usuario = :id_usuario
            FOR UPDATE";
        $stmt_get_info = $conexion->prepare($sql_get_info);
        $stmt_get_info->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
        $stmt_get_info->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_get_info->execute();
        $info = $stmt_get_info->fetch(PDO::FETCH_ASSOC);

        if ($info) {
            $id_cinta = $info['cinta_id_cinta'];
            $id_pelicula = $info['pelicula_id_pelicula'];
            $titulo_pelicula = $info['titulo'];

            // 2. Eliminar el préstamo
            $sql_delete_prestamo = "DELETE FROM prestamo WHERE id_prestamo = :id_prestamo";
            $stmt_delete = $conexion->prepare($sql_delete_prestamo);
            $stmt_delete->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
            $stmt_delete->execute();

            // 3. Actualizar el estado de la cinta a 'disponible'
            $sql_update_cinta = "UPDATE cinta SET estado = 'disponible' WHERE id_cinta = :id_cinta";
            $stmt_update = $conexion->prepare($sql_update_cinta);
            $stmt_update->bindParam(':id_cinta', $id_cinta, PDO::PARAM_INT);
            $stmt_update->execute();

            // 4. Comprobar la lista de espera
            $sql_check_espera = "SELECT id_espera, Usuario_id_usuario FROM lista_espera WHERE pelicula_id_pelicula = :id_pelicula ORDER BY fecha_solicitud ASC LIMIT 1";
            $stmt_check_espera = $conexion->prepare($sql_check_espera);
            $stmt_check_espera->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
            $stmt_check_espera->execute();
            $espera_usuario = $stmt_check_espera->fetch(PDO::FETCH_ASSOC);

            if ($espera_usuario) {
                $id_usuario_notificar = $espera_usuario['Usuario_id_usuario'];
                $id_espera = $espera_usuario['id_espera'];

                // 5. Crear notificación
                $mensaje = "¡La película '" . htmlspecialchars($titulo_pelicula) . "' que esperabas ya está disponible!";
                $sql_notificar = "INSERT INTO notificaciones (usuario_id, mensaje, fecha_creacion) VALUES (:id_usuario, :mensaje, NOW())";
                $stmt_notificar = $conexion->prepare($sql_notificar);
                $stmt_notificar->bindParam(':id_usuario', $id_usuario_notificar, PDO::PARAM_INT);
                $stmt_notificar->bindParam(':mensaje', $mensaje);
                $stmt_notificar->execute();

                // 6. Eliminar de la lista de espera
                $sql_delete_espera = "DELETE FROM lista_espera WHERE id_espera = :id_espera";
                $stmt_delete_espera = $conexion->prepare($sql_delete_espera);
                $stmt_delete_espera->bindParam(':id_espera', $id_espera, PDO::PARAM_INT);
                $stmt_delete_espera->execute();
            }

            // 7. Confirmar la transacción
            $conexion->commit();

            $_SESSION['mensaje_alquiler'] = "Película devuelta correctamente.";
            header("Location: perfil.php");
            exit;

        } else {
            // El préstamo no existe o no pertenece al usuario
            $conexion->rollBack();
            $_SESSION['mensaje_alquiler_error'] = "No se pudo procesar la devolución.";
            header("Location: perfil.php");
            exit;
        }

    } catch (Exception $e) {
        $conexion->rollBack();
        $_SESSION['mensaje_alquiler_error'] = "Error al devolver la película: " . $e->getMessage();
        // error_log($e->getMessage());
        header("Location: perfil.php");
        exit;
    }
} else {
    header("Location: perfil.php");
    exit;
}
?>