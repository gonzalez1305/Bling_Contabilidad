<?php
// Paso 1: Incluir la biblioteca FPDF
require('pdf/fpdf.php');

// Paso 2: Conectar a la base de datos MySQL
$conexion = new mysqli("localhost", "root", "", "blingC");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


// Paso 3: Consulta a la base de datos
$query = "SELECT nombre, apellido, telefono, direccion, fecha_de_nacimiento,  
correo, estado 
FROM usuario";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}

$usuarios = array();
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

// Paso 4: Generación del PDF con FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de Usuarios', 0, 1);

// Iterar sobre los resultados y añadirlos al PDF
foreach ($usuarios as $usuario) {
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(40, 10, 'nombre: ' . $usuario['nombre'], 0, 1);
    $pdf->Cell(40, 10, 'apellido: ' . $usuario['apellido'], 0, 1);
    $pdf->Cell(40, 10, 'telefono: ' . $usuario['telefono'], 0, 1);
    $pdf->Cell(40, 10, 'direccion: ' . $usuario['direccion'], 0, 1);
    $pdf->Cell(40, 10, 'fecha_de_nacimiento: ' . $usuario['fecha_de_nacimiento'], 0, 1);
    $pdf->Cell(40, 10, 'correo: ' . $usuario['correo'], 0, 1);
    $pdf->Cell(40, 10, 'estado: ' . $usuario['estado'], 0, 1);
  
    $pdf->Ln(); // Salto de línea
}

// Salida del PDF
$pdf->Output('D', 'reporte_usuarios.pdf'); // Descargar directamente el PDF

// Paso 5: Cerrar la conexión a la base de datos
$conexion->close();
?>