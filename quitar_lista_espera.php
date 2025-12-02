<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar que el usuario esté logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

// 2. Verificar que la solicitud sea POST y que se haya enviado el id de la película
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pelicula_id'])) {
    $id_pelicula = $_POST['pelicula_id'];
    $id_usuario = $_SESSION['id_usuario'];

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    try {
        // 3. Eliminar al usuario de la lista de espera para esa película
        $sql_delete = "DELETE FROM lista_espera WHERE Usuario_id_usuario = :id_usuario AND pelicula_id_pelicula = :id_pelicula";
        $stmt_delete = $conexion->prepare($sql_delete);
        $stmt_delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_delete->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_delete->execute();

        // 4. (Opcional) Crear una notificación de confirmación
        if ($stmt_delete->rowCount() > 0) {
            $sql_pelicula = "SELECT titulo FROM pelicula WHERE id_pelicula = :id_pelicula";
            $stmt_pelicula = $conexion->prepare($sql_pelicula);
            $stmt_pelicula->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
            $stmt_pelicula->execute();
            $titulo_pelicula = $stmt_pelicula->fetchColumn();

            if ($titulo_pelicula) {
                $mensaje_notif = "Has sido eliminado de la lista de espera para \"$titulo_pelicula\".";
                $sql_notif = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (:id_usuario, :mensaje)";
                $stmt_notif = $conexion->prepare($sql_notif);
                $stmt_notif->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_notif->bindParam(':mensaje', $mensaje_notif);
                $stmt_notif->execute();
            }
            $_SESSION['mensaje_lista_espera'] = "Has sido eliminado de la lista de espera.";
        } else {
            $_SESSION['mensaje_lista_espera_error'] = "No estabas en la lista de espera.";
        }
        
        header("Location: pelicula.php?id=" . $id_pelicula);
        exit;

    } catch (Exception $e) {
        $_SESSION['mensaje_lista_espera_error'] = "Ha ocurrido un error al intentar quitarte de la lista.";
        error_log("Error en quitar_lista_espera.php: " . $e->getMessage());
        header("Location: pelicula.php?id=" . $id_pelicula);
        exit;
    }
} else {
    // Redirigir si no es una solicitud POST válida
    header("Location: listado_peliculas.php");
    exit;
}
?>
