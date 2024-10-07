<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Redirige al login si no es administrador
    header("Location: ../index.php");
    exit();
}

require '../conexion.php';

$id_pedido = $_GET['id_detalles_pedido'];

$sql = "SELECT 
            u.nombre AS cliente,
            DATE(p.fecha) AS fecha,
            p.situacion,
            SUM(dp.unidades) AS total_unidades,
            SUM(dp.precio_total) AS total_precio
        FROM 
            pedido p
        INNER JOIN 
            detalles_pedido dp ON p.id_pedido = dp.fk_id_pedido
        INNER JOIN 
            usuario u ON p.fk_id_usuario = u.id_usuario
        WHERE 
            p.id_pedido = '$id_pedido'
        GROUP BY 
            u.nombre, DATE(p.fecha), p.situacion";

$result = mysqli_query($conectar, $sql);
$pedido = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $situacion = $_POST['situacion'];
    $unidades = $_POST['unidades'];
    $precio_total = $_POST['precio_total'];

    // Actualiza los datos del pedido
    $updateSql = "UPDATE detalles_pedido dp
                  INNER JOIN pedido p ON dp.fk_id_pedido = p.id_pedido
                  SET dp.unidades = '$unidades', dp.precio_total = '$precio_total', p.situacion = '$situacion'
                  WHERE p.id_pedido = '$id_pedido'";

    if (mysqli_query($conectar, $updateSql)) {
        // Si la situación del pedido se cambia a "Entregado", agrega una venta
        if ($situacion === "Entregado") {
            $fecha_venta = date('Y-m-d H:i:s'); // Obtiene la fecha y hora actuales
            $insertVentaSql = "INSERT INTO gestion_ventas (fecha_venta, id_pedido) VALUES ('$fecha_venta', '$id_pedido')";
            mysqli_query($conectar, $insertVentaSql);
        }

        echo "<script>alert('Pedido actualizado correctamente.'); window.location.href = 'validarpedido.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el pedido.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido - Bling Compra</title>
    <!-- CSS de Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body.dark-mode .dataTables_wrapper .dataTables_filter label {
            color: #ffffff; /* Color del texto en la etiqueta de búsqueda */
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: black; /* Texto negro */
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: black; /* Texto negro */
        }
        .form-container .form-label {
            font-weight: bold;
            color: black; /* Texto negro */
        }
        .form-container .form-control {
            border-radius: 5px;
            color: black; /* Texto negro */
        }
        .form-container .btn-container {
            display: flex;
            justify-content: space-between;
        }
        .form-container .btn {
            border-radius: 5px;
            color: black; /* Texto negro */
        }
        .form-container .mb-3 {
            margin-bottom: 1.5rem;
            color: black; /* Texto negro */
        }
        .form-container .toggle-btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black; /* Texto negro */
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
                            <a class="nav-link active" href="./validarpedido.php">
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="form-container mt-5 pt-5">
                    <h1 class="mt-4">Editar Pedido</h1>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" value="<?php echo htmlspecialchars($pedido['cliente']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="text" class="form-control" id="fecha" value="<?php echo htmlspecialchars($pedido['fecha']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="situacion" class="form-label">Situación</label>
                            <select class="form-select" id="situacion" name="situacion">
                                <option value="En proceso" <?php echo ($pedido['situacion'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                                <option value="Entregado" <?php echo ($pedido['situacion'] == 'Entregado') ? 'selected' : ''; ?>>Entregado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="unidades" class="form-label">Unidades</label>
                            <input type="number" class="form-control" id="unidades" name="unidades" value="<?php echo htmlspecialchars($pedido['total_unidades']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio_total" class="form-label">Precio Total</label>
                            <input type="number" class="form-control" id="precio_total" name="precio_total" value="<?php echo htmlspecialchars($pedido['total_precio']); ?>" required>
                        </div>
                        <div class="btn-container">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="validarpedido.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.6.0/js/searchBuilder.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>
