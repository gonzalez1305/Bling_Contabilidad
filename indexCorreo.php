<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Correo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 15;
            padding: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type="email"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enviar Correo</h1>
        <form>
            <input type="email" name="email" placeholder="Introduce el correo del destinatario" required value="carolg998488@gmail.com">
            <input type="submit" value="Enviar Correo">
        </form>

        <?php
        $to = "carolg998488@gmail.com";
        $subject = "Bling Contabilidad";
        $message = "Escribenos si tienes dudas";
        $headers = 'From: dannyrojas@gmail.com' . "\r\n" . 'Reply-to: juanma@gmail.com';

        if (mail($to, $subject, $message, $headers)) {
            echo "<p class='success'>El correo enviado a $to fue correcto</p>";
        } else {
            echo "<p class='error'>El correo no se pudo enviar</p>";
        }
        ?>
    </div>
</body>
</html>
