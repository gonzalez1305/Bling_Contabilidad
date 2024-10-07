<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-responsive {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
        }
        .table tbody td {
            color: black;
        }
        .btn-back {
            margin-bottom: 10px;
        }
        .dataTables_length label,
        .dataTables_info {
            color: black !important;
        }
        .dataTables_filter label {
            color: black !important;
        }
        body.dark-mode .dataTables_length label,
        body.dark-mode .dataTables_info,
        body.dark-mode .dataTables_filter label {
            color: white !important;
        }
        .modal-content {
            color: black;
        }
        body.dark-mode .modal-content {
            color: white;
        }
        .modal-body {
            color: black;
        }
        body.dark-mode .modal-body {
            color: white;
        }
        #confirmDeleteModal .modal-body,
        #confirmDeleteModal .modal-title {
            color: black !important;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-image i {
            font-size: 100px;
            color: #6c757d;
        }
    </style>}
    <style>
    body.dark-mode .form-container {
        color: gray;
    }
    body.dark-mode .form-label {
        color: gray;
    }
    body.dark-mode .form-control {
        color: gray;
        background-color: #343a40;
        border-color: #6c757d;
    }
    body.dark-mode .form-control::placeholder {
        color: #6c757d;
    }
    body.dark-mode .btn-primary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    body.dark-mode .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
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
                            <a class="nav-link active" href="validarusuario.php">
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
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-tag"></i> Marca</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10 col-sm-12">
                            <div class="form-container">
                                <h1>Editar Usuario</h1>
                                <?php
                                include("../conexion.php");

                                if (isset($_POST['enviar'])) {
                                    $id_usuario = $_POST["id_usuario"];
                                    $nombre = $_POST["nombre"];
                                    $apellido = $_POST["apellido"];
                                    $telefono = $_POST["telefono"];
                                    $direccion = $_POST["direccion"];
                                    $fecha_de_nacimiento = $_POST["fecha_de_nacimiento"];
                                    $correo = $_POST["correo"];
                                    $tipo_usuario = $_POST["tipo_usuario"];
                                    $estado = $_POST["estado"]; // Capturando el estado
                                    $imagen = '';

                                    // Validación de campos
                                    $errors = [];

                                    // Aquí van las validaciones que ya tenías

                                    if (!empty($errors)) {
                                        foreach ($errors as $error) {
                                            echo "<div class='alert alert-danger'>$error</div>";
                                        }
                                    } else {
                                        // Manejo de la imagen (igual que tenías)

                                        // Actualización en la base de datos
                                        $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion', fecha_de_nacimiento='$fecha_de_nacimiento', correo='$correo', tipo_usuario='$tipo_usuario', estado='$estado', imagen='$imagen' WHERE id_usuario='$id_usuario'";
                                        $resultado = mysqli_query($conectar, $sql);

                                        if ($resultado) {
                                            echo "<script>alert('Los datos se actualizaron correctamente'); location.assign('validarusuario.php');</script>";
                                        } else {
                                            echo "<script>alert('Los datos NO se actualizaron correctamente'); location.assign('validarusuario.php');</script>";
                                        }
                                        mysqli_close($conectar);
                                    }
                                } else {
                                    if (isset($_GET['id_usuario'])) {
                                        $id_usuario = $_GET['id_usuario'];
                                        $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, tipo_usuario, estado, imagen FROM usuario WHERE id_usuario='$id_usuario'";
                                        $resultado = mysqli_query($conectar, $sql);

                                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                                            $usuario = mysqli_fetch_assoc($resultado);
                                            $nombre = $usuario['nombre'];
                                            $apellido = $usuario['apellido'];
                                            $telefono = $usuario['telefono'];
                                            $direccion = $usuario['direccion'];
                                            $fecha_de_nacimiento = $usuario['fecha_de_nacimiento'];
                                            $correo = $usuario['correo'];
                                            $tipo_usuario = $usuario['tipo_usuario'];
                                            $estado = $usuario['estado']; // Capturando el estado
                                            $imagen = $usuario['imagen'];
                                        } else {
                                            echo "Error al recuperar datos de la base de datos.";
                                            exit;
                                        }
                                    } else {
                                        echo "Error: No se proporcionó el ID del usuario a editar.";
                                        exit;
                                    }
                                }

                                // Obtener roles de la base de datos
                                $roles_sql = "SELECT id_rol, nombre FROM roles";
                                $roles_resultado = mysqli_query($conectar, $roles_sql);
                                ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                                    <div class="profile-image text-center mb-3">
                                        <?php if ($imagen): ?>
                                            <img src="../usuariofoto/<?php echo $imagen; ?>" alt="Imagen de Perfil">
                                        <?php else: ?>
                                            <i class="fas fa-user-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido:</label>
                                        <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección:</label>
                                        <input type="text" name="direccion" class="form-control" value="<?php echo $direccion; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_de_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                        <input type="date" name="fecha_de_nacimiento" class="form-control" value="<?php echo $fecha_de_nacimiento; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo Electrónico:</label>
                                        <div class="form-control" readonly><?php echo $correo; ?></div>
                                        <input type="hidden" name="correo" value="<?php echo $correo; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipo_usuario" class="form-label">Tipo de Usuario:</label>
                                        <select name="tipo_usuario" class="form-control" required>
                                            <?php while ($rol = mysqli_fetch_assoc($roles_resultado)): ?>
                                                <option value="<?php echo $rol['id_rol']; ?>" <?php echo ($tipo_usuario == $rol['id_rol']) ? 'selected' : ''; ?>>
                                                    <?php echo $rol['nombre']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado:</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="Verificado" <?php echo ($estado == 'Verificado') ? 'selected' : ''; ?>>Verificado</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="enviar" class="btn btn-primary">Actualizar</button>
                                    <a href="validarusuario.php" class="btn btn-secondary">Cancelar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>