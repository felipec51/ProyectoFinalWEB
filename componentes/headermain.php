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

    // 1. Obtener datos del usuario desde la base de datos
    try {
        $conexion = Conexion::Conectar();
        $sql = "SELECT nombre FROM Usuario WHERE id_usuario = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $userNombreCompleto = $userData['nombre'];
            
            // Lógica para obtener las iniciales (Ejemplo: "Felipe Murillo" -> "FM")
            $nameParts = explode(" ", trim($userNombreCompleto));
            $initials = "";
            
            // Tomar la primera letra de las dos primeras palabras si existen
            if (count($nameParts) >= 1) {
                $initials .= strtoupper(substr($nameParts[0], 0, 1));
            }
            if (count($nameParts) >= 2) {
                // Solo tomar la inicial de la segunda palabra
                $initials .= strtoupper(substr($nameParts[1], 0, 1));
            }

            // Si se logró obtener alguna inicial
            if (!empty($initials)) {
                 $userNameInitials = $initials;
            } else if (count($nameParts) >= 1) {
                 // Caso de nombre de una sola palabra, toma las dos primeras letras
                 $userNameInitials = strtoupper(substr($nameParts[0], 0, 2));
            }

        }

    } catch (Exception $e) {
        // En caso de error de conexión o consulta
        error_log("Error al obtener datos de usuario en navheader: " . $e->getMessage());
        $userNameInitials = "!"; 
    }

    // 2. Definir los enlaces de navegación y sus archivos
    $navLinks = [
        'Inicio' => 'peliculasMenu.php',
        'Perfil' => 'perfil.php',
        'Favoritos' => 'favoritos.php',
        'Mi Lista' => 'milista.php',
        'Explorar' => 'explorar.php',
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
                        // 3. Aplica la clase 'active' si el nombre de la página coincide
                        $isActive = ($name === $currentPage) ? ' active' : '';
                    ?>
                    <a href="<?php echo $url; ?>" class="nav-link<?php echo $isActive; ?>">
                        <?php echo $name; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="user-actions">
                <button class="notification-btn" aria-label="Notificaciones">
                    <img src="./imgs/icons/campana.svg" alt="Notificaciones" />
                    <span class="badge">2</span>
                </button>
                <div class="avatar-circle" title="<?php echo htmlspecialchars($userNombreCompleto); ?>">
                    <span><?php echo $userNameInitials; ?></span>
                </div>
            </div>
        </div>
    </header>
<?php
}
?>