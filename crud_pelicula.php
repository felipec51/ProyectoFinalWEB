<?php
// Archivo: crud_pelicula.php
include_once './conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// 1. Recolección de datos
$titulo = (isset($_POST['titulo'])) ? $_POST['titulo'] : '';
$anio = (isset($_POST['anio'])) ? $_POST['anio'] : '';
$duracion_min = (isset($_POST['duracion_min'])) ? $_POST['duracion_min'] : '';
$descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '';
$poster_path = (isset($_POST['poster_path'])) ? $_POST['poster_path'] : '';
$precio_alquiler = (isset($_POST['precio_alquiler'])) ? $_POST['precio_alquiler'] : '';
$calificacion = (isset($_POST['calificacion'])) ? $_POST['calificacion'] : '';
$director_id_director = (isset($_POST['director_id_director'])) ? $_POST['director_id_director'] : '';

$opc = (isset($_POST['opc'])) ? $_POST['opc'] : '';
$id_pelicula = (isset($_POST['id_pelicula'])) ? $_POST['id_pelicula'] : ''; 

$data = [];

try {
    switch($opc){
        case 1: // CREATE: Insertar una nueva Película
            $consulta = "INSERT INTO pelicula (titulo, anio, duracion_min, descripcion, poster_path, precio_alquiler, calificacion, director_id_director) 
                         VALUES(?, ?, ?, ?, ?, ?, ?, ?)";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$titulo, $anio, $duracion_min, $descripcion, $poster_path, $precio_alquiler, $calificacion, $director_id_director]); 
            
            $id_insertado = $conexion->lastInsertId();
            // Obtener el registro insertado (con nombre del director)
            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p JOIN director d ON p.director_id_director = d.id_director 
                         WHERE p.id_pelicula = ?";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_insertado]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);       
            break;    
            
        case 2: // UPDATE: Modificar datos de una Película
            $consulta = "UPDATE pelicula SET titulo=?, anio=?, duracion_min=?, descripcion=?, poster_path=?, precio_alquiler=?, calificacion=?, director_id_director=? 
                         WHERE id_pelicula=?";      
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$titulo, $anio, $duracion_min, $descripcion, $poster_path, $precio_alquiler, $calificacion, $director_id_director, $id_pelicula]);        
            
            // Seleccionar el registro actualizado (con nombre del director)
            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p JOIN director d ON p.director_id_director = d.id_director 
                         WHERE p.id_pelicula=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_pelicula]);
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 3: // DELETE: Eliminar una Película
            // ATENCIÓN: Si hay cintas o listas de espera asociadas, la clave foránea podría generar un error.
            $consulta = "DELETE FROM pelicula WHERE id_pelicula=?";       
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_pelicula]);                           
            $data = ['id_pelicula' => $id_pelicula, 'status' => 'eliminado'];
            break;
            
        case 4: // READ: Listar todas las Películas (con nombre del director)
            $consulta = "SELECT p.*, d.nombre AS director_nombre 
                         FROM pelicula p JOIN director d ON p.director_id_director = d.id_director 
                         ORDER BY p.id_pelicula DESC";
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