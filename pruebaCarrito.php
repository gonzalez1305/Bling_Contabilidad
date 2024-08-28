<?php
session_start();
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

if (isset($_POST['idProducto']) && isset($_POST['cantidad']) && isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];

    // Verificar si el producto existe en la tabla producto
    $checkProduct = "SELECT * FROM producto WHERE id_producto = '$idProducto'";
    $productResult = mysqli_query($conectar, $checkProduct);

    if (mysqli_num_rows($productResult) > 0) {
        // Verificar si el producto ya está en el carrito
        $checkCart = "SELECT * FROM carrito WHERE fk_id_producto = '$idProducto' AND fk_id_usuario = '$idUsuario'";
        $result = mysqli_query($conectar, $checkCart);

        if (mysqli_num_rows($result) > 0) {
            // Si el producto ya está en el carrito, actualizar la cantidad
            $updateCart = "UPDATE carrito SET cantidad = cantidad + $cantidad WHERE fk_id_producto = '$idProducto' AND fk_id_usuario = '$idUsuario'";
            if (!mysqli_query($conectar, $updateCart)) {
                echo "Error al actualizar el carrito: " . mysqli_error($conectar);
                exit;
            }
        } else {
            // Si no está en el carrito, agregarlo
            $insertCart = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) VALUES ('$idProducto', '$cantidad', '$idUsuario')";
            if (!mysqli_query($conectar, $insertCart)) {
                echo "Error al añadir al carrito: " . mysqli_error($conectar);
                exit;
            }
        }
    } else {
        // Producto no encontrado
        echo "El producto no existe o no está disponible.";
        exit;
    }
}

// Redirigir de vuelta a la página principal
header('Location: Producto/niño.php');
exit;
?>
