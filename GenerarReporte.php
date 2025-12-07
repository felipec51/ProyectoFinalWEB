<?php
include 'check_session.php'; 

if (!isset($_SESSION["rol_id_rol"]) || $_SESSION["rol_id_rol"] != 1) {
    header("Location: login.php"); 
    exit;
}

include 'componentes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title>Generar Reporte</title>
    <link rel="stylesheet" href="styles/config.css" />
    <link rel="stylesheet" href="styles/paneladmin.css" />
    <link rel="stylesheet" href="styles/generar_reporte.css" />
</head>
<body>
    <div class="desktop">
        <div class="admin-movie-panel-activity">
            <?php rendersidebar() ?>
            <div class="app">
                <div class="sidebarinset">
                    <div class="app2">
                       <header class="app3">
                            <div class="container3" >
                                <div class="rewind-code-film text-main-title">Generar Reporte</div>
                            </div>
                        </header>

                        <div class="main-content">
                            <div class="report-container">
                                <div class="form-column">
                                    <form action="ReportePDF.php" method="GET" class="report-form">
                                        <h2>Generar Reporte PDF</h2>

                                        <div class="form-group">
                                            <label for="tipo_reporte">Tipo de Reporte</label>
                                            <select name="tipo" id="tipo_reporte" class="form-control" required>
                                                <option value="" disabled selected>Seleccione el tipo...</option>
                                                <option value="top10">Tipo de calificacion</option>
                                                <option value="anio">Películas ordenadas por año (desc)</option>
                                                <option value="director">Películas por director (alfabético)</option>
                                                <option value="rango">Películas por rango de años</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="anio_inicial">Año Inicial (solo para rango)</label>
                                            <input type="number" name="ini" id="anio_inicial" class="form-control" min="1900" max="<?php echo date('Y'); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="anio_final">Año Final (solo para rango)</label>
                                            <input type="number" name="fin" id="anio_final" class="form-control" min="1900" max="<?php echo date('Y'); ?>">
                                        </div>

                                        <div class="form-buttons">
                                            <button class="btn btn-danger" type="submit">Generar PDF</button>
                                            <a href="paneladmin.php" class="btn btn-secondary">Volver</a>
                                        </div>
                                    </form>
                                </div>
                                <div class="image-column">
                                    <img src="imgs/fondoini.jpg" alt="Decorative image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>