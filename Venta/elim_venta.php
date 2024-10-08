<?php
// Conectar a la base de datos
$conectar = mysqli_connect('localhost:3307', 'root', '', 'bling_o');

// Definir la variable $id_venta
$id_venta = $_GET['id_venta'];

if (!$conectar) {
    echo "Error: No se pudo conectar a la base de datos.";
    exit;
}

// Eliminar las transacciones asociadas a la venta
$sql = "DELETE FROM transacciones WHERE fk_id_pago = $id_venta";
$resultado = mysqli_query($conectar, $sql);

if ($resultado) {
    // Eliminar el pago
    $sql = "DELETE FROM pago WHERE fk_id_venta = $id_venta";
    $resultado = mysqli_query($conectar, $sql);

    if ($resultado) {
        // Eliminar la venta
        $sql = "DELETE FROM venta WHERE id_venta = $id_venta";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado) {
            header('Location: ventas_list.php');
        } else {
            echo "No se pudo eliminar la venta.";
        }
    } else {
        echo "No se pudo eliminar el pago.";
    }
} else {
    echo "No se pudieron eliminar las transacciones.";
}
