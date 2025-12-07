<?php
function rendernav(): void {
?>
<style>
    .barra-nav-inline {
        position: fixed;
        top: 20px;
        right: 30%;
        z-index: 1000;
        width: auto;
        height: 45px;
        border-radius: 22.5px;
        background-color: rgba(7, 9, 9, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        padding: 0 25px;
        gap: 30px;
        font-family: 'Arimo', sans-serif;
    }

    .nav-item-inline {
        color: #b0b0b0;
        text-decoration: none;
        font-size: 15px;
        transition: color 0.2s ease-in-out;
        white-space: nowrap;
    }

    .nav-item-inline:hover {
        color: #ffffff;
    }

    .back-link-inline {
        font-weight: 700;
        color: #ffffff;
        padding-right: 30px;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
<nav class="barra-nav-inline">
    <a href="peliculasMenu.php" class="nav-item-inline back-link-inline">‹ Volver al Catálogo</a>
    <a href="#traileres-section" class="nav-item-inline">Tráileres</a>
    <a href="#episodios-section" class="nav-item-inline">Avances</a>
    <a href="peliculasMenu.php" class="nav-item-inline">Mas contenido para ver</a>
    <a href="#mas-info-div" class="nav-item-inline">Mas informacion</a>
</nav>
<?php
} 
?>