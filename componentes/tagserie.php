<?php


function rendertags(string $nombre_serie, string $anio, string $duracion_tag, string $clasificacion,string $descripcion, string $actor, string $creadores): void {
?>
<link rel="stylesheet" href="./styles/tagseries.css" />
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
            <strong>Elenco: <?php echo $actor; ?><br>Director: <?php echo $creadores; ?></strong>
        </p>
        
    </div>
</section>
<?php
} 
?>