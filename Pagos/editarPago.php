<?php
require '../conexion.php'; // Conexión a la base de datos

// Verificar si se recibió el ID del pago a editar
if (isset($_GET['id'])) {
    $id_pago = intval($_GET['id']);

    // Obtener la información actual del pago
    $pagoQuery = "SELECT fecha_pago, monto FROM pagos WHERE id_pago = $id_pago";
    $pagoResult = mysqli_query($conectar, $pagoQuery);

    // Verificar si el pago existe
    if (mysqli_num_rows($pagoResult) == 1) {
        $pago = mysqli_fetch_assoc($pagoResult);
        $fecha_pago = $pago['fecha_pago'];
        $monto = $pago['monto'];
    } else {
        $_SESSION['mensaje'] = "Pago no encontrado";
        header("Location: verPago.php");
        exit();
    }
} else {
    $_SESSION['mensaje'] = "ID de pago no proporcionado";
    header("Location: verPago.php");
    exit();
}

// Actualizar el pago si se envió el formulario
if (isset($_POST['actualizar'])) {
    $nueva_fecha_pago = mysqli_real_escape_string($conectar, $_POST['fecha_pago']);
    $nuevo_monto = floatval($_POST['monto']);

    // Validar los datos
    if (!empty($nueva_fecha_pago) && $nuevo_monto > 0) {
        // Actualizar los datos en la base de datos
        $updateQuery = "UPDATE pagos SET fecha_pago = '$nueva_fecha_pago', monto = $nuevo_monto WHERE id_pago = $id_pago";
        if (mysqli_query($conectar, $updateQuery)) {
            $_SESSION['mensaje'] = "Pago actualizado exitosamente";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el pago";
        }
    } else {
        $_SESSION['mensaje'] = "Por favor, complete todos los campos correctamente";
    }

    // Redirigir de vuelta a la lista de pagos
    header("Location: verPago.php");
    exit();
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>
<body>













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
                            <a class="nav-link active" href="./pago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>