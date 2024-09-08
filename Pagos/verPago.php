<?php
require '../conexion.php'; // Conexión a la base de datos

// Consulta para obtener los pagos registrados
$pagoQuery = "SELECT p.id_pago, p.id_gestion_venta, p.fecha_pago, p.monto, p.metodo_pago, gv.id_gestion_venta, gv.fecha_venta
              FROM pagos p
              JOIN gestion_ventas gv ON p.id_gestion_venta = gv.id_gestion_venta";
$pagoResult = mysqli_query($conectar, $pagoQuery);

// Verifica si existe un mensaje en la sesión
session_start();
$resultado = "";
if (isset($_SESSION['mensaje'])) {
    $resultado = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Pagos - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
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
        .mensaje-exito {
            color: green;
            background-color: #d4edda;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
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
                        <a class="nav-link" href="../Usuario/validarusuario.php">Usuarios</a>
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
            <div class="container">
                <h1 class="h2">Listado de Pagos</h1>

                <a class="btn btn-success" href="../reportePago.php" role="button">Reporte Pago</a>
                <a class="btn btn-success" href="../reporteGraficoPago.html" role="button">Reporte Grafico Pago</a>

                <!-- Muestra el mensaje de resultado -->
                <?php if ($resultado): ?>
                    <div id="mensaje" class="mensaje-exito">
                        <p><?php echo $resultado; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Tabla para mostrar los pagos -->
                <table id="pagosTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha de Pago</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>Fecha de Venta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pago = mysqli_fetch_assoc($pagoResult)): ?>
                            <tr>
                                <td><?php echo $pago['fecha_pago']; ?></td>
                                <td><?php echo number_format($pago['monto'], 2); ?> COP</td>
                                <td><?php echo $pago['metodo_pago']; ?></td>
                                <td><?php echo $pago['fecha_venta']; ?></td>
                                <td>
                                    <a href="editarPago.php?id=<?php echo htmlspecialchars($pago['id_pago']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminarPago.php?id=<?php echo $pago['id_pago']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?');">Eliminar</a>

    
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="pago.php" class="btn btn-primary">Agregar Nuevo Pago</a>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#pagosTable').DataTable({
            language: {
                "sEmptyTable": "No hay datos disponibles en la tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing": "Procesando...",
                "sSearch": "Buscar:",
                "sZeroRecords": "No se encontraron resultados",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });
    });

    // Si hay un mensaje, ocúltalo después de 5 segundos
    window.onload = function() {
        var mensaje = document.getElementById('mensaje');
        if (mensaje) {
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 5000); // 5000 milisegundos = 5 segundos
        }
    }
</script>

</body>
</html>

<?php
mysqli_close($conectar);
?>
