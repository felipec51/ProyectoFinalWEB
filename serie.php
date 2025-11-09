<?php
// ==============================================================================
// 1. INCLUSIÓN DE ARCHIVOS Y LÓGICA DE DATOS
// ==============================================================================

// ** MANDAORY: Incluye el archivo que contiene el arreglo de datos $peliculas
// Debe estar en este mismo directorio.
include 'data_peliculas.php';

// ** MANDAORY: Incluye la función que renderiza el bloque de "Más Información"
// Debe estar en el subdirectorio 'componentes'.
include 'componentes/masinfo.php'; 

// 1.1 Lógica para cargar el contenido correcto
// Obtiene el ID de la URL (ej: serie.php?id=stranger_things) o usa un valor predeterminado si no hay ID.
$id_serie = $_GET['id'] ?? 'stranger_things'; 

// Carga los datos de la película/serie actual
if (isset($peliculas[$id_serie])) {
    $pelicula_actual = $peliculas[$id_serie];
} else {
    // Si el ID no existe, carga la serie por defecto para evitar errores
    $pelicula_actual = $peliculas['stranger_things']; 
}

// 1.2 Asignación y sanitización de variables para inyección en HTML
$nombre_serie = htmlspecialchars($pelicula_actual['nombre']);
$fondo_img = htmlspecialchars($pelicula_actual['imagen_fondo']);
$coincidencia = htmlspecialchars($pelicula_actual['coincidencia']);
$anio = htmlspecialchars($pelicula_actual['anio']);
$clasificacion = htmlspecialchars($pelicula_actual['clasificacion']);
// Usamos 'duracion_min' (ej: 2h 15min) para el encabezado. Se usa 'duracion' como fallback.
$duracion_encabezado = htmlspecialchars($pelicula_actual['duracion_min'] ?? $pelicula_actual['duracion']);
// Usamos 'duracion' (ej: 4 temporadas) para el tag de info
$duracion_tag = htmlspecialchars($pelicula_actual['duracion']);
$calidad = htmlspecialchars($pelicula_actual['calidad']);
// nl2br respeta los saltos de línea en la descripción
$descripcion = nl2br(htmlspecialchars($pelicula_actual['descripcion'])); 
$elenco_resumen = htmlspecialchars($pelicula_actual['elenco_resumen']);
$creadores = htmlspecialchars($pelicula_actual['creadores']);

