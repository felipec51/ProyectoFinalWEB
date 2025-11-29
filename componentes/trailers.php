<?php

function renderTrailers(string $nombre_peli, array $trailerUrls): void {
    $posicion_clases = [
        'trailer-1-pos', 
        'trailer-2-pos', 
        'trailer-3-pos'  
    ];

    $trailers_validos = array_filter($trailerUrls, function($url) {
        return !empty(trim($url));
    });

    $numero_trailers = count($trailers_validos);

    if ($numero_trailers === 0) {
        return;
    }
?>
<link rel="stylesheet" href="./styles/trailers.css" />
<section class="traileres2" id="traileres-section">
    <h2 class="trileres">Tráileres (<?php echo $numero_trailers; ?>)</h2>
    
    <?php foreach ($trailers_validos as $index => $url): 
        $clase_posicion = $posicion_clases[$index] ?? 'trailer-default-pos'; 
        
        $titulo_display = ($index === 0) 
            ? '<span class="triler-name-serie-container2"><span class="tr">Tráiler: ' . htmlspecialchars($nombre_peli) . '</span></span>'
            : htmlspecialchars($nombre_peli);
    ?>
    <div class="trailer-card <?php echo $clase_posicion; ?>">
        <iframe 
            class="trailer-image" 
            src="<?php echo htmlspecialchars($url); ?>" 
            title="Tráiler <?php echo $index + 1; ?> de <?php echo htmlspecialchars($nombre_peli); ?>" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;" 
            referrerpolicy="strict-origin-when-cross-origin" 
            allowfullscreen>
        </iframe>
        <p class="name-serie triler-name-serie-container">
            <?php echo $titulo_display; ?>
        </p>
    </div>
    <?php endforeach; ?>
    
</section>

<?php
} 
?>