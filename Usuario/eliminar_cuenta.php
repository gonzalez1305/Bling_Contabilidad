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
// Eliminar los registros relacionados en carrito
$sql_delete_carrito = "DELETE FROM carrito WHERE fk_id_usuario = ?";
$stmt_carrito = $conectar->prepare($sql_delete_carrito);
$stmt_carrito->bind_param("i", $id_usuario);
$stmt_carrito->execute();

// Luego, eliminar el usuario
$sql = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt = $conectar->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    session_destroy();
    header("Location: ../Menu.html");
    exit();
} else {
    echo "Error al eliminar la cuenta. Inténtalo de nuevo.";
}
?>