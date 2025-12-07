<?php

include 'componentes/headermain.php';
require_once 'conexion.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];

$userData = null;
$userInitials = "U";
$memberSince = "Desconocido";
$favoriteGenres = [];
$favoriteActors = [];     
$favoriteDirectors = [];  


try {
    $conexion = Conexion::Conectar();
    $sqlUser = "SELECT username, nombre, direccion, telefono, email, fecha_creacion, rol_id_rol FROM Usuario WHERE id_usuario = :id";
    $stmtUser = $conexion->prepare($sqlUser);
    $stmtUser->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($userData) {

        $nameParts = explode(" ", trim($userData['nombre']));
        $initials = "";
        if (count($nameParts) >= 1) {
            $initials .= strtoupper(substr($nameParts[0], 0, 1));
        }
        if (count($nameParts) >= 2) {
            $initials .= strtoupper(substr($nameParts[1], 0, 1));
        }
        $userInitials = !empty($initials) ? $initials : strtoupper(substr($userData['nombre'], 0, 2));

        
        $dateObj = new DateTime($userData['fecha_creacion']);
        $memberSince = $dateObj->format('F Y'); 
        $monthNames = [
            'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril',
            'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto',
            'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
        ];
        $memberSince = strtr($memberSince, $monthNames);

        $sqlGenres = "
            SELECT g.nombre 
            FROM gusto_genero gg
            JOIN genero g ON gg.genero_id_genero = g.id_genero
            WHERE gg.Usuario_id_usuario = :id
        ";
        $stmtGenres = $conexion->prepare($sqlGenres);
        $stmtGenres->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
        $stmtGenres->execute();
        $favoriteGenres = $stmtGenres->fetchAll(PDO::FETCH_COLUMN);

        $sqlActors = "
            SELECT a.nombre 
            FROM gusto_actor ga
            JOIN actor a ON ga.actor_id_actor = a.id_actor
            WHERE ga.Usuario_id_usuario = :id
        ";
        $stmtActors = $conexion->prepare($sqlActors);
        $stmtActors->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
        $stmtActors->execute();
        $favoriteActors = $stmtActors->fetchAll(PDO::FETCH_COLUMN);

        $sqlDirectors = "
            SELECT d.nombre 
            FROM gusta_director gd
            JOIN director d ON gd.director_id_director = d.id_director
            WHERE gd.Usuario_id_usuario = :id
        ";
        $stmtDirectors = $conexion->prepare($sqlDirectors);
        $stmtDirectors->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
        $stmtDirectors->execute();
        $favoriteDirectors = $stmtDirectors->fetchAll(PDO::FETCH_COLUMN);

    } else {
        $userData = [
            'nombre' => 'Usuario No Encontrado', 'username' => 'n/a', 'email' => 'n/a',
            'telefono' => 'n/a', 'direccion' => 'n/a', 'rol_id_rol' => 2 
        ];
    }
    
} catch (Exception $e) {
    error_log("Error de base de datos en perfil.php: " . $e->getMessage());
    $userData = [
        'nombre' => 'Error de Carga', 'username' => 'n/a', 'email' => 'Error BD',
        'telefono' => 'Error BD', 'direccion' => 'Error BD', 'rol_id_rol' => 2
    ];
    $userInitials = "!";
}

$nombre = htmlspecialchars($userData['nombre']);
$username = htmlspecialchars($userData['username']);
$email = htmlspecialchars($userData['email']);
$telefono = htmlspecialchars($userData['telefono']);
$direccion = nl2br(htmlspecialchars($userData['direccion'])); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - CineMax</title>
    <link rel="stylesheet" href="./styles/perfil.css" />
    <link rel="stylesheet" href="styles/config.css" />
</head>
<body>
    
    <?php navheader("Perfil", $usuario_logueado_id)?>

    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container breadcrumb-content">
            <a href="peliculasMenu.php" class="crumb-link">
                <img class="icon-sm" src="img/vector-22.svg" alt="" /> 
                Página Principal
            </a>
            <span class="crumb-current">> Perfil</span>
        </div>
    </nav>

    <main class="main-content container">
        
        <section class="profile-header">
            <div class="profile-info">
                <div class="avatar-large">
                    <span><?php echo $userInitials; ?></span>
                </div>
                <div class="user-details">
                    <h1><?php echo $nombre; ?></h1>
                    <div class="contact-row">
                        <span class="info-item">
                            <img class="icon-xs" src="./imgs/icons/emailgris.svg" alt="email"> <?php echo $email; ?>
                        </span>
                        <span class="info-item">
                            <img class="icon-xs" src="./imgs/icons/telefono.svg" alt="teléfono"> <?php echo $telefono; ?>
                        </span>
                    </div>
                    <div class="member-since">
                        <img class="icon-xs" src="./imgs/icons/calendariogris.svg" alt="calendario">
                        <span>Miembro desde: <strong><?php echo $memberSince; ?></strong></span>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="editar_perfil.php" class="btn btn-outline">
                    <img src="./imgs/icons/editarbtn.svg" alt=""> Editar perfil
                </a>
                <?php if (isset($userData['rol_id_rol']) && $userData['rol_id_rol'] == 1): ?>
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <img src="./imgs/icons/documento.svg" alt=""> Ir a Ficha Usuario
                </a>
                <?php endif; ?>
            </div>
        </section>

        <div class="info-grid">
            
            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="img/vector-36.svg" alt="">
                        <h2>Datos Personales</h2>
                    </div>
                    <a href="editar_perfil.php#datos-personales" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <label>Nombre completo</label>
                        <p><?php echo $nombre; ?></p>
                    </div>
                    <div class="field-group">
                        <label>User name</label>
                        <p><?php echo $username; ?></p>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="img/vector-5.svg" alt="">
                        <h2>Contacto/Gmail</h2>
                    </div>
                    <a href="editar_perfil.php#contacto" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <label>Email principal</label>
                        <p><?php echo $email; ?></p>
                    </div>
                    <div class="field-group">
                        <label>Teléfono</label>
                        <p><?php echo $telefono; ?></p>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="img/vector-14.svg" alt="">
                        <h2>Dirección</h2>
                    </div>
                    <a href="editar_perfil.php#direccion" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <label>Dirección principal</label>
                        <p><?php echo $direccion; ?></p>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="img/vector-24.svg" alt="">
                        <h2>Géneros Favoritos</h2>
                    </div>
                    <a href="editar_preferencias.php" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <div class="tags">
                            <?php if (!empty($favoriteGenres)): ?>
                                <?php foreach ($favoriteGenres as $genre): ?>
                                    <span class="tag"><?php echo htmlspecialchars($genre); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No has seleccionado géneros.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="./imgs/icons/reproducir.svg" alt=""> <h2>Actores Favoritos</h2>
                    </div>
                    <a href="editar_preferencias.php" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <div class="tags">
                            <?php if (!empty($favoriteActors)): ?>
                                <?php foreach ($favoriteActors as $actor): ?>
                                    <span class="tag"><?php echo htmlspecialchars($actor); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No has seleccionado actores.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="./imgs/icons/vector-22.svg" alt=""> <h2>Directores Favoritos</h2>
                    </div>
                    <a href="editar_preferencias.php" class="edit-icon"><img src="./imgs/icons/editarbtn.svg" alt="Editar"></a>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <div class="tags">
                            <?php if (!empty($favoriteDirectors)): ?>
                                <?php foreach ($favoriteDirectors as $director): ?>
                                    <span class="tag"><?php echo htmlspecialchars($director); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No has seleccionado directores.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </main>
</body>
</html>