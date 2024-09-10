<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $codigo_ingresado = $_POST['codigo'];

    if (filter_var($correo, FILTER_VALIDATE_EMAIL) && !empty($codigo_ingresado)) {
        // Buscar el usuario por correo
        $query = "SELECT codigo_verificacion FROM usuario WHERE correo = '$correo' AND estado = 'Activo'";
        $result = mysqli_query($conectar, $query);
        $usuario = mysqli_fetch_assoc($result);

        if ($usuario && $usuario['codigo_verificacion'] == $codigo_ingresado) {
            // Activar la cuenta del usuario
            $updateQuery = "UPDATE usuario SET estado = 'Verificado', codigo_verificacion = NULL WHERE correo = '$correo'";
            mysqli_query($conectar, $updateQuery);

            echo "<script>";
            echo "alert('Cuenta verificada exitosamente. Puedes iniciar sesión ahora.');";
            echo "window.location.href = 'registroexitoso.html';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Código de verificación incorrecto.');";
            echo "window.history.back();";
            echo "</script>";
        }
    } else {
        echo "<script>";
        echo "alert('Por favor, ingresa un correo y un código de verificación válidos.');";
        echo "window.history.back();";
        echo "</script>";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <!-- Meta tags requeridos -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="imgs/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Verificación de Código</title>

  <style>
    /* Estilos generales */
    * {
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.5s, color 0.5s;
    }

    /* Modo oscuro */
    .dark-mode {
      background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
      color: #ffffff;
    }

    /* Contenedor del formulario */
    .verification-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
      z-index: 1;
      transition: background-color 0.5s, color 0.5s, border-color 0.5s;
    }

    /* Estilo del encabezado del formulario */
    .verification-header {
      background-color: #007bff;
      color: #ffffff;
      padding: 15px;
      border-radius: 10px 10px 0 0;
      text-align: center;
    }

    /* Estilo del formulario */
    .verification-form {
      padding: 20px;
    }

    /* Estilo de los campos del formulario */
    .verification-form .form-control {
      margin-bottom: 15px;
    }

    /* Estilo del botón de verificación */
    .verification-form .btn-primary {
      width: 100%;
      background-color: #007bff;
      border: none;
      color: #ffffff;
    }

    /* Estilo del pie de página del formulario */
    .verification-footer {
      text-align: center;
      margin-top: 20px;
    }

    .verification-footer a {
      color: #007bff;
      text-decoration: none;
    }

    .verification-footer a:hover {
      text-decoration: underline;
    }

    /* Mensaje de error */
    .error-message {
      background-color: #ffffff;
      color: #ff0000;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Estilos en modo oscuro */
    .dark-mode .verification-container {
      background-color: rgba(50, 50, 50, 0.9);
      color: #ffffff;
    }

    .dark-mode .verification-header {
      background-color: #444444;
    }

    .dark-mode .verification-form .btn-primary {
      background-color: #0056b3;
    }

    .dark-mode .error-message {
      background-color: #e6e6e6;
    }

    .dark-mode .verification-footer a {
      color: #0056b3;
    }

    /* Botón de cambio de modo */
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
  <!-- Botón de cambio de modo -->
  <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>
  
  <!-- Contenedor del formulario de verificación -->
  <div class="verification-container">
    <!-- Encabezado del formulario -->
    <div class="verification-header">
      <h1>Verificación de Código</h1>
    </div>
    
    <!-- Formulario de verificación -->
    <form class="verification-form" method="post" action="">
      <div id="errorMessages" class="error-message" style="display: none;"></div>

      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico:</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
      </div>

      <div class="mb-3">
        <label for="codigo" class="form-label">Código de Verificación:</label>
        <input type="text" class="form-control" id="codigo" name="codigo" required>
      </div>
      
      <!-- Botón de verificación -->
      <button class="btn btn-primary" type="submit">Verificar Código</button>
    </form>
    
    <!-- Pie de página del formulario -->
    <div class="verification-footer">
      <a href="index.php">Iniciar sesión</a>
      <br>
      <a href="menu.html" class="btn btn-custom mt-2">Volver al Menú</a>
    </div>
  </div>

  <!-- Script de cambio de modo -->
  <script>
    function toggleMode() {
      document.body.classList.toggle('dark-mode');
      const icon = document.querySelector('.toggle-btn i');
      icon.classList.toggle('fa-sun');
      icon.classList.toggle('fa-moon');
    }
  </script>
  
  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
