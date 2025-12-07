<?php
include 'check_session.php'; 

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: login.php"); 
    exit;
}
$usuario_logueado_id = $_SESSION["id_usuario"];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, width=device-width">
	<link rel="stylesheet" href="./styles/config.css">
	<link rel="stylesheet" href="./styles/compeditarpeli.css" />
</head>
<body>
	<div class="editar-pelicula">
		<div class="fondo-negro-div">
		</div>
		<div class="editar-pelicula-child">
		</div>
		<div class="dialogheader">
			<div class="primitiveh2">
				<b class="editar-pelcula">Editar Película</b>
				<img class="icon" alt="">
			</div>
			<div class="moviedialog">
				<div class="complete-los-detalles">Complete los detalles de la película para Actualizar </div>
			</div>
		</div>
		<div class="editar-pelicula-moviedialog">
			<div class="container">
				<div class="editar-pelicula-container">
					<div class="primitivelabel">
						<div class="ttulo">Título</div>
					</div>
					<div class="input">
						<div class="interestelar">Interestelar</div>
					</div>
				</div>
				<div class="container2">
					<div class="container3">
						<div class="editar-pelicula-primitivelabel">
							<div class="ttulo">Año</div>
						</div>
						<div class="editar-pelicula-input">
							<div class="interestelar">2014</div>
						</div>
					</div>
					<div class="container4">
						<div class="editar-pelicula-primitivelabel">
							<div class="ttulo">Rating</div>
						</div>
						<div class="editar-pelicula-input">
							<div class="interestelar">8.6</div>
						</div>
					</div>
				</div>
				<div class="container5">
					<div class="primitivelabel">
						<div class="ttulo">Género</div>
					</div>
					<div class="primitivebutton">
						<div class="primitivespan">
							<div class="ciencia-ficcin">Ciencia Ficción</div>
						</div>
						<img class="editar-pelicula-icon" alt="">
					</div>
				</div>
				<div class="container6">
					<div class="primitivelabel4">
						<div class="ttulo">Director</div>
					</div>
					<div class="input3">
						<div class="interestelar">Christopher Nolan</div>
					</div>
				</div>
				<div class="container7">
					<div class="primitivelabel4">
						<div class="ttulo">URL del Póster</div>
					</div>
					<div class="input3">
						<div class="httpsimagesunsplashcomph">https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb3ZpZSUyMGNpbmVtYSUyMHBvc3RlcnxlbnwxfHx8fDE3NjI0NDIzNzR8MA&ixlib=rb-4.1.0&q=80&w=1080</div>
					</div>
				</div>
			</div>
			<div class="dialogfooter">
				<div class="button">
					<div class="ciencia-ficcin">Cancelar</div>
				</div>
				<div class="editar-pelicula-button">
					<div class="ciencia-ficcin">Guardar cambios</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>