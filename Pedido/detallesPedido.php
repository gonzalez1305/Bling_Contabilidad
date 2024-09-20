<?php
session_start();
include '../conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo 'No estás logueado.';
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Configuración de paginación
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Límites de 10, 50, 100
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Consultar los pedidos en proceso para el usuario logueado
$query = "
    SELECT p.id_pedido, SUM(dp.precio_total) AS total_precio
    FROM pedido p
    JOIN detalles_pedido dp ON p.id_pedido = dp.fk_id_pedido
    WHERE p.fk_id_usuario = ? 
    AND p.situacion = 'en proceso'
    GROUP BY p.id_pedido
    LIMIT ? OFFSET ?
";
$stmt = $conectar->prepare($query);
$stmt->bind_param('iii', $idUsuario, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Obtener el total de pedidos
$totalQuery = "
    SELECT COUNT(*) as total
    FROM pedido p
    WHERE p.fk_id_usuario = ? 
    AND p.situacion = 'en proceso'
";
$totalStmt = $conectar->prepare($totalQuery);
$totalStmt->bind_param('i', $idUsuario);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalPedidos = $totalRow['total'];
$totalPages = ceil($totalPedidos / $limit);

// Consulta para obtener los detalles del pedido
$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $idPedido = $row['id_pedido'];
    $totalPrecio = $row['total_precio'];

    // Obtener los productos del pedido
    $queryDetalles = "
        SELECT p.nombre, p.talla, dp.unidades, dp.precio_total
        FROM detalles_pedido dp
        JOIN producto p ON dp.fk_id_producto = p.id_producto
        WHERE dp.fk_id_pedido = ?
    ";
    $stmtDetalles = $conectar->prepare($queryDetalles);
    $stmtDetalles->bind_param('i', $idPedido);
    $stmtDetalles->execute();
    $resultDetalles = $stmtDetalles->get_result();

    $productos = [];
    while ($detalle = $resultDetalles->fetch_assoc()) {
        $productos[] = $detalle;
    }

    $pedidos[] = [
        'id_pedido' => $idPedido,
        'total_precio' => $totalPrecio,
        'productos' => $productos
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Carrito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="icon" href="imgs/logo.png">
    <style>
        body {
            background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
            margin: 0;
            padding-bottom: 50px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .pagination {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Detalles del Pedido</h2>

        <!-- Filtros -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="limit" class="form-label">Mostrar:</label> 
                    <select name="limit" id="limit" class="form-select" onchange="this.form.submit()">
                        <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="page" value="1">
        </form>

        <?php if (!empty($pedidos)): ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="mb-4 border rounded p-3">
                    <h4>Pedido ID: <?php echo htmlspecialchars($pedido['id_pedido']); ?></h4>
                    <p><strong>Total Precio: </strong><?php echo number_format($pedido['total_precio'], 2); ?> COP</p>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre del Producto</th>
                                <th>Talla</th>
                                <th>Unidades</th>
                                <th>Precio Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedido['productos'] as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['talla']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['unidades']); ?></td>
                                    <td><?php echo number_format($producto['precio_total'], 2); ?> COP</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes pedidos confirmados en este momento.</p>
        <?php endif; ?>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <a href="../menuV.php" class="btn btn-primary">Volver al menú</a>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
