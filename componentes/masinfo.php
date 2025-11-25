<?php

function renderMasInfo(string $generos, string $es, string $elenco, string $director): void {
?>

<link rel="stylesheet" href="./styles/masinfo.css" />

<section class="mas-info-div" id="mas-info-div">
    <h2 class="ms-info">Más info</h2>
    
    <div class="info-container-flex">

        <div class="info-box info-box-1">
            <div class="div-audio-subtitulos-child"></div>
            <div class="informacion3">
                <div class="generos">
                    <strong class="gneros">Géneros</strong>
                    <span class="series-dramaticasseries-de"><?= htmlspecialchars($generos) ?></span>
                </div>
                <div class="esta-pelicualaseriees">
                    <strong class="esta-serie-es">Esta Serie es ..</strong>
                    <span class="inquietantenostalgicobanda-s"><?= htmlspecialchars($es) ?></span>
                </div>
            </div>
        </div>

        <div class="info-box info-box-2">
            <div class="div-audio-subtitulos-child"></div>
            <div class="informacion">
                <div class="audio">
                    <strong class="audio2">Audio</strong>
                    <span class="ingls-audio">Inglés - Audio descriptivo, Inglés [Original], Español - Audio descriptivo y Español</span>
                </div>
                <div class="subtitulos">
                    <strong class="subttulos">Subtítulos</strong>
                    <span class="ingls-y-espaol">Inglés y Español</span>
                </div>
            </div>
        </div>

        <div class="info-box info-box-3">
            <div class="div-audio-subtitulos-child"></div>
            <div class="informacion2"> 
                <div class="tags-de-peliculas">
                    <strong class="elenco">Elenco:</strong>
                    <span class="elencoCompleto"><?= htmlspecialchars($elenco) ?></span>
                </div>
                <div class="tags-de-peliculas">
                    <strong class="Director">Director:</strong>
                    <span class="DirectorCompleto"><?= htmlspecialchars($director) ?></span>
                </div>
            </div>
        </div>
        
    </div> </section>

<?php
} 
?>