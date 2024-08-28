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
  <title>Verificar Código</title>

  <style>
    body {
      background-color: #007bff;  /* Fondo azul */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .verification-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
    }

    .verification-container h1 {
      margin-bottom: 20px;
    }

    .verification-container .form-control {
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
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
      <div class="mb-3">
        <label for="nueva_contraseña" class="form-label">Nueva Contraseña</label>
        <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required minlength="8">
      </div>
      <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </form>
  </div>

  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
