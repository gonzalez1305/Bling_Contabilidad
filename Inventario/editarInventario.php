<?php
require '../conexion.php'; // Conexion

$id_inventario = '';
$cantidad = '';
$fecha = '';
$cantidad_disponible = '';
$referencia = '';
$id_vendedor = ''; 

// Verifica si se recibió un id para la edicion
if (isset($_GET['id'])) {
    $id_inventario = $_GET['id'];

    // Consulta del inventario
    $sql_select = "SELECT * FROM inventario WHERE id_inventario = '$id_inventario'";
    $resultado = mysqli_query($conectar, $sql_select);

    if (mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);
        $cantidad = $fila['cantidad'];
        $fecha = $fila['fecha'];
        $cantidad_disponible = $fila['cantidad_disponible'];
        $referencia = $fila['referencia'];
        $id_vendedor = $fila['id_vendedor'];
    } else {
        echo "No se encontró el inventario especificado.";
        exit;
    }
}

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_inventario = mysqli_real_escape_string($conectar, $_POST['id_inventario']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $fecha = mysqli_real_escape_string($conectar, $_POST['fecha']);
    $cantidad_disponible = mysqli_real_escape_string($conectar, $_POST['cantidad_disponible']);
    $referencia = mysqli_real_escape_string($conectar, $_POST['referencia']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);

    // Actualizar los datos 
    $sql_update = "UPDATE inventario SET
                   cantidad = '$cantidad',
                   fecha = '$fecha',
                   cantidad_disponible = '$cantidad_disponible',
                   referencia = '$referencia',
                   id_vendedor = '$id_vendedor'
                   WHERE id_inventario = '$id_inventario'";

    if (mysqli_query($conectar, $sql_update)) {
        
        echo "<script>alert('Inventario actualizado correctamente');</script>";
        echo "<script>window.location.href = 'listaInventario.php';</script>";
        exit;
    } else {
        
        echo "Error al actualizar el inventario: " . mysqli_error($conectar);
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Inventario - Bling Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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
        margin-bottom: 20px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Bling Compra</a>
        <link rel="icon" href="../imgs/logo.png">
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
                        <a class="nav-link" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <h1 class="h2">Editar Inventario</h1>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id_inventario" value="<?php echo $id_inventario; ?>">

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" value="<?php echo $cantidad; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $fecha; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad_disponible" class="form-label">Cantidad Disponible:</label>
                    <input type="number" id="cantidad_disponible" name="cantidad_disponible" class="form-control" value="<?php echo $cantidad_disponible; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referencia:</label>
                    <input type="text" id="referencia" name="referencia" class="form-control" value="<?php echo $referencia; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="id_vendedor" class="form-label">ID del Vendedor:</label>
                    <input type="number" id="id_vendedor" name="id_vendedor" class="form-control" value="<?php echo $id_vendedor; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Inventario</button>
            </form>

            <div class="mt-4">
                <a href="../Inventario/listaInventario.php" class="btn btn-secondary">Volver al Listado de Inventario</a>

            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>