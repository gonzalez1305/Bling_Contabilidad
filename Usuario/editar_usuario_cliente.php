<?php
session_start();
include("../conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener la información del usuario
$sql = "SELECT nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, estado, imagen FROM usuario WHERE id_usuario = ?";
$stmt = $conectar->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Verificar si se encontró el usuario
if (!$usuario) {
    echo "No se encontró el usuario.";
    exit();
}

// Manejo del formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? $usuario['nombre'];
    $apellido = $_POST['apellido'] ?? $usuario['apellido'];
    $telefono = $_POST['telefono'] ?? $usuario['telefono'];
    $direccion = $_POST['direccion'] ?? $usuario['direccion'];
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'] ?? $usuario['fecha_de_nacimiento'];
    $correo = $_POST['correo'] ?? $usuario['correo'];
    $estado = $_POST['estado'] ?? $usuario['estado'];
    
    // Manejo de la imagen
    $imagen = $usuario['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $directorio_destino = "../usuariofoto/";
        
        // Verificar si el directorio existe, y si no, crearlo
        if (!is_dir($directorio_destino)) {
            if (!mkdir($directorio_destino, 0755, true)) {
                echo "No se pudo crear el directorio para las imágenes.";
                exit();
            }
        }

        $nombre_archivo = basename($_FILES['imagen']['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;
        
        // Mover el archivo cargado al directorio de destino
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $imagen = $nombre_archivo;
        } else {
            echo "Error al subir la imagen. Verifique el directorio y los permisos.";
            exit();
        }
    }
    
    // Actualización en la base de datos
    $sql = "UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, direccion = ?, fecha_de_nacimiento = ?, correo = ?, estado = ?, imagen = ? WHERE id_usuario = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $telefono, $direccion, $fecha_de_nacimiento, $correo, $estado, $imagen, $id_usuario);
    
    if ($stmt->execute()) {
        // Redirigir a la página de confirmación
        header("Location: confirmacioncliente.php");
        exit();
    } else {
        echo "Error al actualizar la información.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/logo.png">
    <title>Editar Usuario - Bling Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .profile-image img {
            width: 100%;
            height: auto;
        }
        .profile-image .bi {
            font-size: 100px;
            color: #6c757d;
        }
        .info-details {
            max-width: 600px;
        }
        .info-details p {
            margin: 0;
            padding: 8px 0;
        }
        footer {
            background-color: #007bff;
            color: white;
            padding: 1rem 0;
            text-align: center;
            position: absolute;
            width: 100%;
            bottom: 0;
        }
        footer p {
            margin: 0;
        }
        .btn-custom {
            margin: 5px;
        }
        .social-media .btn {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .social-media .btn svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Editar Información</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="profile-info">
                <div class="profile-image">
                    <?php if ($usuario['imagen']): ?>
                        <img src="../usuariofoto/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Imagen de perfil">
                    <?php else: ?>
                        <i class="bi bi-person-circle"></i>
                    <?php endif; ?>
                </div>
                <div class="info-details">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_de_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_de_nacimiento" name="fecha_de_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_de_nacimiento']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="imagen" name="imagen">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Información</button>
                    <a href="./infocliente.php" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.js"></script>
</body>
</html>