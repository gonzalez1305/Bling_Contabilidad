<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización Exitosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .message {
            text-align: center;
        }
        .message h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>¡La información se ha actualizado correctamente!</h1>
        <p>Redirigiendo a la página de listado...</p>
    </div>

    <script>
        // Redirigir a la página de listado después de 2 segundos
        setTimeout(function() {
            window.location.href = './infocliente.php'; 
        }, 2000);
    </script>
</body>
</html>
