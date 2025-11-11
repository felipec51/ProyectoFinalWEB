<?php
function renderdisponible(): void
{ ?>

    <link rel="stylesheet" href="./styles/disponible.css" />

   
<div class="container">
    <!-- Sección de Título y Metadatos -->
    <div class="title-group">
        <div class="main-title">Stranger things</div>
        <div class="metadata-group">
            <div class="metadata-item metadata-match">
                <div class="match-score">98% de coincidencia</div>
            </div>
            <div class="metadata-item metadata-year">
                <div class="text-white">2025</div>
            </div>
            <div class="age-rating-box metadata-age-rating">
                16+
            </div>
            <div class="metadata-item metadata-duration">
                <div class="text-white">2h 15min</div>
            </div>
            <div class="age-rating-box metadata-quality">
                4K Ultra HD
            </div>
        </div>
    </div>

    <!-- Sección de Opciones de Renta -->
    <div class="rent-options-group">
        <div class="option-label-row">
            <div class="label-text">Selecciona el periodo de renta:</div>
        </div>
        <div class="rent-card-container">
            <!-- Tarjeta 1: 3 días -->
            <div class="rent-card card-pos-1">
                <div class="card-icon-container">
                    <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                </div>
                <div class="card-duration-text">3 días</div>
                <div class="card-price-row">
                    <div class="card-price-text">$4.000</div>
                </div>
            </div>

            <!-- Tarjeta 2: 15 días -->
            <div class="rent-card card-pos-2">
                <div class="card-icon-container">
                    <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                </div>
                <div class="card-duration-text">15 días</div>
                <div class="card-price-row">
                    <div class="card-price-text">$10.000</div>
                </div>
            </div>

            <!-- Tarjeta 3: 1 mes -->
            <div class="rent-card card-pos-3">
                <div class="card-icon-container">
                    <img src="./imgs/icons/iconofecha.svg" alt="fecha">
                </div>
                <div class="card-duration-text">1 mes</div>
                <div class="card-price-row">
                    <div class="card-price-text">$19.000</div>
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