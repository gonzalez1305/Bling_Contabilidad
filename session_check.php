<?php
session_start();

// Deshabilitar la caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verifica si la sesión está iniciada
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay una sesión iniciada, redirige al usuario a la página de inicio de sesión
    header("Location: index.php");
    exit();
}
?>