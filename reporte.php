<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
include("conexion.php");


// Paso 3: Consulta a la base de datos
$query = "SELECT id_pedido, fecha, situacion, fk_id_usuario FROM pedido";
$result = $conectar->query($query);

if (!$result) {
    die("Error en la consulta: " . $conectar->error);
}

$pedidos = array();
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de Pedidos', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($pedidos as $pedido) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Pedido: ' . $pedido['id_pedido'], 0, 1);
    $pdf->Cell(40, 10, 'Fecha: ' . $pedido['fecha'], 0, 1);
    $pdf->Cell(40, 10, 'Situacion: ' . $pedido['situacion'], 0, 1);
    $pdf->Cell(40, 10, 'ID Usuario: ' . $pedido['fk_id_usuario'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_pedidos.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conectar->close();
?>