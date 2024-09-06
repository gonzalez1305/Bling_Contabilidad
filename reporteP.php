<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "blingC");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


// Paso 3: Consulta a la base de datos
$query = "SELECT id_producto, talla, color, cantidad, nombre, estado, categorias, precio_unitario  FROM producto";
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
$pdf->Cell(40, 10, 'Reporte de Productos', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($productos as $producto) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Producto: ' . $producto ['id_producto'], 0, 1);
    $pdf->Cell(40, 10, 'Talla: ' . $producto['talla'], 0, 1);
    $pdf->Cell(40, 10, 'Color: ' . $producto['color'], 0, 1);
    $pdf->Cell(40, 10, 'Cantidad: ' . $producto['cantidad'], 0, 1);
    $pdf->Cell(40, 10, 'Nombre: ' . $producto['nombre'], 0, 1);
    $pdf->Cell(40, 10, 'Estado: ' . $producto['estado'], 0, 1);
    $pdf->Cell(40, 10, 'Categoria: ' . $producto['categorias'], 0, 1);
    $pdf->Cell(40, 10, 'Precio unitario: ' . $producto['precio_unitario'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_producto.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>