<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin-styles.css">
    <style>
        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-container {
            background-color: var(--card-background);
            color: var(--text-color);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
        }

        .form-container h1 {
            color: var(--primary-color);
        }

        .form-container .form-label {
            color: var(--text-color);
        }

        .form-container .form-control {
            background-color: var(--input-background);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .form-container .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-container .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-container .btn-primary:hover {
            background-color: var(--primary-color-dark);
            border-color: var(--primary-color-dark);
        }

        .form-container .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .form-container .btn-secondary:hover {
            background-color: var(--secondary-color-dark);
            border-color: var(--secondary-color-dark);
        }

        .profile-image {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-image i {
            font-size: 150px;
            color: var(--text-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
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
                    <li class="nav-item">
                        <div class="theme-switch-wrapper">
                            <label class="theme-switch" for="checkbox">
                                <input type="checkbox" id="checkbox" />
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <a class="nav-link" href="../usuario/validarusuario.php">Usuarios</a>
        <a class="nav-link" href="../dashboard_v.html">Ventas</a>
        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
        <a class="nav-link active" href="../Pedido/validarpedido.php">Pedidos</a>
    </div>

    <div class="content-wrapper">
        <div class="btn-back">
            <a class="btn btn-light text-primary" href="../menuv.html" role="button">Volver al Menú</a>
        </div>
        <h1 class="h2">Editar Usuario</h1>

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12">
                    <div class="form-container">
                        <?php
                        include("../conexion.php");

                        if(isset($_POST['enviar'])){
                            $id_usuario = $_POST["id_usuario"];
                            $nombre = $_POST["nombre"];
                            $apellido = $_POST["apellido"];
                            $telefono = $_POST["telefono"];
                            $direccion = $_POST["direccion"];
                            $fecha_de_nacimiento = $_POST["fecha_de_nacimiento"];
                            $correo = $_POST["correo"];
                            $estado = $_POST["estado"];
                            $tipo_usuario = $_POST["tipo_usuario"];
                            $imagen = '';

                            // Validación de campos
                            $errors = [];

                            if (!preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
                                $errors[] = "El nombre debe contener solo letras.";
                            }

                            if (!preg_match("/^[a-zA-Z\s]+$/", $apellido)) {
                                $errors[] = "El apellido debe contener solo letras.";
                            }

                            if (!preg_match("/^[0-9]{10}$/", $telefono)) {
                                $errors[] = "El teléfono debe tener 10 dígitos.";
                            }

                            if (!DateTime::createFromFormat('Y-m-d', $fecha_de_nacimiento)) {
                                $errors[] = "La fecha de nacimiento no es válida. Debe ser en formato YYYY-MM-DD.";
                            }

                            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "El correo electrónico no es válido.";
                            }

                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }
                            } else {
                                // Manejo de la imagen
                                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                                    $directorio_destino = "../usuariofoto/";

                                    if (!is_dir($directorio_destino)) {
                                        if (!mkdir($directorio_destino, 0755, true)) {
                                            echo "<script>alert('No se pudo crear el directorio para las imágenes.');</script>";
                                            exit();
                                        }
                                    }

                                    $archivo_tmp = $_FILES['imagen']['tmp_name'];
                                    $nombre_archivo_original = basename($_FILES['imagen']['name']);
                                    $extension = strtolower(pathinfo($nombre_archivo_original, PATHINFO_EXTENSION));
                                    $nombre_archivo_nuevo = uniqid() . '.' . $extension;
                                    $ruta_destino = $directorio_destino . $nombre_archivo_nuevo;

                                    $tipos_permitidos = ['image/jpeg', 'image/png'];
                                    if (!in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
                                        echo "<script>alert('Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.');</script>";
                                        exit();
                                    }

                                    if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
                                        $imagen = $nombre_archivo_nuevo;
                                    } else {
                                        echo "<script>alert('Error al subir la imagen. Verifique el directorio y los permisos.');</script>";
                                        exit();
                                    }
                                }

                                // Actualización en la base de datos
                                $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion', fecha_de_nacimiento='$fecha_de_nacimiento', correo='$correo', estado='$estado', tipo_usuario='$tipo_usuario', imagen='$imagen' WHERE id_usuario='$id_usuario'";
                                $resultado = mysqli_query($conectar, $sql);

                                if($resultado){
                                    echo "<script>alert('Los datos se actualizaron correctamente'); location.assign('validarusuario.php');</script>";
                                } else {
                                    echo "<script>alert('Los datos NO se actualizaron correctamente'); location.assign('validarusuario.php');</script>";
                                }
                                mysqli_close($conectar);
                            }
                        } else {
                            if(isset($_GET['id_usuario'])){
                                $id_usuario = $_GET['id_usuario'];
                                $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, estado, tipo_usuario, imagen FROM usuario WHERE id_usuario='$id_usuario'";
                                $resultado = mysqli_query($conectar, $sql);

                                if($resultado && mysqli_num_rows($resultado) > 0){
                                    $usuario = mysqli_fetch_assoc($resultado);
                                    $nombre = $usuario['nombre'];
                                    $apellido = $usuario['apellido'];
                                    $telefono = $usuario['telefono'];
                                    $direccion = $usuario['direccion'];
                                    $fecha_de_nacimiento = $usuario['fecha_de_nacimiento'];
                                    $correo = $usuario['correo'];
                                    $estado = $usuario['estado'];
                                    $tipo_usuario = $usuario['tipo_usuario'];
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
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" class="mt-4">
                            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
                            
                            <!-- Visualización de la imagen actual -->
                            <div class="profile-image">
                                <?php if (!empty($imagen) && file_exists("../usuariofoto/" . $imagen)): ?>
                                    <img src="../usuariofoto/<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de perfil">
                                <?php else: ?>
                                    <i class="bi bi-person-circle"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido:</label>
                                <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($apellido); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($telefono); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($direccion); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_de_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                <input type="text" name="fecha_de_nacimiento" class="form-control" value="<?php echo htmlspecialchars($fecha_de_nacimiento); ?>" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo:</label>
                                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado:</label>
                                <input type="text" name="estado" class="form-control" value="<?php echo htmlspecialchars($estado); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo Usuario:</label>
                                <input type="number" name="tipo_usuario" class="form-control" value="<?php echo htmlspecialchars($tipo_usuario); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen:</label>
                                <input type="file" name="imagen" class="form-control" accept="image/jpeg, image/png">
                            </div>
                            <div class="d-flex justify-content-between">
                                <input type="submit" name="enviar" value="Actualizar" class="btn btn-primary">
                                <a href="validarusuario.php" class="btn btn-secondary">Regresar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/cambiarColor.js"></script>
</body>
</html>