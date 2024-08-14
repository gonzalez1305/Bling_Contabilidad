<?php
require '../conexion.php'; // Conexión

$estado = '';
$fecha_venta = '';
$cantidad = '';
$precio_unitario = '';
$id_vendedor = ''; 
$id_venta = ''; 
$id_producto = ''; 
$comprobante = ''; 

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera y limpia los datos del formulario
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $fecha_venta = mysqli_real_escape_string($conectar, $_POST['fecha_venta']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);
    $id_venta = mysqli_real_escape_string($conectar, $_POST['id_venta']);
    $id_producto = mysqli_real_escape_string($conectar, $_POST['id_producto']);
    $comprobante = mysqli_real_escape_string($conectar, $_POST['comprobante']);

    // Verificar si el id_vendedor existe en la tabla administrador
    $query = "SELECT id_vendedor FROM administrador WHERE id_vendedor = '$id_vendedor'";
    $result = mysqli_query($conectar, $query);
    if (mysqli_num_rows($result) == 0) {
        echo "El ID del vendedor no existe.";
        exit;
    }

    $fecha_registro = date('Y-m-d'); 
    $fecha_actualizacion = date('Y-m-d'); 

    // Subir imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen']['name'];
        $imagen_temporal = $_FILES['imagen']['tmp_name'];
        $ruta_imagen = "ruta/donde/guardar/imagenes/" . $imagen;

        // Validar el tamaño y tipo de archivo
        if ($_FILES['imagen']['size'] > 500000) { // Limitar el tamaño a 500 KB
            echo "El archivo es demasiado grande.";
            exit;
        }
        $imagen_tipo = strtolower(pathinfo($ruta_imagen, PATHINFO_EXTENSION));
        $tipos_permitidos = array("jpg", "jpeg", "png");
        if (!in_array($imagen_tipo, $tipos_permitidos)) {
            echo "Solo se permiten archivos JPG, JPEG y PNG.";
            exit;
        }

        // Mover el archivo a la ruta deseada
        move_uploaded_file($imagen_temporal, $ruta_imagen);
    } else {
        $ruta_imagen = ''; // No se subió ninguna imagen
    }

    // Insertar datos en la base de datos
    $stmt = $conectar->prepare("INSERT INTO gestion_ventas 
        (id_venta, id_vendedor, id_producto, cantidad, precio_unitario, fecha_venta, estado, comprobante, fecha_registro, fecha_actualizacion, imagen) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiddssssss", $id_venta, $id_vendedor, $id_producto, $cantidad, $precio_unitario, $fecha_venta, $estado, $comprobante, $fecha_registro, $fecha_actualizacion, $ruta_imagen);
    
    if ($stmt->execute()) {
        echo "<script>alert('Venta registrada correctamente');</script>";
        echo "<script>window.location.href = 'gestionVlista.php';</script>";
        exit;
    } else {
        echo "Error al registrar la venta: " . $stmt->error;
    }
    
    $stmt->close();
}

mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Venta - Bling Compra</title>
  <title>Listado de Ventas - Bling Compra</title>
  <link rel="icon" href="../imgs/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body {
          background-color: #f8f9fa;
          font-family: Arial, sans-serif;
          margin: 0;
          display: flex;
          flex-direction: column;
          height: 100vh;
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
      .container-fluid {
          flex: 1;
          display: flex;
          padding: 0;
      }
      .row {
          flex: 1;
          display: flex;
          margin: 0;
      }
      .sidebar {
          height: 100%;
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
          flex: 1;
          padding: 20px;
          overflow-y: auto;
      }
      .form-control {
          margin-bottom: 15px;
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
                        <a class="nav-link active" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Agregar Nueva Venta</h1>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <input type="text" class="form-control" id="estado" name="estado" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label">Fecha de Venta:</label>
                        <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" required>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                    </div>

                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario:</label>
                        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_vendedor" class="form-label">ID del Vendedor:</label>
                        <input type="number" class="form-control" id="id_vendedor" name="id_vendedor" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_venta" class="form-label">ID de la Venta:</label>
                        <input type="number" class="form-control" id="id_venta" name="id_venta" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_producto" class="form-label">ID del Producto:</label>
                        <input type="number" class="form-control" id="id_producto" name="id_producto" required>
                    </div>

                    <div class="mb-3">
                        <label for="comprobante" class="form-label">Comprobante:</label>
                        <input type="text" class="form-control" id="comprobante" name="comprobante">
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Venta</button>
                </form>

                <a href="./gestionVlista.php" class="volver-btn">Volver al Listado de Ventas</a>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
