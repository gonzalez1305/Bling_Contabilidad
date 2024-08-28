<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Consulta para obtener los productos de la tabla producto
$query = "SELECT * FROM producto WHERE categorias = 'Zapatos'"; // Filtra según la categoría 'Zapatos'
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estiloCategorias.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Sección Niño</title>
</head>

<body>
    <div id="container" class="d-flex">
        <div id="main-content" class="w-75">
            <div id="header">
                <div id="logo-container">
                    <img src="../imgs/logo.jpeg" alt="logo" id="logo">
                </div>
                <div id="welcome-text" class="text-center">
                    <h1 class="text-center">Sección de niños</h1>
                    <a href="../menuC.html">Volver</a>
                </div>
            </div>
            <div id="zapatos">
                <div class="fila">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="producto">';
                            echo '<img src="../imgs/' . $row['imagen'] . '" alt="Zapato">';
                            echo '<form action="../pruebaCarrito.php" method="post">';
                            echo '<div class="info">';
                            echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($row['nombre']) . '</p>';
                            echo '<p><strong>Talla:</strong> ' . htmlspecialchars($row['talla']) . '</p>';
                            echo '<p><strong>Precio:</strong> $' . number_format($row['precio_unitario'], 2) . '</p>';
                            echo '<label for="cantidad' . $row['id_producto'] . '">Cantidad:</label>';
                            echo '<input type="number" id="cantidad' . $row['id_producto'] . '" name="cantidad" min="1" required>';
                            echo '<input type="hidden" name="idProducto" value="' . $row['id_producto'] . '">';
                            echo '<input type="submit" value="Añadir al carrito">';
                            echo '</div>';
                            echo '</form>';
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
        <div id="cart-sidebar" class="w-25 p-3 border-start">
            <h3>Carrito de Compras</h3>
            <?php
            // Mostrar productos del carrito
            if (isset($idUsuario)) {
                $queryCart = "SELECT c.id_carrito, p.nombre, c.cantidad FROM carrito c 
                              JOIN producto p ON c.fk_id_producto = p.id_producto 
                              WHERE c.fk_id_usuario = '$idUsuario'";
                $resultCart = mysqli_query($conectar, $queryCart);

                if (mysqli_num_rows($resultCart) > 0) {
                    while ($rowCart = mysqli_fetch_assoc($resultCart)) {
                        echo "<p>{$rowCart['nombre']} - Cantidad: {$rowCart['cantidad']}</p>";
                    }
                } else {
                    echo "<p>El carrito está vacío.</p>";
                }
            }
            ?>
            <a href="../menuC.html" class="btn btn-primary mt-3">Seguir comprando</a>
        </div>
    </div>
</body>

</html>
