<?php
require "conexion.php";

$errorMensaje = "";  // Variable para almacenar el mensaje de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];

    $consultaUsuario = "SELECT id_usuario, contraseña, tipo_usuario FROM usuario WHERE correo = '$correo'";
    $resultadoConsulta = mysqli_query($conectar, $consultaUsuario);

    if ($resultadoConsulta) {
        $usuario = mysqli_fetch_assoc($resultadoConsulta);

        if ($usuario && password_verify($contraseña, $usuario["contraseña"])) {
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
            $errorMensaje = "Correo o contraseña incorrectos";
        }
    } else {
        $errorMensaje = "Error al realizar la consulta: " . mysqli_error($conectar);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="imgs/logo.png">
    <title>Login</title>
    <style>
        body {
            background-color: #007bff;  /* Fondo azul */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            z-index: 1;
        }
        .login-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .login-form {
            padding: 20px;
        }
        .login-form .form-control {
            margin-bottom: 15px;
        }
        .login-form .btn-primary {
            width: 100%;
        }
        .login-form .form-check {
            text-align: left;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
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
    <div id="particles-js"></div>
    <div class="login-container">
        <div class="login-header">
            <h1>Inicio de Sesión</h1>
        </div>
        <?php if (!empty($errorMensaje)): ?>
            <div class="error-message">
                <?php echo $errorMensaje; ?>
            </div>
        <?php endif; ?>
        <form class="login-form" action="" method="post">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
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
            <a href="Registro.html">Registrarse</a>
            <br>
            <a href="menu.html" class="btn btn-custom mt-2">Volver al Menú</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('particles.js loaded - callback');
        });
    </script>
</body>
</html>