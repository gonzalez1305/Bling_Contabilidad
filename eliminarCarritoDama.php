<?php

// eliminarProductoCarrito.php
session_start();
include 'conexion.php';

if (isset($_POST['idProducto']) && isset($_SESSION['id_usuario'])) {
    $idProducto = $_POST['idProducto'];
    $idUsuario = $_SESSION['id_usuario'];

    // Primero, obtenemos la cantidad del carrito antes de eliminarlo
    $queryCantidad = "SELECT cantidad FROM carrito WHERE fk_id_producto = ? AND fk_id_usuario = ?";
    $stmtCantidad = $conectar->prepare($queryCantidad);
    $stmtCantidad->bind_param("ii", $idProducto, $idUsuario);
    $stmtCantidad->execute();
    $resultCantidad = $stmtCantidad->get_result();

    if ($resultCantidad->num_rows > 0) {
        $row = $resultCantidad->fetch_assoc();
        $cantidadCarrito = $row['cantidad'];

        // Eliminar el producto del carrito
        $queryEliminar = "DELETE FROM carrito WHERE fk_id_producto = ? AND fk_id_usuario = ?";
        $stmtEliminar = $conectar->prepare($queryEliminar);
        $stmtEliminar->bind_param("ii", $idProducto, $idUsuario);

        if ($stmtEliminar->execute()) {
            // Una vez eliminado del carrito, devolvemos la cantidad al stock del producto
            $queryActualizarStock = "UPDATE producto SET cantidad = cantidad + ? WHERE id_producto = ?";
            $stmtActualizarStock = $conectar->prepare($queryActualizarStock);
            $stmtActualizarStock->bind_param("ii", $cantidadCarrito, $idProducto);
            $stmtActualizarStock->execute();

            // Redirigir de vuelta a la página de productos
            header("Location: ./Inventario/dama.php");
            exit();
        } else {
            echo "Error al eliminar el producto del carrito.";
        }

        $stmtEliminar->close();
        $stmtActualizarStock->close();
    } else {
        echo "Producto no encontrado en el carrito.";
    }

    $stmtCantidad->close();
    $conectar->close();
}


?>
