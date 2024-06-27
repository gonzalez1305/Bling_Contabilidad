<?php
require('fpdf/fpdf.php'); // Incluir la biblioteca FPDF

// Obtener los datos de la imagen de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$imageData = $data['image'];

// Crear un nuevo objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado del PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte Estadístico con Gráfica', 0, 1);

// Insertar la imagen de la gráfica
$pdf->Image($imageData, 10, 30, 180, 100);

// Pie de página
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetY(-15);
$pdf->Cell(0, 10, 'Página ' . $pdf->PageNo(), 0, 0, 'C');

// Salida del PDF (descarga o visualización)
$pdf->Output('D', 'reporte_estadistico.pdf'); // Descargar directamente el PDF
?>
