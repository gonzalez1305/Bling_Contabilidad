<?php
// Conectar a la base de datos
include("conexion.php");

// Consulta a la base de datos
$query = "SELECT fecha, COUNT(*) as total FROM pedido GROUP BY fecha";
$result = $conectar->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($data);

// Cerrar la conexión a la base de datos
$conectar->close();
?>
