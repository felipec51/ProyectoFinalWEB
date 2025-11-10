<?php
/**
 * Función para renderizar el componente "Tráileres" de una serie/película.
 *
 * @param string $nombre_serie Cadena de texto con el nombre de la serie.
 * @return void
 */

function renderTrailers(string $nombre_serie, string $url1,  string $url2): void {
?>
<link rel="stylesheet" href="./styles/trailers.css" />
<section class="traileres2" id="traileres-section">
    <h2 class="trileres">Tráileres</h2>
    <div class="trailer-card trailer-1-pos">
        <iframe class="trailer-image" src="<?php echo $url1; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <p class="triler-name-serie-container">
            <span class="triler-name-serie-container2">
                <span class="tr">Tráiler: <?php echo $nombre_serie; ?> # Temp (5)</span>
            </span>
        </p>
    </div>
    <div class="trailer-card trailer-2-pos">
        <iframe class="trailer-image" src="<?php echo $url2; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <p class="name-serie"><?php echo $nombre_serie; ?> # Temp (4)</p>
    </div>
    <div class="trailer-card trailer-3-pos">
        <iframe class="trailer-image" src="<?php echo $url2; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <p class="name-serie"><?php echo $nombre_serie; ?> # Temp (3)</p>
    </div>
</section>

<?php
} 
?>