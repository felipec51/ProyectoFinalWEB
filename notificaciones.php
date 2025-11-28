<?php
require_once 'conexion.php';
include 'componentes/headermain.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];
$notifications = [];

try {
    $conexion = Conexion::Conectar();

    // Fetch Notifications
    $sqlNotifications = "SELECT mensaje, fecha_creacion, leido FROM notificaciones WHERE usuario_id = :id ORDER BY fecha_creacion DESC";
    $stmtNotifications = $conexion->prepare($sqlNotifications);
    $stmtNotifications->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
    $stmtNotifications->execute();
    $notifications = $stmtNotifications->fetchAll(PDO::FETCH_ASSOC);

    // Mark notifications as read
    $sqlMarkRead = "UPDATE notificaciones SET leido = 1 WHERE usuario_id = :id AND leido = 0";
    $stmtMarkRead = $conexion->prepare($sqlMarkRead);
    $stmtMarkRead->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
    $stmtMarkRead->execute();

} catch (Exception $e) {
    error_log("Error fetching notifications: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/perfil.css" />
    <link rel="stylesheet" href="./styles/notificaciones.css" />
    <link rel="stylesheet" href="styles/config.css" />
</head>
<body>
    <?php navheader("Notificaciones", $usuario_logueado_id); ?>

    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container breadcrumb-content">
            <a href="peliculasMenu.php" class="crumb-link">
                Página Principal
            </a>
            <span class="crumb-current">> Notificaciones</span>
        </div>
    </nav>

    <main class="main-content container">
        <h1>Notificaciones</h1>
        <section class="notifications-section">
            <?php if (!empty($notifications)): ?>
                <div class="notifications-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item card <?php echo $notification['leido'] ? 'read' : 'unread'; ?>">
                            <p class="notification-message"><?php echo htmlspecialchars($notification['mensaje']); ?></p>
                            <span class="notification-date"><?php echo date('d/m/Y H:i', strtotime($notification['fecha_creacion'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tienes notificaciones.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
