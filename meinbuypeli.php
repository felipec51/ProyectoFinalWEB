<?php
// ** MANDAORY: Incluye el archivo que contiene el arreglo de datos $peliculas
// Debe estar en este mismo directorio.
include 'data_peliculas.php';

// ** MANDAORY: Incluye la función que renderiza el bloque de "Más Información"
// Debe estar en el subdirectorio 'componentes'.
include 'componentes/masinfo.php';

// 1.1 Lógica para cargar el contenido correcto
// Obtiene el ID de la URL (ej: serie.php?id=stranger_things) o usa un valor predeterminado si no hay ID.
$id_serie = $_GET['id'] ?? 'stranger_things';

// Carga los datos de la película/serie actual
if (isset($peliculas[$id_serie])) {
	$pelicula_actual = $peliculas[$id_serie];
} else {
	// Si el ID no existe, carga la serie por defecto para evitar errores
	$pelicula_actual = $peliculas['stranger_things'];
}

// 1.2 Asignación y sanitización de variables para inyección en HTML
$nombre_serie = htmlspecialchars($pelicula_actual['nombre']);
$fondo_img = htmlspecialchars($pelicula_actual['imagen_fondo']);
$coincidencia = htmlspecialchars($pelicula_actual['coincidencia']);
$anio = htmlspecialchars($pelicula_actual['anio']);
$clasificacion = htmlspecialchars($pelicula_actual['clasificacion']);
// Usamos 'duracion_min' (ej: 2h 15min) para el encabezado. Se usa 'duracion' como fallback.
$duracion_encabezado = htmlspecialchars($pelicula_actual['duracion_min'] ?? $pelicula_actual['duracion']);
// Usamos 'duracion' (ej: 4 temporadas) para el tag de info
$duracion_tag = htmlspecialchars($pelicula_actual['duracion']);
$calidad = htmlspecialchars($pelicula_actual['calidad']);
// nl2br respeta los saltos de línea en la descripción
$descripcion = nl2br(htmlspecialchars($pelicula_actual['descripcion']));
$elenco_resumen = htmlspecialchars($pelicula_actual['elenco_resumen']);
$creadores = htmlspecialchars($pelicula_actual['creadores']);

// 1.3 Variables específicas para el componente renderMasInfo()
$generos_info = $pelicula_actual['generos'];
$esta_serie_es_info = $pelicula_actual['es'];
$elenco_info = $pelicula_actual['elenco'];
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, width=device-width">
	<link rel="stylesheet" href="./styles/meinbuypeli.css" />
	<link rel="stylesheet" href="./styles/masinfo.css" />
</head>

<body>

	<div class="header">
		<div class="container11">
			<div class="container12">
				<img src="./imgs/icons/Icon-7.svg" class="button-icon icon-sm" alt="">
				<img src="./imgs/icons/Icon-5.svg" class="button-icon icon-sm" alt="">
				<img src="./imgs/icons/Icon-3.svg" class="button-icon icon-sm" alt="">
			</div>
		</div>
		<?php renderMasInfo($generos_info, $esta_serie_es_info, $elenco_info); ?>
		
</body>

</html>