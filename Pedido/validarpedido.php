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
                            <a class="nav-link" href="../dashboard_v.html">Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h1 class="h2">Lista de Pedidos</h1>
                <div class="pedido-container">
                    <table id="pedidosTable">
                        <thead>
                            <tr>
                                <th>ID PEDIDO</th>
                                <th>ID USUARIO</th>
                                <th>FECHA</th>
                                <th>SITUACION</th>
                                <th>NOMBRE</th>
                                <th>UNIDADES</th>
                                <th>PRECIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include("../conexion.php");
                                $sql = "SELECT * FROM pedido
                                    INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
                                    INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto";
                                $resultado = mysqli_query($conectar, $sql);
                                while ($filas = mysqli_fetch_assoc($resultado)) {
                            ?>
                                <tr>
                                    <td><?php echo $filas['id_pedido'] ?></td>
                                    <td><?php echo $filas['fk_id_usuario'] ?></td>
                                    <td><?php echo $filas['fecha'] ?></td>
                                    <td><?php echo $filas['situacion'] ?></td>
                                    <td><?php echo $filas['nombre'] ?></td>
                                    <td><?php echo $filas['unidades'] ?></td>
                                    <td><?php echo $filas['precio_total'] ?></td>
                                    <td>
                                        <?php echo "<a href='editar.php?id_detalles_pedido=" . $filas['id_detalles_pedido'] . "'>Editar</a>"; ?>
                                        -
                                        <?php echo "<a href='eliminar.php?id_detalles_pedido=" . $filas['id_detalles_pedido'] . "' onclick='return confirmar()'>Eliminar</a>"; ?>
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
                dom: 'Bfrtip', // Configuración de los elementos en la interfaz
                buttons: [
                    'searchBuilder', 'pageLength' // Botones disponibles
                ]
            });
        });

        function confirmar() {
            return confirm('¿Está seguro que desea eliminar este pedido?');
        }
    </script>
</body>
</html>
