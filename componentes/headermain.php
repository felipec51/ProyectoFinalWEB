<?php
// Archivo: headermain.php

// Asegúrate de que la ruta a tu archivo de conexión sea correcta
require_once 'conexion.php'; 

/**
 * Renderiza el encabezado de navegación.
 * * @param string $currentPage El nombre de la página actual para aplicar la clase 'active'.
 * @param int $userId El ID del usuario actualmente logueado.
 */
function navheader(string $currentPage, int $userId): void
{
    $userNameInitials = "U"; // Iniciales por defecto
    $userNombreCompleto = "Usuario Desconocido";
    $unreadCount = 0;

    // 1. Obtener datos del usuario y conteo de notificaciones
    try {
        $conexion = Conexion::Conectar();
        
        // Datos del usuario
        $sqlUser = "SELECT nombre FROM Usuario WHERE id_usuario = :id";
        $stmtUser = $conexion->prepare($sqlUser);
        $stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtUser->execute();
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $userNombreCompleto = $userData['nombre'];
            $nameParts = explode(" ", trim($userNombreCompleto));
            $initials = "";
            if (count($nameParts) >= 1) $initials .= strtoupper(substr($nameParts[0], 0, 1));
            if (count($nameParts) >= 2) $initials .= strtoupper(substr($nameParts[1], 0, 1));
            if (!empty($initials)) {
                 $userNameInitials = $initials;
            } else if (count($nameParts) >= 1) {
                 $userNameInitials = strtoupper(substr($nameParts[0], 0, 2));
            }
        }

        // Conteo de notificaciones no leídas
        $sqlCount = "SELECT COUNT(*) FROM notificaciones WHERE id_usuario = :id AND leido = 0";
        $stmtCount = $conexion->prepare($sqlCount);
        $stmtCount->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtCount->execute();
        $unreadCount = $stmtCount->fetchColumn();

    } catch (Exception $e) {
        error_log("Error en navheader: " . $e->getMessage());
        $userNameInitials = "!"; 
    }

    // 2. Definir los enlaces de navegación
    $navLinks = [
        'Inicio' => 'peliculasMenu.php',
        'Perfil' => 'perfil.php',
        'Favoritos' => 'favoritos.php',
        'Mis Alquileres' => 'mis_alquileres.php',
        'Actores' => 'seleccion_favoritos_directores.php',
    ];
?>
    <link rel="stylesheet" href="./styles/headermain.css" />

    <header class="top-navigation">
        <div class="containernav nav-content">
            <div class="brand">
                <span class="brand-name">RewindCodeFilm</span>
            </div>

            <nav class="main-nav">
                <?php foreach ($navLinks as $name => $url): ?>
                    <?php 
                        $isActive = ($name === $currentPage) ? ' active' : '';
                    ?>
                    <a href="<?php echo $url; ?>" class="nav-link<?php echo $isActive; ?>">
                        <?php echo $name; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="user-actions">
                <a href="notificaciones.php" class="notification-btn" aria-label="Notificaciones">
                    <img src="./imgs/icons/campana.svg" alt="Notificaciones" />
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
                <div class="avatar-circle" title="<?php echo htmlspecialchars($userNombreCompleto); ?>">
                    <span><?php echo $userNameInitials; ?></span>
                </div>
            </div>
        </div>
    </header>
<?php
}
?>