<?php

require_once 'conexion.php';
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
        <div class="nav">
            <div class="rewind-code-film text-main-title">RewindCodeFilm</div>

            <div class="button_perfil" id="btn-perfil">
                <a href="perfil.php">
                    <img src="./imgs/icons/iconuser.png" class="icon8" alt="Icono de Perfil">
                    <strong>Perfil</strong>
                </a>
            </div>

            <div class="button_perfil" id="btn-noti">
                <a href="perfil.php">
                    <img src="./imgs/icons/icono-10-(17).svg" class="icon8" alt="Icono de Notificaciones">
                    <strong>Notificaciones</strong>
                </a>
            </div>

            <div class="button_perfil" id="btn-config">
                <a href="perfil.php">
                    <img src="./imgs/icons/icono-10-(10).svg" class="icon8" alt="Icono de Configuración">
                    <strong>Configuración</strong>
                </a>
            </div>

        </div>

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