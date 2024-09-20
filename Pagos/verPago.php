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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-image img {
            max-width: 50px;
            max-height: 50px;
            width: auto;
            height: auto;
            border-radius: 50%;
            object-fit: cover;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
        }
        .table tbody td {
            color: black;
        }
        .btn-back {
            margin-bottom: 10px;
        }
        .dataTables_length label,
        .dataTables_info {
            color: black !important;
        }
        .dataTables_filter label {
            color: black !important;
        }
        body.dark-mode .dataTables_length label,
        body.dark-mode .dataTables_info,
        body.dark-mode .dataTables_filter label {
            color: white !important;
        }
        .modal-content {
            color: black;
        }
        body.dark-mode .modal-content {
            color: white;
        }
        .modal-body {
            color: black;
        }
        body.dark-mode .modal-body {
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
                            <a class="nav-link active" href="../Usuario/validarusuario.php">
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
                            <a class="nav-link" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Listado de Pagos</h1>
                </div>

                <div class="btn-back">
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>
                <a class="btn btn-success" href="../reportePago.php" role="button">Reporte Pago</a>
                <a class="btn btn-success" href="../reporteGraficoPago.html" role="button">Reporte Gráfico Pago</a>
                <a class="btn btn-success" href="pago.php" role="button">Nuevo pago</a>


                <?php if ($resultado): ?>
                    <div id="mensaje" class="mensaje-exito">
                        <p><?php echo $resultado; ?></p>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="pagosTable" class="display table table-striped table-bordered">
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
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
    <script>
        $(document).ready(function() {
            $('#pagosTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                }
            });
        });

        window.onload = function() {
            var mensaje = document.getElementById('mensaje');
            if (mensaje) {
                setTimeout(function() {
                    mensaje.style.display = 'none';
                }, 5000);
            }
        }
    </script>
</body>
</html>

<?php
mysqli_close($conectar);
?>