<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
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
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link active" href="./verPago.php">Pagos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../Marca/listaMarcas.php">Marca</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Editar Pago</h1>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
