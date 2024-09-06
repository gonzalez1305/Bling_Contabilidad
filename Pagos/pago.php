<?php
require '../conexion.php'; // Conexión a la base de datos

// Inicializa un mensaje de resultado vacío
$resultado = "";

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_gestion_venta = $_POST['id_gestion_venta'];
    $monto = $_POST['monto_pago'];
    $metodo_pago = $_POST['metodo_pago'];
    $fecha_pago = date('Y-m-d');
    $estado = 'pendiente';

    // Inserta el pago en la tabla 'pagos'
    $pagoQuery = "INSERT INTO pagos (id_gestion_venta, fecha_pago, monto, metodo_pago, estado) 
                  VALUES ('$id_gestion_venta', '$fecha_pago', '$monto', '$metodo_pago', '$estado')";

    if (mysqli_query($conectar, $pagoQuery)) {
        session_start();
        $_SESSION['mensaje'] = "El pago ha sido registrado exitosamente.";
        header("Location: pago.php");
        exit();
    } else {
        $resultado = "Error al registrar el pago: " . mysqli_error($conectar);
    }
}

// Consulta para obtener las ventas disponibles para el pago
$ventasQuery = "SELECT gv.id_gestion_venta, dp.precio_total 
                FROM gestion_ventas gv 
                JOIN detalles_pedido dp ON gv.id_detalles_pedido = dp.id_detalles_pedido";
$ventasResult = mysqli_query($conectar, $ventasQuery);

// Verifica si existe un mensaje en la sesión
session_start();
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
    <title>Realizar Pago - Bling Compra</title>
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
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Realizar Pago</h1>

                <!-- Muestra el mensaje de resultado -->
                <?php if ($resultado): ?>
                    <div id="mensaje" class="mensaje-exito">
                        <p><?php echo $resultado; ?></p>
                    </div>
                <?php endif; ?>

                <form action="pago.php" method="post">
                    <div class="mb-3">
                        <label for="id_gestion_venta" class="form-label">Seleccionar Venta:</label>
                        <select name="id_gestion_venta" id="id_gestion_venta" class="form-select" required>
                            <?php while($venta = mysqli_fetch_assoc($ventasResult)): ?>
                                <option value="<?php echo $venta['id_gestion_venta']; ?>">
                                    Venta ID: <?php echo $venta['id_gestion_venta']; ?> - Total: <?php echo number_format($venta['precio_total'], 2); ?> COP
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="monto_pago" class="form-label">Monto del Pago:</label>
                        <input type="number" name="monto_pago" id="monto_pago" class="form-control" required step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                            <option value="Efectivo">Efectivo</option>
                        <select>
                    </div>

                    <button type="submit" class="btn btn-success">Realizar Pago</button>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
