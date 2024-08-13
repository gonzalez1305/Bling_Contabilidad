<?php
require '../conexion.php';

// Obtener los productos desde la base de datos
$sql_select = "SELECT * FROM producto";
$resultado = mysqli_query($conectar, $sql_select);

if (!$resultado) {
    die("Error al consultar la base de datos: " . mysqli_error($conectar));
}

// Cerrar conexión
mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos - Bling Compra</title>
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
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .btn-edit,
        .btn-delete {
            margin-right: 5px;
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
            <h1 class="h2">Lista de Productos</h1>

            <a href="./crearProducto.php" class="btn btn-primary mb-3">Añadir Nuevo Producto</a>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Talla</th>
                        <th>Color</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Categorías</th>
                        <th>Precio Unitario</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($row['talla']); ?></td>
                            <td><?php echo htmlspecialchars($row['color']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            <td><?php echo htmlspecialchars($row['categorias']); ?></td>
                            <td><?php echo htmlspecialchars($row['precio_unitario']); ?></td>
                            <td>
                                <?php if ($row['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="img-thumbnail" alt="Imagen" style="max-width: 100px;">
                                <?php else: ?>
                                    No hay imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="./editarProducto.php?php echo htmlspecialchars($row['id_producto']); ?>" class="btn btn-warning btn-sm btn-edit">Editar</a>
                                <a href="./eliminarProducto.php?php echo htmlspecialchars($row['id_producto']); ?>" class="btn btn-danger btn-sm btn-delete">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
