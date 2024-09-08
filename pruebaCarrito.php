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
    $cantidad = intval($_POST['cantidad']);
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

        if ($cantidad > 0 && $cantidad <= $cantidadDisponible) {
            // Insertar el producto en el carrito o actualizar la cantidad
            $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
            $stmt = $conectar->prepare($insertCartQuery);
            $stmt->bind_param('iiii', $idProducto, $cantidad, $idUsuario, $cantidad);
            $stmt->execute();

            // Actualizar la cantidad del producto en el inventario
            $updateProductQuery = "UPDATE producto SET cantidad = cantidad - ? WHERE id_producto = ?";
            $stmt = $conectar->prepare($updateProductQuery);
            $stmt->bind_param('ii', $cantidad, $idProducto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Producto añadido al carrito'];

                // Obtener los detalles del carrito
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
                $response['message'] = 'No se pudo actualizar el producto.';
            }
        } else {
            $response['message'] = 'Cantidad no válida o insuficiente.';
        }
    } else {
        $response['message'] = 'Producto no encontrado.';
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

$conectar->close();
