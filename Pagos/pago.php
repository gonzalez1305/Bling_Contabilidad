<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}

require '../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_gestion_venta = $_POST['id_gestion_venta'];
    $fecha_pago = $_POST['fecha_pago'];
    $monto = $_POST['monto'];

    $sql_insert_pago = "INSERT INTO pagos (id_gestion_venta, fecha_pago, monto) VALUES ('$id_gestion_venta', '$fecha_pago', '$monto')";

    if (mysqli_query($conectar, $sql_insert_pago)) {
        $_SESSION['mensaje'] = "Pago registrado correctamente";
        header("Location: verPago.php");
        exit();
    } else {
        echo "Error al registrar el pago: " . mysqli_error($conectar);
    }
}

// Obtener las ventas para el desplegable
$sql_select_ventas = "
    SELECT gv.id_gestion_venta, gv.fecha_venta, SUM(dp.precio_total) as precio_total
    FROM gestion_ventas gv
    JOIN detalles_pedido dp ON gv.id_pedido = dp.fk_id_pedido
    JOIN pedido p ON gv.id_pedido = p.id_pedido
    WHERE p.situacion = 'entregado'
    GROUP BY gv.id_gestion_venta, gv.fecha_venta
";
$resultado_ventas = mysqli_query($conectar, $sql_select_ventas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pago - Bling Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
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
                        <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="h2">Registrar Nuevo Pago</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="id_gestion_venta" class="form-label">Venta</label>
                <select class="form-select" id="id_gestion_venta" name="id_gestion_venta" required onchange="updateMonto()">
                    <option value="" selected disabled>Seleccione una venta</option>
                    <?php while ($venta = mysqli_fetch_assoc($resultado_ventas)): ?>
                        <option value="<?php echo $venta['id_gestion_venta']; ?>" data-monto="<?php echo $venta['precio_total']; ?>">
                            <?php echo "Venta ID: " . $venta['id_gestion_venta'] . " - Fecha: " . $venta['fecha_venta']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
            </div>
            <div class="mb-3">
                <label for="monto" class="form-label">Monto</label>
                <input type="number" step="0.01" class="form-control" id="monto" name="monto" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Pago</button>
            <a class="btn btn-secondary" href="verPago.php">Cancelar</a>
        </form>
    </div>

    <script>
        function updateMonto() {
            var select = document.getElementById('id_gestion_venta');
            var monto = select.options[select.selectedIndex].getAttribute('data-monto');
            document.getElementById('monto').value = monto;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conectar);
?>