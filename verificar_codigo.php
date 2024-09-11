<!doctype html>
<html lang="en">
<head>
  <!-- Meta tags requeridos -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="icon" href="imgs/logo.png">
  <title>Registro Usuario</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

    /* Dark Mode styles */
    .dark-mode {
      background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
      color: #ffffff;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 90%;
      width: 600px;
      transition: background-color 0.5s, color 0.5s, border-color 0.5s;
    }

    .dark-mode .login-container {
      background-color: rgba(50, 50, 50, 0.9);
      color: #ffffff;
    }

    .login-header {
      background-color: #007bff;
      color: #ffffff;
      padding: 15px;
      border-radius: 10px 10px 0 0;
      text-align: center;
      width: 100%;
    }

    .dark-mode .login-header {
      background-color: #444444;
      color: #ffffff;
    }

    .login-form {
      padding: 20px;
    }

    .login-form .form-control {
      margin-bottom: 15px;
    }

    .login-form .btn-primary {
      width: 100%;
      background-color: #007bff;
      border: none;
      color: #ffffff;
    }

    .dark-mode .login-form .btn-primary {
      background-color: #0056b3;
    }

    .login-form .btn-primary:hover {
      background-color: #0056b3;
    }

    .dark-mode .login-form .btn-primary:hover {
      background-color: #003d7a;
    }

    .error-message {
      background-color: #ffffff;
      color: #ff0000;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 15px;
    }

    .dark-mode .error-message {
      background-color: #e6e6e6;
      color: #ff0000;
    }

    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #007bff;
    }

    .dark-mode .password-toggle {
      color: #ffffff;
    }

    .login-footer {
      text-align: center;
      margin-top: 20px;
    }

    .login-footer a {
      color: #007bff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 5px;
      display: inline-block;
    }

    .dark-mode .login-footer a {
      color: #ffffff;
    }

    .login-footer a:hover {
      background-color: #0056b3;
    }

    .dark-mode .login-footer a:hover {
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

    /* Media queries for responsiveness */
    @media (max-width: 768px) {
      .login-container {
        padding: 20px;
        max-width: 100%;
      }

      .login-form .form-control {
        margin-bottom: 10px;
      }

      .password-toggle {
        font-size: 16px;
      }
    }

    @media (max-width: 576px) {
      .login-container {
        padding: 15px;
        max-width: 100%;
      }

      .login-form .form-control {
        margin-bottom: 10px;
      }

      .login-header h1 {
        font-size: 1.5rem;
      }

      .toggle-btn {
        top: 10px;
        right: 10px;
      }
    }
  </style>
</head>

<body>
  <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>

  <!-- Contenedor del formulario de registro -->
  <div class="login-container">
    <!-- Encabezado del formulario -->
    <div class="login-header">
      <h1>Registro</h1>
    </div>
    
    <!-- Formulario de registro -->
    <form class="login-form" action="registro.php" method="post" onsubmit="return validateForm()">
      <div id="errorMessages" class="error-message" style="display: none;"></div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required pattern="[A-Za-z\s]+">
          <small class="form-text text-muted">Solo se permiten letras.</small>
        </div>
        <div class="col-md-6">
          <label for="apellido" class="form-label">Apellido</label>
          <input type="text" class="form-control" name="apellido" id="apellido" required pattern="[A-Za-z\s]+">
          <small class="form-text text-muted">Solo se permiten letras.</small>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" name="telefono" id="telefono" required pattern="\d{10}">
          <small class="form-text text-muted">Debe ser un número de 10 dígitos.</small>
        </div>
        <div class="col-md-6">
          <label for="direccion" class="form-label">Dirección</label>
          <input type="text" class="form-control" name="direccion" id="direccion" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="fecha" class="form-label">Fecha de Nacimiento</label>
          <input type="date" class="form-control" name="fecha" id="fecha" required>
        </div>
        <div class="col-md-6">
          <label for="correo" class="form-label">Correo</label>
          <input type="email" class="form-control" name="correo" id="correo" required>
        </div>
      </div>  
      <div class="mb-3 position-relative">
        <label for="contraseña" class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="contraseña" id="contraseña" required minlength="8" 
               pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, una letra y un número.">
        <span class="password-toggle" onclick="togglePassword()">&#128065;</span>
        <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres, una letra, un número y un símbolo especial.</small>
      </div>
      
      <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>

    <!-- Pie de página del formulario -->
    <div class="login-footer">
      <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
  </div>

  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-w5xITqK4KjlYhGM7TO2BmgQ7kwbG65V6TxKb/HDlcl9oaRIeFS2PthST7xLO7Di6" crossorigin="anonymous"></script>

  <!-- Scripts personalizados -->
  <script>
    function toggleMode() {
      document.body.classList.toggle('dark-mode');
    }

    function validateForm() {
      const nombre = document.getElementById('nombre').value;
      const apellido = document.getElementById('apellido').value;
      const telefono = document.getElementById('telefono').value;
      const direccion = document.getElementById('direccion').value;
      const fecha = document.getElementById('fecha').value;
      const correo = document.getElementById('correo').value;
      const contraseña = document.getElementById('contraseña').value;
      const errorMessages = document.getElementById('errorMessages');

      errorMessages.innerHTML = '';
      let hasError = false;

      if (!/^[A-Za-z\s]+$/.test(nombre)) {
        errorMessages.innerHTML += '<p>Nombre inválido. Solo se permiten letras.</p>';
        hasError = true;
      }
      
      if (!/^[A-Za-z\s]+$/.test(apellido)) {
        errorMessages.innerHTML += '<p>Apellido inválido. Solo se permiten letras.</p>';
        hasError = true;
      }
      
      if (!/\d{10}/.test(telefono)) {
        errorMessages.innerHTML += '<p>Teléfono inválido. Debe ser un número de 10 dígitos.</p>';
        hasError = true;
      }
      
      if (hasError) {
        errorMessages.style.display = 'block';
        return false;
      }
      return true;
    }

    function togglePassword() {
      const passwordField = document.getElementById('contraseña');
      const passwordToggle = document.querySelector('.password-toggle');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.innerHTML = '&#128065;'; // Eye Open
      } else {
        passwordField.type = 'password';
        passwordToggle.innerHTML = '&#128274;'; // Eye Closed
      }
    }
  </script>
</body>
</html>
