<?php
require '../conexion.php'; // Conexión a la base de datos
session_start();

// Verificar si se recibió el ID del pago a eliminar
if (isset($_GET['id'])) {
    $id_pago = intval($_GET['id']);

    // Consulta para eliminar el pago
    $deleteQuery = "DELETE FROM pagos WHERE id_pago = $id_pago";
    
    // Ejecutar la consulta y verificar si se eliminó correctamente
    if (mysqli_query($conectar, $deleteQuery)) {
        $_SESSION['mensaje'] = "Pago eliminado exitosamente";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el pago";
    }

    // Redirigir de vuelta a la lista de pagos
    header("Location: verPago.php");
    exit();
} else {
    $_SESSION['mensaje'] = "ID de pago no proporcionado";
    header("Location: verPago.php");
    exit();
}

mysqli_close($conectar);
?>
