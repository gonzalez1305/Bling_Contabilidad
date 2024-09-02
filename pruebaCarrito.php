<?php
session_start();
include 'conexion.php';

if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
} else {
    echo "<script>alert('Por favor, inicie sesión para continuar.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

if (isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];

    // Verificar si ya existe un pedido "en proceso" para el usuario
    $consultaPedido = "SELECT id_pedido FROM Pedido WHERE fk_id_usuario = '$idUsuario' AND situacion = 'en proceso'";
    $resultadoPedido = mysqli_query($conectar, $consultaPedido);

    if (mysqli_num_rows($resultadoPedido) > 0) {
        // Si existe un pedido en proceso, obtener su id
        $pedidoExistente = mysqli_fetch_assoc($resultadoPedido);
        $idPedido = $pedidoExistente['id_pedido'];
    } else {
        // Si no existe, crear un nuevo pedido en proceso
        $insertPedido = "INSERT INTO Pedido (fecha, situacion, fk_id_usuario) VALUES (NOW(), 'en proceso', '$idUsuario')";
        mysqli_query($conectar, $insertPedido);
        $idPedido = mysqli_insert_id($conectar);
    }

    // Insertar el producto en el carrito
    $insertCarrito = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) VALUES ('$idProducto', '$cantidad', '$idUsuario')";
    mysqli_query($conectar, $insertCarrito);

    echo "<script>alert('Producto añadido al pedido en proceso.');</script>";
    echo "<script>window.location.href = 'verPedido.php';</script>";
} else {
    echo "<script>alert('Información incompleta. Por favor, intente nuevamente.');</script>";
    echo "<script>window.history.back();</script>";
}
?>
