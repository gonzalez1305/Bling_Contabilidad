<?php
// Conectar a la base de datos
include("conexion2.php");

// Consulta a la base de datos
$query = "SELECT fecha, SUM(total_cantidad) as total_cantidad, SUM(total_venta) as total_venta FROM venta GROUP BY fecha";
$result = $conexion->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($data);

// Cerrar la conexión a la base de datos
$conexion->close();
?>
