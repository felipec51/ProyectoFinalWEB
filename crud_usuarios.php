<?php

require_once 'conexion.php'; 

class CrudUsuarios {
    private $conn;

    public function __construct() {
        
        $this->conn = Conexion::Conectar();
    }

    public function obtenerUsuarios() {
        try {
            $sql = "SELECT u.id_usuario, u.username, u.nombre, u.direccion, u.telefono, u.email, r.nombre AS rol_nombre 
                    FROM Usuario u
                    JOIN rol r ON u.rol_id_rol = r.id_rol
                    ORDER BY u.id_usuario DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerUsuarioPorId($id_usuario) {
        try {
            
            $sql = "SELECT id_usuario, username, nombre, direccion, telefono, email, password, rol_id_rol 
                    FROM Usuario 
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerRoles() {
        try {
            $sql = "SELECT id_rol, nombre FROM rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return null;
        }
    }

    public function editarUsuario($datos) {
        try {
            $sql = "UPDATE Usuario SET 
                    username = :username, 
                    nombre = :nombre, 
                    direccion = :direccion, 
                    telefono = :telefono, 
                    email = :email, 
                    password = :password,
                    rol_id_rol = :rol_id_rol
                    WHERE id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $datos['username']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':password', $datos['password']); 
            $stmt->bindParam(':rol_id_rol', $datos['rol_id_rol'], PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al editar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarUsuario($id_usuario) {
        try {
            
            
            $sql = "DELETE FROM Usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            
            if ($e->getCode() == 23000) { 
                $_SESSION['message'] = [
                    'type' => 'error', 
                    'text' => 'No se puede eliminar el usuario porque tiene registros asociados (ej. préstamos). Debe eliminarlos primero.'
                ];
            }
            return false;
        }
    }
}
?>