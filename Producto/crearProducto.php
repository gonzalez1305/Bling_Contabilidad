<?php
require '../conexion.php'; 

// Inicialización de variables
$id_producto = '';
$talla = '';
$color = '';
$cantidad = '';
$descripcion = '';
$nombre = '';
$estado = '';
$categorias = '';
$precio_unitario = '';
$fk_id_inventario = '';
$fk_id_venta = '';
$fk_id_pedido = '';
$imagen = ''; 

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $id_producto = mysqli_real_escape_string($conectar, $_POST['id_producto']);
    $talla = mysqli_real_escape_string($conectar, $_POST['talla']);
    $color = mysqli_real_escape_string($conectar, $_POST['color']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $descripcion = mysqli_real_escape_string($conectar, $_POST['descripcion']);
    $nombre = mysqli_real_escape_string($conectar, $_POST['nombre']);
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $categorias = mysqli_real_escape_string($conectar, $_POST['categorias']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $fk_id_inventario = mysqli_real_escape_string($conectar, $_POST['fk_id_inventario']);
    $fk_id_venta = mysqli_real_escape_string($conectar, $_POST['fk_id_venta']);
    $fk_id_pedido = mysqli_real_escape_string($conectar, $_POST['fk_id_pedido']);

    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_path = 'ruta/donde/guardar/imagenes/' . $imagen_nombre;

        // Mover archivo al directorio de imágenes
        move_uploaded_file($imagen_tmp, $imagen_path);
    } else {
        $imagen_path = ''; // No se subió ninguna imagen
    }

    // Insertar o actualizar el producto en la base de datos
    if (isset($_POST['editar']) && $_POST['editar'] == 'true') {
        // Actualizar producto
        $id_producto_edit = mysqli_real_escape_string($conectar, $_POST['id_producto_edit']);
        $sql_update = "UPDATE producto SET 
                       talla = '$talla', 
                       color = '$color', 
                       cantidad = '$cantidad', 
                       descripcion = '$descripcion', 
                       nombre = '$nombre', 
                       estado = '$estado', 
                       categorias = '$categorias', 
                       precio_unitario = '$precio_unitario', 
                       fk_id_inventario = '$fk_id_inventario', 
                       fk_id_venta = '$fk_id_venta', 
                       fk_id_pedido = '$fk_id_pedido', 
                       imagen = '$imagen_path' 
                       WHERE id_producto = '$id_producto_edit'";

        if (mysqli_query($conectar, $sql_update)) {
            echo "<script>alert('Producto actualizado correctamente');</script>";
            echo "<script>window.location.href = 'productosLista.php';</script>";
            exit;
        } else {
            echo "Error al actualizar el producto: " . mysqli_error($conectar);
        }
    } else {
        // Insertar nuevo producto
        $sql_insert = "INSERT INTO producto 
                       (id_producto, talla, color, cantidad, descripcion, nombre, estado, categorias, precio_unitario, fk_id_inventario, fk_id_venta, fk_id_pedido, imagen) 
                       VALUES ('$id_producto', '$talla', '$color', '$cantidad', '$descripcion', '$nombre', '$estado', '$categorias', '$precio_unitario', '$fk_id_inventario', '$fk_id_venta', '$fk_id_pedido', '$imagen_path')";

        if (mysqli_query($conectar, $sql_insert)) {
            echo "<script>alert('Producto registrado correctamente');</script>";
            echo "<script>window.location.href = 'productosLista.php';</script>";
            exit;
        } else {
            echo "Error al registrar el producto: " . mysqli_error($conectar);
        }
    }
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    // Si se va a editar un producto, recuperar los datos
    $id_producto = mysqli_real_escape_string($conectar, $_GET['id']);
    $sql_select = "SELECT * FROM producto WHERE id_producto = '$id_producto'";
    $resultado = mysqli_query($conectar, $sql_select);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        // Asignar los valores de la consulta a las variables del formulario
        $talla = $fila['talla'];
        $color = $fila['color'];
        $cantidad = $fila['cantidad'];
        $descripcion = $fila['descripcion'];
        $nombre = $fila['nombre'];
        $estado = $fila['estado'];
        $categorias = $fila['categorias'];
        $precio_unitario = $fila['precio_unitario'];
        $fk_id_inventario = $fila['fk_id_inventario'];
        $fk_id_venta = $fila['fk_id_venta'];
        $fk_id_pedido = $fila['fk_id_pedido'];
        $imagen = $fila['imagen']; // Obtener la imagen actual
    } else {
        echo "Error al consultar la base de datos: " . mysqli_error($conectar);
    }
}

