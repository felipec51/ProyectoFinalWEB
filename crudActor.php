<?php
// Incluimos la clase de conexión
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// 1. Recolección de datos
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_actor = (isset($_POST['id_actor'])) ? $_POST['id_actor'] : ''; 

$data = [];

try {
    switch($opc){
        case 1: // CREATE: Insertar un nuevo actor
            // El id_actor es AUTO_INCREMENT, no se incluye en el INSERT
            $consulta = "INSERT INTO actor (nombre) VALUES(?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$nombre]); 
            
            // Obtener el registro insertado (opcional, pero útil para AJAX)
            $id_insertado = $conexion->lastInsertId();
            $consulta = "SELECT id_actor, nombre FROM actor WHERE id_actor = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: // UPDATE: Modificar el nombre de un actor
            $consulta = "UPDATE actor SET nombre=? WHERE id_actor=?";      
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$nombre, $id_actor]);        
            
            // Seleccionar el registro actualizado
            $consulta = "SELECT id_actor, nombre FROM actor WHERE id_actor=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_actor]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: // DELETE: Eliminar un actor
            $consulta = "DELETE FROM actor WHERE id_actor=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_actor]);                           
            $data = ['id_actor' => $id_actor, 'status' => 'eliminado']; // Devolver el ID eliminado
            break;
            
        case 4: // READ: Listar todos los actores
            $consulta = "SELECT id_actor, nombre FROM actor";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
    }
} catch (PDOException $e) {
    // Captura de errores de la base de datos (ej. restricciones de clave externa)
    $data = ['error' => 'Error de Base de Datos: ' . $e->getMessage()];
}


print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = null;
?>