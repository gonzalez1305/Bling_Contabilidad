<?php
include("conexion.php");

$sql = "SELECT fecha_venta, COUNT(*) AS cantidad FROM gestion_ventas GROUP BY fecha_venta";
$resultado = mysqli_query($conectar, $sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conectar));
}

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
