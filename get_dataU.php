<?php
include("conexion.php");

$sql = "SELECT tipo_usuario, COUNT(*) AS cantidad FROM usuario GROUP BY tipo_usuario";
$resultado = mysqli_query($conectar, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

echo json_encode($data);
?>
