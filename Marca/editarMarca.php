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

if (isset($_GET['id'])) {
    $id_marca = $_GET['id'];

    // Consulta para obtener los detalles de la marca
    $query_marca = "SELECT * FROM marca WHERE id_marca = $id_marca";
    $result_marca = mysqli_query($conectar, $query_marca);

    if (mysqli_num_rows($result_marca) == 1) {
        $marca = mysqli_fetch_assoc($result_marca);
        $nombre_marca = $marca['nombre_marca'];
    } else {
        echo "<script>alert('Marca no encontrada'); window.location.href='listaMarcas.php';</script>";
    }
}

if (isset($_POST['actualizar'])) {
    $nombre_marca_actualizado = $_POST['nombre_marca'];

    // Consulta para actualizar la marca
    $update_query = "UPDATE marca SET nombre_marca = '$nombre_marca_actualizado' WHERE id_marca = $id_marca";

    if (mysqli_query($conectar, $update_query)) {
        echo "<script>alert('Marca actualizada exitosamente'); window.location.href='listaMarcas.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar la marca');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Marca - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link active" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pagos/verPago.php">Pagos</a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca</a>
                        </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Editar Marca</h1>
                <div class="form-container">
                    <form action="editarMarca.php?id=<?php echo $id_marca; ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre_marca" class="form-label">Nombre de la Marca</label>
                            <input type="text" class="form-control" id="nombre_marca" name="nombre_marca" value="<?php echo $nombre_marca; ?>" required>
                        </div>
                        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Marca</button>
                        <a href="listaMarcas.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conectar);
?>
