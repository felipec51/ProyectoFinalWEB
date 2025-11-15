<?php
// Archivo: listado_peliculas.php (¡Solo proveedor de datos!)

// Incluimos la conexión a la base de datos (Asegúrate de que la ruta sea correcta)
include_once './conexion.php'; 

// 1. Conectar a la base de datos
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// La variable debe llamarse $peliculas, porque es el nombre que usa paneladmin.php
$peliculas = []; 

try {
    // 2. Consulta SQL para traer Películas, Director y todos los Géneros
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
            genero g ON pg.genero_id_genero = g.id_genero
        GROUP BY
            p.id_pelicula, p.titulo, p.anio, p.poster_path, p.calificacion, d.nombre
        ORDER BY 
            p.anio DESC";
            
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();        
    // 3. Almacenamos el resultado en la variable $peliculas
    $peliculas = $resultado->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si hay un error, $peliculas se mantiene como un array vacío.
    // echo "Error al cargar películas: " . $e->getMessage(); // Descomentar para depurar
    $peliculas = [];
}

$conexion = null;

// ATENCIÓN: No hay llamada a renderpeliculas() aquí, solo se define la variable $peliculas
?>