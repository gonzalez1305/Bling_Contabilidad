<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: ../index.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <style>
        .manual-link {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .manual-link:hover {
            color: #0056b3;
        }

        .dark-mode .manual-link {
            color: #66b2ff;
        }

        .dark-mode .manual-link:hover {
            color: #3399ff;
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
                            <a class="nav-link" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Editar Marca</h1>
                </div>
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
            </main>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>

<?php
mysqli_close($conectar);
?>