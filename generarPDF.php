<?php

require_once './conexion.php';
require_once './fpdf184/fpdf.php';

class GenerarPDF
{
    private $conexion;

    public function __construct()
    {
        $obj = new Conexion();
        $this->conexion = $obj->Conectar();
    }

    // =====================================================================
    // ============= NUEVO MÉTODO PARA GENERAR REPORTES ====================
    // =====================================================================
    public function generarReportePeliculas($tipo, $ini = null, $fin = null)
{
    // Obtener los datos según el tipo
    switch ($tipo) {

        case "top10":
            $sql = "SELECT titulo, calificacion, anio
                    FROM pelicula
                    ORDER BY CAST(calificacion AS DECIMAL(4,2)) DESC
                    LIMIT 10";
            $stmt = $this->conexion->query($sql);
            break;

        case "anio":
            // Si vienen ini y fin, aplicamos filtro BETWEEN (orden asc para rango).
            if (!empty($ini) && !empty($fin)) {
                $sql = "SELECT titulo, anio, calificacion
                        FROM pelicula
                        WHERE anio BETWEEN ? AND ?
                        ORDER BY anio ASC";
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute([$ini, $fin]);
            } else {
                // Si no hay rango, listamos todas las películas por año (desc)
                $sql = "SELECT titulo, anio, calificacion
                        FROM pelicula
                        ORDER BY anio DESC";
                $stmt = $this->conexion->query($sql);
            }
            break;

        case "director":
            // Consulta que ya tienes funcionando (usa join con columna director_id_director)
            $sql = "SELECT 
                        p.titulo,
                        d.nombre AS director,
                        p.anio,
                        p.calificacion
                    FROM pelicula p
                    INNER JOIN director d 
                        ON p.director_id_director = d.id_director
                    ORDER BY d.nombre ASC";
            $stmt = $this->conexion->query($sql);
            break;

        case "rango":
            // Si el usuario usó explícitamente la opción 'rango', requerimos ini/fin
            if (empty($ini) || empty($fin)) {
                die("Debe ingresar los años inicial y final para el rango.");
            }
            $sql = "SELECT titulo, anio, calificacion
                    FROM pelicula
                    WHERE anio BETWEEN ? AND ?
                    ORDER BY anio ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$ini, $fin]);
            break;

        default:
            die("Tipo de reporte inválido.");
    }

    // Obtener resultados
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$peliculas || count($peliculas) === 0) {
        die("No hay datos para generar el reporte.");
    }

    // Generar PDF (salida igual a la que usas)
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);

    // Título del reporte según tipo
    $titulos = [
        "top10" => "Calificacion",
        "anio" => !empty($ini) && !empty($fin) ? "PELICULAS ENTRE $ini Y $fin" : "PELÍCULAS POR ANIO (DESC)",
        "director" => "PELICULAS POR DIRECTOR (A-Z)",
        "rango" => "PELICULAS ENTRE $ini Y $fin"
    ];

    $titulo = $titulos[$tipo] ?? "REPORTE DE PELICULAS";
    $pdf->Cell(0,10, utf8_decode($titulo), 0, 1, 'C');
    $pdf->Ln(8);

    // Encabezados simples
    if($tipo=="top10"){
    $pdf->Cell(100,10,'TItulo',1,0,'C');
    $pdf->Cell(20,10,'Anio',1,0,'C');
    $pdf->Cell(50,10,'Calificacion',1,1,'C');
    }elseif($tipo=="anio"){
    $pdf->Cell(100,10,'TItulo',1,0,'C');
    $pdf->Cell(20,10,'Anio',1,0,'C');
    $pdf->Cell(50,10,'Calificacion',1,1,'C');
    }elseif($tipo=="director"){
    $pdf->Cell(100,10,'TItulo',1,0,'C');
    $pdf->Cell(30,10,'Director',1,0,'C');
    $pdf->Cell(20,10,'Anio',1,0,'C');
    $pdf->Cell(30,10,'Calificacion',1,1,'C');
    }elseif($tipo=="rango"){
    $pdf->Cell(100,10,'TItulo',1,0,'C');
    $pdf->Cell(20,10,'Anio',1,0,'C');
    $pdf->Cell(50,10,'Calificacion',1,1,'C');
    }
    // Contenido
    $pdf->SetFont('Arial','',11);
    foreach ($peliculas as $p) {
        $pdf->Cell(100,8, utf8_decode($p['titulo']),1);
        if ($tipo=="director"){
        $pdf->Cell(30,8, utf8_decode($p['director']),1);
        $pdf->Cell(20,8, $p['anio'],1);
        $pdf->Cell(30,8, $p['calificacion'],1);
        }else{
        $pdf->Cell(20,8, $p['anio'],1);
        $pdf->Cell(50,8, $p['calificacion'],1);
        }
        $pdf->Ln();
    }

    $pdf->Output();
    exit;
}


    // =====================================================================
    //  MÉTODOS ORIGINALES 
    // =====================================================================

    public function generarFacturaPDF($factura, $usuario, $pelicula)
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);

        $pdf->Cell(0,10,'REPORTE DE FACTURA',0,1,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial','',12);

        $pdf->Cell(0,10,'Cliente: ' . $usuario['nombre'],0,1);
        $pdf->Cell(0,10,'Pelicula: ' . $pelicula['titulo'],0,1);
        $pdf->Cell(0,10,'Precio: $' . $factura['precio_alquiler'],0,1);
        $pdf->Cell(0,10,'Fecha de factura: ' . $factura['fecha_factura'],0,1);

        $pdf->Ln(5);
        $pdf->Cell(0,10,'Generado automaticamente.',0,1);

        return $pdf->Output('S');
    }

    public function guardarPDF($idFactura, $pdfContent)
    {
        $sql = "INSERT INTO reportes (id_factura, nombre_reporte, fecha_generacion, archivo)
                VALUES (?, ?, NOW(), ?)";

        $query = $this->conexion->prepare($sql);

        $nombre = "Reporte Factura " . $idFactura;

        $query->bindParam(1, $idFactura);
        $query->bindParam(2, $nombre);
        $query->bindParam(3, $pdfContent, PDO::PARAM_LOB);

        return $query->execute();
    }

    public function generarYGuardarReporte($factura, $usuario, $pelicula)
    {
        $pdf = $this->generarFacturaPDF($factura, $usuario, $pelicula);
        return $this->guardarPDF($factura['id_factura'], $pdf);
    }
}
