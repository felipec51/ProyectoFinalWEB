<?php
// Archivo: componente_peliculas.php (Donde defines la función)
function renderpeliculas(array $peliculas): void
{
    ?>
    <link rel="stylesheet" href="./styles/adminpeliculas.css" />
<div class="peliculas-container">
<?php foreach($peliculas as $pelicula): 
    // Los nombres de las variables se ajustan a las alias de la consulta SQL
    $id = htmlspecialchars($pelicula['id_pelicula']); 
    $nombre = htmlspecialchars($pelicula['titulo']);
    $director = htmlspecialchars($pelicula['director_nombre']);
    $anio = htmlspecialchars($pelicula['anio']);
    $generos = htmlspecialchars($pelicula['generos']); // Ahora es 'generos'
    $img = htmlspecialchars($pelicula['poster_path']); // Ahora es 'poster_path'
    $calificacion = htmlspecialchars($pelicula['calificacion']); // Ahora es 'calificacion'
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
                <div class="button2" data-id="<?php echo $id; ?>">
                    <img src="./imgs/icons/icono-10-(12).svg" class="icon5" alt="">
                    <div class="editar">Editar</div>
                </div>
                <img src="./imgs/icons/icono-10-(8).svg" class="button-icon3" alt="">
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php
}
?>