<?php
session_start();
include '../conexion.php';

// Inicialización de la respuesta en caso de error
$response = ['status' => 'error', 'message' => 'Error desconocido'];

// Verificar si el formulario fue enviado por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['id_usuario'])) {
        $response['message'] = 'No estás logueado.';
        echo json_encode($response);
        exit();
    }

    $idUsuario = $_SESSION['id_usuario'];

    // Crear un nuevo pedido
    $crearPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES (NOW(), 'en proceso', ?)";
    $stmtPedido = $conectar->prepare($crearPedido);
    $stmtPedido->bind_param('i', $idUsuario);

    if ($stmtPedido->execute()) {
        $idPedido = $stmtPedido->insert_id;  // Obtener el ID del pedido creado

        // Consultar los productos del carrito
        $consultaCarrito = "SELECT fk_id_producto, cantidad FROM carrito WHERE fk_id_usuario = ?";
        $stmtCarrito = $conectar->prepare($consultaCarrito);
        $stmtCarrito->bind_param('i', $idUsuario);
        $stmtCarrito->execute();
        $resultadoCarrito = $stmtCarrito->get_result();

        if ($resultadoCarrito->num_rows > 0) {
            // Preparar la inserción de los detalles del pedido
            $insertarDetalle = "INSERT INTO detalles_pedido (fk_id_pedido, fk_id_producto, unidades, precio_total) VALUES (?, ?, ?, ?)";
            $stmtDetalle = $conectar->prepare($insertarDetalle);

            while ($producto = $resultadoCarrito->fetch_assoc()) {
                $idProducto = $producto['fk_id_producto'];
                $cantidad = $producto['cantidad'];

                // Obtener el precio del producto
                $consultaPrecio = "SELECT precio_unitario, cantidad FROM producto WHERE id_producto = ?";
                $stmtPrecio = $conectar->prepare($consultaPrecio);
                $stmtPrecio->bind_param('i', $idProducto);
                $stmtPrecio->execute();
                $resultadoPrecio = $stmtPrecio->get_result();
                $productoDetalles = $resultadoPrecio->fetch_assoc();

                $precioProducto = $productoDetalles['precio_unitario'];
                $cantidadDisponible = $productoDetalles['cantidad'];

                $precioTotal = $cantidad * $precioProducto; // Calcular el precio total

                // Insertar cada producto del carrito en la tabla de detalles de pedido
                $stmtDetalle->bind_param('iiid', $idPedido, $idProducto, $cantidad, $precioTotal);
                $stmtDetalle->execute();

                // Actualizar la cantidad del producto en el inventario
                $updateProductQuery = "UPDATE producto SET cantidad = cantidad - ? WHERE id_producto = ?";
                $stmtUpdateProduct = $conectar->prepare($updateProductQuery);
                $stmtUpdateProduct->bind_param('ii', $cantidad, $idProducto);
                $stmtUpdateProduct->execute();
            }

            // Eliminar los productos del carrito después de confirmar el pedido
            $borrarCarrito = "DELETE FROM carrito WHERE fk_id_usuario = ?";
            $stmtBorrarCarrito = $conectar->prepare($borrarCarrito);
            $stmtBorrarCarrito->bind_param('i', $idUsuario);
            $stmtBorrarCarrito->execute();

            $response['status'] = 'success';
            $response['message'] = 'Pedido confirmado exitosamente.';
        } else {
            $response['message'] = 'El carrito está vacío.';
        }
    } else {
        $response['message'] = 'Error al crear el pedido.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Confirmados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Pedido Confirmado</h1>
        <p><?php echo $response['message']; ?></p>
        <a href="../menuC.html" class="btn btn-custom">Volver al Menú</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
