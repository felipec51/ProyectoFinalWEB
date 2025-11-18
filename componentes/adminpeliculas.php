<?php
function renderpeliculas(array $peliculas): void
{
    ?>
    <link rel="stylesheet" href="./styles/adminpeliculas.css" />
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

            <!-- BOTONES CRUD -->
            <div class="container18 flex-row-center">

                <!-- EDITAR -->
                <div class="button2 btnEditar" data-id="<?php echo $id; ?>">
                    <img src="./imgs/icons/icono-10-(12).svg" class="icon5" alt="">
                    <div class="editar">Editar</div>
                </div>

                <!-- ELIMINAR -->
                <div class="button-icon3 btnEliminar" data-id="<?php echo $id; ?>">
                    <img src="./imgs/icons/icono-10-(8).svg" alt="">
                </div>

            </div>

        </div>
    </div>

<?php endforeach; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // BOTÓN EDITAR
    document.querySelectorAll(".btnEditar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            console.log("Editar película ID:", id);

            // Más adelante aquí abrimos modal o formulario
            alert("Editar película ID: " + id);
        });
    });

    document.querySelectorAll(".btnEliminar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            console.log("Eliminar película ID:", id);

            // Más adelante aquí conectamos al CRUD real
            alert("Eliminar película ID: " + id);
        });
    });

});
</script>

<?php
}
?>
