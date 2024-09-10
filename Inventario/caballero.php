<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
include '../conexion.php';

// Consulta para obtener los productos de la categoría 'caballero' y que estén disponibles
$query = "SELECT * FROM producto WHERE categorias = 'caballero' AND estado = 'disponible'";
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Productos Caballero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        #header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }

        #header img {
            width: 150px;
        }

        #productos {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        .notification {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            display: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-info:hover {
            background-color: #117a8b;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        #cart-sidebar {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div id="header" class="bg-primary text-white text-center p-3 rounded">
            <img src="../imgs/logo.png" alt="logo" id="logo" class="mb-2">
            <h1>Sección de Caballero</h1>
            <a href="../menuC.html" class="btn btn-light">Volver</a>
        </div>

        <div class="row mt-4">
            <!-- Contenedor de productos -->
            <div id="productos" class="col-md-8">
                <div class="row">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-md-6 mb-4">';
                            echo '<div class="card">';
                            echo "<img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen' style='max-width: 100px;'>";
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($row['nombre']) . '</h5>';
                            echo '<p class="card-text">Talla: ' . htmlspecialchars($row['talla']) . '</p>';
                            echo '<p class="card-text">Precio: $' . number_format($row['precio_unitario'], 2, ',', '.') . '</p>';
                            echo '<p class="card-text">Cantidad Disponible: ' . htmlspecialchars($row['cantidad']) . '</p>';
                            echo '<form method="POST" action="../pruebaCarrito.php" class="add-to-cart-form">';
                            echo '<input type="hidden" name="idProducto" value="' . htmlspecialchars($row['id_producto']) . '">';
                            echo '<input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">';
                            echo '<div class="mb-3">';
                            echo '<label for="cantidad' . $row['id_producto'] . '" class="form-label">Cantidad:</label>';
                            echo '<input type="number" name="cantidad" id="cantidad' . $row['id_producto'] . '" class="form-control" value="1" min="1" max="' . htmlspecialchars($row['cantidad']) . '" required>';
                            echo '</div>';
                            echo '<button type="submit" class="btn btn-primary">Agregar al Carrito</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hay productos disponibles.</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Contenedor Carrito en el lado derecho -->
            <div class="col-md-4">
                <div id="cart-sidebar" class="mt-5">
                    <h3>Carrito de Compras</h3>
                    <div id="cart-details" class="card p-3">
                        <?php
                        // Consulta para obtener los detalles del carrito
                        $cartQuery = "SELECT p.id_producto, p.nombre, SUM(c.cantidad) as cantidad, p.precio_unitario
                          FROM carrito c
                          JOIN producto p ON c.fk_id_producto = p.id_producto
                          WHERE c.fk_id_usuario = ?
                          GROUP BY p.id_producto";

                        $stmt = $conectar->prepare($cartQuery);
                        $stmt->bind_param('i', $idUsuario);
                        $stmt->execute();
                        $cartResult = $stmt->get_result();

                        $total = 0; // Inicializar el total del carrito
                        if ($cartResult->num_rows > 0) {
                            while ($row = $cartResult->fetch_assoc()) {
                                $subtotal = $row['precio_unitario'] * $row['cantidad'];
                                $total += $subtotal;
                        
                                // Mostrar detalles del producto en el carrito
                                echo '<p>' . htmlspecialchars($row['nombre']) . ' - Cantidad: ' . $row['cantidad'] . ' - Precio Total: $' . number_format($subtotal, 2, ',', '.') . '</p>';
                                echo '<form method="POST" action="../eliminarProductoCarrito.php">';
                                echo '<input type="hidden" name="idProducto" value="' . $row['id_producto'] . '">';
                                echo '<button type="submit" class="btn btn-danger">Eliminar</button>';
                                echo '</form>';
                            }
                            echo '<h4>Total: $' . number_format($total, 2, ',', '.') . '</h4>';
                        } else {
                            echo '<p>El carrito está vacío.</p>';
                        }
                        
                        $stmt->close();
                        ?>
                    </div>
                    <a href="../menuC.html" class="btn btn-primary mt-3">Seguir comprando</a>
                    <!-- Confirmar Pedido -->
                    <form method="POST" action="../Pedido/verPedido.php">
                      <button type="submit" name="confirmarPedido" class="btn btn-danger mt-3">Confirmar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="notification alert" role="alert" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Manejo del envío del formulario
            $('.add-to-cart-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: '../pruebaCarrito.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#notification').text(response.message).removeClass('alert-danger').addClass('alert-success').show();
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 2000);

                            // Actualizar los detalles del carrito
                            updateCartDetails(response.carrito);
                        } else {
                            $('#notification').text(response.message).removeClass('alert-success').addClass('alert-danger').show();
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 2000);
                        }
                    }
                });
            });

            // Función para actualizar los detalles del carrito
            function updateCartDetails(carrito) {
                var cartDetails = $('#cart-details');
                cartDetails.empty();

                var total = 0;

                if (carrito.length > 0) {
                    carrito.forEach(function(item) {
                        var subtotal = parseFloat(item.precio_unitario) * parseFloat(item.cantidad);
                        total += subtotal;
                        cartDetails.append(
                            '<p>' + item.nombre + ' - Cantidad: ' + item.cantidad + ' - Precio Total: $' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</p>' +
                            '<form method="POST" action="../eliminarProductoCarrito.php">' +
                            '<input type="hidden" name="idProducto" value="' + item.id_producto + '">' +
                            '<button type="submit" class="btn btn-danger">Eliminar</button>' +
                            '</form>'
                        );
                    });

                    cartDetails.append('<h4>Total: $' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</h4>');
                } else {
                    cartDetails.append('<p>El carrito está vacío.</p>');
                }
            }
        });
    </script>
</body>
</html>
