<?php
require '../conexion.php';

// Verificar si se ha enviado el ID del producto para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_producto = mysqli_real_escape_string($conectar, $_GET['id']);

    // Consulta para eliminar el producto
    $sql_delete = "DELETE FROM producto WHERE id_producto = '$id_producto'";

    if (mysqli_query($conectar, $sql_delete)) {
        // Éxito al eliminar
        echo "<script>alert('Producto eliminado correctamente');</script>";
        echo "<script>window.location.href = 'productosLista.php';</script>";
        exit;
    } else {
        // Error al eliminar
        echo "Error al eliminar el producto: " . mysqli_error($conectar);
    }
} else {
    echo "ID de producto no especificado.";
}

// Cerrar conexión
mysqli_close($conectar);
?>
