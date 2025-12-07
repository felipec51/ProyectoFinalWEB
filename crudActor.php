<?php

include_once './conexion.php';

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: login.php"); 
    exit;
}
$usuario_logueado_id = $_SESSION["id_usuario"];



$objeto = new Conexion();
$conexion = $objeto->Conectar();


$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_actor = (isset($_POST['id_actor'])) ? $_POST['id_actor'] : ''; 

$data = [];

try {
    switch($opc){
        case 1: 
            
            $consulta = "INSERT INTO actor (nombre) VALUES(?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$nombre]);             
            $id_insertado = $conexion->lastInsertId();
            $consulta = "SELECT id_actor, nombre FROM actor WHERE id_actor = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: 
            $consulta = "UPDATE actor SET nombre=? WHERE id_actor=?";      
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$nombre, $id_actor]);        
            
            
            $consulta = "SELECT id_actor, nombre FROM actor WHERE id_actor=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_actor]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: 
            $consulta = "DELETE FROM actor WHERE id_actor=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_actor]);                           
            $data = ['id_actor' => $id_actor, 'status' => 'eliminado']; 
            break;
          
        case 4: 
            $consulta = "SELECT id_actor, nombre FROM actor";
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