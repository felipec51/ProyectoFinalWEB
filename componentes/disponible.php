<?php
function renderdisponible($nombre_peli, $anio, $duracion_tag, $precio): void
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

        <div class="rent-options-group">
            <div class="option-label-row">
                <div class="label-text">Selecciona el periodo de renta:</div>
            </div>
            <div class="rent-card-container">
                <div class="rent-card card-pos-1">
                    <div class="card-icon-container">
                        <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                    </div>
                    <div class="card-duration-text">3 días</div>
                    <div class="card-price-row">
                        <div class="card-price-text">$<?php echo $precio; ?></div>
                    </div>
                </div>

                <!-- Tarjeta 2: 15 días -->
                <div class="rent-card card-pos-2">
                    <div class="card-icon-container">
                        <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                    </div>
                    <div class="card-duration-text">15 días</div>
                    <div class="card-price-row">
                        <div class="card-price-text">$<?php echo $precio * 1.3; ?></div>
                    </div>
                </div>

                <!-- Tarjeta 3: 1 mes -->
                <div class="rent-card card-pos-3">
                    <div class="card-icon-container">
                        <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                    </div>
                    <div class="card-duration-text">1 mes</div>
                    <div class="card-price-row">
                        <div class="card-price-text">$<?php echo $precio * 1.6; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de Rentar Película -->
        <div class="rent-button">
            <div class="button-icon-container">
                <img src="./imgs/icons/movieIcon.svg" alt="fecha">

            </div>
            <div class="button-text">Rentar película</div>
        </div>

    </div>
<?php
}
?>