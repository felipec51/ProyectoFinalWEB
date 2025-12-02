<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexion.php';
include 'componentes/headermain.php';

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];
$notifications = [];

try {
    $conexion = Conexion::Conectar();

    // 2. Lógica para marcar notificaciones como leídas
    // Marcar una notificación específica como leída
    if (isset($_GET['mark_read'])) {
        $notification_id = filter_var($_GET['mark_read'], FILTER_VALIDATE_INT);
        if ($notification_id) {
            $sqlMarkOne = "UPDATE notificaciones SET leido = 1 WHERE id_notificacion = :notif_id AND id_usuario = :user_id";
            $stmtMarkOne = $conexion->prepare($sqlMarkOne);
            $stmtMarkOne->bindParam(':notif_id', $notification_id, PDO::PARAM_INT);
            $stmtMarkOne->bindParam(':user_id', $usuario_logueado_id, PDO::PARAM_INT);
            $stmtMarkOne->execute();
            // Redirigir para limpiar la URL
            header("Location: notificaciones.php");
            exit;
        }
    }

    // Marcar todas las notificaciones como leídas
    if (isset($_GET['mark_all']) && $_GET['mark_all'] === 'true') {
        $sqlMarkAll = "UPDATE notificaciones SET leido = 1 WHERE id_usuario = :user_id AND leido = 0";
        $stmtMarkAll = $conexion->prepare($sqlMarkAll);
        $stmtMarkAll->bindParam(':user_id', $usuario_logueado_id, PDO::PARAM_INT);
        $stmtMarkAll->execute();
        // Redirigir para limpiar la URL
        header("Location: notificaciones.php");
        exit;
    }

    // 3. Obtener todas las notificaciones del usuario (corregido)
    $sqlNotifications = "SELECT id_notificacion, mensaje, fecha, leido FROM notificaciones WHERE id_usuario = :id ORDER BY fecha DESC";
    $stmtNotifications = $conexion->prepare($sqlNotifications);
    $stmtNotifications->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
    $stmtNotifications->execute();
    $notifications = $stmtNotifications->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log("Error en la página de notificaciones: " . $e->getMessage());
    // Opcional: mostrar un mensaje de error al usuario
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/config.css" />
    <link rel="stylesheet" href="./styles/notificaciones.css" />
    <link rel="stylesheet" href="./styles/perfil.css" /> <!-- Dependiendo de los estilos que uses -->
</head>
<body>
    <?php navheader("Notificaciones", $usuario_logueado_id); ?>

    <div class="container page-container">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="peliculasMenu.php" class="crumb-link">Página Principal</a>
            <span class="crumb-separator">></span>
            <span class="crumb-current">Notificaciones</span>
        </nav>

        <main class="main-content">
            <div class="notifications-header">
                <h1>Notificaciones</h1>
                <a href="notificaciones.php?mark_all=true" class="mark-all-btn">Marcar todas como leídas</a>
            </div>
            
            <section class="notifications-section">
                <?php if (!empty($notifications)): ?>
                    <div class="notifications-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item card <?php echo !$notification['leido'] ? 'unread' : 'read'; ?>">
                                <p class="notification-message"><?php echo htmlspecialchars($notification['mensaje']); ?></p>
                                <span class="notification-date"><?php echo date('d/m/Y H:i', strtotime($notification['fecha'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-notifications">
                        <p>No tienes notificaciones por el momento.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>