<?php
session_start();
include '../conexion.php';

// Verificar si se ha pasado un id de producto
if (isset($_GET['id'])) {
    $idProducto = intval($_GET['id']); // Obtener el id del producto desde la URL

    // Consulta para obtener los detalles del producto
    $query = "SELECT * FROM producto WHERE id_producto = ?";
    $stmt = $conectar->prepare($query);
    $stmt->bind_param('i', $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo 'Producto no encontrado.';
        exit();
    }

    // Obtener la cantidad del producto en el carrito del usuario
    if (isset($_SESSION['id_usuario'])) {
        $idUsuario = intval($_SESSION['id_usuario']);
        
        $cartQuery = "SELECT cantidad FROM carrito WHERE fk_id_producto = ? AND fk_id_usuario = ?";
        $cartStmt = $conectar->prepare($cartQuery);
        $cartStmt->bind_param('ii', $idProducto, $idUsuario);
        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();
        
        if ($cartResult->num_rows > 0) {
            $cartItem = $cartResult->fetch_assoc();
            $cantidadCarrito = $cartItem['cantidad'];
        } else {
            $cantidadCarrito = 0; // Si no hay el producto en el carrito
        }
    } else {
        $cantidadCarrito = 0; // Si el usuario no está logueado
    }
} else {
    echo 'ID de producto no especificado.';
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Detalles del Producto</title>
</head>
<body>
    <div class="container mt-5">
        <div id="header" class="bg-primary text-white text-center p-3 rounded">
            <img src="../imgs/logo.jpeg" alt="logo" id="logo" class="mb-2" style="width: 150px;">
            <h1>Detalles del Producto</h1>
            <a href="../menuC.html" class="btn btn-light">Volver</a>
        </div>

        <div id="producto-detalle" class="mt-4">
            <div class="card">
                <img src="../imgs/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                    <p class="card-text">Talla: <?php echo htmlspecialchars($producto['talla']); ?></p>
                    <p class="card-text">Precio Unitario: $<?php echo number_format($producto['precio_unitario'], 2); ?></p>
                    <!-- Mostrar la cantidad seleccionada en el carrito -->
                    <p class="card-text">Cantidad en Carrito: <?php echo $cantidadCarrito; ?></p>
                    <!-- Precio total calculado -->
                    <div id="precio-total" class="mt-3">
                        <p><strong>Precio Total:</strong> $<span id="total-precio"><?php echo number_format($producto['precio_unitario'] * $cantidadCarrito, 2); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para actualizar el precio total según la cantidad seleccionada
        // Solo si la cantidad es editable, de lo contrario esta sección se puede omitir
        document.getElementById('cantidad')?.addEventListener('input', function() {
            const cantidad = this.value;
            const precioUnitario = <?php echo $producto['precio_unitario']; ?>;
            const precioTotal = cantidad * precioUnitario;
            document.getElementById('total-precio').textContent = precioTotal.toFixed(2);
        });
    </script>
</body>
</html>
