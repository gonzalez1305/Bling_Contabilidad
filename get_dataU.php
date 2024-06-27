<?php
// Conectar a la base de datos
include("conexion2.php");

// Consulta a la base de datos para obtener la cantidad de usuarios por tipo
$query = "SELECT tipo_usuario, COUNT(*) as cantidad FROM usuario GROUP BY tipo_usuario";
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
