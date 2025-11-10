<?php
/**
 * Función para renderizar el componente "Más Info" de una serie/película.
 

 * @return void
 */


function rendernav(): void {
?>
<link rel="stylesheet" href="./styles/navpeli.css" />
<nav class="barra-nav-en-peliculas">
            <div class="barra-nav-en-peliculas-child"></div>
            <a href="#traileres-section" class="nav-item traileres">Tráileres</a>
            <a href="#episodios-section" class="nav-item episodios">Episodios</a>
            <a href="#" class="nav-item mas-contenido-para">Mas contenido para ver</a>
            <a href="#" class="nav-item planes">Planes</a>
        </nav>
<?php
} 
?>