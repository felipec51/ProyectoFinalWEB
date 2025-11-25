<?php
// Archivo: adminusuarios.php
session_start();


require_once 'crud_usuarios.php';
$crud = new CrudUsuarios();

$message = null; 


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_usuario_delete = intval($_GET['id']);
    if ($crud->eliminarUsuario($id_usuario_delete)) {
        $message = ['type' => 'success', 'text' => 'Usuario eliminado con éxito.'];
    } else {

        if (!isset($_SESSION['message'])) {
             $message = ['type' => 'error', 'text' => 'Error al eliminar el usuario.'];
        } else {
             $message = $_SESSION['message'];
             unset($_SESSION['message']);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'save_edit') {
    $datos = [
        'id_usuario'    => intval($_POST['id_usuario']),
        'username'      => trim($_POST['username']),
        'nombre'        => trim($_POST['nombre']),
        'direccion'     => trim($_POST['direccion']),
        'telefono'      => trim($_POST['telefono']),
        'email'         => trim($_POST['email']),
        'password'      => $_POST['password'],
        'rol_id_rol'    => intval($_POST['rol_id_rol'])
    ];

    if ($crud->editarUsuario($datos)) {
        $message = ['type' => 'success', 'text' => 'Usuario actualizado con éxito.'];
        unset($_GET['id']); 
        unset($_GET['action']);
    } else {
        $message = ['type' => 'error', 'text' => 'Error al actualizar el usuario.'];
    }
}


$usuario_a_editar = null;
$roles = $crud->obtenerRoles();

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_usuario_edit = intval($_GET['id']);
    $usuario_a_editar = $crud->obtenerUsuarioPorId($id_usuario_edit);

    if (!$usuario_a_editar) {
        $message = ['type' => 'error', 'text' => 'Usuario no encontrado para edición.'];
    }
}

$usuarios = $crud->obtenerUsuarios();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link rel="stylesheet" href="./styles/adminusuarios.css"> 
</head>
<body class="netflix-admin-body"> <a href="paneladmin.php" class="back-link">
        &leftarrow; Volver al Panel de Administración
    </a>

    <h2>Administración de Usuarios</h2>

    <?php if ($message): ?>
        <div class="message <?= htmlspecialchars($message['type']) ?>">
            <?= htmlspecialchars($message['text']) ?>
        </div>
    <?php endif; ?>

    <?php if ($usuario_a_editar): ?>
    <div class="edit-form-container">
        <h3>Editar Usuario: <?= htmlspecialchars($usuario_a_editar['nombre']) ?></h3>
        <form action="adminusuarios.php" method="POST">
            <input type="hidden" name="action" value="save_edit">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario_a_editar['id_usuario']) ?>">

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($usuario_a_editar['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario_a_editar['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario_a_editar['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario_a_editar['direccion']) ?>">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario_a_editar['telefono']) ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="text" id="password" name="password" value="<?= htmlspecialchars($usuario_a_editar['password']) ?>" required>
            </div>

            <div class="form-group">
                <label for="rol_id_rol">Rol:</label>
                <select id="rol_id_rol" name="rol_id_rol" required>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['id_rol']) ?>" 
                                <?= ($rol['id_rol'] == $usuario_a_editar['rol_id_rol']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn">Guardar Cambios</button>
                <a href="adminusuarios.php" class="action-btn cancel-btn">Cancelar</a> </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($usuarios): ?>
    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id_usuario']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['nombre']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['direccion']) ?></td>
                <td><?= htmlspecialchars($user['telefono']) ?></td>
                <td><?= htmlspecialchars($user['rol_nombre']) ?></td>
                <td>
                    <a href="adminusuarios.php?action=edit&id=<?= htmlspecialchars($user['id_usuario']) ?>" 
                       class="action-btn edit-btn">Editar</a>
                    
                    <a href="adminusuarios.php?action=delete&id=<?= htmlspecialchars($user['id_usuario']) ?>" 
                       class="action-btn delete-btn" 
                       onclick="return confirm('¿Estás seguro de que quieres eliminar a <?= htmlspecialchars($user['nombre']) ?>?');">
                       Eliminar
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No se encontraron usuarios o hubo un error al cargar la lista.</p>
    <?php endif; ?>

</body>
</html>