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
    $cantidad = intval($_POST['cantidad']); // Puede ser positivo o negativo para agregar o quitar
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

        // Verificar si la cantidad es positiva o negativa (agregar o quitar del carrito)
        if ($cantidad > 0 && $cantidad <= $cantidadDisponible) {
            // Añadir el producto al carrito o actualizar la cantidad
            $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
            $stmt = $conectar->prepare($insertCartQuery);
            $stmt->bind_param('iiii', $idProducto, $cantidad, $idUsuario, $cantidad);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Producto añadido al carrito'];
            } else {
                $response['message'] = 'No se pudo añadir el producto al carrito.';
            }
        } elseif ($cantidad < 0) {
            // Eliminar productos del carrito
            $removeCartQuery = "UPDATE carrito SET cantidad = cantidad + ? WHERE fk_id_producto = ? AND fk_id_usuario = ?";
            $stmt = $conectar->prepare($removeCartQuery);
            $stmt->bind_param('iii', $cantidad, $idProducto, $idUsuario);
            $stmt->execute();

            // Si la cantidad en el carrito es 0 o menor, eliminar el registro del carrito
            $deleteCartQuery = "DELETE FROM carrito WHERE cantidad <= 0 AND fk_id_producto = ? AND fk_id_usuario = ?";
            $stmt = $conectar->prepare($deleteCartQuery);
            $stmt->bind_param('ii', $idProducto, $idUsuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Producto eliminado del carrito'];
            } else {
                $response['message'] = 'No se pudo actualizar el carrito.';
            }
        } else {
            $response['message'] = 'Cantidad no válida o insuficiente.';
        }

        // Obtener el nuevo estado del carrito
        $cartQuery = "SELECT p.nombre, SUM(c.cantidad) as cantidad, p.precio_unitario 
                      FROM carrito c
                      JOIN producto p ON c.fk_id_producto = p.id_producto
                      WHERE c.fk_id_usuario = ?
                      GROUP BY p.id_producto";
        $stmt = $conectar->prepare($cartQuery);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $cartResult = $stmt->get_result();

        $cartItems = [];
        $total = 0; // Inicializar el total

        while ($row = $cartResult->fetch_assoc()) {
            $subtotal = $row['precio_unitario'] * $row['cantidad'];
            $total += $subtotal; // Acumulando el total
            $cartItems[] = [
                'nombre' => htmlspecialchars($row['nombre']),
                'cantidad' => $row['cantidad'],
                'precio_unitario' => $row['precio_unitario'],
                'subtotal' => $subtotal
            ];
        }

        $response['carrito'] = $cartItems;
        $response['total'] = $total; // Enviar el total sin formato
    } else {
        $response['message'] = 'Producto no encontrado.';
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

$conectar->close();
?>
