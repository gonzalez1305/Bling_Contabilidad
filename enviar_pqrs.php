<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para validar el número de teléfono (solo dígitos)
function validarTelefono($telefono) {
    return preg_match('/^\d+$/', $telefono);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $tipo_pqrs = $_POST["tipo_pqrs"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $errors = [];

    // Validaciones
    if (empty($email) || empty($nombre) || empty($telefono) || empty($tipo_pqrs) || empty($subject) || empty($message)) {
        $errors[] = 'Por favor, complete todos los campos.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'El correo electrónico no es válido.';
    }

    if (!validarTelefono($telefono)) {
        $errors[] = 'El número de teléfono debe contener solo dígitos.';
    }

    if (strlen($message) < 1) {
        $errors[] = 'El mensaje debe contener al menos un carácter.';
    }

    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        header("Location: formulario_pqrs.html");
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'blingcontabilidadgaes@gmail.com';
        $mail->Password = 'mgzhlqxhogvdnlnm'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');
        $mail->addAddress('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');

        // Contenido del correo enviado
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "PQR desde $email - Motivo: $subject";
        $mail->Body    = "
            <h1>Nuevo PQR recibido</h1>
            <p><strong>Nombre del remitente:</strong> $nombre</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Teléfono:</strong> $telefono</p>
            <p><strong>Tipo de PQR:</strong> $tipo_pqrs</p>
            <p><strong>Asunto:</strong> $subject</p>
            <p><strong>Mensaje:</strong><br>$message</p>
        ";

        $mail->send();
        header("Location: exito.html");
        exit();
    } catch (Exception $e) {
        session_start();
        $_SESSION['errors'] = ['Error al enviar el PQRS: ' . $mail->ErrorInfo];
        header("Location: formulario_pqrs.html");
        exit();
    }
} else {
    header("Location: formulario_pqrs.html");
    exit();
}
?>
