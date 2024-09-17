<?php
require '../conexion.php'; // Conexión a la base de datos

// Consultas para obtener los ID de Detalles Pedido y Vendedores
$query_detalles_pedido = "
    SELECT dp.id_detalles_pedido, dp.precio_total 
    FROM detalles_pedido dp
    JOIN pedido p ON dp.fk_id_pedido = p.id_pedido
    WHERE p.situacion = 'entregado'
";
$result_detalles_pedido = mysqli_query($conectar, $query_detalles_pedido);

$query_vendedores = "SELECT id_vendedor FROM administrador";
$result_vendedores = mysqli_query($conectar, $query_vendedores);

// Verificar si se ha enviado una solicitud de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gestion_venta'])) {
    $id_gestion_venta = $_POST['id_gestion_venta'];
    $id_detalles_pedido = $_POST['id_detalles_pedido'];
    $id_vendedor = $_POST['id_vendedor'];
    $fecha_venta = $_POST['fecha_venta'];
    $fecha_registro = $_POST['fecha_registro'];

    // Validar fechas
    $today = date('Y-m-d');
    if ($fecha_venta > $today || $fecha_registro > $today) {
        echo "<script>alert('Las fechas no pueden ser futuras.'); window.history.back();</script>";
        exit;
    }

    // Actualizar el registro de gestión de ventas
    $sql_update = "UPDATE gestion_ventas 
                   SET id_detalles_pedido='$id_detalles_pedido', id_vendedor='$id_vendedor', fecha_venta='$fecha_venta', fecha_registro='$fecha_registro'
                   WHERE id_gestion_venta='$id_gestion_venta'";

    if (mysqli_query($conectar, $sql_update)) {
        echo "<script>alert('Registro actualizado correctamente'); window.location.href='gestionVentasLista.php';</script>";
    } else {
        echo "Error: " . $sql_update . "<br>" . mysqli_error($conectar);
    }
}

// Obtener el ID de gestión de venta a editar
if (isset($_GET['id'])) {
    $id_gestion_venta = $_GET['id'];
    $sql_select = "
        SELECT gv.id_gestion_venta, gv.id_detalles_pedido, gv.id_vendedor, gv.fecha_venta, gv.fecha_registro,
               dp.precio_total
        FROM gestion_ventas gv
        JOIN detalles_pedido dp ON gv.id_detalles_pedido = dp.id_detalles_pedido
        JOIN pedido p ON dp.fk_id_pedido = p.id_pedido
        WHERE p.situacion = 'entregado' AND gv.id_gestion_venta = '$id_gestion_venta'
    ";
    $result = mysqli_query($conectar, $sql_select);
    $venta = mysqli_fetch_assoc($result);
} else {
    echo "ID de gestión de venta no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta - Bling Compra</title>
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
        .card {
            margin-bottom: 20px;
        }
        .volver-btn {
            margin-top: 20px;
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
                        <a class="nav-link active" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pagos/pago.php">Pagos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Editar Venta</h1>
                <form action="" method="post">
                    <input type="hidden" name="id_gestion_venta" value="<?php echo $venta['id_gestion_venta']; ?>">

                    <div class="mb-3">
                        <label for="id_detalles_pedido" class="form-label">ID Detalles Pedido:</label>
                        <select name="id_detalles_pedido" id="id_detalles_pedido" class="form-select" required>
                            <?php
                            // Volver a cargar las opciones y marcar la opción seleccionadaa
                            while($row = mysqli_fetch_assoc($result_detalles_pedido)) {
                                $selected = ($row['id_detalles_pedido'] == $venta['id_detalles_pedido']) ? 'selected' : '';
                                echo "<option value='" . $row['id_detalles_pedido'] . "' $selected>" . $row['id_detalles_pedido'] . " - Precio Total: " . number_format($row['precio_total'], 2) . " COP</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_vendedor" class="form-label">ID Vendedor:</label>
                        <select name="id_vendedor" id="id_vendedor" class="form-select" required>
                            <?php
                            // Volver a cargar las opciones y marcar la opción seleccionada
                            while($row = mysqli_fetch_assoc($result_vendedores)) {
                                $selected = ($row['id_vendedor'] == $venta['id_vendedor']) ? 'selected' : '';
                                echo "<option value='" . $row['id_vendedor'] . "' $selected>" . $row['id_vendedor'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" value="<?php echo $venta['fecha_venta']; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro:</label>
                        <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" value="<?php echo $venta['fecha_registro']; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Registro</button>
                    <a href="gestionVentasLista.php" class="btn btn-secondary volver-btn">Volver</a>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>