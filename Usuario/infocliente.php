<?php
session_start();
include("../conexion.php");

// Verificar si el usuario ha iniciado sesión ya
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/logo.png">
    <title>Mi Cuenta - Bling Compra</title>
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

        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
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
            transition: transform 0.3s;
        }

        .profile-image img {
            width: 100%;
            height: auto;
        }

        .profile-image .bi {
            font-size: 100px;
            color: #6c757d;
        }

        .profile-image:hover {
            transform: scale(1.1);
        }

        .info-details {
            max-width: 600px;
            text-align: left;
        }

        .info-details p {
            margin: 0;
            padding: 8px 0;
        }

        .btn-custom {
            margin: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-custom:hover {
            transform: scale(1.05);
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
    </style>
</head>
<body>

<button class="toggle-btn" onclick="toggleMode()"><i class="fas fa-sun"></i></button>

<div class="container">
    <div class="profile-info">
        <div class="profile-image">
            <?php if ($usuario['imagen']): ?>
                <img src="../usuariofoto/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Imagen de perfil">
            <?php else: ?>
                <i class="bi bi-person-circle"></i>
            <?php endif; ?>
        </div>
        <div class="info-details">
            <h1>Mi Cuenta</h1>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($usuario['apellido']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($usuario['fecha_de_nacimiento']); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($usuario['estado']); ?></p>
            <a href="editar_usuario_cliente.php" class="btn btn-primary btn-custom">Editar Información</a>
            <a href="../MenuC.html" class="btn btn-secondary btn-custom">Volver</a>
            <a href="confirmar_eliminacion.php" class="btn btn-danger btn-custom">Eliminar Cuenta</a>
        </div>
    </div>
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