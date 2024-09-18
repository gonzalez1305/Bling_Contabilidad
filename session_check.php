<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}


if ($_SESSION['tipo_usuario'] != 2) { 
    header("Location: no_autorizado.php");
    exit();
}
?>