<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Bling Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dash.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Bling Compra</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="menu.html">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="validarusuario.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="./dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="./dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-10">
                        <h1 class="h3">Editar Usuario</h1>
                        <?php
                        include("conexion.php");

                        if(isset($_POST['enviar'])){
                            $id_usuario = $_POST["id_usuario"];
                            $nombre = $_POST["nombre"];
                            $apellido = $_POST["apellido"];
                            $telefono = $_POST["telefono"];
                            $direccion = $_POST["direccion"];

                            $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', telefono='$telefono', direccion='$direccion' WHERE id_usuario = '$id_usuario'";
                            $resultado = mysqli_query($conectar, $sql);

                            if($resultado){
                                echo "<script language='javascript'>";
                                echo "alert('Los datos se actualizaron correctamente');";
                                echo "location.assign('validarusuario.php');";
                                echo "</script>";
                            } else {
                                echo "<script language='javascript'>";
                                echo "alert('Los datos NO se actualizaron correctamente');";
                                echo "location.assign('validarusuario.php');";
                                echo "</script>";
                            }
                            mysqli_close($conectar);

                        } else {
                            if(isset($_GET['id_usuario'])){
                                $id_usuario = $_GET['id_usuario'];
                                $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, estado, tipo_usuario FROM usuario WHERE id_usuario = '$id_usuario'";
                                $resultado = mysqli_query($conectar, $sql);

                                if($resultado && mysqli_num_rows($resultado) > 0){
                                    $usuario = mysqli_fetch_assoc($resultado);
                                    $nombre = $usuario['nombre'];
                                    $apellido = $usuario['apellido'];
                                    $telefono = $usuario['telefono'];
                                    $direccion = $usuario['direccion'];
                                } else {
                                    echo "Error al recuperar datos de la base de datos.";
                                }
                            } else {
                                echo "Error: No se proporcionó el ID del usuario a editar.";
                                exit;
                            }
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="mt-4">
                            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                            <div class="mb-3">
                                <label for="id_usuario" class="form-label">ID Usuario:</label>
                                <p class="form-control-plaintext"><?php echo $usuario['id_usuario']; ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido:</label>
                                <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" name="direccion" class="form-control" value="<?php echo $direccion; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_de_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                <p class="form-control-plaintext"><?php echo $usuario['fecha_de_nacimiento']; ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo:</label>
                                <p class="form-control-plaintext"><?php echo $usuario['correo']; ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado:</label>
                                <p class="form-control-plaintext"><?php echo $usuario['estado']; ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo Usuario:</label>
                                <p class="form-control-plaintext"><?php echo $usuario['tipo_usuario']; ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <input type="submit" name="enviar" value="Actualizar" class="btn btn-primary">
                                <a href="validarusuario.php" class="btn btn-secondary">Regresar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>