<?php
session_start();
include("../conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Consulta para eliminar al usuario
$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conectar->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    // Cerrar sesión y redirigir al menú
    session_destroy();
    header("Location: ../Menu.html");
    exit();
} else {
    echo "Error al eliminar la cuenta. Inténtalo de nuevo.";
}
?>
