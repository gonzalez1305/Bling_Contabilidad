<?php
require "conexion.php";

$errorMensaje = "";  // Variable para almacenar el mensaje de error
$advertencia = "";   // Variable para almacenar el mensaje de advertencia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];

    // Consultar la base de datos para obtener la información del usuario
    $consultaUsuario = "SELECT id_usuario, contraseña, tipo_usuario, estado FROM usuario WHERE correo = '$correo'";
    $resultadoConsulta = mysqli_query($conectar, $consultaUsuario);

    if ($resultadoConsulta) {
        $usuario = mysqli_fetch_assoc($resultadoConsulta);

        if ($usuario) {
            if (password_verify($contraseña, $usuario["contraseña"])) {
                if ($usuario["estado"] == "Verificado") {
                    // Iniciar sesión
                    session_start();
                    $_SESSION["id_usuario"] = $usuario["id_usuario"];
                    $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

                    // Redirigir al menú correspondiente
                    if ($usuario["tipo_usuario"] == 1) {
                        header("Location: menuV.html"); // Redirigir a la interfaz del vendedor
                    } elseif ($usuario["tipo_usuario"] == 2) {
                        header("Location: menuC.html"); // Redirigir a la interfaz del cliente
                    } else {
                        // Manejar otros tipos de usuarios si es necesario
                        echo "Tipo de usuario no reconocido";
                    }

                    exit();
                } else {
                    $advertencia = "Tu cuenta no está verificada. <a href='reenviar_codigo.html'>Reenviar código de verificación</a>";
                }
            } else {
                $errorMensaje = "Correo o contraseña incorrectos";
            }
        } else {
            $errorMensaje = "Correo no encontrado";
        }
    } else {
        $errorMensaje = "Error al realizar la consulta: " . mysqli_error($conectar);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login</title>
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

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            z-index: 1;
            transition: background-color 0.5s, color 0.5s;
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
            transition: background-color 0.5s, color 0.5s;
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
            transition: background-color 0.5s;
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

        .error-message, .advertencia-message {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.5s, color 0.5s;
        }

        .error-message {
            background-color: #ffffff;
            color: #ff0000;
        }

        .dark-mode .error-message {
            background-color: #e6e6e6;
            color: #ff0000;
        }

        .advertencia-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .dark-mode .advertencia-message {
            background-color: #5a5a5a;
            color: #d9534f;
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

        .btn-back {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.5s;
            margin: 10px 5px;
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
    <div class="login-container">
        <div class="login-header">
            <h1>Inicio de Sesión</h1>
        </div>
        <?php if (!empty($errorMensaje)): ?>
            <div class="error-message">
                <?php echo $errorMensaje; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($advertencia)): ?>
            <div class="advertencia-message">
                <?php echo $advertencia; ?>
            </div>
        <?php endif; ?>
        <form class="login-form" action="" method="post">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3 position-relative">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                <span class="password-toggle" onclick="togglePassword()">&#128065;</span>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                <label class="form-check-label" for="invalidCheck">
                    Acepto todas las condiciones
                </label>
            </div>
            <button class="btn btn-primary" type="submit">Iniciar Sesión</button>
        </form>
        <div class="login-footer">
            <a href="Registro.html" class="btn-back">Registrarse</a>
            <a href="menu.html" class="btn-back">Volver al Menú</a>
            <a href="recuperar_contraseña.php" class="btn-back">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function toggleMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.querySelector('.toggle-btn i');
            icon.classList.toggle('fa-sun');
            icon.classList.toggle('fa-moon');
        }

        function togglePassword() {
            const passwordField = document.getElementById('contraseña');
            const passwordToggle = document.querySelector('.password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.innerHTML = '&#128065;'; // Ojo abierto
            } else {
                passwordField.type = 'password';
                passwordToggle.innerHTML = '&#128065;'; // Ojo cerrado
            }
        }
    </script>
</body>
</html>

    </script>
</body>
</html>