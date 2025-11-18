<?php
// Archivo: crud_usuarios.php

// Asegúrate de incluir el archivo de conexión
require_once 'conexion.php'; 

class CrudUsuarios {
    private $conn;

    public function __construct() {
        // Establece la conexión al crear una instancia de la clase
        $this->conn = Conexion::Conectar();
    }

    /**
     * Obtiene todos los usuarios de la base de datos con el nombre de su rol.
     * @return array|null Un array de usuarios o null si hay un error.
     */
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

    /**
     * Obtiene un solo usuario por su ID.
     * @param int $id_usuario El ID del usuario.
     * @return array|null Un array con los datos del usuario o null si no se encuentra.
     */
    public function obtenerUsuarioPorId($id_usuario) {
        try {
            // Se incluyen la contraseña y el rol_id para el formulario de edición.
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

    /**
     * Obtiene todos los roles disponibles (para el select en el formulario de edición).
     * @return array|null Un array de roles o null si hay un error.
     */
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

    /**
     * Actualiza los datos de un usuario.
     * NOTA: La contraseña no se encripta aquí ya que en la BD que adjuntaste está en texto plano.
     * @param array $datos Los datos del usuario a actualizar.
     * @return bool True si la actualización fue exitosa, False en caso contrario.
     */
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

    /**
     * Elimina un usuario por su ID.
     * @param int $id_usuario El ID del usuario a eliminar.
     * @return bool True si la eliminación fue exitosa, False en caso contrario.
     */
    public function eliminarUsuario($id_usuario) {
        try {
            // Nota: Podría haber errores de clave foránea (ej. si el usuario tiene préstamos o gustos)
            // Se debe considerar la eliminación en cascada en la BD o eliminar registros relacionados primero.
            $sql = "DELETE FROM Usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            // En caso de error de clave foránea, podemos dar un mensaje más amigable
            if ($e->getCode() == 23000) { // Código para Integrity Constraint Violation
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