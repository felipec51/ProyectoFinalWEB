<?php
require_once 'conexion.php'; 

function navheader(string $currentPage, int $userId): void
{
    $userNameInitials = "U"; 
    $userNombreCompleto = "Usuario Desconocido";
    $unreadCount = 0;

    
    try {
        $conexion = Conexion::Conectar();
        
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

        $sqlCount = "SELECT COUNT(*) FROM notificaciones WHERE id_usuario = :id AND leido = 0";
        $stmtCount = $conexion->prepare($sqlCount);
        $stmtCount->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtCount->execute();
        $unreadCount = $stmtCount->fetchColumn();

    } catch (Exception $e) {
        error_log("Error en navheader: " . $e->getMessage());
        $userNameInitials = "!"; 
    }
    $navLinks = [
        'Inicio' => 'peliculasMenu.php',
        'Perfil' => 'perfil.php',
        'Mis Alquileres' => 'mis_alquileres.php'
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