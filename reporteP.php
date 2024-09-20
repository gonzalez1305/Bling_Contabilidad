<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "blingC");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Paso 3: Consulta a la base de datos
$query = "SELECT p.nombre, p.estado, p.precio_unitario, m.nombre_marca, t.talla, t.cantidad 
          FROM producto p 
          JOIN marca m ON p.fk_id_marca = m.id_marca 
          JOIN tallas t ON p.id_producto = t.fk_id_producto";  // Asegúrate de que fk_id_producto sea la clave foránea

$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$productos = array();
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Productos', 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de las columnas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(30, 10, 'Estado', 1);
$pdf->Cell(30, 10, 'Precio', 1);
$pdf->Cell(40, 10, 'Marca', 1);
$pdf->Cell(40, 10, 'Talla', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Ln();

// Iterar sobre los resultados y añadirlos al PDF
$pdf->SetFont('Arial', '', 12);
foreach ($productos as $producto) {
    $pdf->Cell(40, 10, $producto['nombre'], 1);
    $pdf->Cell(30, 10, $producto['estado'], 1);
    $pdf->Cell(30, 10, $producto['precio_unitario'], 1);
    $pdf->Cell(40, 10, $producto['nombre_marca'], 1);
    $pdf->Cell(40, 10, $producto['talla'], 1);
    $pdf->Cell(30, 10, $producto['cantidad'], 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_producto.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>
