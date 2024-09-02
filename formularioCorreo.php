<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de PQRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="imgs/logo.png">
    <style>
        /* Estilos generales de la pagina */
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #007bff;  /* Fondo azul */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px; 
            position: relative; 
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
        .error-message {
            background-color: #ffffff;
            color: #ff0000;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .warning-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: absolute; 
            width: calc(100% - 60px); 
            top: -40px; 
            left: 30px; 
        }
        .row .col-md-6 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
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
                            <option value="Consulta">Consulta</option> <!-- Nuevo tipo -->
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
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const nombre = document.getElementById('nombre').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            const tipo_pqrs = document.getElementById('tipo_pqrs').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            let isValid = true;

            // Ocultar mensajes de error
            document.querySelectorAll('.field-error').forEach(el => el.style.display = 'none');

            // Validaciones
            if (!email || !nombre || !telefono || !tipo_pqrs || !subject || !message) {
                document.getElementById('errorMessages').innerHTML = 'Por favor, complete todos los campos.';
                document.getElementById('errorMessages').style.display = 'block';
                isValid = false;
            }

            if (!/^[a-zA-Z\s]+$/.test(nombre)) {
                document.getElementById('nombreError').innerHTML = 'El nombre solo debe contener letras.';
                document.getElementById('nombreError').style.display = 'block';
                isValid = false;
            }

            // Validación del número de teléfono (formato internacional)
            if (!/^\+?[1-9]\d{1,14}$/.test(telefono)) {
                document.getElementById('telefonoError').innerHTML = 'El número de teléfono debe ser en formato internacional.';
                document.getElementById('telefonoError').style.display = 'block';
                isValid = false;
            }

            if (message.length < 10) {
                document.getElementById('messageError').innerHTML = 'El mensaje debe tener al menos 10 caracteres.';
                document.getElementById('messageError').style.display = 'block';
                isValid = false;
            }

            // Mostrar errores
            if (!isValid) {
                return false;  // Evitar el envío del formulario
            }
            
            // Enviar el formulario si no hay errores
            document.getElementById('errorMessages').style.display = 'none';
            return true;
        }
    </script>
</body>
</html>
