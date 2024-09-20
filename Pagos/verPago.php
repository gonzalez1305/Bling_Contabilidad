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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        .mensaje-exito {
            color: green;
            background-color: #d4edda;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .dark-mode .table-striped tbody tr {
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../menuV.php">
                <img src="../imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                Bling Compra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button id="darkModeToggle" class="btn btn-outline-light toggle-btn">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../Usuario/validarusuario.php">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">
                                <i class="fas fa-chart-line"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Inventario/listaInventario.php">
                                <i class="fas fa-box"></i> Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pedido/validarpedido.php">
                                <i class="fas fa-clipboard-list"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Listado de Pagos</h1>
                </div>
                <div class="container">
                    <a class="btn btn-success mb-3" href="../reportePago.php" role="button">Reporte Pago</a>
                    <a class="btn btn-success mb-3" href="../reporteGraficoPago.html" role="button">Reporte Gráfico Pago</a>

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

    <a href="../manualdeusuariov.php" class="manual-link">Manual de Usuario</a>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="../script.js"></script>
    <script>
        $(document).ready(function() {
            $('#pagosTable').DataTable({
                language: {
                    "sEmptyTable": "No hay datos disponibles en la tabla",
                    "sInfo": "Mostrando START a END de TOTAL entradas",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "sInfoFiltered": "(filtrado de MAX entradas totales)",
                    "sLengthMenu": "Mostrar MENU entradas",
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

            // Alternar modo oscuro
            $('#darkModeToggle').on('click', function() {
                $('body').toggleClass('dark-mode');
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