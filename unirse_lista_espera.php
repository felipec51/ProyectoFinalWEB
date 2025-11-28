<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

// Verificar que la solicitud sea POST y que se haya enviado el id de la película
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pelicula_id'])) {
    $id_pelicula = $_POST['pelicula_id'];
    $id_usuario = $_SESSION['id_usuario'];

    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    try {
        // 1. Verificar si el usuario ya está en la lista de espera para esta película
        $sql_check_lista = "SELECT * FROM lista_espera WHERE Usuario_id_usuario = :id_usuario AND pelicula_id_pelicula = :id_pelicula";
        $stmt_check_lista = $conexion->prepare($sql_check_lista);
        $stmt_check_lista->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_check_lista->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_check_lista->execute();

        if ($stmt_check_lista->fetch()) {
            $_SESSION['mensaje_lista_espera'] = "Ya estás en la lista de espera para esta película.";
            header("Location: pelicula.php?id=" . $id_pelicula);
            exit;
        }

        // 2. Verificar si el usuario ya tiene una copia de esta película alquilada
        $sql_check_prestamo = "SELECT p.id_prestamo FROM prestamo p JOIN cinta c ON p.cinta_id_cinta = c.id_cinta WHERE p.Usuario_id_usuario = :id_usuario AND c.pelicula_id_pelicula = :id_pelicula";
        $stmt_check_prestamo = $conexion->prepare($sql_check_prestamo);
        $stmt_check_prestamo->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_check_prestamo->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_check_prestamo->execute();

        if ($stmt_check_prestamo->fetch()) {
            $_SESSION['mensaje_lista_espera'] = "Ya tienes esta película alquilada.";
            header("Location: pelicula.php?id=" . $id_pelicula);
            exit;
        }

        // 3. Insertar al usuario en la lista de espera
        $fecha_solicitud = date('Y-m-d H:i:s');
        $sql_insert = "INSERT INTO lista_espera (Usuario_id_usuario, pelicula_id_pelicula, fecha_solicitud) VALUES (:id_usuario, :id_pelicula, :fecha_solicitud)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt_insert->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
        $stmt_insert->bindParam(':fecha_solicitud', $fecha_solicitud);
        $stmt_insert->execute();

        $_SESSION['mensaje_lista_espera'] = "¡Te has añadido a la lista de espera con éxito! Te notificaremos cuando esté disponible.";
        header("Location: pelicula.php?id=" . $id_pelicula);
        exit;

    } catch (Exception $e) {
        $_SESSION['mensaje_lista_espera_error'] = "Ha ocurrido un error. Por favor, inténtalo de nuevo.";
        // Log del error: error_log($e->getMessage());
        header("Location: pelicula.php?id=" . $id_pelicula);
        exit;
    }
} else {
    // Redirigir si no es una solicitud POST válida
    header("Location: listado_peliculas.php");
    exit;
}
?>