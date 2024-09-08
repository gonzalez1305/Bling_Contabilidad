<?php
session_start();
include 'conexion.php';

// Verificar si hay una sesión activa
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];

    // Eliminar los productos del carrito asociados al usuario
    $deleteCartQuery = "DELETE FROM carrito WHERE fk_id_usuario = ?";
    $stmt = $conectar->prepare($deleteCartQuery);
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Destruir la sesión
    session_destroy();
}

// Redirigir al usuario a la página de inicio de sesión o a una página de confirmación de cierre de sesión
header("Location: index.php");
exit();
?>
