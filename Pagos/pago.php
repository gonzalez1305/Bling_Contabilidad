<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
?>
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

    // Inserta el pago en la tabla 'pagos'
    $pagoQuery = "INSERT INTO pagos (id_gestion_venta, fecha_pago, monto, metodo_pago) 
                  VALUES ('$id_gestion_venta', '$fecha_pago', '$monto', '$metodo_pago')";

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


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
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
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Realizar Pago</h1>
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>

                <!-- Muestra el mensaje de resultado -->
                <?php if ($resultado): ?>
                    <div id="mensaje" class="alert alert-success">
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
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Realizar Pago</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
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