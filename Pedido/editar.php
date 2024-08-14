<?php
include("../conexion.php");

// Inicializar variables
$pedidoDetalles = array(); // Inicializa un array vacío para evitar advertencias

if (isset($_POST['enviar'])) {
    // Si se ha enviado el formulario
    $unidades = $_POST["unidades"];
    $id_detalles_pedido = $_POST["id_detalles_pedido"];

    // Obtén el precio unitario del producto
    $sqlPrecioUnitario = "SELECT precio_unitario FROM producto WHERE id_producto = (SELECT fk_id_producto FROM detalles_pedido WHERE id_detalles_pedido = '".$id_detalles_pedido."')";
    $resultadoPrecioUnitario = mysqli_query($conectar, $sqlPrecioUnitario);

    if ($resultadoPrecioUnitario && mysqli_num_rows($resultadoPrecioUnitario) > 0) {
        $filaPrecioUnitario = mysqli_fetch_assoc($resultadoPrecioUnitario);
        $precioUnitario = $filaPrecioUnitario['precio_unitario'];

        // Calcula el nuevo precio total
        $nuevoPrecioTotal = $precioUnitario * $unidades;

        // Actualiza las unidades y el precio total en la base de datos
        $sql = "UPDATE detalles_pedido SET unidades='".$unidades."', precio_total='".$nuevoPrecioTotal."' WHERE id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            echo "<script language='javascript'>";
            echo "alert('Los datos se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        } else {
            echo "<script language='javascript'>";
            echo "alert('Los datos NO se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        }
    } else {
        echo "Error al obtener el precio unitario del producto.";
    }

    mysqli_close($conectar);
} else {
    // Si no se ha enviado el formulario
    if (isset($_GET['id_detalles_pedido'])) {
        $id_detalles_pedido = $_GET['id_detalles_pedido'];
        $sql = "SELECT * FROM pedido
                INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
                INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto
                WHERE detalles_pedido.id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $pedidoDetalles = mysqli_fetch_assoc($resultado);
            $id_detalles_pedido = $pedidoDetalles['id_detalles_pedido'];
            $unidades = $pedidoDetalles['unidades'];
        } else {
            echo "Error al recuperar datos de la base de datos.";
        }
    } else {
        echo "Error: No se proporcionó el ID del pedido a editar.";
        exit; // Termina el script si no hay ID de pedido
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="css/estilopedido.css">
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
            <h1 class="h2">Editar Pedido</h1>
            <div class="container mt-5">
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="p-4 border rounded-3 bg-light">
                    <input type="hidden" name="id_detalles_pedido" value="<?php echo $id_detalles_pedido; ?>">
                    <div class="mb-3">
                        <label class="form-label"><strong>ID Pedido:</strong></label>
                        <input type="text" name="id_pedido" value="<?php echo $pedidoDetalles['id_pedido']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>ID Usuario:</strong></label>
                        <input type="text" name="fk_id_usuario" value="<?php echo $pedidoDetalles['fk_id_usuario']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Fecha:</strong></label>
                        <input type="text" name="fecha" value="<?php echo $pedidoDetalles['fecha']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Situación:</strong></label>
                        <input type="text" name="situacion" value="<?php echo $pedidoDetalles['situacion']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Nombre del Producto:</strong></label>
                        <input type="text" name="nombre" value="<?php echo $pedidoDetalles['nombre']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Precio Total:</strong></label>
                        <input type="text" name="precio_total" value="<?php echo $pedidoDetalles['precio_total']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="unidades" class="form-label"><strong>Unidades:</strong></label>
                        <input type="text" name="unidades" value="<?php echo $unidades; ?>" class="form-control" required>
                    </div>
                    <button type="submit" name="enviar" class="btn btn-primary">ACTUALIZAR</button>
                    <a href="validarpedido.php" class="btn btn-secondary ms-2">Regresar</a>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
