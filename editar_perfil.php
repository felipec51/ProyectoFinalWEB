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

$usuario_id = $_SESSION["id_usuario"];
$mensaje = "";
$tipo_mensaje = ""; 

try {
    $conexion = Conexion::Conectar();

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        $password_nueva = $_POST['password'];

        
        if (empty($nombre) || empty($email)) {
            $mensaje = "El nombre y el correo son obligatorios.";
            $tipo_mensaje = "error";
        } else {
            
            if (!empty($password_nueva)) {
                
                
                $sql = "UPDATE Usuario SET nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion, password = :pass WHERE id_usuario = :id";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':pass', $password_nueva);
            } else {
                $sql = "UPDATE Usuario SET nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id_usuario = :id";
                $stmt = $conexion->prepare($sql);
            }

            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':id', $usuario_id);

            if ($stmt->execute()) {
                $mensaje = "¡Perfil actualizado correctamente!";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Hubo un error al guardar los cambios.";
                $tipo_mensaje = "error";
            }
        }
    }

    
    $sqlUser = "SELECT nombre, username, email, telefono, direccion FROM Usuario WHERE id_usuario = :id";
    $stmtUser = $conexion->prepare($sqlUser);
    $stmtUser->bindParam(':id', $usuario_id);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $mensaje = "Error de conexión: " . $e->getMessage();
    $tipo_mensaje = "error";
}


$nombre_val = htmlspecialchars($userData['nombre'] ?? '');
$email_val = htmlspecialchars($userData['email'] ?? '');
$telefono_val = htmlspecialchars($userData['telefono'] ?? '');
$direccion_val = htmlspecialchars($userData['direccion'] ?? '');
$username_val = htmlspecialchars($userData['username'] ?? '');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - CineMax</title>
    <link rel="stylesheet" href="./styles/perfil.css" />
    <link rel="stylesheet" href="styles/config.css" />
    
    <style>

        .form-edit {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .form-group label {
            font-weight: bold;
            color: #ccc;
        }
        .form-group input, .form-group textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #222;
            color: white;
            font-size: 1rem;
        }
        .form-group input:focus {
            border-color: #e50914; 
            outline: none;
        }
        .btn-save {
            background-color: #e50914;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            margin-top: 10px;
            text-align: center;
        }
        .btn-save:hover {
            background-color: #b20710;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .alert.success { background-color: #2ecc71; color: #fff; }
        .alert.error { background-color: #e74c3c; color: #fff; }
        .readonly-field { background-color: #1a1a1a !important; color: #777 !important; cursor: not-allowed; }
    </style>
</head>
<body>
    
    <?php navheader("Editar Perfil", $usuario_id)?>

    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container breadcrumb-content">
            <a href="peliculasMenu.php" class="crumb-link">Página Principal</a>
            <span class="crumb-separator">/</span>
            <a href="perfil.php" class="crumb-link">Perfil</a>
            <span class="crumb-current">> Editar</span>
        </div>
    </nav>

    <main class="main-content container">
        
        <section class="profile-header">
            <div class="user-details">
                <h1>Editar mis datos</h1>
                <p>Actualiza tu información personal</p>
            </div>
        </section>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="info-grid">
            <article class="card" style="grid-column: span 2; max-width: 800px; margin: 0 auto;">
                <div class="card-header">
                    <div class="card-title">
                        <img class="icon-md" src="./imgs/icons/editarbtn.svg" alt="">
                        <h2>Formulario de Actualización</h2>
                    </div>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="" class="form-edit">
                        
                        <div class="form-group">
                            <label for="username">Usuario (No editable):</label>
                            <input type="text" value="<?php echo $username_val; ?>" class="readonly-field" disabled>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre Completo:</label>
                            <input type="text" name="nombre" id="nombre" value="<?php echo $nombre_val; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" name="email" id="email" value="<?php echo $email_val; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" name="telefono" id="telefono" value="<?php echo $telefono_val; ?>">
                        </div>

                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" value="<?php echo $direccion_val; ?>">
                        </div>

                        <div class="form-group" style="margin-top: 15px; border-top: 1px solid #333; paddingTop: 15px;">
                            <label for="password">Nueva Contraseña (Dejar en blanco para no cambiar):</label>
                            <input type="password" name="password" id="password" placeholder="Ingresa nueva contraseña solo si deseas cambiarla">
                        </div>

                        <button type="submit" class="btn-save">Guardar Cambios</button>
                    </form>

                </div>
            </article>
        </div>
    </main>
</body>
</html>