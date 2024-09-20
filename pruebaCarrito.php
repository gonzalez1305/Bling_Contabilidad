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

    // Verificar la existencia del producto y la cantidad disponible
    $checkProductQuery = "SELECT cantidad, precio_unitario FROM producto WHERE id_producto = ?";
    $stmt = $conectar->prepare($checkProductQuery);
    $stmt->bind_param('i', $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $cantidadDisponible = $product['cantidad'];
        
        $precioUnitario = $product['precio_unitario'];

        // Verificar si la cantidad es válida
        if ($cantidad > 0 && $cantidad <= $cantidadDisponible) {
            // Añadir o actualizar el producto en el carrito
            $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) VALUES (?, ?, ?)
                                ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
            $stmt = $conectar->prepare($insertCartQuery);
            $stmt->bind_param('iii', $idProducto, $cantidad, $idUsuario);
            if ($stmt->execute()) {
                // Actualizar la cantidad disponible del producto
                $updateQuantityQuery = "UPDATE producto SET cantidad = cantidad - ? WHERE id_producto = ?";
                $stmt = $conectar->prepare($updateQuantityQuery);
                $stmt->bind_param('ii', $cantidad, $idProducto);
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Producto añadido al carrito con éxito.';
                } else {
                    $response['message'] = 'Error al actualizar la cantidad del producto.';
                }
            } else {
                $response['message'] = 'Error al añadir el producto al carrito.';
            }
        } else {
            $response['message'] = 'Cantidad no válida o insuficiente.';
        }
    } else {
        $response['message'] = 'Producto no encontrado.';
    }

    echo json_encode($response);
}
?>
