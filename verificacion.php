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
<html lang="en">
<head>
  <!-- Meta tags requeridos -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="icon" href="imgs/logo.png">
  <title>Verificación de Código</title>

  <style>
    /* Estilos generales */
    * {
      box-sizing: border-box;
    }

    body {
      background-color: #007bff;  /* Fondo azul */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      overflow: hidden;
    }

    /* Contenedor del formulario de verificación */
    .verification-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
      z-index: 1;
    }

    /* Encabezado del formulario */
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
    }

    /* Pie de página del formulario */
    .verification-footer {
      text-align: center;
      margin-top: 20px;
    }

    /* Enlaces del pie de página footers*/
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
  </style>
</head>

<body>
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

      <br>
      <a href="menu.html" class="btn btn-custom mt-2">Volver al Menú</a>
      <a href="reenviar_codigo.html" class="btn btn-custom mt-2">Volver a enviar codigo</a>
    </div>
  </div>

  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
