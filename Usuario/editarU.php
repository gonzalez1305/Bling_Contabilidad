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
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tu estilo aquí */
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
                        <div class="profile-image">
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
