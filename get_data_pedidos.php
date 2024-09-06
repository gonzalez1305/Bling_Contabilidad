<?php
include("conexion.php");

$sql = "SELECT fecha, COUNT(*) AS situacion  FROM pedido 
        GROUP BY fecha";
       
$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
