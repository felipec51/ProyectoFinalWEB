<?php
// ReportePDF.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $base = __DIR__;
    $fpdfPath = $base . '/fpdf.php';
    $conexionPath = $base . '/conexion.php';

    if (!file_exists($fpdfPath)) {
        throw new Exception("No se encontró fpdf.php en: $fpdfPath");
    }
    if (!file_exists($conexionPath)) {
        throw new Exception("No se encontró conexion.php en: $conexionPath");
    }

    require_once $conexionPath;
    require_once $fpdfPath;

    $tipo = $_GET['tipo'] ?? '';
    $ini  = isset($_GET['ini']) && $_GET['ini'] !== '' ? intval($_GET['ini']) : null;
    $fin  = isset($_GET['fin']) && $_GET['fin'] !== '' ? intval($_GET['fin']) : null;

    $db = Conexion::Conectar();

    // Construir consulta según tipo
    switch ($tipo) {
        case 'top10':
            // Intentamos ordenar por calificación; si es textual, fallback por año.
            $sql = "SELECT p.titulo, p.anio, p.calificacion, COALESCE(d.nombre,'-') AS director
                    FROM pelicula p
                    LEFT JOIN director d ON p.director_id_director = d.id_director
                    ORDER BY CAST(REPLACE(p.calificacion, '+', '') AS SIGNED) DESC, p.anio DESC
                    LIMIT 10";
            $stmt = $db->prepare($sql);
            break;

        case 'anio':
            $sql = "SELECT p.titulo, p.anio, p.calificacion, COALESCE(d.nombre,'-') AS director
                    FROM pelicula p
                    LEFT JOIN director d ON p.director_id_director = d.id_director
                    ORDER BY p.anio DESC, p.titulo ASC";
            $stmt = $db->prepare($sql);
            break;

        case 'director':
            $sql = "SELECT p.titulo, p.anio, p.calificacion, COALESCE(d.nombre,'-') AS director
                    FROM pelicula p
                    LEFT JOIN director d ON p.director_id_director = d.id_director
                    ORDER BY d.nombre ASC, p.titulo ASC";
            $stmt = $db->prepare($sql);
            break;

        case 'rango':
            if ($ini === null || $fin === null) {
                throw new Exception("Para el reporte por rango es necesario indicar ini y fin.");
            }
            if ($ini > $fin) {
                throw new Exception("Año inicial no puede ser mayor que año final.");
            }
            $sql = "SELECT p.titulo, p.anio, p.calificacion, COALESCE(d.nombre,'-') AS director
                    FROM pelicula p
                    LEFT JOIN director d ON p.director_id_director = d.id_director
                    WHERE p.anio BETWEEN :ini AND :fin
                    ORDER BY p.anio ASC, p.titulo ASC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':ini', $ini, PDO::PARAM_INT);
            $stmt->bindParam(':fin', $fin, PDO::PARAM_INT);
            break;

        default:
            throw new Exception("Tipo de reporte no válido.");
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generar PDF
    $pdf = new FPDF('L','mm','A4'); // horizontal para más columnas
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,8,utf8_decode('Reporte de Películas - RewindCodeFilm'),0,1,'C');
    $pdf->SetFont('Arial','',10);
    $fechaGen = date('d/m/Y H:i:s');
    $pdf->Cell(0,6,"Generado: $fechaGen",0,1,'R');
    $pdf->Ln(4);

    // Cabecera de tabla
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(230,230,230);
    $pdf->Cell(100,8,utf8_decode('Título'),1,0,'L',true);
    $pdf->Cell(25,8,'Año',1,0,'C',true);
    $pdf->Cell(35,8,utf8_decode('Calificación'),1,0,'C',true);
    $pdf->Cell(95,8,utf8_decode('Director'),1,0,'L',true);
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);

    if (count($rows) === 0) {
        $pdf->Cell(255,8,utf8_decode('No se encontraron registros para los criterios seleccionados.'),1,1,'C');
    } else {
        foreach ($rows as $r) {
            $titulo = utf8_decode($r['titulo'] ?? '-');
            $anio = $r['anio'] ?? '-';
            $cal = utf8_decode($r['calificacion'] ?? '-');
            $dir = utf8_decode($r['director'] ?? '-');

            // Ajustar celda de título si es muy larga: multi-cell manual
            $pdf->Cell(100,8, $pdf->GetStringWidth($titulo) > 96 ? substr($titulo,0,55)."..." : $titulo,1,0,'L');
            $pdf->Cell(25,8,$anio,1,0,'C');
            $pdf->Cell(35,8,$cal,1,0,'C');
            $pdf->Cell(95,8, $pdf->GetStringWidth($dir) > 90 ? substr($dir,0,50)."..." : $dir,1,0,'L');
            $pdf->Ln();
        }
    }

    // Enviar output
    $pdf->Output('I', 'reporte_peliculas_' . date('Ymd_His') . '.pdf');
    exit;

} catch (Exception $e) {
    // Mostrar error claro en pantalla (útil para depuración). 
    // En producción puedes loguearlo en un archivo en lugar de mostrarlo.
    echo "<h3>Error al generar el reporte</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    // Si quieres ver trace:
    // echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}
