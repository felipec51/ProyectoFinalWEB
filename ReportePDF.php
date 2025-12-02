<?php
require_once './generarPDF.php';

// Validar que se envió el tipo
if (!isset($_GET['tipo']) || empty($_GET['tipo'])) {
    die("Error: Debe seleccionar un tipo de reporte.");
}

$tipo = $_GET['tipo'];
$ini  = $_GET['ini'] ?? null;
$fin  = $_GET['fin'] ?? null;

$pdfGen = new GenerarPDF();

// Llamamos al método para generar el reporte
$pdfGen->generarReportePeliculas($tipo, $ini, $fin);
