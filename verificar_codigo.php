<?php
require 'conexion.php'; // Archivo para conectar con la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $codigoIngresado = $_POST['codigo'];
    $nuevaContraseña = $_POST['nueva_contraseña'];

    // Verificar el código ingresado
    $stmt = $conectar->prepare("SELECT codigo_recuperacion FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($codigoRecuperacion);
    $stmt->fetch();
    $stmt->close();

    if ($codigoIngresado === $codigoRecuperacion) {
        // Cifrar la nueva contraseña
        $contraseñaCifrada = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $conectar->prepare("UPDATE usuario SET contraseña = ?, codigo_recuperacion = NULL WHERE correo = ?");
        $stmt->bind_param("ss", $contraseñaCifrada, $correo);
        if ($stmt->execute()) {
            echo "<script>alert('Contraseña cambiada exitosamente.'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error al cambiar la contraseña. Por favor, intenta nuevamente.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Código de recuperación incorrecto.'); window.history.back();</script>";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="icon" href="imgs/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Verificar Código</title>

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

    .verification-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
      transition: background-color 0.5s, color 0.5s;
    }

    .dark-mode .verification-container {
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

    .eye-icon {
      cursor: pointer;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
    }
    
    .form-group {
      position: relative;
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
  <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>
  <div class="verification-container">
    <h1>Verificar Código</h1>
    <form method="post" action="">
      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
      </div>
      <div class="mb-3">
        <label for="codigo" class="form-label">Código de Recuperación</label>
        <input type="text" class="form-control" id="codigo" name="codigo" required>
      </div>
      <div class="mb-3 form-group">
        <label for="nueva_contraseña" class="form-label">Nueva Contraseña</label>
        <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required minlength="8">
        <span class="eye-icon" onclick="togglePassword()">
          👁️
        </span>
      </div>
      <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
      <a href="menu.html">Volver</a>
    </form>
  </div>

  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  
  <script>
    function toggleMode() {
      document.body.classList.toggle('dark-mode');
      const icon = document.querySelector('.toggle-btn i');
      icon.classList.toggle('fa-sun');
      icon.classList.toggle('fa-moon');
    }

    function togglePassword() {
      var passwordField = document.getElementById('nueva_contraseña');
      var eyeIcon = document.querySelector('.eye-icon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.textContent = '🙈'; // Cambia el emoji cuando la contraseña es visible
      } else {
        passwordField.type = 'password';
        eyeIcon.textContent = '👁️'; // Cambia el emoji cuando la contraseña está oculta
      }
    }
  </script>
</body>
</html>