<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
include("conexion2.php");


// Paso 3: Consulta a la base de datos
$query = "SELECT id_venta, fecha, total_cantidad, total_venta FROM venta";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$ventas = array();
while ($row = $result->fetch_assoc()) {
    $ventas[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de Ventas', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($ventas as $venta) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Venta: ' . $venta['id_venta'], 0, 1);
    $pdf->Cell(40, 10, 'Fecha: ' . $venta['fecha'], 0, 1);
    $pdf->Cell(40, 10, 'total_cantidad: ' . $venta['total_cantidad'], 0, 1);
    $pdf->Cell(40, 10, 'total_venta: ' . $venta['total_venta'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_ventas.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>