// Cerrar conexión
mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear/Editar Producto - Bling Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="../imgs/logo.png">
  <link rel="stylesheet" href="css/style.css">
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
          padding: 20px;
      }
      .card {
          margin-bottom: 20px;
      }
      .container {
          max-width: 600px;
          margin: 50px auto;
          padding: 20px;
          background-color: #ffffff;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h1 {
          text-align: center;
          margin-bottom: 20px;
      }
      form {
          display: flex;
          flex-direction: column;
      }
      label {
          margin-bottom: 5px;
          font-weight: bold;
      }
      input[type="text"],
      input[type="date"],
      input[type="number"],
      input[type="submit"],
      input[type="file"] {
          padding: 10px;
          margin-bottom: 15px;
          border: 1px solid #dee2e6;
          border-radius: 5px;
          font-size: 16px;
      }
      input[type="submit"] {
          background-color: #007bff;
          color: #ffffff;
          cursor: pointer;
      }
      input[type="submit"]:hover {
          background-color: #0056b3;
      }
      .volver-btn {
          display: inline-block;
          padding: 10px 20px;
          margin-top: 10px;
          font-size: 16px;
          color: #ffffff;
          background-color: #007bff;
          border: none;
          border-radius: 5px;
          text-align: center;
          cursor: pointer;
          text-decoration: none;
      }
      .volver-btn:hover {
          background-color: #0056b3;
      }
      .img-thumbnail {
          max-width: 150px;
          max-height: 150px;
          object-fit: cover;
          display: block;
          margin-bottom: 10px;
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
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <h1 class="h2"><?php echo isset($_GET['id']) ? 'Editar Producto' : 'Crear Producto'; ?></h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <?php if (isset($_GET['id'])): ?>
                    <input type="hidden" name="editar" value="true">
                    <input type="hidden" name="id_producto_edit" value="<?php echo $id_producto; ?>">
                <?php endif; ?>

                <!-- Campos del formulario con valores prellenados -->
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">

                <label for="talla">Talla:</label>
                <input type="text" id="talla" name="talla" value="<?php echo $talla; ?>" required>

                <label for="color">Color:</label>
                <input type="text" id="color" name="color" value="<?php echo $color; ?>" required>

                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>" required>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo $estado; ?>" required>

                <label for="categorias">Categorías:</label>
                <input type="text" id="categorias" name="categorias" value="<?php echo $categorias; ?>" required>

                <label for="precio_unitario">Precio Unitario:</label>
                <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" value="<?php echo $precio_unitario; ?>" required>

                <label for="fk_id_inventario">ID Inventario:</label>
                <input type="text" id="fk_id_inventario" name="fk_id_inventario" value="<?php echo $fk_id_inventario; ?>" required>

                <label for="fk_id_venta">ID Venta:</label>
                <input type="text" id="fk_id_venta" name="fk_id_venta" value="<?php echo $fk_id_venta; ?>" required>

                <label for="fk_id_pedido">ID Pedido:</label>
                <input type="text" id="fk_id_pedido" name="fk_id_pedido" value="<?php echo $fk_id_pedido; ?>" required>

                <label for="imagen">Imagen:</label>
                <?php if ($imagen): ?>
                    <img src="<?php echo $imagen; ?>" class="img-thumbnail" alt="Imagen actual">
                <?php endif; ?>
                <input type="file" id="imagen" name="imagen">

                <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar Producto' : 'Crear Producto'; ?>">
            </form>

            <a href="productosLista.php" class="volver-btn">Volver al Listado de Productos</a>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
