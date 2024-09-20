<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}

require '../conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pedidos - Bling Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../menuV.php">
                <img src="../imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                Bling Compra
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-5">
        <h1 class="mt-4">Detalles de Pedidos</h1>
        <div class="pedido-container">
            <table id="detallesTable" class="display">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Situación</th>
                        <th>Producto</th>
                        <th>Unidades</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT 
                                p.id_pedido,
                                u.nombre AS cliente,
                                DATE(p.fecha) AS fecha,
                                p.situacion,
                                dp.fk_id_producto,
                                dp.unidades,
                                dp.precio_total
                            FROM 
                                pedido p
                            INNER JOIN 
                                detalles_pedido dp ON p.id_pedido = dp.fk_id_pedido
                            INNER JOIN 
                                usuario u ON p.fk_id_usuario = u.id_usuario
                            ORDER BY 
                                p.fecha DESC";
                    $resultado = mysqli_query($conectar, $sql);
                    while ($filas = mysqli_fetch_assoc($resultado)) {
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($filas['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($filas['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($filas['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($filas['situacion']); ?></td>
                            <td><?php echo htmlspecialchars($filas['fk_id_producto']); ?></td>
                            <td><?php echo htmlspecialchars($filas['unidades']); ?></td>
                            <td><?php echo htmlspecialchars($filas['precio_total']); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <a href="../menuV.php" class="btn btn-primary mt-3">Volver</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#detallesTable').DataTable({
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)"
                }
            });
        });
    </script>
</body>
</html>
