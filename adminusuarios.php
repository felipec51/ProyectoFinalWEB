<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php

//include 'componentes/sidebar.php';

// Conexión
$conexion = new mysqli("localhost", "root", "", "FinWeb");
$conexion->set_charset("utf8");

// --- ELIMINAR ---
if (isset($_GET['action']) && $_GET['action'] == 'eliminar') {
    $id = intval($_GET['id']);
    $conexion->query("DELETE FROM Usuario WHERE id_usuario = $id");
    header("Location: adminusuarios.php");
    exit;
}

// --- GUARDAR NUEVO ---
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $conexion->query("INSERT INTO Usuario(nombre, username, email, rol_id_rol, fecha_creacion)
                      VALUES ('$nombre', '$username', '$email', '$rol', NOW())");

    header("Location: adminusuarios.php");
    exit;
}

// --- ACTUALIZAR ---
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $conexion->query("UPDATE Usuario SET 
                        nombre='$nombre',
                        username='$username',
                        email='$email',
                        rol_id_rol='$rol'
                      WHERE id_usuario=$id");

    header("Location: adminusuarios.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="styles/peliculasMenu.css" />
    <link rel="stylesheet" href="styles/config.css" />
    <link rel="stylesheet" href="./styles/paneladmin.css" />
</head>

<body>

<div class="desktop">
    <div class="admin-movie-panel-activity">
        <div class="app">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php rendersidebar(); ?>
            </div>

            <div class="sidebarinset">
                <div class="app2">

                    <?php
                    // Acción detectada
                    $action = isset($_GET['action']) ? $_GET['action'] : '';

                    // === FORMULARIO AGREGAR ===
                    if ($action == 'agregar') :
                    ?>

                        <h2 class="text-base">Agregar Usuario</h2>

                        <form method="POST">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required>

                            <label>Usuario</label>
                            <input type="text" name="username" required>

                            <label>Correo</label>
                            <input type="email" name="email" required>

                            <label>Rol</label>
                            <input type="number" name="rol" required>

                            <button class="button" type="submit" name="guardar">Guardar</button>
                        </form>

                        <a href="adminusuarios.php">🔙 Volver</a>

                    <?php
                    // === FORMULARIO EDITAR ===
                    elseif ($action == 'editar'):
                        $id = intval($_GET['id']);
                        $u = $conexion->query("SELECT * FROM Usuario WHERE id_usuario=$id")->fetch_assoc();
                    ?>

                        <h2 class="text-base">Editar Usuario</h2>

                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $u['id_usuario'] ?>">

                            <label>Nombre</label>
                            <input type="text" name="nombre" value="<?= $u['nombre'] ?>" required>

                            <label>Usuario</label>
                            <input type="text" name="username" value="<?= $u['username'] ?>" required>

                            <label>Correo</label>
                            <input type="email" name="email" value="<?= $u['email'] ?>" required>

                            <label>Rol</label>
                            <input type="number" name="rol" value="<?= $u['rol_id_rol'] ?>" required>

                            <button class="button" type="submit" name="actualizar">Actualizar</button>
                        </form>

                        <a href="adminusuarios.php">🔙 Volver</a>

                    <?php
                    // === LISTA DE USUARIOS ===
                    else:
                        $usuarios = $conexion->query("SELECT * FROM Usuario");
                    ?>

                        <header class="app3">
                            <div class="container">
                                <div class="primitivebutton flex-row-center">
                                    <div class="primitivespan flex-row-center">
                                        <div class="todos text-base">Usuarios</div>
                                    </div>
                                </div>
                            </div>

                            <div class="container3 flex-row-center">
                                <div class="button" onclick="window.location.href='adminusuarios.php?action=agregar'">
                                    <div class="agregar-pelcula text-base">Agregar Usuario</div>
                                </div>
                            </div>
                        </header>

                        <div class="container14">
                            <?php while ($u = $usuarios->fetch_assoc()) : ?>
                                <div class="card card-base" style="padding:20px;margin-bottom:10px;">
                                    <div class="text-base">👤 <?= $u['nombre'] ?></div>
                                    <div class="text-secundario">Usuario: <?= $u['username'] ?></div>
                                    <div class="text-secundario">Correo: <?= $u['email'] ?></div>
                                    <div class="text-secundario">Rol: <?= $u['rol_id_rol'] ?></div>

                                    <div style="margin-top:10px; display:flex; gap:10px;">
                                        <button class="button" onclick="window.location.href='adminusuarios.php?action=editar&id=<?= $u['id_usuario'] ?>'">
                                            Editar
                                        </button>

                                        <button class="button" style="background:#ff5b5b;"
                                            onclick="if(confirm('¿Eliminar usuario?')) window.location.href='adminusuarios.php?action=eliminar&id=<?= $u['id_usuario'] ?>'">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
