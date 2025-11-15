<?php
// Archivo: crud_cinta.php
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// 1. Recolección de datos (Ajustado a la tabla cinta)
$estado = (isset($_POST['estado'])) ? $_POST['estado'] : '';
$numerocopias = (isset($_POST['numerocopias'])) ? $_POST['numerocopias'] : '';
$pelicula_id_pelicula = (isset($_POST['pelicula_id_pelicula'])) ? $_POST['pelicula_id_pelicula'] : '';

$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_cinta = (isset($_POST['id_cinta'])) ? $_POST['id_cinta'] : '';

$data = [];

try {
    switch($opc){
        case 1: // CREATE: Insertar una nueva cinta
            // Uso de Sentencias Preparadas (más seguro)
            $consulta = "INSERT INTO cinta (estado, numerocopias, pelicula_id_pelicula) VALUES(?, ?, ?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$estado, $numerocopias, $pelicula_id_pelicula]); 
            
            $id_insertado = $conexion->lastInsertId();
            // SELECT con JOIN para mostrar también el título de la película
            $consulta = "SELECT c.*, p.titulo AS pelicula_titulo 
                         FROM cinta c JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula 
                         WHERE c.id_cinta = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: // UPDATE: Modificar datos de una cinta
            $consulta = "UPDATE cinta SET estado=?, numerocopias=?, pelicula_id_pelicula=? WHERE id_cinta=?";      
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$estado, $numerocopias, $pelicula_id_pelicula, $id_cinta]);        
            
            // Seleccionar el registro actualizado con el título de la película
            $consulta = "SELECT c.*, p.titulo AS pelicula_titulo 
                         FROM cinta c JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula 
                         WHERE c.id_cinta=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_cinta]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: // DELETE: Eliminar una cinta
            $consulta = "DELETE FROM cinta WHERE id_cinta=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_cinta]);                           
            $data = ['id_cinta' => $id_cinta, 'status' => 'eliminado'];
            break;
            
        case 4: // READ: Listar todas las cintas (con el nombre de la película)
            $consulta = "SELECT c.*, p.titulo AS pelicula_titulo 
                         FROM cinta c JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula 
                         ORDER BY c.id_cinta DESC";
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