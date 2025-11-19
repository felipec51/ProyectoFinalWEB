<?php
// 1. Incluimos el archivo de conexión proporcionado
require_once 'conexion.php';

// 2. Obtenemos las películas de la base de datos
try {
    $objetoConexion = Conexion::Conectar();
    // Seleccionamos ID, título y el poster (imagen) de la tabla pelicula
    $sql = "SELECT id_pelicula, titulo, poster_path FROM pelicula"; 
    $sentencia = $objetoConexion->prepare($sql);
    $sentencia->execute();
    $listaPeliculas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. Dividimos el array de películas en grupos de 6 para el diseño
    $gruposPeliculas = array_chunk($listaPeliculas, 6);

} catch (Exception $e) {
    echo "Error al cargar películas: " . $e->getMessage();
    $gruposPeliculas = []; // Array vacío en caso de error para no romper el HTML
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <link rel="stylesheet" href="styles/peliculasMenu.css" />
        <link rel="stylesheet" href="styles/config.css" />
        <title>RewindCodeFilm</title>
    </head>
    <body>
        <div class="desktop">
            <div class="nav">
                <div class="rewind-code-film text-main-title">RewindCodeFilm</div>
            </div>
            
            <div class="conten-div">
                <div class="text-wrapper-4 text-main-title">Solo en RewindCodeFilm</div>
                <p class="p text-body">
                    En RewindCodeFilm tenemos las mejores películas, que no hay en otro lugar. 
                    Disfruta tus Películas, Series, especiales, etc. Y todos elegidos solo para ti.
                </p>
            </div>
            
            <?php 
            // 4. Verificamos si hay películas para mostrar
            if (count($gruposPeliculas) > 0): 
                // Recorremos cada grupo de 6 películas (cada fila)
                foreach ($gruposPeliculas as $grupo): 
            ?>
                <div class="vtp-peliculas">
                    <?php 
                    // Recorremos cada película DENTRO del grupo de 6
                    foreach ($grupo as $pelicula): 
                        // Sanitizamos los datos para evitar errores de HTML
                        $titulo = htmlspecialchars($pelicula['titulo']);
                        $imagen = htmlspecialchars($pelicula['poster_path']);
                        $id = $pelicula['id_pelicula'];
                    ?>
                        <img 
                            src="<?php echo $imagen; ?>" 
                            alt="<?php echo $titulo; ?>" 
                            title="<?php echo $titulo; ?>"
                            onclick="location.href='pelicula.php?id=<?php echo $id; ?>'" 
                            style="cursor: pointer;"
                        />
                    <?php endforeach; ?>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <p style="color: white; text-align: center;">No hay películas disponibles en este momento.</p>
            <?php endif; ?>
            
        </div>
    </body>
</html>