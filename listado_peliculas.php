<?php

include_once './conexion.php'; 

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$peliculas = []; 

$search_term = isset($_GET['search']) ? $_GET['search'] : '';

try {
    
    $consulta = "
        SELECT 
            p.id_pelicula,
            p.titulo, 
            p.anio, 
            p.poster_path, 
            p.calificacion,
            d.nombre AS director_nombre, 
            GROUP_CONCAT(g.nombre SEPARATOR ', ') AS generos
        FROM 
            pelicula p
        JOIN 
            director d ON p.director_id_director = d.id_director
        LEFT JOIN 
            pelicula_genero pg ON p.id_pelicula = pg.pelicula_id_pelicula
        LEFT JOIN 
            genero g ON pg.genero_id_genero = g.id_genero";

    if (!empty($search_term)) {
        $consulta .= " WHERE p.titulo LIKE :search_term OR d.nombre LIKE :search_term";
    }

    $consulta .= " GROUP BY
            p.id_pelicula, p.titulo, p.anio, p.poster_path, p.calificacion, d.nombre
        ORDER BY 
            p.anio DESC";
            
    $resultado = $conexion->prepare($consulta);

    if (!empty($search_term)) {
        $resultado->bindValue(':search_term', '%' . $search_term . '%');
    }
    
    $resultado->execute();        
    
    $peliculas = $resultado->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    
    
    $peliculas = [];
}

$conexion = null;
?>