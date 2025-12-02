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
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Generar Reporte PDF</h2>

    <form action="ReportePDF.php" method="GET" class="card p-4 shadow">

        <div class="mb-3">
            <label class="form-label">Tipo de Reporte</label>
            <select name="tipo" class="form-select" required>
                <option value="" disabled selected>Seleccione el tipo...</option>
                <option value="top10">Top 10 por calificación (mejor a peor)</option>
                <option value="anio">Películas ordenadas por año (desc)</option>
                <option value="director">Películas por director (alfabético)</option>
                <option value="rango">Películas por rango de años</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Año Inicial (solo para rango)</label>
            <input type="number" name="ini" class="form-control" min="1900" max="<?php echo date('Y'); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Año Final (solo para rango)</label>
            <input type="number" name="fin" class="form-control" min="1900" max="<?php echo date('Y'); ?>">
        </div>

        <button class="btn btn-primary" type="submit">Generar PDF</button>
        <a href="peliculasMenu.php" class="btn btn-secondary">Volver</a>

    </form>
</div>
</body>
</html>
