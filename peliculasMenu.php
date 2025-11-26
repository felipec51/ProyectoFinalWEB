<?php
include 'componentes/headermain.php';
require_once 'conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];


try {
    $objetoConexion = Conexion::Conectar();

    $sql = "SELECT id_pelicula, titulo, poster_path FROM pelicula";
    $sentencia = $objetoConexion->prepare($sql);
    $sentencia->execute();
    $listaPeliculas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $gruposPeliculas = array_chunk($listaPeliculas, 6);
} catch (Exception $e) {
    echo "Error al cargar películas: " . $e->getMessage();
    $gruposPeliculas = [];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles/peliculasMenu.css" />
    <link rel="stylesheet" href="styles/config.css" />
    <title>RewindCodeFilm</title>
</head>

<body>

    <div class="desktop">
        <?php navheader("Inicio", $usuario_logueado_id) ?>

        <div class="conten-div">
            <div class="text-wrapper-4 text-main-title">Solo en RewindCodeFilm</div>
            <p class="p text-body">
                En RewindCodeFilm tenemos las mejores películas, que no hay en otro lugar.
                Disfruta tus Películas, Series, especiales, etc. Y todos elegidos solo para ti.
            </p>
        </div>
        <?php

        if (count($gruposPeliculas) > 0):

            foreach ($gruposPeliculas as $grupo):
        ?>
                <div class="vtp-peliculas">
                    <?php

                    foreach ($grupo as $pelicula):

                        $titulo = htmlspecialchars($pelicula['titulo']);
                        $imagen = htmlspecialchars($pelicula['poster_path']);
                        $id = $pelicula['id_pelicula'];
                    ?>
                        <img
                            src="<?php echo $imagen; ?>"
                            alt="<?php echo $titulo; ?>"
                            title="<?php echo $titulo; ?>"
                            onclick="location.href='pelicula.php?id=<?php echo $id; ?>'"
                            style="cursor: pointer;" />
                    <?php endforeach; ?>
                </div>
            <?php
            endforeach;
        else:
            ?>
            <p style="color: white; text-align: center;">No hay películas disponibles en este momento.</p>
        <?php endif; ?>

    </div>
</body>

</html>