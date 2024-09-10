<?php

//conexion al servidor
$host = "localhost";
$user = "root";
$clave = "";
$bd = "blingC";

$conectar = mysqli_connect($host, $user, $clave, $bd);
if (!$conectar){
    echo 'La conexion se ha interrumpido o no se completo';
}

?>