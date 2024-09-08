<?php
include("conexion.php");

$sql = "SELECT fecha_pago, COUNT(*) AS cantidad FROM pagos GROUP BY fecha_pago";

$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
