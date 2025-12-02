<?php
require_once 'conexion.php';
date_default_timezone_set('America/Bogota');
header('Content-Type: application/json'); // Importante para que JS entienda la respuesta

try {
    $conexion = Conexion::Conectar();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión a BD: " . $e->getMessage()]);
    exit();
}

$id_usuario  = isset($_POST['id_usuario'])  ? (int)$_POST['id_usuario']  : 0;
$id_pelicula = isset($_POST['id_pelicula']) ? (int)$_POST['id_pelicula'] : 0;
$precio      = isset($_POST['precio'])      ? $_POST['precio']          : '0.00';

if ($id_usuario <= 0 || $id_pelicula <= 0 || !is_numeric($precio) || (float)$precio <= 0) {
    echo json_encode(["success" => false, "message" => "Datos inválidos."]);
    exit();
}

$conexion->beginTransaction();

try {
    // 1. Obtener nombre usuario
    $stmt = $conexion->prepare("SELECT nombre FROM Usuario WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) { $nombreUsuario = $row['nombre']; } else { throw new Exception("Usuario no encontrado."); }

    // 2. Obtener nombre película
    $stmt = $conexion->prepare("SELECT titulo FROM pelicula WHERE id_pelicula = :id_pelicula");
    $stmt->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { throw new Exception("Película no encontrada."); }
    $nombrePelicula = $row['titulo'];

    // 3. VERIFICACIÓN DE PRÉSTAMO ACTIVO (Esta es la parte clave para tu notificación)
    $sqlCheck = "SELECT COUNT(*) AS total FROM prestamo p JOIN cinta c ON p.cinta_id_cinta = c.id_cinta WHERE p.Usuario_id_usuario = :id_usuario AND c.pelicula_id_pelicula = :id_pelicula AND p.estado_alquiler = 'en curso'";
    $stmt = $conexion->prepare($sqlCheck);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['total'] > 0) {
        throw new Exception("Ya tienes un préstamo activo de esta película. Debes devolverla antes de volver a alquilarla.");
    }

    // 4. Buscar cinta disponible
    $sqlCinta = "SELECT c.id_cinta FROM cinta c LEFT JOIN prestamo p ON c.id_cinta = p.cinta_id_cinta AND p.estado_alquiler = 'en curso' WHERE c.pelicula_id_pelicula = :id_pelicula AND p.id_prestamo IS NULL LIMIT 1 FOR UPDATE";
    $stmt = $conexion->prepare($sqlCinta);
    $stmt->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) { $id_cinta = $row['id_cinta']; } else { throw new Exception("No hay copias disponibles."); }

    // 5. Decrementar el contador de copias de la película
    $sqlDecrement = "UPDATE pelicula SET ncopias = ncopias - 1 WHERE id_pelicula = :id_pelicula AND ncopias > 0";
    $stmtDecrement = $conexion->prepare($sqlDecrement);
    $stmtDecrement->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $stmtDecrement->execute();

    if ($stmtDecrement->rowCount() == 0) {
        throw new Exception("La última copia acaba de ser alquilada por otra persona. Inténtalo de nuevo.");
    }

    // 6. Insertar Préstamo
    $fecha_prestamo = date('Y-m-d H:i:s');
    $fecha_devolucion = date('Y-m-d H:i:s', strtotime('+7 days'));
    $sqlPrestamo = "INSERT INTO prestamo (fecha_prestamo, fecha_devolucion, Usuario_id_usuario, cinta_id_cinta, estado_alquiler) VALUES (:fecha_prestamo, :fecha_devolucion, :id_usuario, :id_cinta, 'en curso')";
    $stmt = $conexion->prepare($sqlPrestamo);
    $stmt->bindParam(':fecha_prestamo', $fecha_prestamo);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_cinta', $id_cinta, PDO::PARAM_INT);
    $stmt->execute();
    $id_prestamo_generado = $conexion->lastInsertId();

    // 7. Insertar Factura
    $sqlFactura = "INSERT INTO factura (Nombre_user, precio_alquiler, fecha_factura, nombre_pelicula, Usuario_id_usuario) VALUES (:nombre_user, :precio_alquiler, :fecha_factura, :id_cinta, :id_usuario_fk)";
    $stmt = $conexion->prepare($sqlFactura);
    $stmt->bindParam(':nombre_user', $nombreUsuario);
    $stmt->bindParam(':precio_alquiler', $precio);
    $stmt->bindParam(':fecha_factura', $fecha_prestamo);
    $stmt->bindParam(':id_cinta', $id_cinta, PDO::PARAM_INT); 
    $stmt->bindParam(':id_usuario_fk', $id_usuario, PDO::PARAM_INT); 
    $stmt->execute();

    // 8. Insertar Notificación de Alquiler Exitoso
    $mensajeNotif = "¡Alquiler exitoso! Has alquilado la película \"$nombrePelicula\". Tienes hasta el " . date('d/m/Y', strtotime($fecha_devolucion)) . " para devolverla.";
    $sqlNotif = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (:id_usuario, :mensaje)";
    $stmtNotif = $conexion->prepare($sqlNotif);
    $stmtNotif->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmtNotif->bindParam(':mensaje', $mensajeNotif);
    $stmtNotif->execute();

    $conexion->commit();

    // Tras el commit, obtener el número de copias actualizado para devolverlo
    $stmtCopias = $conexion->prepare("SELECT ncopias FROM pelicula WHERE id_pelicula = :id_pelicula");
    $stmtCopias->bindParam(':id_pelicula', $id_pelicula, PDO::PARAM_INT);
    $stmtCopias->execute();
    $nuevasCopias = $stmtCopias->fetchColumn();

    echo json_encode([
        "success" => true,
        "message" => "¡Alquiler realizado con éxito!",
        "fecha_devolucion" => $fecha_devolucion,
        "ncopias" => $nuevasCopias ?? 0 // Devolver el nuevo conteo
    ]);

} catch (Exception $e) {
    if ($conexion->inTransaction()) { $conexion->rollBack(); }
    // Aquí se devuelve el mensaje de error que JS mostrará
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>