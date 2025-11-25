<?php
function renderpeliculas(array $peliculas): void
{
    ?>
    <link rel="stylesheet" href="./styles/listpeliculasadmin.css" />
<div class="peliculas-container">
<?php foreach($peliculas as $pelicula): 

    $id = htmlspecialchars($pelicula['id_pelicula']); 
    $nombre = htmlspecialchars($pelicula['titulo']);
    $director = htmlspecialchars($pelicula['director_nombre']);
    $anio = htmlspecialchars($pelicula['anio']);
    $generos = htmlspecialchars($pelicula['generos']);
    $img = htmlspecialchars($pelicula['poster_path']);
    $calificacion = htmlspecialchars($pelicula['calificacion']);
?>
    <div class="netflixmoviecard card-base">

        <img src="<?php echo $img; ?>" class="image-inception-icon" alt="fondo de <?php echo $nombre; ?>">

        <div class="container15">

            <div class="heading-35 text-base"><?php echo $nombre; ?></div>

            <div class="paragra">
                <div class="en-el-catlogo"><?php echo $director; ?></div>
            </div>

            <div class="container16 flex-row-center">
                <div class="text flex-row-center">
                    <div class="ciencia-ficcin"><?php echo $anio; ?></div>
                </div>

                <div class="container17 flex-row-center">
                    <img src="./imgs/icons/icono-10-(14).svg" class="icon4" alt="">
                    <div class="text3 flex-row-center">
                        <div class="ciencia-ficcin"><?php echo $calificacion; ?></div>
                    </div>
                </div>
            </div>

            <div class="badge flex-row-center">
                <div class="ciencia-ficcin"><?php echo $generos; ?></div>
            </div>

            <div class="container18 flex-row-center">

                <div class="button2 btnEditar" data-id="<?php echo $id; ?>">
                    <img src="./imgs/icons/icono-10-(12).svg" class="icon5" alt="">
                   <a href="editar_pelicula.php?id=<?php echo $id; ?>"><div class="editar">Editar</div></a>
                </div>

               

            </div>

        </div>
    </div>

<?php endforeach; ?>
</div>

<?php
}
?>
