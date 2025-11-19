<?php
// 1. Incluir la conexión y componentes
require_once 'conexion.php';

// Componentes visuales
include 'componentes/masinfo.php';
include 'componentes/tagserie.php';
include 'componentes/trailers.php';
include 'componentes/navpeli.php';
include 'componentes/avances.php';
include 'componentes/nodisponible.php';
include 'componentes/disponible.php';

// 2. Obtener el ID de la URL
// Si no hay ID, por defecto usamos el 1 (o el que prefieras como "home")
$id_peli = isset($_GET['id']) ? $_GET['id'] : 1;

// 3. Conectar a la BD
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Inicializar variables por defecto para evitar errores si el ID no existe
$nombre_peli = "No encontrado";
$fondo_img = "";
$descripcion = "La película solicitada no existe.";
$anio = "";
$duracion_encabezado = "";
$duracion_tag = "";
$clasificacion = "";
$creadores = "";
$ncopia = 0;
$ncopiastotales = 0;
$nfila = 1;
$url_trailer1 = "";
$url_trailer2 = "";
$generos_info = [];
$elenco_info = [];
$episodios = []; 

try {
    // ---------------------------------------------------------
    // CONSULTA 1: Datos principales de la película + Director
    // ---------------------------------------------------------
    $sql = "SELECT p.*, d.nombre as nombre_director 
            FROM pelicula p 
            JOIN director d ON p.director_id_director = d.id_director 
            WHERE p.id_pelicula = :id";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_peli);
    $stmt->execute();
    $pelicula_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pelicula_actual) {
        // Asignación de variables desde la BD
        $nombre_peli = htmlspecialchars($pelicula_actual['titulo']);
        $fondo_img = htmlspecialchars($pelicula_actual['poster_path']);
        $anio = htmlspecialchars($pelicula_actual['anio']);
		$precio = htmlspecialchars($pelicula_actual['precio_alquiler']);
        $clasificacion = htmlspecialchars($pelicula_actual['calificacion']);
        $descripcion = nl2br(htmlspecialchars($pelicula_actual['descripcion']));
        $creadores = htmlspecialchars($pelicula_actual['nombre_director']);
        
        // Formatear duración (ej: de 120 a "2h 0min")
        $horas = floor($pelicula_actual['duracion_min'] / 60);
        $minutos = $pelicula_actual['duracion_min'] % 60;
        $duracion_encabezado = "{$horas}h {$minutos}m";
        $duracion_tag = "{$pelicula_actual['duracion_min']} min";

        // Lógica de copias
        $ncopia = $pelicula_actual['ncopias'];
        $ncopiastotales = $pelicula_actual['ncopias']; // Asumimos total = actuales por ahora
        
        // Variables estáticas o no presentes en la BD actual
        $coincidencia = "98%"; // Valor simulado
        $calidad = "HD";       // Valor simulado
        $nfila = 1;            // Valor simulado


        $sqlGeneros = "SELECT g.nombre 
                       FROM genero g
                       JOIN pelicula_genero pg ON g.id_genero = pg.genero_id_genero
                       WHERE pg.pelicula_id_pelicula = :id";
        $stmtG = $conexion->prepare($sqlGeneros);
        $stmtG->bindParam(':id', $id_peli);
        $stmtG->execute();
        // Creamos un array simple de nombres de géneros
        $generos_info = $stmtG->fetchAll(PDO::FETCH_COLUMN);

        $sqlActores = "SELECT a.nombre 
                       FROM actor a
                       JOIN pelicula_actor pa ON a.id_actor = pa.actor_id_actor
                       WHERE pa.pelicula_id_pelicula = :id";
        $stmtA = $conexion->prepare($sqlActores);
        $stmtA->bindParam(':id', $id_peli);
        $stmtA->execute();
        $elenco_info = $stmtA->fetchAll(PDO::FETCH_COLUMN);
        
        // Convertimos el array de actores a string para el resumen superior
        $elenco_resumen = implode(", ", array_slice($elenco_info, 0, 3)) . "...";


        $sqlTrailers = "SELECT ruta FROM traileres WHERE pelicula_id_pelicula = :id";
        $stmtT = $conexion->prepare($sqlTrailers);
        $stmtT->bindParam(':id', $id_peli);
        $stmtT->execute();
        $trailers = $stmtT->fetchAll(PDO::FETCH_COLUMN);
        
        $url_trailer1 = isset($trailers[0]) ? $trailers[0] : "";
        $url_trailer2 = isset($trailers[1]) ? $trailers[1] : "";

    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title>RewindCodeFilm - <?php echo $nombre_peli; ?></title>
    <link rel="stylesheet" href="./styles/serie.css" />
    <link rel="stylesheet" href="./styles/masinfo.css" />
</head>

<body>
    <div class="desktop">
    
        <img src="<?php echo $fondo_img; ?>" class="fondestringer-icon" alt="Carátula de <?php echo $nombre_peli; ?>" onerror="this.src='imgs/default_poster.jpg'">
        
        <?php rendernav(); ?>
        
        <?php
        // Lógica de disponibilidad basada en la base de datos
        if ($ncopia > 0) {
            renderdisponible($nombre_peli, $anio, $duracion_tag,$precio);
        } else {
            // Si es 0, no está disponible
            rendernodisponible($nombre_peli, $ncopia, $ncopiastotales, $nfila, $duracion_tag, $anio);
        }
        ?>
        
        <div class="rewindcodefilm">RewindCodeFilm</div>
        
        <?php 
            // Renderizamos los tags con datos de la BD
            rendertags($nombre_peli, $anio, $duracion_tag, $clasificacion, $descripcion, $elenco_resumen, $creadores); 
        ?>
        
        <?php 
            // Renderizamos los trailers obtenidos de la tabla 'traileres'
            renderTrailers($nombre_peli, $url_trailer1, $url_trailer2); 
        ?>
        
        <?php 
            // Renderizamos más info con datos reales
            renderMasInfo($generos_info[0], $descripcion, $elenco_resumen, $creadores); 
        ?>
    </div>
</body>
</html>