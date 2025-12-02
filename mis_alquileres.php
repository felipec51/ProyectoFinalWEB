<?php
require_once 'conexion.php';
include 'componentes/headermain.php'; // Asegúrate de que este archivo exista

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: iniciarsesion.php");
    exit;
}

$usuario_logueado_id = $_SESSION["id_usuario"];
$rentedMovies = [];

try {
    $conexion = Conexion::Conectar();

    // **** CAMBIO AQUÍ: Añadimos 'AND pr.estado_alquiler = 'en curso'' ****
    $sqlRented = "
        SELECT p.titulo, pr.id_prestamo, pr.fecha_prestamo, pr.fecha_devolucion
        FROM prestamo pr
        JOIN cinta c ON pr.cinta_id_cinta = c.id_cinta
        JOIN pelicula p ON c.pelicula_id_pelicula = p.id_pelicula
        WHERE pr.Usuario_id_usuario = :id 
          AND pr.estado_alquiler = 'en curso'
        ORDER BY pr.fecha_devolucion ASC
    ";
    $stmtRented = $conexion->prepare($sqlRented);
    $stmtRented->bindParam(':id', $usuario_logueado_id, PDO::PARAM_INT);
    $stmtRented->execute();
    $rentedMovies = $stmtRented->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log("Error fetching rented movies: " . $e->getMessage());
    // Opcional: mostrar un mensaje de error al usuario
    $_SESSION['mensaje_alquiler_error'] = "Error al cargar tus alquileres.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Alquileres - RewindCodeFilm</title>
    <link rel="stylesheet" href="./styles/perfil.css" />
    <link rel="stylesheet" href="./styles/mis_alquileres.css" />
    <link rel="stylesheet" href="styles/config.css" />
</head>
<body class="page-layout-simple">
    <?php navheader("Mis Alquileres", $usuario_logueado_id); ?>

    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container breadcrumb-content">
            <a href="peliculasMenu.php" class="crumb-link">
                Página Principal
            </a>
            <span class="crumb-current">> Mis Alquileres</span>
        </div>
    </nav>

    <main class="main-content container">
        <h1>Mis Alquileres</h1>
        <section class="rentals-section">
            <?php if (isset($_SESSION['mensaje_alquiler'])): ?>
                <div class="alert-message success">
                    <?php echo $_SESSION['mensaje_alquiler']; unset($_SESSION['mensaje_alquiler']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensaje_alquiler_error'])): ?>
                <div class="alert-message error">
                    <?php echo $_SESSION['mensaje_alquiler_error']; unset($_SESSION['mensaje_alquiler_error']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($rentedMovies)): ?>
                <div class="rentals-list">
                    <?php foreach ($rentedMovies as $rental): ?>
                        <div class="rental-item card">
                            <div class="rental-details">
                                <h3><?php echo htmlspecialchars($rental['titulo']); ?></h3>
                                <p>Alquilada el: <?php echo date('d/m/Y', strtotime($rental['fecha_prestamo'])); ?></p>
                                <p>Vence el: <?php echo date('d/m/Y', strtotime($rental['fecha_devolucion'])); ?></p>
                            </div>
                            <!-- La acción del formulario es correcta, apunta a devolver_pelicula.php -->
                            <form action="devolver_pelicula.php" method="POST" class="rental-action">
                                <input type="hidden" name="prestamo_id" value="<?php echo $rental['id_prestamo']; ?>">
                                <button type="submit" class="btn btn-danger">Devolver</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tienes ninguna película alquilada en este momento.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>