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
    // Validar y sanitizar los datos del formulario
    $nombre = filter_var($_POST['nombre'] ?? $usuario['nombre'], FILTER_SANITIZE_STRING);
    $apellido = filter_var($_POST['apellido'] ?? $usuario['apellido'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($_POST['telefono'] ?? $usuario['telefono'], FILTER_SANITIZE_STRING);
    $direccion = filter_var($_POST['direccion'] ?? $usuario['direccion'], FILTER_SANITIZE_STRING);
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'] ?? $usuario['fecha_de_nacimiento'];
    $correo = filter_var($_POST['correo'] ?? $usuario['correo'], FILTER_SANITIZE_EMAIL);


    // Validar la fecha de nacimiento
    $fecha_de_nacimiento = DateTime::createFromFormat('Y-m-d', $fecha_de_nacimiento);
    if (!$fecha_de_nacimiento || $fecha_de_nacimiento->format('Y-m-d') != $_POST['fecha_de_nacimiento']) {
        echo "Fecha de nacimiento inválida.";
        exit();
    }

    // Validar el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico inválido.";
        exit();
    }
    
    // Manejo de la imagen
    $imagen = $usuario['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $archivo_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_archivo = basename($_FILES['imagen']['name']);
        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $archivos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

        // Validar el tipo de archivo
        if (!in_array($ext, $archivos_permitidos)) {
            echo "Solo se permiten imágenes en formato JPG, JPEG, PNG o GIF.";
            exit();
        }

        // Verificar si el directorio existe, y si no, crearlo
        $directorio_destino = "../usuariofoto/";
        if (!is_dir($directorio_destino)) {
            if (!mkdir($directorio_destino, 0755, true)) {
                echo "No se pudo crear el directorio para las imágenes.";
                exit();
            }
        }

        $ruta_destino = $directorio_destino . $nombre_archivo;
        
        // Mover el archivo cargado al directorio de destino
        if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
            $imagen = $nombre_archivo;
        } else {
            echo "Error al subir la imagen. Verifique el directorio y los permisos.";
            exit();
        }
    }
    
    // Actualización en la base de datos
    $sql = "UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, direccion = ?, fecha_de_nacimiento = ?, correo = ?, estado = ?, imagen = ? WHERE id_usuario = ?";
    $stmt = $conectar->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $telefono, $direccion, $fecha_de_nacimiento->format('Y-m-d'), $correo, $estado, $imagen, $id_usuario);
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.5s, color 0.5s;
        }

        .dark-mode {
            background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
            color: #ffffff;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            text-align: center;
            transition: background-color 0.5s, color 0.5s;
        }

        .dark-mode .container {
            background-color: rgba(50, 50, 50, 0.9);
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            color: #ffffff;
        }

        .dark-mode .btn-primary {
            background-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .dark-mode .btn-primary:hover {
            background-color: #003d7a;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
            transition: background-color 0.3s;
        }

        .dark-mode .toggle-btn {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .toggle-btn i {
            font-size: 20px;
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
    </style>
</head>
<body>

<button class="toggle-btn" onclick="toggleMode()"><i class="fas fa-sun"></i></button>

<div class="container">
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
                <div class="mb-1">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                </div>
                <div class="mb-1">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                </div>
                <div class="mb-1">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
                </div>
                <div class="mb-1">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
                </div>
                <div class="mb-1">
                    <label for="fecha_de_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_de_nacimiento" name="fecha_de_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_de_nacimiento']); ?>" required>
                </div>
                <div class="mb-1">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                </div>

                <div class="mb-1">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Información</button>
                <a href="./infocliente.php" class="btn btn-secondary">Volver</a>
                <a href="../recuperar_contraseña.php" class="btn btn-secondary">Recuperar - cambiar contraseña</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.js"></script>
<script>
    function toggleMode() {
        document.body.classList.toggle('dark-mode');
        const icon = document.querySelector('.toggle-btn i');
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }
</script>
</body>
</html>