<?php
include("conexion.php");

$sql = "SELECT fecha_pago, SUM(monto) AS total_monto 
        FROM pagos 
        GROUP BY fecha_pago 
       ";

$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
