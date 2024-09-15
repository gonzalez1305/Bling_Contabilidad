<?php
$to = "juanandresramirezmunoz94@gmail.com";
$subject = "PQRS";


$status = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        $message = $_POST['message'];

        $headers = 'From: juanandresramirezmunoz94@gmail.com' . "\r\n" .
                   'Reply-To: jmsanchezromero14@gmail.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $status = 'success'; // Enviar éxito
        } else {
            $status = 'error'; // Error en el envío
        }

        // Redirige a la misma página con un parámetro de estado
        header("Location: enviar_correo.php?status=$status");
        exit();
    } else {
        $status = 'empty'; // Mensaje vacío
        header("Location: enviar_correo.php?status=$status");
        exit();
    }
}

// Mostrar el resultado basado en el estado del parámetro de URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Correo - Bling Compra</title>
    <link rel="icon" href="imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #ffffff;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="card">
                <div class="card-header">
                    Resultado del Envío de Correo
                </div>
                <div class="card-body">
                    <?php
                    if ($status == 'success') {
                        echo '<div class="alert alert-success" role="alert">Correo enviado correctamente.</div>';
                    } elseif ($status == 'error') {
                        echo '<div class="alert alert-danger" role="alert">Error al enviar el correo.</div>';
                    } elseif ($status == 'empty') {
                        echo '<div class="alert alert-danger" role="alert">El mensaje no puede estar vacío.</div>';
                    }
                    ?>
                </div>
            </div>
            <a class="btn btn-light text-primary mt-3" href="formularioCorreo.php" role="button">Volver al Formulario</a>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
