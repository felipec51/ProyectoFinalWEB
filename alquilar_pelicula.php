<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pelicula_id'])) {
    $id_pelicula = $_POST['pelicula_id'];
    $id_usuario = $_SESSION['id_usuario'];

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    try {
        $conexion->beginTransaction();

        // 1. Buscar una cinta disponible y bloquearla para evitar que otro la tome
        $sql_find_cinta = "SELECT id_cinta FROM cinta WHERE pelicula_id_pelicula = :id_pelicula AND estado = 'disponible' LIMIT 1 FOR UPDATE";
        $stmt_find = $conexion->prepare($sql_find_cinta);
        $stmt_find->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_find->execute();
        $cinta = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if ($cinta) {
            $id_cinta = $cinta['id_cinta'];

            // 2. Actualizar el estado de la cinta a 'prestada'
            $sql_update_cinta = "UPDATE cinta SET estado = 'prestada' WHERE id_cinta = :id_cinta";
            $stmt_update = $conexion->prepare($sql_update_cinta);
            $stmt_update->bindParam(':id_cinta', $id_cinta, PDO::PARAM_INT);
            $stmt_update->execute();

            // 3. Insertar el registro del préstamo
            $fecha_prestamo = date('Y-m-d H:i:s');
            $fecha_devolucion = date('Y-m-d H:i:s', strtotime('+7 days')); // Alquiler por 7 días

            $sql_insert_prestamo = "INSERT INTO prestamo (fecha_prestamo, fecha_devolucion, Usuario_id_usuario, cinta_id_cinta) VALUES (:fecha_prestamo, :fecha_devolucion, :id_usuario, :id_cinta)";
            $stmt_insert = $conexion->prepare($sql_insert_prestamo);
            $stmt_insert->bindParam(':fecha_prestamo', $fecha_prestamo);
            $stmt_insert->bindParam(':fecha_devolucion', $fecha_devolucion);
            $stmt_insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->bindParam(':id_cinta', $id_cinta, PDO::PARAM_INT);
            $stmt_insert->execute();

            // 4. Confirmar la transacción
            $conexion->commit();

            // Redirigir con mensaje de éxito
            $_SESSION['mensaje_alquiler'] = "¡Película alquilada con éxito! Disfrútala.";
            header("Location: perfil.php");
            exit;

        } else {
            // No hay cintas disponibles, deshacer la transacción
            $conexion->rollBack();
            $_SESSION['mensaje_alquiler_error'] = "Lo sentimos, no hay copias disponibles en este momento.";
            header("Location: pelicula.php?id=" . $id_pelicula);
            exit;
        }

    } catch (Exception $e) {
        // En caso de error, deshacer la transacción
        $conexion->rollBack();
        $_SESSION['mensaje_alquiler_error'] = "Ha ocurrido un error al procesar tu solicitud. Por favor, inténtalo de nuevo.";
        // Log del error: error_log($e->getMessage());
        header("Location: pelicula.php?id=" . $id_pelicula);
        exit;
    }
} else {
    // Si no es una solicitud POST, redirigir
    header("Location: listado_peliculas.php");
    exit;
}
?>