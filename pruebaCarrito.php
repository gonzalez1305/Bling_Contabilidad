<?php
session_start();
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php'); // Redirigir a la página de login si no está logueado
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Obtener detalles del carrito
$queryCart = "
    SELECT c.id_carrito, p.nombre, p.talla, p.precio_unitario, c.cantidad, (c.cantidad * p.precio_unitario) AS precio_total
    FROM carrito c
    JOIN producto p ON c.fk_id_producto = p.id_producto
    WHERE c.fk_id_usuario = '$idUsuario'
";
$resultCart = mysqli_query($conectar, $queryCart);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>Carrito de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .carrito {
            margin: 20px;
        }
        .producto {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background: #fff;
            margin-bottom: 20px;
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
    <div class="container carrito">
        <h1>Carrito de Compras</h1>
        <?php
        if (mysqli_num_rows($resultCart) > 0) {
            echo '<div class="row">';
            $total = 0;
            while ($rowCart = mysqli_fetch_assoc($resultCart)) {
                $precioTotal = $rowCart['cantidad'] * $rowCart['precio_unitario'];
                $total += $precioTotal;
                echo '<div class="col-md-4 mb-3">';
                echo '<div class="producto">';
                echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($rowCart['nombre']) . '</p>';
                echo '<p><strong>Talla:</strong> ' . htmlspecialchars($rowCart['talla']) . '</p>';
                echo '<p><strong>Precio Unitario:</strong> $' . number_format($rowCart['precio_unitario'], 2) . '</p>';
                echo '<p><strong>Cantidad:</strong> ' . htmlspecialchars($rowCart['cantidad']) . '</p>';
                echo '<p><strong>Precio Total:</strong> $' . number_format($precioTotal, 2) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo "<hr><p><strong>Total Carrito:</strong> $" . number_format($total, 2) . "</p>";
        } else {
            echo '<p>No hay productos en el carrito.</p>';
        }
        ?>
        <form method="post" action="detalleProducto.php">
            <button type="submit" name="confirmarPedido" class="btn btn-primary">Confirmar Pedido</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
