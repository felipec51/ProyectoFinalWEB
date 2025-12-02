<?php
require_once 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];


include 'componentes/masinfo.php';
include 'componentes/tagserie.php';
include 'componentes/trailers.php';
include 'componentes/navpeli.php';
include 'componentes/avances.php';
include 'componentes/nodisponible.php';
include 'componentes/disponible.php';

$id_peli = isset($_GET['id']) ? $_GET['id'] : 1;

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$nombre_peli = "No encontrado";
$fondo_img = "";
$descripcion = "La película solicitada no existe.";
$anio = "";
$duracion_encabezado = "";
$duracion_tag = "";
$clasificacion = "";
$creadores = "";
$ncopia = 0;
$ncopiastotales = 0;
$nfila = 1;
$trailers = []; 
$generos_info = [];
$elenco_info = [];
$episodios = []; 

try {
    $sql = "SELECT p.*, d.nombre as nombre_director 
            FROM pelicula p 
            JOIN director d ON p.director_id_director = d.id_director 
            WHERE p.id_pelicula = :id";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_peli);
    $stmt->execute();
    $pelicula_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pelicula_actual) {
        $nombre_peli = htmlspecialchars($pelicula_actual['titulo']);
        $fondo_img = htmlspecialchars($pelicula_actual['poster_path']);
        $anio = htmlspecialchars($pelicula_actual['anio']);
        $precio = htmlspecialchars($pelicula_actual['precio_alquiler']);
        $clasificacion = htmlspecialchars($pelicula_actual['calificacion']);
        $descripcion = nl2br(htmlspecialchars($pelicula_actual['descripcion']));
        $creadores = htmlspecialchars($pelicula_actual['nombre_director']);
        
        $horas = floor($pelicula_actual['duracion_min'] / 60);
        $minutos = $pelicula_actual['duracion_min'] % 60;
        $duracion_encabezado = "{$horas}h {$minutos}m";
        $duracion_tag = "{$pelicula_actual['duracion_min']} min";

        $ncopia = $pelicula_actual['ncopias'];
        
        // Corregido: Obtener el total de copias desde la tabla 'cinta'
        $sqlTotalCopias = "SELECT COUNT(*) FROM cinta WHERE pelicula_id_pelicula = :id_peli";
        $stmtTotal = $conexion->prepare($sqlTotalCopias);
        $stmtTotal->bindParam(':id_peli', $id_peli, PDO::PARAM_INT);
        $stmtTotal->execute();
        $ncopiastotales = $stmtTotal->fetchColumn();

        $coincidencia = "98%"; 
        $calidad = "HD";       
        $nfila = 1;            


        $sqlGeneros = "SELECT g.nombre 
                       FROM genero g
                       JOIN pelicula_genero pg ON g.id_genero = pg.genero_id_genero
                       WHERE pg.pelicula_id_pelicula = :id";
        $stmtG = $conexion->prepare($sqlGeneros);
        $stmtG->bindParam(':id', $id_peli);
        $stmtG->execute();
        $generos_info = $stmtG->fetchAll(PDO::FETCH_COLUMN);

        $sqlActores = "SELECT a.nombre 
                       FROM actor a
                       JOIN pelicula_actor pa ON a.id_actor = pa.actor_id_actor
                       WHERE pa.pelicula_id_pelicula = :id";
        $stmtA = $conexion->prepare($sqlActores);
        $stmtA->bindParam(':id', $id_peli);
        $stmtA->execute();
        $elenco_info = $stmtA->fetchAll(PDO::FETCH_COLUMN);
        
        $elenco_resumen = implode(", ", array_slice($elenco_info, 0, 3)) . "...";


        $sqlTrailers = "SELECT ruta FROM traileres WHERE pelicula_id_pelicula = :id ORDER BY id_traileres ASC";
        $stmtT = $conexion->prepare($sqlTrailers);
        $stmtT->bindParam(':id', $id_peli);
        $stmtT->execute();
        $trailers = $stmtT->fetchAll(PDO::FETCH_COLUMN);

        // Verificar si el usuario está en la lista de espera, solo si no hay copias
        $usuario_en_lista = false;
        if ($ncopia <= 0) {
            $sqlCheckEspera = "SELECT Usuario_id_usuario FROM lista_espera WHERE Usuario_id_usuario = :id_usuario AND pelicula_id_pelicula = :id_peli";
            $stmtCheckEspera = $conexion->prepare($sqlCheckEspera);
            $stmtCheckEspera->bindParam(':id_usuario', $usuario_logueado_id, PDO::PARAM_INT);
            $stmtCheckEspera->bindParam(':id_peli', $id_peli, PDO::PARAM_INT);
            $stmtCheckEspera->execute();
            if ($stmtCheckEspera->fetch()) {
                $usuario_en_lista = true;
            }
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title>RewindCodeFilm - <?php echo $nombre_peli; ?></title>
    <link rel="stylesheet" href="./styles/serie.css" />
    <link rel="stylesheet" href="./styles/masinfo.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="desktop">
    
        <img src="<?php echo $fondo_img; ?>" class="fondestringer-icon" alt="Carátula de <?php echo $nombre_peli; ?>" onerror="this.src='imgs/default_poster.jpg'">
        
        <?php rendernav(); ?>
        
        <?php
        if ($ncopia > 0) {
            renderdisponible($nombre_peli, $anio, $duracion_tag, $precio, $id_peli, $usuario_logueado_id, $ncopia, $ncopiastotales);
        } else {
            rendernodisponible($nombre_peli, $ncopia, $ncopiastotales, $nfila, $duracion_tag, $anio, $id_peli, $usuario_en_lista);
        }
        ?>
        
        <div class="rewindcodefilm">RewindCodeFilm</div>
        
        <?php 
            $primer_genero = isset($generos_info[0]) ? $generos_info[0] : 'Desconocido';
            rendertags($nombre_peli, $anio, $duracion_tag, $clasificacion, $descripcion, $elenco_resumen, $creadores); 
        ?>
        
        <?php 
            renderTrailers($nombre_peli, $trailers); 
        ?>
        
        <?php 
            renderMasInfo($primer_genero, $descripcion, $elenco_resumen, $creadores); 
        ?>
    </div>

    
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formAlquiler = document.getElementById('form-alquiler');

        if (formAlquiler) {
            formAlquiler.addEventListener('submit', function(e) {
                e.preventDefault();

                const rentButton = document.getElementById('rent-button');
                const buttonText = rentButton.querySelector('.texto-boton'); // CORREGIDO
                const originalButtonText = buttonText.innerHTML;
                const ncopiasDisplay = document.getElementById('ncopias-display');

                // 1. Estado de carga
                rentButton.disabled = true;
                buttonText.innerHTML = 'Procesando...';

                const formData = new FormData(this);

                fetch('alquilar_pelicula.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('El servidor respondió con un error.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // 2. Éxito
                        Swal.fire({
                            title: '¡Alquiler Exitoso!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Entendido'
                        });

                        if (ncopiasDisplay) {
                            ncopiasDisplay.textContent = data.ncopias;
                        }

                        buttonText.innerHTML = 'Ya lo tienes alquilado';
                        rentButton.disabled = true;

                        if (data.ncopias <= 0) {
                            formAlquiler.style.display = 'none';
                            const container = document.getElementById('main-container');
                            if (container) {
                                 const noCopiesMsg = document.createElement('p');
                                 noCopiesMsg.textContent = 'No quedan más copias disponibles.';
                                 noCopiesMsg.style.color = 'white';
                                 noCopiesMsg.style.marginTop = '20px';
                                 container.appendChild(noCopiesMsg);
                            }
                        }
                    } else {
                        // 3. Error del servidor (ej: ya alquilada)
                        Swal.fire({
                            title: 'Atención',
                            text: data.message,
                            icon: 'warning',
                            confirmButtonText: 'Entendido'
                        });
                        rentButton.disabled = false;
                        buttonText.innerHTML = originalButtonText;
                    }
                })
                .catch(error => {
                    // 4. Error de red o conexión
                    console.error('Error en fetch:', error);
                    Swal.fire({
                        title: 'Error de Conexión',
                        text: 'No se pudo completar la solicitud.',
                        icon: 'error'
                    });
                    rentButton.disabled = false;
                    buttonText.innerHTML = originalButtonText;
                });
            });
        }
    });
</script>