<?php
session_start();
include 'conexion.php';

$response = ['status' => 'error', 'message' => 'Error desconocido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['id_usuario'])) {
        $response['message'] = 'No estás logueado.';
        echo json_encode($response);
        exit();
    }

    $idProducto = intval($_POST['idProducto']);
    $cantidad = intval($_POST['cantidad']); // La cantidad debe ser positiva
    $idUsuario = intval($_SESSION['id_usuario']);
    $talla = $_POST['talla']; // Obtener la talla seleccionada

    // Verificar la existencia del producto y la cantidad disponible en la tabla tallas
    $checkSizeQuery = "SELECT t.cantidad, p.precio_unitario FROM tallas t 
                       JOIN producto p ON t.fk_id_producto = p.id_producto 
                       WHERE t.fk_id_producto = ? AND t.talla = ?";
    $stmt = $conectar->prepare($checkSizeQuery);
    $stmt->bind_param('is', $idProducto, $talla);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $cantidadDisponible = $product['cantidad'];
        $precioUnitario = $product['precio_unitario'];

        // Verificar si la cantidad es válida
        if ($cantidad > 0 && $cantidad <= $cantidadDisponible) {
            // Añadir o actualizar el producto en el carrito
            $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario, talla) VALUES (?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
            $stmt = $conectar->prepare($insertCartQuery);
            $stmt->bind_param('iiis', $idProducto, $cantidad, $idUsuario, $talla); // Añadir la talla
            if ($stmt->execute()) {
                // Actualizar la cantidad disponible de tallas
                $updateQuantityQuery = "UPDATE tallas SET cantidad = cantidad - ? WHERE fk_id_producto = ? AND talla = ?";
                $stmt = $conectar->prepare($updateQuantityQuery);
                $stmt->bind_param('iis', $cantidad, $idProducto, $talla);
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Producto añadido al carrito con éxito.';
                } else {
                    $response['message'] = 'Error al actualizar la cantidad de tallas.';
                    error_log('Error al actualizar cantidad para producto ID ' . $idProducto);
                }
            } else {
                $response['message'] = 'Error al añadir el producto al carrito.';
                error_log('Error al añadir al carrito para producto ID ' . $idProducto);
            }
        } else {
            $response['message'] = 'Cantidad no válida o insuficiente.';
        }
    } else {
        $response['message'] = 'Producto o talla no encontrada.';
    }

    echo json_encode($response);
}
?>