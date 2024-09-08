<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "blingC");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


// Paso 3: Consulta a la base de datos
$query = "SELECT id_pago, id_gestion_venta, monto, metodo_pago, fecha_pago FROM pagos";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$pagos = array();
while ($row = $result->fetch_assoc()) {
    $pagos[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de Productos', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($pagos as $pago) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Pago: ' . $pago ['id_pago'], 0, 1);
    $pdf->Cell(40, 10, 'ID Venta: ' . $pago['id_gestion_venta'], 0, 1);
    $pdf->Cell(40, 10, 'Monto: ' . $pago['monto'], 0, 1);
    $pdf->Cell(40, 10, 'Metodo Pago: ' . $pago['metodo_pago'], 0, 1);
    $pdf->Cell(40, 10, 'Fecha Pago: ' . $pago['fecha_pago'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_pago.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>