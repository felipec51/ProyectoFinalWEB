<?php
/**
 * Función para renderizar el componente "Más Info" de una serie/película.
 *
 * @param string $generos Cadena de texto con los géneros de la serie.
 * @param string $es Cadena de texto con las características principales de la serie.
 * @param string $elenco 
 * @return void 
 */

$peliculas = [
    // Datos de Stranger Things (ID: stranger_things)
    'stranger_things' => [
        'nombre' => 'Stranger Things',
        'imagen_fondo' => './imgs/fondestringer.png',
        'coincidencia' => '98%',
        'anio' => '2025',
        'clasificacion' => '16+',
        'duracion' => '4 temporadas',
        'calidad' => '4K Ultra HD',
        'descripcion' => 'Una fuerza maligna desciende sobre un pequeño pueblo de Indiana en los 80, forzando a un grupo de niños a desentrañar misterios sobrenaturales y experimentos gubernamentales secretos.',
        'elenco_resumen' => 'Winona Ryder, David Harbour, Millie Bobby Brown, Finn Wolfhard, Gaten Matarazzo, Caleb McLaughlin, Noah Schnapp, Sadie Sink, Natalia Dyer y Charlie Heaton',
        'creadores' => 'The Duffer Brothers',
        // Datos para la función renderMasInfo() en componentes/masinfo.php
        'generos' => "Series dramáticas, Series de sci-fi, Series de adolescentes, Series de terror",
        'es' => "Inquietante, Nostálgico, Banda Sonora destacada, Poderes psíquicos.<br>Los 80, De terror, Conspiración, Sci-fi, De adolescentes.",
        'elenco' => "Winona Ryder, David Harbour, Millie Bobby Brown, Finn Wolfhard, Gaten Matarazzo, Caleb McLaughlin, Noah Schnapp, Sadie Sink, Natalia Dyer y Charlie Heaton"
    ],
    
    // Datos de OTRA SERIE DE EJEMPLO (ID: the_crown)
    'the_crown' => [
        'nombre' => 'The Crown',
        'imagen_fondo' => 'https://placehold.co/1920x1104/1A2E44/FFF?text=The+Crown+Background', 
        'coincidencia' => '90%',
        'anio' => '2023',
        'clasificacion' => '13+',
        'duracion' => '6 temporadas',
        'calidad' => 'HD',
        'descripcion' => 'Una crónica de la vida de la Reina Isabel II, explorando sus relaciones, rivalidades y los eventos que moldearon la segunda mitad del siglo XX.',
        'elenco_resumen' => 'Olivia Colman, Imelda Staunton, Claire Foy, Matt Smith, Helena Bonham Carter',
        'creadores' => 'Peter Morgan',
        // Datos para la función renderMasInfo()
        'generos' => "Series históricas, Series dramáticas, Series de Reino Unido",
        'es' => "Formal, Épico, Basado en hechos reales, Vestuario espectacular.",
        'elenco' => "Claire Foy, Olivia Colman, Imelda Staunton, Matt Smith, Tobias Menzies y Jonathan Pryce"
    ]
    
    // ¡Aquí puedes seguir agregando más películas o series!
];


function renderMasInfo(string $generos, string $es, string $elenco): void {
?>

<section class="mas-info-div">
    <h2 class="ms-info">Más info</h2>

    <div class="info-box info-box-1">
        <div class="div-audio-subtitulos-child"></div>
        <div class="informacion3">
            <div class="generos">
                <strong class="gneros">Géneros</strong>
                <!-- USO DE $generos (Parámetro 1 de la función) -->
                <span class="series-dramaticasseries-de"><?= $generos ?></span>
            </div>
            <div class="esta-pelicualaseriees">
                <strong class="esta-serie-es">Esta Serie es ..</strong>
                <!-- USO DE $es (Parámetro 2 de la función) -->
                <span class="inquietantenostalgicobanda-s"><?= $es ?></span>
            </div>
            <div class="acerda-peliculaserie">
                <strong class="acerca-de-nombre">Acerca de Nombre de serie</strong>
                <span class="descubre-el-detras">Descubre el detrás de cámara y obtén más info en Tudum.com</span>
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
                <strong class="elenco">Elenco</strong>
                <span class="winona-ryder-david"><?= $elenco ?></span>
            </div>
        </div>
    </div>
</section>

<?php
} 
?>