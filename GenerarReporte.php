<?php
// GenerarReporte.php (vista simple)
session_start();
// Opcional: proteger para administradores
// if (!isset($_SESSION['rol_id_rol']) || $_SESSION['rol_id_rol'] != 1) { header("Location: peliculasMenu.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Generar Reporte PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
<div class="container mt-5">
    <h2 class="mb-4" style="color:#FFFFFF">Generar Reporte PDF</h2>

<form action="ReportePDF.php" method="GET" class="card p-4 shadow bg-dark border border-white border-1">

        <div class="mb-3">
            <label class="form-label" style="color:#FFFFFF">Tipo de Reporte</label>
            <select name="tipo" class="form-select bg-dark" style="color:#FFFFFF" required >
                <option value="" disabled selected>Seleccione el tipo...</option>
                <option value="top10">Tipo de calificacion</option>
                <option value="anio">Películas ordenadas por año (desc)</option>
                <option value="director">Películas por director (alfabético)</option>
                <option value="rango">Películas por rango de años</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" style="color:#FFFFFF">Año Inicial (solo para rango)</label>
            <input type="number" name="ini" style="color:#FFFFFF" class="form-control bg-dark" min="1900" max="<?php echo date('Y'); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label" style="color:#FFFFFF">Año Final (solo para rango)</label>
            <input type="number" name="fin" style="color:#FFFFFF" class="form-control bg-dark" min="1900" max="<?php echo date('Y'); ?>">
        </div>

        <button class="btn btn-danger" type="submit">Generar PDF</button>
        <a href="paneladmin.php" class="btn btn-secondary">Volver</a>

    </form>
</div>
</body>
</html>
