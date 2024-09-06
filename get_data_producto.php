<?php
include("conexion.php");

$sql = "SELECT categorias, COUNT(*) AS cantidad FROM producto GROUP BY categorias";
$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
