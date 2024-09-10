<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de ajustar la ruta si no usas Composer
require 'conexion.php'; // Archivo para conectar con la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Verificar si el correo existe en la base de datos
        $stmt = $conectar->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conectar->error);
        }
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
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
                $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');
                $mail->addAddress($correo);

                // Contenido
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Establece la codificación en UTF-8
                $mail->Subject = 'Código de Recuperación de Contraseña';
                $mail->Body    = 'Tu código de recuperación es: <b>' . $codigoRecuperacion . '</b>';

                $mail->send();

                // Guardar el código de recuperación en la base de datos
                $updateStmt = $conectar->prepare("UPDATE usuario SET codigo_recuperacion = ? WHERE correo = ?");
                if ($updateStmt === false) {
                    die('Error al preparar la consulta: ' . $conectar->error);
                }
                $updateStmt->bind_param("ss", $codigoRecuperacion, $correo);
                if ($updateStmt->execute()) {
                    echo "<script>alert('Código de recuperación enviado a tu correo.'); window.location.href = 'verificar_codigo.php';</script>";
                } else {
                    echo "<script>alert('Error al actualizar el código de recuperación.'); window.history.back();</script>";
                }
                $updateStmt->close();

            } catch (Exception $e) {
                echo "<script>alert('Error al enviar el correo: " . $mail->ErrorInfo . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Correo no encontrado.'); window.history.back();</script>";
        }

        $stmt->close();
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    body {
      background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      transition: background 0.5s, color 0.5s;
    }

    .dark-mode {
      background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
      color: #ffffff;
    }

    .recovery-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
      transition: background-color 0.5s, color 0.5s;
    }

    .dark-mode .recovery-container {
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

    .btn-custom {
      background-color: #007bff;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
    }

    .dark-mode .btn-custom {
      background-color: #0056b3;
    }

    .btn-custom:hover {
      background-color: #0056b3;
    }

    .dark-mode .btn-custom:hover {
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
      transition: transform 0.3s ease;
    }

    .dark-mode .toggle-btn i {
      transform: rotate(180deg);
    }
  </style>
</head>

<body>
  <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>
  <div class="recovery-container">
    <h1>Recuperar Contraseña</h1>
    <form method="post" action="">
      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
      </div>
      <button type="submit" class="btn btn-primary">Enviar Código</button>
      <a href="menu.html" class="btn btn-custom mt-2">Volver al Menú</a>
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
  </script>
</body>
</html>
