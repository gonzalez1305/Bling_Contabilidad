<?php
require "conexion.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$telefono = $_POST["telefono"];
$direccion = $_POST["direccion"];
$fecha_de_nacimiento = $_POST["fecha"];
$correo = $_POST["correo"];
$contraseña = $_POST["contraseña"];
$cod_vendedor = isset($_POST["cod_vendedor"]) ? $_POST["cod_vendedor"] : "";
$estado = "Activo";
$cliente = 2; 
$vendedor = 1; 
$tipo_usuario = isset($_POST["esVendedor"]) ? $vendedor : $cliente;


$verificarCorreo = "SELECT COUNT(*) as total FROM usuario WHERE correo = '$correo'";
$queryVerificarCorreo = mysqli_query($conectar, $verificarCorreo);
$resultadoVerificarCorreo = mysqli_fetch_assoc($queryVerificarCorreo);

if ($resultadoVerificarCorreo['total'] > 0) {
    echo "<script>";
    echo "alert('El correo ya está registrado. Por favor, elija otro correo.');";
    echo "window.history.back();";
    echo "</script>";
} else {

    $contraseñaCifrada = password_hash($contraseña, PASSWORD_DEFAULT);


    $codigo_verificacion = rand(100000, 999999);

 
    $insertusuario = "INSERT INTO usuario (nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, contraseña, estado, tipo_usuario, fk_id_rol, codigo_verificacion) VALUES ('$nombre', '$apellido', '$telefono', '$direccion', '$fecha_de_nacimiento', '$correo', '$contraseñaCifrada', '$estado', '$tipo_usuario', '$tipo_usuario', '$codigo_verificacion')";
    $queryusuario = mysqli_query($conectar, $insertusuario);

    if ($queryusuario) {
        $idGeneradousuario = mysqli_insert_id($conectar);

        // Enviar correo de verificación
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
        $mail->setFrom('blingcontabilidadgaes@gmail.com', 'Bling Contabilidad');
            $mail->addAddress($correo);

   
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Código de Verificación';
            $mail->Body    = 'Tu código de verificación es: ' . $codigo_verificacion;

            $mail->send();

    
            echo "<script>";
            echo "alert('Usuario registrado exitosamente. Por favor, revisa tu correo para el código de verificación.');";
            echo "window.location.href = 'verificacion.php';";
            echo "</script>";

        } catch (Exception $e) {
            echo "<script>";
            echo "alert('Error al enviar el correo: " . $mail->ErrorInfo . "');";
            echo "window.history.back();";
            echo "</script>";
        }

        if ($tipo_usuario == $vendedor) {
            $insertVendedor = "INSERT INTO administrador (cod_vendedor, fk_id_usuario) VALUES('$cod_vendedor','$idGeneradousuario')";
            $queryVendedor = mysqli_query($conectar, $insertVendedor);
            
            if ($queryVendedor) {
                echo "<script>";
                echo "alert('Vendedor registrado correctamente.');";
                echo "window.location.href = 'menu.html';";
                echo "</script>";
            } else {
                echo "<script>";
                echo "alert('Error al registrar el vendedor. Por favor, intente nuevamente.');";
                echo "window.history.back();";
                echo "</script>";
            }
        }
    } else {
        echo "<script>";
        echo "alert('Error al registrarse. Por favor, intente nuevamente.');";
        echo "window.history.back();";
        echo "</script>";
    }
}
?>
