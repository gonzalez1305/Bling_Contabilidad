<?php
require '../conexion.php';

// Inicializar variables para almacenar los valores del formulario
$id_producto = '';
$nombre = '';
$descripcion = '';
$talla = '';
$color = '';
$cantidad = '';
$estado = '';
$categorias = '';
$precio_unitario = '';
$imagen = ''; // Nueva variable para almacenar la imagen

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $id_producto = mysqli_real_escape_string($conectar, $_POST['id_producto']);
    $nombre = mysqli_real_escape_string($conectar, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conectar, $_POST['descripcion']);
    $talla = mysqli_real_escape_string($conectar, $_POST['talla']);
    $color = mysqli_real_escape_string($conectar, $_POST['color']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $categorias = mysqli_real_escape_string($conectar, $_POST['categorias']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);

    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_path = 'ruta/donde/guardar/imagenes/' . $imagen_nombre;

        // Mover archivo al directorio de imágenes
        move_uploaded_file($imagen_tmp, $imagen_path);

        // Actualizar la ruta de la imagen en la base de datos
        $imagen_update = ", imagen = '$imagen_path'";
    } else {
        $imagen_update = '';
    }

    // Actualizar los datos en la base de datos
    $sql_update = "UPDATE producto SET 
                   nombre = '$nombre', 
                   descripcion = '$descripcion', 
                   talla = '$talla', 
                   color = '$color', 
                   cantidad = '$cantidad', 
                   estado = '$estado', 
                   categorias = '$categorias', 
                   precio_unitario = '$precio_unitario' 
                   $imagen_update
                   WHERE id_producto = '$id_producto'";

    if (mysqli_query($conectar, $sql_update)) {
        // Éxito al actualizar
        echo "<script>alert('Producto actualizado correctamente');</script>";
        echo "<script>window.location.href = 'productosLista.php';</script>";
        exit;
    } else {
        // Error al actualizar
        echo "Error al actualizar el producto: " . mysqli_error($conectar);
    }
} else {
    // Si no se envió el formulario, recuperar el id_producto de la URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id_producto = mysqli_real_escape_string($conectar, $_GET['id']);

        // Consultar la base de datos para obtener los datos del registro a editar
        $sql_select = "SELECT * FROM producto WHERE id_producto = '$id_producto'";
        $resultado = mysqli_query($conectar, $sql_select);

        if ($resultado) {
            $fila = mysqli_fetch_assoc($resultado);
            // Asignar los valores de la consulta a las variables del formulario
            $nombre = $fila['nombre'];
            $descripcion = $fila['descripcion'];
            $talla = $fila['talla'];
            $color = $fila['color'];
            $cantidad = $fila['cantidad'];
            $estado = $fila['estado'];
            $categorias = $fila['categorias'];
            $precio_unitario = $fila['precio_unitario'];
            $imagen = $fila['imagen']; // Obtener la imagen actual
        } else {
            echo "Error al consultar la base de datos: " . mysqli_error($conectar);
        }
    } else {
        echo "ID de producto no especificado.";
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
    <title>Editar Producto - Bling Compra</title>
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
            <h1 class="h2">Editar Producto</h1>

            <form action="editarProducto.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($id_producto); ?>">
                
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>" required>

                <label for="talla">Talla</label>
                <input type="text" id="talla" name="talla" value="<?php echo htmlspecialchars($talla); ?>" required>

                <label for="color">Color</label>
                <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($color); ?>" required>

                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>

                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>" required>

                <label for="categorias">Categorías</label>
                <input type="text" id="categorias" name="categorias" value="<?php echo htmlspecialchars($categorias); ?>" required>

                <label for="precio_unitario">Precio Unitario</label>
                <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" value="<?php echo htmlspecialchars($precio_unitario); ?>" required>

                <label for="imagen">Imagen (opcional)</label>
                <input type="file" id="imagen" name="imagen">

                <?php if ($imagen): ?>
                    <label>Imagen Actual</label>
                    <img src="<?php echo htmlspecialchars($imagen); ?>" class="img-thumbnail" alt="Imagen Actual">
                <?php endif; ?>

                <input type="submit" value="Actualizar Producto">
                <a href="productosLista.php" class="volver-btn">Volver a la Lista</a>
            </form>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
