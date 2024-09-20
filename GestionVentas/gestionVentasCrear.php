<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}

require '../conexion.php'; // Conexión a la base de datos

// Consultas para obtener los ID de Detalles Pedido con precio_total solo si el pedido está en situación "entregado"
$query_detalles_pedido = "
    SELECT dp.id_detalles_pedido, dp.precio_total 
    FROM detalles_pedido dp
    JOIN pedido p ON dp.fk_id_pedido = p.id_pedido
    WHERE p.situacion = 'entregado'
";
$result_detalles_pedido = mysqli_query($conectar, $query_detalles_pedido);

// Consulta para obtener los Vendedores
$query_vendedores = "SELECT id_vendedor FROM administrador";
$result_vendedores = mysqli_query($conectar, $query_vendedores);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y validar los datos del formularios
    $id_detalles_pedido = $_POST['id_detalles_pedido'];
    
    $fecha_venta = $_POST['fecha_venta'];
    $fecha_registro = $_POST['fecha_registro'];

    // Validar fechas
    $fecha_actual = date('Y-m-d');
    if ($fecha_venta > $fecha_actual) {
        echo "<script>alert('La fecha de venta no puede ser mayor a la fecha actual.');</script>";
    } elseif ($fecha_registro > $fecha_actual) {
        echo "<script>alert('La fecha de registro no puede ser mayor a la fecha actual.');</script>";
    } elseif ($fecha_registro <= $fecha_venta) {
        echo "<script>alert('La fecha de registro debe ser mayor que la fecha de venta.');</script>";
    } else {
        // Crear el SQL para insertar el nuevo registro
        $sql = "INSERT INTO gestion_ventas (id_detalles_pedido, fecha_venta, fecha_registro)
                VALUES ('$id_detalles_pedido', '$fecha_venta', '$fecha_registro')";

        if (mysqli_query($conectar, $sql)) {
            echo "<script>alert('Nuevo registro creado exitosamente'); window.location.href='gestionVentasLista.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conectar);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                            <a class="nav-link active" href="./gestionVentasLista.php">
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
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca</a>
                        </li>
                    </ul>
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
                        <a class="nav-link active" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pagos/verPago.php">Pagos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Marca/listaMarcas.php">Marca</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Crear Nueva Venta</h1>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="id_detalles_pedido" class="form-label">ID Detalles Pedido:</label>
                        <select name="id_detalles_pedido" id="id_detalles_pedido" class="form-select" required>
                            <?php while($row = mysqli_fetch_assoc($result_detalles_pedido)): ?>
                                <option value="<?php echo $row['id_detalles_pedido']; ?>" data-price="<?php echo number_format($row['precio_total'], 2); ?>">
                                    <?php echo $row['id_detalles_pedido']; ?> - Precio Total: <?php echo number_format($row['precio_total'], 2); ?> COP
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio_total" class="form-label">Precio Total:</label>
                        <div id="precio_total" class="form-control" readonly>
                            Seleccione un ID Detalles Pedido
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro:</label>
                        <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Crear Registro</button>
                    <a href="gestionVentasLista.php" class="btn btn-secondary volver-btn">Volver al Listado</a>
                </form>
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

<?php
mysqli_close($conectar);
?>