// 1.3 Variables específicas para el componente renderMasInfo()
$generos_info = $pelicula_actual['generos'];
$esta_serie_es_info = $pelicula_actual['es'];
$elenco_info = $pelicula_actual['elenco'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title>RewindCodeFilm - <?php echo $nombre_serie; ?></title>
    <link rel="stylesheet" href="./styles/serie.css" />
    <link rel="stylesheet" href="./styles/masinfo.css" />
</head>

<body>
    <div class="desktop">
        <img src="<?php echo $fondo_img; ?>" class="fondestringer-icon" alt="Carátula de <?php echo $nombre_serie; ?>">

        <div class="disponible-rentar">
            <h1 class="heading-3">
                <span class="stranger-things"><?php echo $nombre_serie; ?></span>
                <div class="container2">
                    <span class="text"><?php echo $coincidencia; ?> de coincidencia</span>
                    <span class="text2"><?php echo $anio; ?></span>
                    <div class="badge rental-badge">
                        <span class="k-ultra-hd"><?php echo $clasificacion; ?></span>
                    </div>
                    <span class="text3"><?php echo $duracion_encabezado; ?></span>
                    <div class="badge rental-badge">
                        <span class="k-ultra-hd"><?php echo $calidad; ?></span>
                    </div>
                </div>
            </h1>

            <div class="dias-de-alquiler">
                <p class="paragraph">
                    <span class="selecciona-el-periodo">Selecciona el periodo de renta:</span>
                </p>
                <div class="container">
                    <div class="rental-option option-1">
                        <img src="./imgs/icons/iconofecha.svg" class="icon" alt="iconodefecha">
                        <span class="app5">3 días</span>
                        <span class="app2">$4.000</span>
                    </div>
                    <div class="rental-option option-2">
                        <img src="./imgs/icons/iconofecha.svg" class="icon" alt="iconodefecha">
                        <span class="app5">15 días</span>
                        <span class="app2">$10.000</span>
                    </div>
                    <div class="rental-option option-3">
                        <img src="./imgs/icons/iconofecha.svg" class="icon" alt="iconodefecha">
                        <span class="app5">1 mes</span>
                        <span class="app2">$19.000</span>
                    </div>
                </div>
            </div>

            <button class="rentarbtn">
                <img src="./imgs/icons/movieIcon.svg" class="icon4" alt="iconorentar">
                <span class="rentar-pelcula">Rentar película</span>
            </button>
        </div>

        <nav class="barra-nav-en-peliculas">
            <div class="barra-nav-en-peliculas-child"></div>
            <a href="#traileres-section" class="nav-item traileres">Tráileres</a>
            <a href="#episodios-section" class="nav-item episodios">Episodios</a>
            <a href="#" class="nav-item mas-contenido-para">Mas contenido para ver</a>
            <a href="#" class="nav-item planes">Planes</a>
        </nav>

        <div class="rewindcodefilm">RewindCodeFilm</div>

        <section class="info-pelicula">
            <div class="tags-de-peliculas">
                <div class="contenedor-div"></div>
                <h2 class="name-peli"><?php echo $nombre_serie; ?></h2>
                <span class="i"><?php echo $anio; ?> </span>
                <span class="temporadas"><?php echo $duracion_tag; ?></span>
                <span class="i2"><?php echo $clasificacion; ?></span>
                <span class="sci-fi">Sci-fi</span> 
            </div>
            <div class="text-info-peli">
                <p class="lorem-ipsum-dolor"><?php echo $descripcion; ?></p>
                <div class="text-info-peli-child"></div>
                <p class="elenco-wilona">
                    <strong>Elenco:</strong> <?php echo $elenco_resumen; ?><br>
                    <strong>Creadores:</strong> <?php echo $creadores; ?>
                </p>
            </div>
        </section>

        <section class="traileres2" id="traileres-section">
            <h2 class="trileres">Tráileres</h2>
            <div class="trailer-card trailer-1-pos">
                <iframe class="trailer-image"  src="https://www.youtube.com/embed/uDYUjyTUeek?si=8UCCMbPrSlSd4QE1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                <p class="triler-name-serie-container">
                    <span class="triler-name-serie-container2">
                        <span class="tr">Tráiler: <?php echo $nombre_serie; ?> # Temp (5)</span>
                    </span>
                </p>
            </div>
            <div class="trailer-card trailer-2-pos">
                <img class="trailer-image" alt="Tráiler de la serie">
                <p class="name-serie"><?php echo $nombre_serie; ?> # Temp (4)</p>
            </div>
            <div class="trailer-card trailer-3-pos">
                <img class="trailer-image" alt="Tráiler de la serie">
                <p class="name-serie"><?php echo $nombre_serie; ?> # Temp (3)</p>
            </div>
        </section>

        <section class="episodios2" id="episodios-section">
            <h2 class="episodios3">Episodios</h2>
            <div class="btn-elegir-temp">
                <div class="btn-elegir-temp-child"></div>
                <span class="nombre-de-serie"><?php echo $nombre_serie; ?></span>
            </div>

            <div class="episode-card episode-1-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 1">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            &lt;&lt;Capitulo uno: La desaparicion de
                            <span class="will-byers">Will Byers &gt;&gt;</span>
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-2-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 2">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            &lt;&lt;Capitulo dos: La desaparicion de
                            <span class="will-byers">Will Byers &gt;&gt;</span>
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-3-pos">
                <div class="episode-card-bg"></div>
                <iframe  class="episode-image-thumb" width="560" height="315" src="https://www.youtube.com/embed/DyvhuchMHY8?si=3pRu43BD7Do3Mefr&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            &lt;&lt;Capitulo tres: La desaparicion de
                            <span class="will-byers">Will Byers &gt;&gt;</span>
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
            <div class="episode-card episode-4-pos">
                <div class="episode-card-bg"></div>
                <img class="episode-image-thumb" alt="Miniatura del Capítulo 4">
                <div class="info-capitulo">
                    <h3 class="episode-title-text">
                        <span>
                            &lt;&lt;Capitulo cuatro: La desaparicion de
                            <span class="will-byers">Will Byers &gt;&gt;</span>
                        </span>
                    </h3>
                    <p class="descripsion-del-capitulo">Descripsion del capitulo <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam hendrerit magna vel ante eleifend efficitur. Proin quis urna id mauris </p>
                </div>
            </div>
        </section>
        
        <?php renderMasInfo($generos_info, $esta_serie_es_info, $elenco_info); ?>
    
    </div>
</body>
</html>