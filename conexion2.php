<?php
$conexion = mysqli_connect('localhost:3306', 'root', '', 'bling');

if (!$conexion) {
     echo "Error: No se pudo conectar a la base de datos.";
     exit;
}
?>