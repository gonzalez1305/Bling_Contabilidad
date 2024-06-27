<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>

    <!-- Estilos para DataTables y botones -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">

    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .REPORTES {
            margin-top: 20px;
            text-align: center;
        }

        .volver-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
        // Incluir la conexión a la base de datos
        include("conexion.php");
        
        // Consulta para obtener datos de pedidos con detalles
        $sql = "SELECT * FROM pedido
            INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
            INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto";
        $resultado = mysqli_query($conectar, $sql);
    ?>

    <div class="container">
        <h1>Lista de Pedidos</h1>
        
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
                    // Mostrar los datos de pedidos en la tabla
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
                            <a class="btn" href='editar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>'>Editar</a>
                            <a class="btn" href='eliminar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido']; ?>' onclick='return confirmar()'>Eliminar</a>

                        </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>

        <div class="REPORTES">
            <!-- Enlace para imprimir reportes -->
            <a class="btn" href="reporte.php">Imprimir Reportes</a>
        
            
            <!-- Botón para generar reporte estadístico con gráfica -->
            <button class="btn" onclick="window.location.href='reporteGrafico.html'">Generar Reporte Estadístico con Gráfica</button>
        </div>
        <!-- Volver al menú principal -->
        <a href="menuV.html" class="volver-btn">Volver al Menú Principal</a>
    </div>

    <!-- Scripts de DataTables y configuración -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.6.0/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables con botones y búsqueda avanzada
            $('#pedidosTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'searchBuilder', 'pageLength'
                ]
            });
        });
    </script>
</body>
</html>
