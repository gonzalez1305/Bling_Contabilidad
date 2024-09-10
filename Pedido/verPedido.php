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
                $consultaPrecio = "SELECT precio_unitario FROM producto WHERE id_producto = ?";
                $stmtPrecio = $conectar->prepare($consultaPrecio);
                $stmtPrecio->bind_param('i', $idProducto);
                $stmtPrecio->execute();
                $resultadoPrecio = $stmtPrecio->get_result();
                $precioProducto = $resultadoPrecio->fetch_assoc()['precio_unitario'];

                $precioTotal = $cantidad * $precioProducto; // Calcular el precio total

                // Insertar cada producto del carrito en la tabla de detalles de pedido
                $stmtDetalle->bind_param('iiid', $idPedido, $idProducto, $cantidad, $precioTotal);
                $stmtDetalle->execute();
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4 text-center">Pedidos Confirmados</h2>

        <!-- Mostrar mensajes de respuesta -->
        <?php if (isset($response['status']) && $response['status'] === 'success'): ?>
            <div class="alert alert-success">
                <?php echo $response['message']; ?>
            </div>
        <?php elseif (isset($response['status']) && $response['status'] === 'error'): ?>
            <div class="alert alert-danger">
                <?php echo $response['message']; ?>
            </div>
        <?php endif; ?>

        <!-- Botón para volver al menú -->
        <div class="text-center mt-4">
            <a href="../menuC.html" class="btn btn-secondary">Volver al Menú</a>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
