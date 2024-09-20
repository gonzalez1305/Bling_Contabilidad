<?php
session_start();
include '../conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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
        $consultaCarrito = "SELECT fk_id_producto, cantidad, talla FROM carrito WHERE fk_id_usuario = ?";
        $stmtCarrito = $conectar->prepare($consultaCarrito);
        $stmtCarrito->bind_param('i', $idUsuario);
        $stmtCarrito->execute();
        $resultadoCarrito = $stmtCarrito->get_result();

        if ($resultadoCarrito->num_rows > 0) {
            // Preparar la inserción de los detalles del pedido
            $insertarDetalle = "INSERT INTO detalles_pedido (fk_id_pedido, fk_id_producto, unidades, precio_total, talla) VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $conectar->prepare($insertarDetalle);

            while ($producto = $resultadoCarrito->fetch_assoc()) {
                $idProducto = $producto['fk_id_producto'];
                $cantidad = $producto['cantidad'];
                $tallaSeleccionada = $producto['talla']; // Obtener la talla del carrito

                // Obtener el precio del producto
                $consultaPrecio = "SELECT precio_unitario FROM producto WHERE id_producto = ?";
                $stmtPrecio = $conectar->prepare($consultaPrecio);
                $stmtPrecio->bind_param('i', $idProducto);
                $stmtPrecio->execute();
                $resultadoPrecio = $stmtPrecio->get_result();
                $precioProducto = $resultadoPrecio->fetch_assoc()['precio_unitario'];

                $precioTotal = $cantidad * $precioProducto; // Calcular el precio total

                // Insertar cada producto del carrito en la tabla de detalles de pedido
                $stmtDetalle->bind_param('iiids', $idPedido, $idProducto, $cantidad, $precioTotal, $tallaSeleccionada);
                $stmtDetalle->execute();
            }

            // Eliminar los productos del carrito después de confirmar el pedido
            $borrarCarrito = "DELETE FROM carrito WHERE fk_id_usuario = ?";
            $stmtBorrarCarrito = $conectar->prepare($borrarCarrito);
            $stmtBorrarCarrito->bind_param('i', $idUsuario);
            $stmtBorrarCarrito->execute();

            // Enviar correo de confirmación de pedido
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'blingcontabilidadgaes@gmail.com';
                $mail->Password = 'mgzhlqxhogvdnlnm'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Obtener el correo del usuario
                $consultaCorreo = "SELECT correo FROM usuario WHERE id_usuario = ?";
                $stmtCorreo = $conectar->prepare($consultaCorreo);
                $stmtCorreo->bind_param('i', $idUsuario);
                $stmtCorreo->execute();
                $resultadoCorreo = $stmtCorreo->get_result();
                $correoUsuario = $resultadoCorreo->fetch_assoc()['correo'];

                // Destinatario del correo
                $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');
                $mail->addAddress($correoUsuario);

                // Contenido
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Confirmación de Pedido';
                $mail->Body    = 'Tu pedido ha sido confirmado exitosamente. El ID de tu pedido es: ' . $idPedido;

                $mail->send();

                $response['status'] = 'success';
                $response['message'] = 'Pedido confirmado exitosamente. Se ha enviado un correo de confirmación.';
            } catch (Exception $e) {
                $response['message'] = 'Pedido confirmado, pero hubo un error al enviar el correo de confirmación: ' . $mail->ErrorInfo;
            }
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
            transition: background 0.5s, color 0.5s;
        }

        .dark-mode {
            background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
            color: #ffffff;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            transition: background-color 0.5s, color 0.5s;
        }

        .dark-mode .container {
            background-color: rgba(50, 50, 50, 0.9);
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            color: #ffffff;
        }

        .dark-mode .btn-primary {
            background-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .dark-mode .btn-primary:hover {
            background-color: #003d7a;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
            transition: background-color 0.3s;
        }

        .dark-mode .toggle-btn {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .toggle-btn i {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>
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
            <a href="../verDetallesCarrito.php" class="btn btn-secondary">Ver pedidos</a>
        </div>
        <div class="text-center mt-4">
            <a href="../menuC.php" class="btn btn-secondary">Volver al menú</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMode() {
            document.body.classList.toggle('dark-mode');
            const btn = document.querySelector('.toggle-btn i');
            btn.classList.toggle('fa-sun');
            btn.classList.toggle('fa-moon');
        }
    </script>
</body>
</html>
