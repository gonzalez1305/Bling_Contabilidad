<?php
session_start();
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php'); // Redirigir a la página de login si no está logueado
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Verificar si se ha pasado un ID de producto y si el producto está en el carrito
$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener detalles del carrito
$queryCart = "
    SELECT c.id_carrito, p.nombre, p.talla, p.precio_unitario, c.cantidad, (c.cantidad * p.precio_unitario) AS precio_total
    FROM carrito c
    JOIN producto p ON c.fk_id_producto = p.id_producto
    WHERE c.fk_id_usuario = '$idUsuario'
";
$resultCart = mysqli_query($conectar, $queryCart);

// Obtener detalles del producto si se pasa un ID
if ($idProducto) {
    $queryProduct = "SELECT * FROM producto WHERE id_producto = '$idProducto' AND id_producto IN (SELECT fk_id_producto FROM carrito WHERE fk_id_usuario = '$idUsuario')";
    $resultProduct = mysqli_query($conectar, $queryProduct);
    $product = mysqli_fetch_assoc($resultProduct);
}

// Agregar detalles del carrito a la tabla detalles_pedido
if ($idProducto && isset($product)) {
    // Insertar un nuevo pedido y obtener su ID
    $queryInsertPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES (NOW(), 'en proceso', '$idUsuario')";
    $resultInsertPedido = mysqli_query($conectar, $queryInsertPedido);
    $idPedido = mysqli_insert_id($conectar); // Obtener el ID del pedido recién insertado

    // Obtener detalles del carrito para el usuario
    $queryCartDetails = "SELECT c.fk_id_producto, c.cantidad, (c.cantidad * p.precio_unitario) AS precio_total 
                         FROM carrito c 
                         JOIN producto p ON c.fk_id_producto = p.id_producto 
                         WHERE c.fk_id_usuario = '$idUsuario'";
    $resultCartDetails = mysqli_query($conectar, $queryCartDetails);

    // Insertar cada detalle en la tabla detalles_pedido
    while ($rowCart = mysqli_fetch_assoc($resultCartDetails)) {
        $queryInsertDetalles = "INSERT INTO detalles_pedido (fk_id_producto, fk_id_pedido, unidades, precio_total) 
                                VALUES ('{$rowCart['fk_id_producto']}', '$idPedido', '{$rowCart['cantidad']}', '{$rowCart['precio_total']}')";
        mysqli_query($conectar, $queryInsertDetalles);
    }

    // Vaciar el carrito después de insertar los detalles
    $queryDeleteCart = "DELETE FROM carrito WHERE fk_id_usuario = '$idUsuario'";
    mysqli_query($conectar, $queryDeleteCart);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estiloCategorias.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Detalles del Carrito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        #container {
            display: flex;
            margin: 20px auto;
            max-width: 1200px;
        }
        #main-content {
            flex: 1;
            padding: 20px;
        }
        #cart-sidebar {
            border-left: 1px solid #ddd;
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
        #cart-details {
            padding: 20px;
            background: #fff;
            border-radius: 5px;
        }
        #cart-sidebar {
            background: #fff;
            border-radius: 5px;
        }
        #cart-image {
            text-align: center;
            margin-top: 20px;
        }
        #cart-image img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="container" class="d-flex">
        <div id="main-content" class="w-75">
            <div id="header">
                <div id="logo-container">
                    <img src="../imgs/logo.jpeg" alt="logo" id="logo">
                </div>
                <div id="welcome-text">
                    <h1>Detalles del Carrito</h1>
                    <a href="../menuC.html" class="btn btn-light">Volver</a>
                </div>
            </div>
            <div id="cart-details">
                <?php
                // Mostrar detalles del carrito
                if (mysqli_num_rows($resultCart) > 0) {
                    echo '<div class="row">';
                    while ($rowCart = mysqli_fetch_assoc($resultCart)) {
                        echo '<div class="col-md-4 mb-3">';
                        echo '<div class="producto">';
                        echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($rowCart['nombre']) . '</p>';
                        echo '<p><strong>Talla:</strong> ' . htmlspecialchars($rowCart['talla']) . '</p>';
                        echo '<p><strong>Precio Unitario:</strong> $' . number_format($rowCart['precio_unitario'], 2) . '</p>';
                        echo '<p><strong>Cantidad:</strong> ' . htmlspecialchars($rowCart['cantidad']) . '</p>';
                        echo '<p><strong>Precio Total:</strong> $' . number_format($rowCart['precio_total'], 2) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>No hay productos en el carrito.</p>';
                }

                // Mostrar detalles de un producto específico si se pasó un ID
                if ($idProducto && $product) {
                    echo '<div class="producto">';
                    echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($product['nombre']) . '</p>';
                    echo '<p><strong>Talla:</strong> ' . htmlspecialchars($product['talla']) . '</p>';
                    echo '<p><strong>Precio Unitario:</strong> $' . number_format($product['precio_unitario'], 2) . '</p>';
                    echo '<p><strong>Cantidad Disponible:</strong> ' . htmlspecialchars($product['cantidad']) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Contenedor Carrito -->
        <div id="cart-sidebar" class="w-25">
            <h3>Carrito de Compras</h3>
            <?php
            // Mostrar productos del carrito
            if (isset($idUsuario)) {
                $queryCart = "SELECT c.id_carrito, p.nombre, c.cantidad, p.precio_unitario FROM carrito c 
                              JOIN producto p ON c.fk_id_producto = p.id_producto 
                              WHERE c.fk_id_usuario = '$idUsuario'";
                $resultCart = mysqli_query($conectar, $queryCart);

                if (mysqli_num_rows($resultCart) > 0) {
                    $total = 0;
                    while ($rowCart = mysqli_fetch_assoc($resultCart)) {
                        $precioTotal = $rowCart['cantidad'] * $rowCart['precio_unitario'];
                        $total += $precioTotal;
                        echo "<p>{$rowCart['nombre']} - Cantidad: {$rowCart['cantidad']} - Precio Total: $" . number_format($precioTotal, 2) . "</p>";
                    }
                    echo "<hr><p><strong>Total Carrito:</strong> $" . number_format($total, 2) . "</p>";
                } else {
                    echo "<p>No hay productos en el carrito.</p>";
                }
            }
            ?>
            <form method="post" action="">
                <button type="submit" name="confirmarPedido" class="btn btn-primary">Confirmar Pedido</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Confirmar el pedido y vaciar el carrito
if (isset($_POST['confirmarPedido'])) {
    // Verificar si hay productos en el carrito
    $queryCheckCart = "SELECT * FROM carrito WHERE fk_id_usuario = '$idUsuario'";
    $resultCheckCart = mysqli_query($conectar, $queryCheckCart);

    if (mysqli_num_rows($resultCheckCart) > 0) {
        // Insertar un nuevo pedido
        $queryInsertPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES (NOW(), 'en proceso', '$idUsuario')";
        $resultInsertPedido = mysqli_query($conectar, $queryInsertPedido);
        $idPedido = mysqli_insert_id($conectar); // Obtener el ID del pedido recién insertado

        // Insertar detalles del pedido
        $queryCartDetails = "SELECT c.fk_id_producto, c.cantidad, (c.cantidad * p.precio_unitario) AS precio_total 
                             FROM carrito c 
                             JOIN producto p ON c.fk_id_producto = p.id_producto 
                             WHERE c.fk_id_usuario = '$idUsuario'";
        $resultCartDetails = mysqli_query($conectar, $queryCartDetails);

        while ($rowCart = mysqli_fetch_assoc($resultCartDetails)) {
            $queryInsertDetalles = "INSERT INTO detalles_pedido (fk_id_producto, fk_id_pedido, unidades, precio_total) 
                                    VALUES ('{$rowCart['fk_id_producto']}', '$idPedido', '{$rowCart['cantidad']}', '{$rowCart['precio_total']}')";
            mysqli_query($conectar, $queryInsertDetalles);
        }

        // Vaciar el carrito después de insertar los detalles
        $queryDeleteCart = "DELETE FROM carrito WHERE fk_id_usuario = '$idUsuario'";
        mysqli_query($conectar, $queryDeleteCart);

        // Redirigir al usuario a una página de éxito
        header('Location: ../exito.php');
        exit();
    } else {
        echo '<script>alert("No hay productos en el carrito para confirmar el pedido.");</script>';
    }
}
?>
