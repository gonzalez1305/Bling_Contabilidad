<?php
session_start();
include '../conexion.php';

$idUsuario = intval($_SESSION['id_usuario']);

$query = "
    SELECT dp.id_detalles_pedido, p.nombre, dp.unidades, dp.precio_total
    FROM detalles_pedido dp
    JOIN producto p ON dp.fk_id_producto = p.id_producto
    JOIN pedido pe ON dp.fk_id_pedido = pe.id_pedido
    WHERE pe.fk_id_usuario = ? AND pe.situacion = 'en proceso'
";

$stmt = $conectar->prepare($query);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Carrito</title>
    <!-- Incluye tu CSS personalizado y Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles del Carrito</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Detalles Pedido</th>
                    <th>Nombre del Producto</th>
                    <th>Unidades</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id_detalles_pedido']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['unidades']) . '</td>';
                        echo '<td>' . number_format($row['precio_total'], 2) . ' COP</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No hay detalles de carrito disponibles.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
