<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
include("conexion2.php");


// Paso 3: Consulta a la base de datos
$query = "SELECT id_inventario, cantidad, fecha, cantidad_disponible, referencia, id_vendedor FROM inventario";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$inventarios = array();
while ($row = $result->fetch_assoc()) {
    $inventarios[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de Pedidos', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($inventarios as $inventario) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Inventario: ' . $inventario['id_inventario'], 0, 1);
    $pdf->Cell(40, 10, 'cantidad: ' . $inventario['cantidad'], 0, 1);
    $pdf->Cell(40, 10, 'fecha: ' . $inventario['fecha'], 0, 1);
    $pdf->Cell(40, 10, 'cantidad_disponible: ' . $inventario['cantidad_disponible'], 0, 1);
    $pdf->Cell(40, 10, 'referencia: ' . $inventario['referencia'], 0, 1);
    $pdf->Cell(40, 10, 'id_vendedor: ' . $inventario['id_vendedor'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_inventario.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>