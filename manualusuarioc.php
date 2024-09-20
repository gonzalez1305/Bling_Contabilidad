<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Uso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="/imgs/logo.png">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #9ec8d6, #d5e5ea, #ffffff);
            color: #333;
            transition: background 0.5s, color 0.5s;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dark-mode {
            background: linear-gradient(to bottom, #2c2b33, #1a1a1a, #000000);
            color: #ffffff;
        }

        h1, h2, p {
            transition: color 0.5s;
        }

        .dark-mode h1 {
            color: #007bff;
        }

        .dark-mode h2 {
            color: #0056b3;
        }

        .dark-mode p {
            color: #dddddd;
        }

        .container {
            max-width: 800px;
            margin: 20px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: background 0.5s, color 0.5s;
        }

        .dark-mode .container {
            background: rgba(50, 50, 50, 0.9);
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

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .dark-mode .back-btn {
            background-color: #0056b3;
        }

        .dark-mode .back-btn:hover {
            background-color: #003f7f;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }

        ul li a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        ul li a:hover {
            color: #0056b3;
        }

        .dark-mode ul li a {
            color: #66b2ff;
        }

        .dark-mode ul li a:hover {
            color: #3399ff;
        }

        iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleMode()"><i class="fa-solid fa-sun"></i></button>

    <div class="container">
        <h1>Manual de Uso</h1>
        <iframe src="https://www.youtube.com/embed/qzQfSE5tc5k?si=6ea1kM-0nV96RaC5" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <ul>
            <li><a href="https://youtu.be/qzQfSE5tc5k?si=6ea1kM-0nV96RaC5&t=20" target="_blank">00:20 - Editar usuario</a></li>
            <li><a href="https://youtu.be/qzQfSE5tc5k?si=6ea1kM-0nV96RaC5&t=60" target="_blank">01:00 - Cambiar contraseña</a></li>
            <li><a href="https://youtu.be/qzQfSE5tc5k?si=6ea1kM-0nV96RaC5&t=85" target="_blank">01:25 - Ver catálogo y comprar / Uso del carrito</a></li>
            <li><a href="https://youtu.be/qzQfSE5tc5k?si=6ea1kM-0nV96RaC5&t=160" target="_blank">02:40 - Recuperar contraseña o código</a></li>
        </ul>
        <a href="menuC.php" class="back-btn">Volver al Menú</a>
    </div>

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