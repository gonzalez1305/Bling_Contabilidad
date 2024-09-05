<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
include '../conexion.php';

// Consulta para obtener los productos de la categoría 'niño' y que estén disponibles
$query = "SELECT * FROM producto WHERE categorias = 'niño' AND estado = 'disponible'";
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
    <title>Productos Niño</title>
</head>

<body>
    <div class="container mt-5">
        <div id="header" class="bg-primary text-white text-center p-3 rounded">
            <img src="../imgs/logo.jpeg" alt="logo" id="logo" class="mb-2" style="width: 150px;">
            <h1>Sección de Niño</h1>
            <a href="../menuC.html" class="btn btn-light">Volver</a>
        </div>

        <div id="productos" class="row mt-4">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<img src="../imgs/' . htmlspecialchars($row['imagen']) . '" class="card-img-top" alt="Producto">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['nombre']) . '</h5>';
                    echo '<p class="card-text">Talla: ' . htmlspecialchars($row['talla']) . '</p>';
                    echo '<p class="card-text">Precio: $' . number_format($row['precio_unitario'], 2) . '</p>';
                    echo '<p class="card-text">Cantidad Disponible: ' . htmlspecialchars($row['cantidad']) . '</p>';
                    echo '<form method="POST" action="../pruebaCarrito.php" class="add-to-cart-form">';
                    echo '<input type="hidden" name="idProducto" value="' . htmlspecialchars($row['id_producto']) . '">';
                    echo '<input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">';
                    echo '<div class="mb-3">';
                    echo '<label for="cantidad' . $row['id_producto'] . '" class="form-label">Cantidad:</label>';
                    echo '<input type="number" name="cantidad" id="cantidad' . $row['id_producto'] . '" class="form-control" value="1" min="1" max="' . htmlspecialchars($row['cantidad']) . '" required>';
                    echo '</div>';
                    echo '<button type="submit" class="btn btn-primary">Agregar al Carrito</button>';
                    echo '<a href="detalleProducto.php?id=' . htmlspecialchars($row['id_producto']) . '" class="btn btn-info mt-2">Ver Detalles</a>';
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

        <!-- Contenedor Carrito -->
        <div id="cart-sidebar" class="mt-5">
            <h3>Carrito de Compras</h3>
            <div id="cart-details" class="card p-3">
                <?php
                // Consulta para obtener los detalles del carrito
                $cartQuery = "SELECT p.nombre, c.cantidad, p.precio_unitario 
                              FROM carrito c
                              JOIN producto p ON c.fk_id_producto = p.id_producto
                              WHERE c.fk_id_usuario = ?";
                $stmt = $conectar->prepare($cartQuery);
                $stmt->bind_param('i', $idUsuario);
                $stmt->execute();
                $cartResult = $stmt->get_result();

                $total = 0; // Inicializar el total del carrito

                if ($cartResult->num_rows > 0) {
                    while ($row = $cartResult->fetch_assoc()) {
                        $subtotal = $row['precio_unitario'] * $row['cantidad'];
                        $total += $subtotal; // Sumar al total
                        echo '<p>' . htmlspecialchars($row['nombre']) . ' - Cantidad: ' . $row['cantidad'] . ' - Precio Total: $' . number_format($subtotal, 2) . '</p>';
                    }
                    echo '<h4>Total: $' . number_format($total, 2) . '</h4>'; // Mostrar el total
                } else {
                    echo '<p>Tu carrito está vacío.</p>';
                }
                ?>
            </div>
            <a href="../menuC.html" class="btn btn-primary mt-3">Seguir comprando</a>
        </div>
    </div>

    <div id="notification" class="notification alert" role="alert"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Manejo de notificación de éxito o error al agregar al carrito
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevenir la acción por defecto del formulario
                const formData = new FormData(this);

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
                    }, 3000); // Ocultar la notificación después de 3 segundos
                })
                .catch(error => {
                    console.error('Error:', error);
                    const notification = document.getElementById('notification');
                    notification.textContent = 'Error en la solicitud al servidor.';
                    notification.className = 'notification alert alert-danger';
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                });
            });
        });

        function updateCartDetails(carrito) {
            const cartDetails = document.getElementById('cart-details');
            cartDetails.innerHTML = '';

            let total = 0;

            if (carrito.length > 0) {
                carrito.forEach(item => {
                    const subtotal = item.precio_unitario * item.cantidad;
                    total += subtotal;
                    cartDetails.innerHTML += `<p>${item.nombre} - Cantidad: ${item.cantidad} - Precio Total: $${subtotal.toFixed(2)}</p>`;
                });
                cartDetails.innerHTML += `<h4>Total: $${total.toFixed(2)}</h4>`;
            } else {
                cartDetails.innerHTML = '<p>El carrito está vacío.</p>';
            }
        }
    </script>
</body>

</html>
