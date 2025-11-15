<?php
// Archivo: crud_usuario.php
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// 1. Recolección de datos
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$direccion = (isset($_POST['direccion'])) ? $_POST['direccion'] : '';
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$rol_id_rol = (isset($_POST['rol_id_rol'])) ? $_POST['rol_id_rol'] : '';

$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_usuario = (isset($_POST['id_usuario'])) ? $_POST['id_usuario'] : ''; 

$data = [];
$fecha_creacion = date('Y-m-d H:i:s'); // Para el campo fecha_creacion en el INSERT

try {
    switch($opc){
        case 1: // CREATE: Insertar un nuevo Usuario
            // A. HASHEAR la contraseña por seguridad
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $consulta = "INSERT INTO Usuario (username, password, nombre, direccion, telefono, email, fecha_creacion, rol_id_rol) 
                         VALUES(?, ?, ?, ?, ?, ?, ?, ?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$username, $password_hashed, $nombre, $direccion, $telefono, $email, $fecha_creacion, $rol_id_rol]); 
            
            // B. Obtener el registro insertado (incluyendo el nombre del rol)
            $id_insertado = $conexion->lastInsertId();
            $consulta = "SELECT u.id_usuario, u.username, u.nombre, u.direccion, u.telefono, u.email, u.fecha_creacion, r.nombre AS rol_nombre 
                         FROM Usuario u JOIN rol r ON u.rol_id_rol = r.id_rol 
                         WHERE u.id_usuario = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: // UPDATE: Modificar datos de un Usuario
            // Determinar si se actualiza también la contraseña
            if (!empty($password)) {
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                $consulta = "UPDATE Usuario SET username=?, password=?, nombre=?, direccion=?, telefono=?, email=?, rol_id_rol=? 
                             WHERE id_usuario=?";
                $resultado = $conexion->prepare($consulta);
                $resultado->execute([$username, $password_hashed, $nombre, $direccion, $telefono, $email, $rol_id_rol, $id_usuario]);
            } else {
                // Si la contraseña está vacía, no se actualiza (se mantiene la actual)
                $consulta = "UPDATE Usuario SET username=?, nombre=?, direccion=?, telefono=?, email=?, rol_id_rol=? 
                             WHERE id_usuario=?";
                $resultado = $conexion->prepare($consulta);
                $resultado->execute([$username, $nombre, $direccion, $telefono, $email, $rol_id_rol, $id_usuario]);
            }
            
            // C. Seleccionar el registro actualizado (incluyendo el nombre del rol)
            $consulta = "SELECT u.id_usuario, u.username, u.nombre, u.direccion, u.telefono, u.email, u.fecha_creacion, r.nombre AS rol_nombre 
                         FROM Usuario u JOIN rol r ON u.rol_id_rol = r.id_rol 
                         WHERE u.id_usuario=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_usuario]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: // DELETE: Eliminar un Usuario
            $consulta = "DELETE FROM Usuario WHERE id_usuario=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_usuario]);                           
            $data = ['id_usuario' => $id_usuario, 'status' => 'eliminado'];
            break;
            
        case 4: // READ: Listar todos los Usuarios (con nombre del rol)
            $consulta = "SELECT u.id_usuario, u.username, u.nombre, u.direccion, u.telefono, u.email, u.fecha_creacion, r.nombre AS rol_nombre 
                         FROM Usuario u JOIN rol r ON u.rol_id_rol = r.id_rol 
                         ORDER BY u.id_usuario DESC";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
    }
} catch (PDOException $e) {
    $data = ['error' => 'Error de Base de Datos: ' . $e->getMessage()];
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = null;
?>