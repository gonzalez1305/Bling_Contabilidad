<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}
require '../conexion.php';

// Verificar si se recibió el ID del pago a editar
if (isset($_GET['id'])) {
    $id_pago = intval($_GET['id']);
    $pagoQuery = "SELECT fecha_pago, monto FROM pagos WHERE id_pago = $id_pago";
    $pagoResult = mysqli_query($conectar, $pagoQuery);

    if (mysqli_num_rows($pagoResult) == 1) {
        $pago = mysqli_fetch_assoc($pagoResult);
        $fecha_pago = $pago['fecha_pago'];
        $monto = $pago['monto'];
    } else {
        echo "<script>alert('Pago no encontrado'); window.location.href='verPago.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de pago no proporcionado'); window.location.href='verPago.php';</script>";
    exit();
}

// Actualizar el pago si se envió el formulario
if (isset($_POST['actualizar'])) {
    $nueva_fecha_pago = mysqli_real_escape_string($conectar, $_POST['fecha_pago']);
    $nuevo_monto = floatval($_POST['monto']);

    if (!empty($nueva_fecha_pago) && $nuevo_monto > 0) {
        $updateQuery = "UPDATE pagos SET fecha_pago = '$nueva_fecha_pago', monto = $nuevo_monto WHERE id_pago = $id_pago";
        if (mysqli_query($conectar, $updateQuery)) {
            echo "<script>alert('Pago actualizado exitosamente'); window.location.href='verPago.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el pago');</script>";
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago - Bling Compra</title>
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
                    <h1 class="h2">Editar Pago</h1>
                </div>

                <div class="btn-back">
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>

                <div class="table-responsive">
                    <div class="form-container">
                        <form action="editarPago.php?id=<?php echo $id_pago; ?>" method="POST">
                            <div class="mb-3">
                                <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="<?php echo htmlspecialchars($fecha_pago); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="number" step="0.01" class="form-control" id="monto" name="monto" value="<?php echo htmlspecialchars($monto); ?>" required>
                            </div>
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                            <a href="verPago.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>

