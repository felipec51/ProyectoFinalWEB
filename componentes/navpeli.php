<?php


function rendernav(): void {
?>
<link rel="stylesheet" href="./styles/navpeli.css" />
<nav class="barra-nav-en-peliculas">
            <div class="barra-nav-en-peliculas-child"></div>
            <a href="#traileres-section" class="nav-item traileres">Tráileres</a>
            <a href="#episodios-section" class="nav-item episodios">Avances</a>
            <a href="peliculasMenu.php" class="nav-item mas-contenido-para">Mas contenido para ver</a>
            <a href="#mas-info-div" class="nav-item planes">Mas informacion</a>
        </nav>
<?php
} 
?>