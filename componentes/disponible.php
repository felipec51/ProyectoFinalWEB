<?php
function renderdisponible(string $nombre_peli, string $anio, string $duracion_tag, string $precio, int $id_peli, int $id_usuario_sesion, int $ncopias, int $ncopiastotales): void
{ ?>

    <link rel="stylesheet" href="./styles/nodisponible.css" /> 
    <link rel="stylesheet" href="./styles/disponible.css" />

    <div class="container" id="main-container">
        <div class="info-principal">
            <div class="titulo-principal"><?php echo $nombre_peli; ?></div>
            <div class="detalles">
                <div class="coincidencia">98% de coincidencia</div>
                <div class="anio"><?php echo $anio; ?></div>
                <div class="edad">16+</div>
                <div class="duracion"><?php echo $duracion_tag; ?></div>
                <div class="calidad">4K Ultra HD</div>
            </div>
        </div>

        <div class="panel-informacion">
            <div class="bloque disponibles">
                <div class="fila">
                    <div class="icono">
                        <img src="./imgs/icons/Icon-movie2.svg" alt="disponibles" />
                    </div>
                    <div class="texto">
                        <div class="etiqueta">Disponibles</div>
                        <div class="valor" id="ncopias-display"><?php echo $ncopias; ?></div>
                    </div>
                    <div class="divisor"></div>
                    <div class="icono">
                        <img src="./imgs/icons/Icon-2.svg" alt="Total copias" />
                    </div>
                    <div class="texto">
                        <div class="etiqueta">Total copias</div>
                        <div class="valor"><?php echo $ncopiastotales; ?></div>
                    </div>
                </div>
            </div>

            <div class="bloque precio">
                <div class="precio-valor">$<?php echo number_format((float)$precio, 2); ?> <span class="moneda">COP</span></div>
                <div class="mensaje">Disponible para Rentar</div>
            </div>

            <form id="form-alquiler" action="alquilar_pelicula.php" method="POST">
                <input type="hidden" name="id_pelicula" value="<?php echo $id_peli; ?>">
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_sesion; ?>">
                <input type="hidden" name="precio" value="<?php echo $precio; ?>">
                <button type="submit" class="boton-renta boton-alquilar" id="rent-button">
                    <img src="./imgs/icons/movieIcon.svg" alt="Rentar" />
                    <div class="texto-boton">Rentar película</div>
                </button>
            </form>
        </div>
    </div>
<?php
}
?>