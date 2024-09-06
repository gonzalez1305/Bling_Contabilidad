<?php
include("conexion.php");

$sql = "SELECT id_detalles_pedido, fecha_venta FROM gestion_ventas";
$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
