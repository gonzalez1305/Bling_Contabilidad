<?php include '../session_check.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            background-color: #ffffff;
            color: #333333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-container h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .form-container .form-label {
            color: #333333;
        }

        .form-container .form-control {
            background-color: #f1f1f1;
            color: #333333;
            border: 1px solid #ced4da;
        }

        .form-container .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-container .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-container .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-container .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .form-container .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
            border: 2px solid #007bff;
        }

        .profile-image i {
            font-size: 150px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="form-container">
                    <h1>Editar Usuario</h1>
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
                            $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion', fecha_de_nacimiento='$fecha_de_nacimiento', correo='$correo', tipo_usuario='$tipo_usuario', imagen='$imagen' WHERE id_usuario='$id_usuario'";
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
                            $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, tipo_usuario, imagen FROM usuario WHERE id_usuario='$id_usuario'";
                            $resultado = mysqli_query($conectar, $sql);

                            if($resultado && mysqli_num_rows($resultado) > 0){
                                $usuario = mysqli_fetch_assoc($resultado);
                                $nombre = $usuario['nombre'];
                                $apellido = $usuario['apellido'];
                                $telefono = $usuario['telefono'];
                                $direccion = $usuario['direccion'];
                                $fecha_de_nacimiento = $usuario['fecha_de_nacimiento'];
                                $correo = $usuario['correo'];
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>