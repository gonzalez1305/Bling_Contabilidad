<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de ajustar la ruta si no usas Composer
require 'conexion.php'; // Archivo para conectar con la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Generar un código de recuperación aleatorio
        $codigoRecuperacion = bin2hex(random_bytes(4)); // Genera un código de 8 caracteres hexadecimales

        // Enviar el código por correo electrónico
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blingcontabilidadgaes@gmail.com';
            $mail->Password = 'mgzhlqxhogvdnlnm'; // Usa contraseñas de aplicación si es necesario
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Destinatario
            $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Tu Nombre');
            $mail->addAddress($correo);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Código de Recuperación de Contraseña';
            $mail->Body    = 'Tu código de recuperación es: <b>' . $codigoRecuperacion . '</b>';

            $mail->send();

            // Guardar el código de recuperación en la base de datos
            $stmt = $conectar->prepare("UPDATE usuario SET codigo_recuperacion = ? WHERE correo = ?");
            if ($stmt === false) {
                die('Error al preparar la consulta: ' . $conectar->error);
            }
            $stmt->bind_param("ss", $codigoRecuperacion, $correo);
            if ($stmt->execute()) {
                echo "<script>alert('Código de recuperación enviado a tu correo.'); window.location.href = 'verificar_codigo.php';</script>";
            } else {
                echo "<script>alert('Error al actualizar el código de recuperación.'); window.history.back();</script>";
            }
            $stmt->close();

        } catch (Exception $e) {
            echo 'Error al enviar el correo: ', $mail->ErrorInfo;
        }
    } else {
        echo "<script>alert('Dirección de correo inválida.'); window.history.back();</script>";
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
  <title>Recuperar Contraseña</title>

  <style>
    body {
      background-color: #007bff;  /* Fondo azul */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .recovery-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
    }

    .recovery-container h1 {
      margin-bottom: 20px;
    }

    .recovery-container .form-control {
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="recovery-container">
    <h1>Recuperar Contraseña</h1>
    <form method="post" action="">
      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
      </div>
      <button type="submit" class="btn btn-primary">Enviar Código</button>
    </form>
  </div>

  <!-- Bootstrap Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
