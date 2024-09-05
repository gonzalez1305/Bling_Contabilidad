<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta para obtener los productos de la categoría 'dama' y que estén disponibles
$query = "SELECT * FROM producto WHERE categorias = 'dama' AND estado = 'disponible'";
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estiloCategorias.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Sección Dama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        #container {
            display: flex;
            flex-wrap: wrap;
            margin: 20px auto;
            max-width: 1200px;
        }
        #main-content {
            flex: 3;
            padding: 20px;
        }
        #cart-sidebar {
            flex: 1;
            border-left: 1px solid #ddd;
            padding: 20px;
        }
        #logo {
            width: 150px;
        }
        #header {
            padding: 20px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        #productos {
            padding: 20px;
        }
        .producto {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background: #fff;
            margin-bottom: 20px;
        }
        .producto img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
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
    </style>
</head>

<body>
    <div id="container">
        <div id="main-content">
            <div id="header">
                <div id="logo-container">
                    <img src="../imgs/logo.jpeg" alt="logo" id="logo">
                </div>
                <div id="welcome-text">
                    <h1>Sección de Dama</h1>
                    <a href="../menuC.html" class="btn btn-light">Volver</a>
                </div>
            </div>
            <div id="productos">
                <div class="row">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-md-4 mb-3">';
                            echo '<div class="producto">';
                            echo '<img src="../imgs/jordan1.jpg' . htmlspecialchars($row['imagen']) . '" alt="Producto">';
                            echo '<div class="info">';
                            echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($row['nombre']) . '</p>';
                            echo '<p><strong>Talla:</strong> ' . htmlspecialchars($row['talla']) . '</p>';
                            echo '<p><strong>Precio:</strong> $' . number_format($row['precio_unitario'], 2) . '</p>';
                            echo '<p><strong>Cantidad Disponible:</strong> ' . htmlspecialchars($row['cantidad']) . '</p>';
                            echo '<label for="cantidad' . $row['id_producto'] . '">Cantidad:</label>';
                            echo '<input type="number" id="cantidad' . $row['id_producto'] . '" name="cantidad" min="1" max="' . htmlspecialchars($row['cantidad']) . '" required data-cantidad="' . htmlspecialchars($row['cantidad']) . '" oninput="validateQuantity(this)">';
                            echo '<input type="hidden" name="idProducto" value="' . $row['id_producto'] . '">';
                            echo '<button class="btn btn-primary mt-2" onclick="addToCart(' . $row['id_producto'] . ')">Añadir al carrito</button>';
                            echo '<a href="detalleProducto.php?id=' . $row['id_producto'] . '" class="btn btn-info mt-2">Ver Detalles</a>';
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
        </div>

        <!-- Contenedor Carrito -->
        <div id="cart-sidebar">
            <h3>Carrito de Compras</h3>
            <div id="cart-details">
                <!-- Los detalles del carrito se actualizarán aquí -->
            </div>
            <a href="../menuC.html" class="btn btn-primary mt-3">Seguir comprando</a>
        </div>
    </div>

    <div id="notification" class="notification alert" role="alert"></div>

    <script>
        function validateQuantity(input) {
            const availableQuantity = parseInt(input.dataset.cantidad);
            const requestedQuantity = parseInt(input.value);
            if (requestedQuantity > availableQuantity) {
                alert('La cantidad solicitada excede la cantidad disponible.');
                input.value = availableQuantity;
            }
        }

        function addToCart(idProducto) {
            const cantidadInput = document.querySelector(`#cantidad${idProducto}`);
            const cantidad = cantidadInput ? cantidadInput.value : 1;

            if (cantidad <= 0) {
                alert('Cantidad inválida.');
                return;
            }

                fetch('../pruebaCarrito.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const notification = document.getElementById('notification');
                    if (data.status === 'success') {
                        updateCartDetails(data.carrito);
                        notification.textContent = data.message;
                        notification.className = 'notification alert alert-success';
                    } else {
                        notification.textContent = data.message;
                        notification.className = 'notification alert alert-danger';
                    }
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 5000); // Ocultar la notificación después de 3 segundos
                })
                .catch(error => {
                    console.error('Error:', error);
                    const notification = document.getElementById('notification');
                    notification.textContent = 'Error en la solicitud al servidor.';
                    notification.className = 'notification alert alert-danger';
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 5000);
                });
            };

        function updateCartDetails(carrito) {
            const cartDetails = document.getElementById('cart-details');
            cartDetails.innerHTML = '';

            if (carrito.length > 0) {
                carrito.forEach(item => {
                    cartDetails.innerHTML += `<p>${item.nombre} - Cantidad: ${item.cantidad} - Precio Total: $${item.precio_total.toFixed(2)}</p>`;
                });
            } else {
                cartDetails.innerHTML = '<p>El carrito está vacío.</p>';
            }
        }
    </script>
</body>

</html>
