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
</div>

<div class="container mt-4">       <div class="d-flex justify-content-center">
        <h2 class="section-title">Contactactanos por nuestras redes sociales!</h2> </div>
        <div class="d-flex justify-content-center">
            <a href="https://www.instagram.com/blingcontabilidad/" class="btn btn-primary mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.79 0 5.571.015 4.77.072 3.97.13 3.28.33 2.7.69c-.6.36-1.06.81-1.42 1.42-.36.6-.56 1.29-.62 2.03C.01 5.43 0 5.79 0 8c0 2.21.015 2.43.072 3.23.057.8.257 1.49.61 2.07.36.6.81 1.06 1.42 1.42.6.36 1.29.56 2.03.62C5.43 15.99 5.79 16 8 16c2.21 0 2.43-.015 3.23-.072.8-.057 1.49-.257 2.07-.61.6-.36 1.06-.81 1.42-1.42.36-.6.56-1.29.62-2.03C15.99 10.57 16 10.21 16 8c0-2.21-.015-2.43-.072-3.23-.057-.8-.257-1.49-.61-2.07-.36-.6-.81-1.06-1.42-1.42-.6-.36-1.29-.56-2.03-.62C10.57.01 10.21 0 8 0zm0 1.5c2.11 0 2.34.013 3.13.07.6.05 1.11.25 1.58.62.47.36.82.77 1.18 1.18.36.47.57.98.62 1.58.057.79.07 1.02.07 3.13s-.013 2.34-.07 3.13c-.05.6-.25 1.11-.62 1.58-.36.47-.77.82-1.18 1.18-.47.36-.98.57-1.58.62-.79.057-1.02.07-3.13.07s-2.34-.013-3.13-.07c-.6-.05-1.11-.25-1.58-.62-.47-.36-.82-.77-1.18-1.18-.36-.47-.57-.98-.62-1.58C1.513 10.34 1.5 10.11 1.5 8s.013-2.34.07-3.13c.05-.6.25-1.11.62-1.58.36-.47.77-.82 1.18-1.18.47-.36.98-.57 1.58-.62C5.66 1.513 5.89 1.5 8 1.5zm0 4.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 1.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                </svg> Instagram
            </a>
            <a href="https://api.whatsapp.com/send?phone=573222465996" class="btn btn-success mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                </svg> WhatsApp
            </a>
            <a href="https://www.youtube.com/channel/UCoJhZ0ileMMnQ2Wkp1bFnCA" class="btn btn-danger mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                    <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.6-.36 1.06-.81 1.42-1.42.36-.6.56-1.29.62-2.03C5.43.01 5.79 0 8 0c2.21 0 2.43.015 3.23.072.8.057 1.49.257 2.07.61.6.36 1.06.81 1.42 1.42.36.6.56 1.29.62 2.03C15.99 5.57 16 5.79 16 8c0 2.21-.015 2.43-.072 3.23-.057.8-.257 1.49-.61 2.07-.36.6-.81 1.06-1.42 1.42-.6.36-1.29.56-2.03.62C10.57 15.99 10.21 16 8 16c-2.21 0-2.43-.015-3.23-.072-.8-.057-1.49-.257-2.07-.61-.6-.36-1.06-.81-1.42-1.42-.36-.6-.56-1.29-.62-2.03C.01 10.57 0 10.21 0 8c0-2.21.015-2.43.072-3.23.057-.8.257-1.49.61-2.07.36-.6.81-1.06 1.42-1.42.6-.36 1.29-.56 2.03-.62C5.66.01 5.89 0 8 0zm0 1.5c2.11 0 2.34.013 3.13.07.6.05 1.11.25 1.58.62.47.36.82.77 1.18 1.18.36.47.57.98.62 1.58.057.79.07 1.02.07 3.13s-.013 2.34-.07 3.13c-.05.6-.25 1.11-.62 1.58-.36.47-.77.82-1.18 1.18-.47.36-.98.57-1.58.62-.79.057-1.02.07-3.13.07s-2.34-.013-3.13-.07c-.6-.05-1.11-.25-1.58-.62-.47-.36-.82-.77-1.18-1.18-.36-.47-.57-.98-.62-1.58C1.513 10.34 1.5 10.11 1.5 8s.013-2.34.07-3.13c.05-.6.25-1.11.62-1.58.36-.47.77-.82 1.18-1.18.47-.36.98-.57 1.58-.62C5.66 1.513 5.89 1.5 8 1.5zm0 4.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 1.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                </svg> YouTube
            </a>
        </div>
    </div>

    <footer class="mt-4">
        <p>&copy; 2023 Bling Compra, Inc. Todos los derechos reservados</p>
    </footer>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.js"></script>
</body>
</html>
