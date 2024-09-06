<?php
require '../conexion.php'; // Conexión

$talla = '';
$color = '';
$cantidad = '';
$nombre = '';
$estado = '';
$categorias = '';
$precio_unitario = '';
$imagen_ruta = '';

// Verifica y crea la carpeta uploads si no existe
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Obtener las marcas desde la base de datos
$sql_marcas = "SELECT id_marca, nombre_marca FROM marca";
$resultado_marcas = mysqli_query($conectar, $sql_marcas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $talla = mysqli_real_escape_string($conectar, $_POST['talla']);
    $color = mysqli_real_escape_string($conectar, $_POST['color']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $nombre = mysqli_real_escape_string($conectar, $_POST['nombre']);
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $categorias = mysqli_real_escape_string($conectar, $_POST['categorias']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $fk_id_marca = mysqli_real_escape_string($conectar, $_POST['marca']); // Captura de la marca seleccionada
    
    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_ruta = 'uploads/' . basename($imagen_nombre);
        
        if (move_uploaded_file($imagen_tmp, $imagen_ruta)) {
            // Imagen subida correctamente
        } else {
            echo "Error al subir la imagen. Verifica que la carpeta exista y tenga permisos adecuados.";
            exit;
        }
    }

    // Inserción de los datos en la tabla producto
    $sql_insert_producto = "INSERT INTO producto (talla, color, cantidad, nombre, fk_id_marca, estado, categorias, precio_unitario, imagen)
                            VALUES ('$talla', '$color', '$cantidad', '$nombre', '$fk_id_marca', '$estado', '$categorias', '$precio_unitario', '$imagen_ruta')";

    if (mysqli_query($conectar, $sql_insert_producto)) {
        // Obtener el ID del nuevo producto
        $id_producto = mysqli_insert_id($conectar);

        // Insertar en la tabla intermedia marca_producto
        $sql_insert_marca_producto = "INSERT INTO marca_producto (fk_id_producto, fk_id_marca)
                                      VALUES ('$id_producto', '$fk_id_marca')";

        if (mysqli_query($conectar, $sql_insert_marca_producto)) {
            echo "<script>alert('Inventario registrado correctamente');</script>";
            echo "<script>window.location.href = 'listaInventario.php';</script>";
            exit;
        } else {
            echo "Error al registrar la relación marca-producto: " . mysqli_error($conectar);
        }
    } else {
        echo "Error al registrar el inventario: " . mysqli_error($conectar);
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../imgs/logo.png">
  <title>Crear Inventario - Bling Compra</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="../Pagos/pago.php">Pagos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <h1 class="h2">Agregar Nuevo Producto</h1>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="talla" class="form-label">Talla:</label>
                    <input type="text" id="talla" name="talla" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color:</label>
                    <input type="text" id="color" name="color" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <input type="text" id="estado" name="estado" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="categorias" class="form-label">Categorias:</label>
                    <input type="text" id="categorias" name="categorias" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca:</label>
                    <select id="marca" name="marca" class="form-control" required>
                        <option value="">Seleccione una marca</option>
                        <?php while ($fila_marca = mysqli_fetch_assoc($resultado_marcas)) { ?>
                            <option value="<?php echo $fila_marca['id_marca']; ?>">
                                <?php echo $fila_marca['nombre_marca']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="precio_unitario" class="form-label">Precio Unitario:</label>
                    <input type="text" id="precio_unitario" name="precio_unitario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Registrar Producto</button>
            </form>
            <div class="volver-btn">
                <a href="listaInventario.php" class="btn btn-secondary">Volver a Inventario</a>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
