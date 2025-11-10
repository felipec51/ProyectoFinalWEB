<?php
/**
 * Función para renderizar el componente "Más Info" de una serie/película.
 *
 * @param string $nombre_serie Cadena de texto con el nombre de la serie.
 * @param string $anio Cadena de texto con el año de la serie.
 * @param string $duracion_tag Cadena de texto con la duración de la serie.
 * @param string $clasificacion Cadena de texto con la clasificación de la serie.
 * @param string $descripcion Cadena de texto con la descripción de la serie.
 * @param string $elenco_resumen Cadena de texto con el resumen del elenco.
 * @param string $creadores Cadena de texto con los creadores de la serie.
 * @return void
 */


function rendertags(string $nombre_serie, string $anio, string $duracion_tag, string $clasificacion,string $descripcion, string $elenco_resumen, string $creadores): void {
?>
<link rel="stylesheet" href="./styles/tagsinfo.css" />
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
<?php
} 
?>