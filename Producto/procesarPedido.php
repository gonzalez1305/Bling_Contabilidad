<?php
session_start();
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idProducto'], $_POST['cantidad'], $_POST['idUsuario'])) {
        $idProducto = intval($_POST['idProducto']);
        $cantidad = intval($_POST['cantidad']);
        $idUsuario = intval($_POST['idUsuario']);
        $fecha = date('Y-m-d H:i:s'); // Fecha actual

        // Consulta para obtener el precio del producto
        $query = "SELECT precio_unitario FROM producto WHERE id_producto = $idProducto";
        $result = mysqli_query($conectar, $query);
        $producto = mysqli_fetch_assoc($result);
        $precioUnitario = $producto['precio_unitario'];

        $precioTotal = $precioUnitario * $cantidad;

        // Crear un nuevo pedido
        $queryPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES ('$fecha', 'en proceso', $idUsuario)";
        mysqli_query($conectar, $queryPedido);

        // Obtener el ID del nuevo pedido
        $idPedido = mysqli_insert_id($conectar);

        // Insertar en detalles_pedido
        $queryDetallesPedido = "INSERT INTO detalles_pedido (fk_id_producto, fk_id_pedido, unidades, precio_total) VALUES ($idProducto, $idPedido, $cantidad, $precioTotal)";
        mysqli_query($conectar, $queryDetallesPedido);

        // Redirigir al usuario a una página de confirmación o al carrito
        header("Location: ../carrito.php");
        exit();
    } else {
        // Redirigir si los datos no están disponibles
        header("Location: ../menuC.html");
        exit();
    }
} else {
    // Redirigir si el método de solicitud no es POST
    header("Location: ../menuC.html");
    exit();
}
