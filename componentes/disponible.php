<?php
function renderdisponible($nombre_peli, $anio, $duracion_tag, $precio, $id_peli, $id_usuario_sesion): void
{ ?>

    <link rel="stylesheet" href="./styles/disponible.css" />


    <div class="container">
        <div class="title-group">
            <div class="main-title"><?php echo $nombre_peli; ?></div>
            <div class="metadata-group">
                <div class="metadata-item metadata-match">
                    <div class="match-score">98% de coincidencia</div>
                </div>
                <div class="metadata-item metadata-year">
                    <div class="text-white"><?php echo $anio; ?></div>
                </div>
                <div class="age-rating-box metadata-age-rating">
                    16+
                </div>
                <div class="metadata-item metadata-duration">
                    <div class="text-white"><?php echo $duracion_tag; ?></div>
                </div>
                <div class="age-rating-box metadata-quality">
                    4K Ultra HD
                </div>
            </div>
        </div>

        <form id="form-alquiler" action="alquilar_pelicula.php" method="POST">
            <input type="hidden" name="id_pelicula" value="<?php echo $id_peli; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_sesion; ?>"> 
            <input type="hidden" name="precio" value="<?php echo $precio; ?>"> 
            <div class="rent-options-group">
                <div class="option-label-row">
                    <div class="label-text">Selecciona el periodo de renta:</div>
                </div>
                <div class="rent-card-container">
                    <div class="rent-card card-pos-1">
                        <div class="card-icon-container">
                            <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                        </div>
                        <div class="card-duration-text">7 días</div>
                        <div class="card-price-row">
                            <div class="card-price-text">$<?php echo $precio; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="rent-button">
                <div class="button-icon-container">
                    <img src="./imgs/icons/movieIcon.svg" alt="fecha">

                </div>
                <div class="button-text">Rentar película</div>
            </button>
        </form>

    </div>
<?php
}
?>