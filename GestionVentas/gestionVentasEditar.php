<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}

require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gestion_venta'])) {
    $id_gestion_venta = $_POST['id_gestion_venta'];
    $fecha_venta = $_POST['fecha_venta'];
    $fecha_registro = $_POST['fecha_registro'];

    // Actualizar los datos en la base de datos
    $sql_update = "UPDATE gestion_ventas SET fecha_venta = '$fecha_venta', fecha_registro = '$fecha_registro' WHERE id_gestion_venta = '$id_gestion_venta'";

    if (mysqli_query($conectar, $sql_update)) {
        echo "<script>alert('Gestión de Venta actualizada correctamente');</script>";
        echo "<script>window.location.href = 'gestionVentasLista.php';</script>";
    } else {
        echo "Error al actualizar la gestión de venta: " . mysqli_error($conectar);
    }
}

$id_gestion_venta = $_GET['id'] ?? null;
if ($id_gestion_venta) {
    $sql_select = "SELECT * FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
    $resultado = mysqli_query($conectar, $sql_select);
    $venta = mysqli_fetch_assoc($resultado);
} else {
    echo "ID de gestión de venta no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       .form-container {
    max-width: 600px;
    margin: 50px auto 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.form-container h1 {
    margin-bottom: 20px;
    font-size: 1.75rem;
    font-weight: 500;
}
.form-container .form-label {
    font-weight: 500;
}
.form-container .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}
.form-container .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
.form-container .btn-light {
    background-color: #f8f9fa;
    border-color: #ced4da;
}
.form-container .btn-light:hover {
    background-color: #e2e6ea;
    border-color: #dae0e5;
}

/* Estilos para modo oscuro */
body.dark-mode .form-container {
    background-color: #343a40;
    color: #fff; /* Texto negro en modo oscuro */
}
body.dark-mode .form-container .form-label {
    color: #fff; /* Texto negro en modo oscuro */
}
body.dark-mode .form-container .form-control {
    background-color: #495057;
    color: #fff; /* Texto negro en modo oscuro */
    border-color: #6c757d;
}
body.dark-mode .form-container .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}
body.dark-mode .form-container .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
body.dark-mode .form-container .btn-light {
    background-color: #6c757d;
    border-color: #ced4da;
}
body.dark-mode .form-container .btn-light:hover {
    background-color: #5a6268;
    border-color: #dae0e5;
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="form-container mt-5 pt-5">
                    <h1 class="h2">Editar Venta</h1>
                    <form method="POST" action="" onsubmit="return validateDates();">
                        <input type="hidden" name="id_gestion_venta" value="<?php echo htmlspecialchars($venta['id_gestion_venta']); ?>">
                        <div class="mb-3">
                            <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                            <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" value="<?php echo htmlspecialchars($venta['fecha_venta']); ?>" required>
                        </div>
                     
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a class="btn btn-light" href="gestionVentasLista.php">Cancelar</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        function validateDates() {
            const fechaVenta = new Date(document.getElementById('fecha_venta').value);
            const fechaRegistro = new Date(document.getElementById('fecha_registro').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Asegurarse de que la comparación sea solo de fechas

            if (fechaVenta > today) {
                alert("La fecha de venta no puede ser mayor a la fecha actual.");
                return false;
            }

            if (fechaRegistro < fechaVenta) {
                alert("La fecha de registro no puede ser menor a la fecha de venta.");
                return false;
            }

            return true;
        }

        // Toggle dark mode
        document.getElementById('darkModeToggle').addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
        });
    </script>
        <script src="../script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>