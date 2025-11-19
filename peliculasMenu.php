<?php
// Incluye el archivo de conexión a la base de datos
require_once 'conexion.php'; 

// 1. Función para obtener las películas y agruparlas por género
function obtenerPeliculasPorGenero() {
    try {
        // Establecer conexión usando la clase que ya tienes
        $conexion = Conexion::Conectar();
        
        // Consulta SQL para obtener título, ID, URL del póster y nombre del género
        // Usamos JOIN para relacionar Pelicula con Genero a través de Pelicula_Genero
        $sql = "SELECT 
                    p.id_pelicula, 
                    p.titulo, 
                    p.poster_path, 
                    g.nombre AS nombre_genero
                FROM 
                    pelicula p
                JOIN 
                    pelicula_genero pg ON p.id_pelicula = pg.pelicula_id_pelicula
                JOIN 
                    genero g ON pg.genero_id_genero = g.id_genero
                ORDER BY 
                    g.nombre, p.titulo";
        
        $consulta = $conexion->prepare($sql);
        $consulta->execute();
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        // Array para almacenar las películas agrupadas
        $peliculas_por_genero = [];
        
        // Agrupar los resultados en PHP
        foreach ($resultados as $pelicula) {
            $genero = $pelicula['nombre_genero'];
            
            // Si el género aún no existe en el array, lo inicializamos
            if (!isset($peliculas_por_genero[$genero])) {
                $peliculas_por_genero[$genero] = [];
            }
            
            // Añadir la película al grupo de su género
            $peliculas_por_genero[$genero][] = $pelicula;
        }
        
        return $peliculas_por_genero;

    } catch (Exception $e) {
        // Manejo de errores
        error_log("Error al obtener películas: " . $e->getMessage());
        return []; // Retorna un array vacío si hay un error
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

$peliculas_agrupadas = obtenerPeliculasPorGenero();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <title>Menú de Películas - RewindCodeFilm</title>
        <link rel="stylesheet" href="styles/peliculasMenu.css" />
        <link rel="stylesheet" href="styles/config.css" />
    </head>
    <body>
        <div class="desktop">
            <div class="nav">
                <div class="rewind-code-film text-main-title">RewindCodeFilm</div>
            </div>
            
            <div class="conten-div">
                <div class="text-wrapper-4 text-main-title">Catálogo Completo</div>
                <p class="p text-body">
                    En RewindCodeFilm tenemos las mejores películas y series, Disfruta de todo nuestro catálogo elegido solo para ti.
                </p>
            </div>
            
            <?php if (!empty($peliculas_agrupadas)): ?>
                <?php foreach ($peliculas_agrupadas as $genero => $peliculas): ?>
                    
                    <div class="vtp-peliculas">
                        <?php foreach ($peliculas as $pelicula): 
                            // Usamos htmlspecialchars para prevenir XSS y para manejar URLs con caracteres especiales.
                            // id_pelicula se usa para el enlace a pelicula.php
                            // poster_path se usa como URL de la imagen
                            $pelicula_url = htmlspecialchars($pelicula['poster_path']);
                            $pelicula_id = htmlspecialchars($pelicula['id_pelicula']);
                            $pelicula_titulo = htmlspecialchars($pelicula['titulo']);
                        ?>
                            <img 
                                src="<?= $pelicula_url ?>" 
                                alt="<?= $pelicula_titulo ?>" 
                                title="<?= $pelicula_titulo ?>"
                                onclick="location.href='pelicula.php?id=<?= $pelicula_id ?>'" 
                            />
                        <?php endforeach; ?>
                        
                        <div class="text-wrapper-5 text-section-title"><?= htmlspecialchars($genero) ?></div>
                    </div>
                
                <?php endforeach; ?>
            <?php else: ?>
                <div class="conten-div" style="text-align: center; margin-top: 50px;">
                    <p class="p text-body">No hay películas cargadas en el catálogo en este momento.</p>
                </div>
            <?php endif; ?>
            </div>
    </body>
</html>