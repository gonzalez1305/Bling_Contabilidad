<?php
session_start();
include 'conexion.php';

if (!$conectar) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la conexión a la base de datos.']);
    exit();
}

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado.']);
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

if (isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
    $idProducto = intval($_POST['idProducto']);
    $cantidad = intval($_POST['cantidad']);

    if ($cantidad > 0) {
        // Obtener la cantidad disponible del producto
        $productQuery = "SELECT cantidad FROM producto WHERE id_producto = ? AND estado = 'disponible'";
        if ($stmt = $conectar->prepare($productQuery)) {
            $stmt->bind_param('i', $idProducto);
            if ($stmt->execute()) {
                $productResult = $stmt->get_result();
                if ($productResult->num_rows > 0) {
                    $product = $productResult->fetch_assoc();
                    $cantidadDisponible = $product['cantidad'];

                    // Verificar si la cantidad solicitada excede la cantidad disponible
                    if ($cantidad <= $cantidadDisponible) {
                        // Verificar si el producto ya está en el carrito
                        $checkCartQuery = "SELECT cantidad FROM carrito WHERE fk_id_producto = ? AND fk_id_usuario = ?";
                        if ($stmt = $conectar->prepare($checkCartQuery)) {
                            $stmt->bind_param('ii', $idProducto, $idUsuario);
                            if ($stmt->execute()) {
                                $cartResult = $stmt->get_result();
                                if ($cartResult->num_rows > 0) {
                                    $cartItem = $cartResult->fetch_assoc();
                                    $cantidadEnCarrito = $cartItem['cantidad'];

                                    // Verificar si agregar la nueva cantidad supera la disponible
                                    if (($cantidadEnCarrito + $cantidad) <= $cantidadDisponible) {
                                        $updateCartQuery = "UPDATE carrito SET cantidad = cantidad + ? WHERE fk_id_producto = ? AND fk_id_usuario = ?";
                                        if ($stmt = $conectar->prepare($updateCartQuery)) {
                                            $stmt->bind_param('iii', $cantidad, $idProducto, $idUsuario);
                                            if ($stmt->execute()) {
                                                $response = ['status' => 'success', 'message' => 'Producto actualizado en el carrito.'];
                                            } else {
                                                $response = ['status' => 'error', 'message' => 'Error al actualizar el carrito.'];
                                            }
                                        } else {
                                            $response = ['status' => 'error', 'message' => 'Error en la consulta de actualización.'];
                                        }
                                    } else {
                                        $response = ['status' => 'error', 'message' => 'La cantidad total en el carrito excede la cantidad disponible.'];
                                    }
                                } else {
                                    $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) VALUES (?, ?, ?)";
                                    if ($stmt = $conectar->prepare($insertCartQuery)) {
                                        $stmt->bind_param('iii', $idProducto, $cantidad, $idUsuario);
                                        if ($stmt->execute()) {
                                            $response = ['status' => 'success', 'message' => 'Producto añadido al carrito.'];
                                        } else {
                                            $response = ['status' => 'error', 'message' => 'Error al añadir el producto al carrito.'];
                                        }
                                    } else {
                                        $response = ['status' => 'error', 'message' => 'Error en la consulta de inserción.'];
                                    }
                                }

                                // Obtener detalles del carrito
                                $cartDetailsQuery = "SELECT p.nombre, c.cantidad, (p.precio_unitario * c.cantidad) AS precio_total 
                                                     FROM carrito c
                                                     JOIN producto p ON c.fk_id_producto = p.id_producto
                                                     WHERE c.fk_id_usuario = ?";
                                if ($stmt = $conectar->prepare($cartDetailsQuery)) {
                                    $stmt->bind_param('i', $idUsuario);
                                    if ($stmt->execute()) {
                                        $cartResult = $stmt->get_result();
                                        $carrito = [];
                                        while ($row = $cartResult->fetch_assoc()) {
                                            $carrito[] = $row;
                                        }
                                        $response['carrito'] = $carrito;
                                    } else {
                                        $response['message'] = 'Error al obtener los detalles del carrito.';
                                    }
                                } else {
                                    $response['message'] = 'Error en la consulta de detalles del carrito.';
                                }
                            } else {
                                $response = ['status' => 'error', 'message' => 'Error en la consulta de carrito.'];
                            }
                        } else {
                            $response = ['status' => 'error', 'message' => 'Error en la consulta de verificación del carrito.'];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => 'La cantidad solicitada excede la cantidad disponible.'];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => 'El producto no está disponible.'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Error al ejecutar la consulta del producto.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Error en la consulta del producto.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Cantidad inválida.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Datos de entrada inválidos.'];
}

echo json_encode($response);
?>
