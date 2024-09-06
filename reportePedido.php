<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "blingC");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


// Paso 3: Consulta a la base de datos
$query = "SELECT * FROM pedido
                                    INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
                                    INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
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
    $pdf->Cell(40, 10, 'ID Pedido: ' . $pedido ['id_pedido'], 0, 1);
    $pdf->Cell(40, 10, 'ID Usuario: ' . $pedido['fk_id_usuario'], 0, 1);
    $pdf->Cell(40, 10, 'Fecha: ' . $pedido['fecha'], 0, 1);
    $pdf->Cell(40, 10, 'Situacion: ' . $pedido['situacion'], 0, 1);
    $pdf->Cell(40, 10, 'Nombre Producto: ' . $pedido['nombre'], 0, 1);
    $pdf->Cell(40, 10, 'Unidades: ' . $pedido['unidades'], 0, 1);
    $pdf->Cell(40, 10, 'Precio Total: ' . $pedido['precio_total'], 0, 1);
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_pedidos.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>