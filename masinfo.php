<?php
/**
 * BASE DE DATOS MOCK: Arreglo asociativo con los datos de series/películas.
 * La clave de cada elemento es el ID único (slug) que se usará en la URL.
 * Ejemplo: serie.php?id=stranger_things
 */
$peliculas = [
    // ===============================================
    // Datos de Stranger Things (ID: stranger_things)
    // ===============================================
    'stranger_things' => [
        'nombre' => 'Stranger Things',
        'imagen_fondo' => './imgs/fondestringer.png',
        'coincidencia' => '98%',
        'anio' => '2025',
        'clasificacion' => '16+',
        'duracion' => '4 temporadas', // Para el tag de info
        'duracion_min' => '2h 15min', // Para el encabezado
        'calidad' => '4K Ultra HD',
        'descripcion' => 'Una fuerza maligna desciende sobre un pequeño pueblo de Indiana en los 80, forzando a un grupo de niños a desentrañar misterios sobrenaturales y experimentos gubernamentales secretos.',
        'elenco_resumen' => 'Winona Ryder, David Harbour, Millie Bobby Brown, Finn Wolfhard, Gaten Matarazzo, Caleb McLaughlin, Noah Schnapp, Sadie Sink, Natalia Dyer y Charlie Heaton',
        'creadores' => 'The Duffer Brothers',
        // Datos para la función renderMasInfo()
        'generos' => "Series dramáticas, Series de sci-fi, Series de adolescentes, Series de terror",
        'es' => "Inquietante, Nostálgico, Banda Sonora destacada, Poderes psíquicos.<br>Los 80, De terror, Conspiración, Sci-fi, De adolescentes.",
        'elenco' => "Winona Ryder, David Harbour, Millie Bobby Brown, Finn Wolfhard, Gaten Matenazzo, Caleb McLaughlin, Noah Schnapp, Sadie Sink, Natalia Dyer y Charlie Heaton"
    ],
    
    // ===========================================
    // Datos de OTRA SERIE DE EJEMPLO (ID: the_crown)
    // ===========================================
    'the_crown' => [
        'nombre' => 'The Crown',
        'imagen_fondo' => 'https://placehold.co/1920x1104/1A2E44/FFF?text=The+Crown+Background', 
        'coincidencia' => '90%',
        'anio' => '2023',
        'clasificacion' => '13+',
        'duracion' => '6 temporadas',
        'duracion_min' => '1h 30min', 
        'calidad' => 'HD',
        'descripcion' => 'Una crónica de la vida de la Reina Isabel II, explorando sus relaciones, rivalidades y los eventos que moldearon la segunda mitad del siglo XX.',
        'elenco_resumen' => 'Olivia Colman, Imelda Staunton, Claire Foy, Matt Smith, Helena Bonham Carter',
        'creadores' => 'Peter Morgan',
        // Datos para la función renderMasInfo()
        'generos' => "Series históricas, Series dramáticas, Series de Reino Unido",
        'es' => "Formal, Épico, Basado en hechos reales, Vestuario espectacular.",
        'elenco' => "Claire Foy, Olivia Colman, Imelda Staunton, Matt Smith, Tobias Menzies y Jonathan Pryce"
    ]
];
?>