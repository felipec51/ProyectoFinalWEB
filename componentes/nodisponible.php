<?php
function rendernodisponible(string $nombre_serie, string $ncopia, string $ncopiastotales, string $nfila, string $duracion_tag, string $anio, string $id_peli, bool $usuario_en_lista): void
{
?>
  <link rel="stylesheet" href="./styles/nodisponible.css" />

  <div class="container">
    <div class="info-principal">
      <div class="titulo-principal"><?php echo $nombre_serie; ?></div>
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
            <div class="valor"><?php echo $ncopia; ?></div>
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

      <div class="bloque fila-espera">
        <div class="fila">
          <div class="icono">
           <img src="./imgs/icons/Icon-4.svg" alt="fila" />
          </div>
          <div class="texto">
            <div class="numero"><?php echo $nfila; ?>  en fila</div>
           
          </div>
        </div>
        <div class="fila">
          <div class="icono">
            <img src="./imgs/icons/Icon-fecha2.svg" alt="fecha" />
          </div>
          <div class="texto">
            <div class="etiqueta">Disponible aprox.</div>
            <div class="valor">15 de noviembre de 2025</div>
          </div>
        </div>
      </div>

      <div class="bloque precio">
        <div class="precio-valor">$14.99 <span class="moneda">USD</span></div>
        <div class="mensaje">No disponible para Rentar</div>
      </div>

      <?php if ($usuario_en_lista): ?>
        <!-- Formulario para QUITARSE de la lista -->
        <form action="quitar_lista_espera.php" method="POST" style="display: contents;">
            <input type="hidden" name="pelicula_id" value="<?php echo $id_peli; ?>">
            <button type="submit" class="boton-renta boton-quitar">
              <img src="./imgs/icons/Icon-close.svg" alt="quitar" />
              <div class="texto-boton">Quitar de la fila</div>
            </button>
        </form>
      <?php else: ?>
        <!-- Formulario para AÑADIRSE a la lista -->
        <form action="unirse_lista_espera.php" method="POST" style="display: contents;">
            <input type="hidden" name="pelicula_id" value="<?php echo $id_peli; ?>">
            <button type="submit" class="boton-renta boton-fila">
              <img src="./imgs/icons/Icon-6.svg" alt="añadir" />
              <div class="texto-boton">Añadirme a la fila</div>
            </button>
        </form>
      <?php endif; ?>
    </div>
  </div>

<?php
}
?>