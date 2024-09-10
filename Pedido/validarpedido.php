<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>

    <!-- CSS de Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../imgs/logo.png">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #ffffff;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .pedido-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        table th {
            background-color: #007bff;
            color: #ffffff;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bling Compra</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="../menu.html">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="../Usuario/validarusuario.php">Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Inventario/listaInventario.php">Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="../Pagos/verPago.php">Pagos</a>
                    </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h1 class="h2">Lista de Pedidos</h1>
                <a class="btn btn-success" href="../reportePedido.php" role="button">Reporte Pedidos</a>
                <a class="btn btn-success" href="../reporteGraficoPedidos.html" role="button">Reporte Pedidos Gráfico</a>

                <div class="pedido-container">
                    <table id="pedidosTable">
                        <thead>
                            <tr>
                                <th>CLIENTE</th>
                                <th>FECHA</th>
                                <th>SITUACION</th>
                                <th>UNIDADES</th>
                                <th>PRECIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include("../conexion.php");
                                $sql = "SELECT 
                                    p.id_pedido, 
                                    p.fecha, 
                                    p.situacion, 
                                    dp.unidades, 
                                    dp.precio_total, 
                                    u.nombre AS cliente
                                FROM 
                                    pedido p
                                INNER JOIN 
                                    detalles_pedido dp ON p.id_pedido = dp.fk_id_pedido
                                INNER JOIN 
                                    producto pr ON dp.fk_id_producto = pr.id_producto
                                INNER JOIN 
                                    usuario u ON p.fk_id_usuario = u.id_usuario";
                                $resultado = mysqli_query($conectar, $sql);
                                while ($filas = mysqli_fetch_assoc($resultado)) {
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($filas['cliente']) ?></td>
                                    <td><?php echo htmlspecialchars($filas['fecha']) ?></td>
                                    <td><?php echo htmlspecialchars($filas['situacion']) ?></td>
                                    <td><?php echo htmlspecialchars($filas['unidades']) ?></td>
                                    <td><?php echo htmlspecialchars($filas['precio_total']) ?></td>
                                    <td>
                                        <a href='editar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>' class='btn btn-warning btn-sm'>Editar</a>
                                        <a href='eliminar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>' class='btn btn-danger btn-sm' onclick='return confirmar()'>Eliminar</a>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <a href="../menuV.html" class="btn">Volver</a><br><br>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts de DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.6.0/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#pedidosTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'searchBuilder', 'pageLength'
                ],
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
