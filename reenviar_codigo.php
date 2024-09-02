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
