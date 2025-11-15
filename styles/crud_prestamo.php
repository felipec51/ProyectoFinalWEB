<?php
// Archivo: crud_prestamo.php
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// 1. Recolección de datos
$fecha_prestamo = (isset($_POST['fecha_prestamo'])) ? $_POST['fecha_prestamo'] : date('Y-m-d H:i:s'); // Usar fecha actual si no se provee
$fecha_devolucion = (isset($_POST['fecha_devolucion'])) ? $_POST['fecha_devolucion'] : ''; // Puede estar vacía si es un préstamo nuevo
$observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
$Usuario_id_usuario = (isset($_POST['Usuario_id_usuario'])) ? $_POST['Usuario_id_usuario'] : '';
$cinta_id_cinta = (isset($_POST['cinta_id_cinta'])) ? $_POST['cinta_id_cinta'] : '';

$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_prestamo = (isset($_POST['id_prestamo'])) ? $_POST['id_prestamo'] : ''; 

$data = [];

try {
    switch($opc){
        case 1: // CREATE: Registrar un nuevo Préstamo
            // Usar NULL para fecha_devolucion si no se provee o está vacía (asumiendo que puede ser NULL en DB)
            $fecha_devolucion_sql = (empty($fecha_devolucion)) ? NULL : $fecha_devolucion;
            
            $consulta = "INSERT INTO prestamo (fecha_prestamo, fecha_devolucion, observaciones, Usuario_id_usuario, cinta_id_cinta) 
                         VALUES(?, ?, ?, ?, ?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$fecha_prestamo, $fecha_devolucion_sql, $observaciones, $Usuario_id_usuario, $cinta_id_cinta]); 
            
            $id_insertado = $conexion->lastInsertId();
            // Obtener el registro insertado con detalles del Usuario y la Película
            $consulta = "SELECT p.*, u.nombre AS nombre_usuario, c.id_cinta, pi.titulo AS titulo_pelicula
                         FROM prestamo p
                         JOIN Usuario u ON p.Usuario_id_usuario = u.id_usuario
                         JOIN cinta c ON p.cinta_id_cinta = c.id_cinta
                         JOIN pelicula pi ON c.pelicula_id_pelicula = pi.id_pelicula
                         WHERE p.id_prestamo = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: // UPDATE: Modificar Préstamo (ej. registrar devolución)
            $consulta = "UPDATE prestamo SET fecha_prestamo=?, fecha_devolucion=?, observaciones=?, Usuario_id_usuario=?, cinta_id_cinta=? 
                         WHERE id_prestamo=?";      
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$fecha_prestamo, $fecha_devolucion, $observaciones, $Usuario_id_usuario, $cinta_id_cinta, $id_prestamo]);        
            
            // Seleccionar el registro actualizado con detalles
            $consulta = "SELECT p.*, u.nombre AS nombre_usuario, c.id_cinta, pi.titulo AS titulo_pelicula
                         FROM prestamo p
                         JOIN Usuario u ON p.Usuario_id_usuario = u.id_usuario
                         JOIN cinta c ON p.cinta_id_cinta = c.id_cinta
                         JOIN pelicula pi ON c.pelicula_id_pelicula = pi.id_pelicula
                         WHERE p.id_prestamo=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_prestamo]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: // DELETE: Eliminar un Préstamo (cancelar)
            $consulta = "DELETE FROM prestamo WHERE id_prestamo=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_prestamo]);                           
            $data = ['id_prestamo' => $id_prestamo, 'status' => 'eliminado'];
            break;
            
        case 4: // READ: Listar todos los Préstamos (con nombres de Usuario y Película)
            $consulta = "SELECT p.*, u.nombre AS nombre_usuario, c.id_cinta, pi.titulo AS titulo_pelicula
                         FROM prestamo p
                         JOIN Usuario u ON p.Usuario_id_usuario = u.id_usuario
                         JOIN cinta c ON p.cinta_id_cinta = c.id_cinta
                         JOIN pelicula pi ON c.pelicula_id_pelicula = pi.id_pelicula
                         ORDER BY p.fecha_prestamo DESC";
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