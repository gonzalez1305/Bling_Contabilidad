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
            <form class="login-form" action="enviar_pqrs.php" method="post" onsubmit="return validateForm()">
                <div id="errorMessages" class="error-message" style="display: none;"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div id="emailError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div id="nombreError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            <div id="telefonoError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tipo_pqrs" class="form-label">Tipo de PQR</label>
                            <select class="form-select" id="tipo_pqrs" name="tipo_pqrs" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Queja">Queja</option>
                                <option value="Sugerencia">Sugerencia</option>
                                <option value="Reclamo">Reclamo</option>
                                <option value="Consulta">Consulta</option>
                            </select>
                            <div id="tipo_pqrsError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                            <div id="subjectError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required minlength="10"></textarea>
                            <div id="messageError" class="field-error" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button><br><br>
            </form>
        </div>
    </div>
    <script>
        function toggleMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.querySelector('.toggle-btn i');
            icon.classList.toggle('fa-sun');
            icon.classList.toggle('fa-moon');
        }

        function validateForm() {
            
        }
    </script>
</body>
</html>
