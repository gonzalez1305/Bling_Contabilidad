<?php
require "conexion.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Buscar el usuario por correo
        $query = "SELECT codigo_verificacion FROM usuario WHERE correo = '$correo' AND estado = 'Activo'";
        $result = mysqli_query($conectar, $query);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);

            // Generar un nuevo código de verificación
            $nuevo_codigo_verificacion = rand(100000, 999999);

            // Este genera el nuevo codigo en la base de datos
            $updateQuery = "UPDATE usuario SET codigo_verificacion = '$nuevo_codigo_verificacion' WHERE correo = '$correo'";
            mysqli_query($conectar, $updateQuery);

            // Enviar correo con el nuevo código de verificación
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'blingcontabilidadgaes@gmail.com';
                $mail->Password = 'mgzhlqxhogvdnlnm'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Destinatario del correo
                $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');
                $mail->addAddress($correo);

                // Contenido
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Establece la codificación en UTF-8
                $mail->Subject = 'Nuevo Código de Verificación';
                $mail->Body    = 'Tu nuevo código de verificación es: ' . $nuevo_codigo_verificacion;

                $mail->send();

                echo "<script>";
                echo "alert('El código de verificación ha sido reenviado a tu correo.');";
                echo "window.location.href = 'verificacion.php';";
                echo "</script>";

            } catch (Exception $e) {
                echo "<script>";
                echo "alert('Error al enviar el correo: " . $mail->ErrorInfo . "');";
                echo "window.history.back();";
                echo "</script>";
            }
        } else {
            echo "<script>";
            echo "alert('No se encontró ninguna cuenta con ese correo.');";
            echo "window.history.back();";
            echo "</script>";
        }
    } else {
        echo "<script>";
        echo "alert('Por favor, ingresa un correo válido.');";
        echo "window.history.back();";
        echo "</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de PQRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="imgs/logo.png">
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

        /* Dark Mode styles */
        .dark-mode {
            background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
            color: #ffffff;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            width: 90vw;
            max-width: 1200px;
            border-radius: 10px;
            overflow: hidden;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #000000;
            border: 2px solid #cccccc;
            border-radius: 10px;
            transition: background-color 0.5s, color 0.5s, border-color 0.5s;
        }

        /* Form styles for dark mode */
        .dark-mode .form-container {
            background-color: rgba(50, 50, 50, 0.9);
            color: #ffffff;
            border-color: #444444;
        }

        .login-header {
            background-color: #cccccc;
            color: #000000;
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
            width: 100%;
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

        .error-message, .warning-message {
            width: 100%;
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

        .warning-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .warning-message {
            background-color: #5a5a5a;
            color: #d9534f;
        }

        .btn-back {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: none;
            text-align: center;
        }

        .dark-mode .btn-back {
            background-color: #0056b3;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        .dark-mode .btn-back:hover {
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
    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>
    <div class="container">
        <div class="form-container">
            <div class="warning-message">
                Por favor, ponga datos reales para poder comunicarnos y responder a tu consulta.
            </div>
            <div class="login-header">
                <h1>Formulario de PQRS</h1>
            </div>
            <form class="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Código</button>
            </form>
            <a href="index.php" class="btn-back">Volver al Menú</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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
