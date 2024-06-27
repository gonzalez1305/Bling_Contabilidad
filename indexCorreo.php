<?php
$to = "sofianajar486@gmail.com";
$subject="Bling Contabilidad";
$message="Escribenos si tienes dudas";
$headers='From: dannyrojas@gmail.com'."\r\n".'Reply-to: juanma@gmail.com';


if (mail($to, $subject, $message, $headers)){

    echo "El correo enviado a $to fue correcto";
}
else{
    echo "El correo no se pudo enviar";
}
?>