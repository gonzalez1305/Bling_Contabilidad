<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <style>
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
                touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
               -moz-user-select: none;
                -ms-user-select: none;
                    user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-default {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }

        .btn-default:hover,
        .btn-default:focus,
        .btn-default:active,
        .btn-default.active,
        .open .dropdown-toggle.btn-default {
            color: #333;
            background-color: #e6e6e6;
            border-color: #adadad;
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

    <div class="pedido-container">
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
                            <a class="btn btn-default" href='editar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>'>Editar</a>
                            <a class="btn btn-default" href='eliminar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>' onclick='return confirmar()'>Eliminar</a>
                        </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>

        <div class="REPORTES">
            <!-- Enlace para imprimir reportes -->
            <a class="btn btn-default" href="reporte.php">Imprimir Reportes</a>
            
            <!-- Botón para generar PDF estadístico -->
            <button class="btn btn-default" id="btnGenerarPDF">Generar PDF Estadístico</button>
            
            <!-- Botón para generar reporte estadístico con gráfica -->
            <button class="btn btn-default" onclick="window.location.href='reporteGrafico.html'">Generar Reporte Estadístico con Gráfica</button>
        </div>

        <!-- Script para manejar la generación del PDF estadístico -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.getElementById('btnGenerarPDF').addEventListener('click', function() {
                // Captura la gráfica como imagen usando html2canvas
                html2canvas(document.getElementById('myChart')).then(function(canvas) {
                    // Genera la imagen como PNG
                    var imgData = canvas.toDataURL('image/png');

                    // Envía la imagen al servidor para generar el PDF
                    fetch('generar_pdf.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ image: imgData }),
                    }).then(function(response) {
                        // Maneja la respuesta (puede ser descargar el PDF generado)
                        // Por ejemplo, redirigir a la URL del PDF generado
                        window.location.href = response.url; // Suponiendo que el servidor devuelve la URL del PDF
                    });
                });
            });
        </script>

        <!-- Volver al menú principal -->
        <a href="menuV.html">Volver</a><br><br>

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
    </div>
</body>
</html>
