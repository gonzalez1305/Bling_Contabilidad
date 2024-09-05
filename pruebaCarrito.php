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
        $productQuery = "SELECT * FROM producto WHERE id_producto = ? AND estado = 'disponible'";
        if ($stmt = $conectar->prepare($productQuery)) {
            $stmt->bind_param('i', $idProducto);
            if ($stmt->execute()) {
                $productResult = $stmt->get_result();
                if ($productResult->num_rows > 0) {
                    $product = $productResult->fetch_assoc();
                    if ($cantidad <= $product['cantidad']) {
                        // Iniciar la transacción
                        $conectar->begin_transaction();
                        
                        try {
                            // Verificar si el producto ya está en el carrito
                            $checkCartQuery = "SELECT * FROM carrito WHERE fk_id_producto = ? AND fk_id_usuario = ?";
                            if ($stmt = $conectar->prepare($checkCartQuery)) {
                                $stmt->bind_param('ii', $idProducto, $idUsuario);
                                if ($stmt->execute()) {
                                    $cartResult = $stmt->get_result();
                                    if ($cartResult->num_rows > 0) {
                                        // Actualizar cantidad en el carrito
                                        $updateCartQuery = "UPDATE carrito SET cantidad = cantidad + ? WHERE fk_id_producto = ? AND fk_id_usuario = ?";
                                        if ($stmt = $conectar->prepare($updateCartQuery)) {
                                            $stmt->bind_param('iii', $cantidad, $idProducto, $idUsuario);
                                            $stmt->execute();
                                        } else {
                                            throw new Exception('Error en la consulta de actualización del carrito.');
                                        }
                                    } else {
                                        // Insertar nuevo producto en el carrito
                                        $insertCartQuery = "INSERT INTO carrito (fk_id_producto, cantidad, fk_id_usuario) VALUES (?, ?, ?)";
                                        if ($stmt = $conectar->prepare($insertCartQuery)) {
                                            $stmt->bind_param('iii', $idProducto, $cantidad, $idUsuario);
                                            $stmt->execute();
                                        } else {
                                            throw new Exception('Error en la consulta de inserción del carrito.');
                                        }
                                    }

                                    // Descontar la cantidad en la tabla producto
                                    $updateProductQuery = "UPDATE producto SET cantidad = cantidad - ? WHERE id_producto = ?";
                                    if ($stmt = $conectar->prepare($updateProductQuery)) {
                                        $stmt->bind_param('ii', $cantidad, $idProducto);
                                        $stmt->execute();
                                    } else {
                                        throw new Exception('Error en la consulta de actualización del producto.');
                                    }

                                    // Confirmar la transacción
                                    $conectar->commit();

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
                                            $response = ['status' => 'success', 'message' => 'Producto añadido al carrito.', 'carrito' => $carrito];
                                        } else {
                                            $response = ['status' => 'error', 'message' => 'Error al obtener los detalles del carrito.'];
                                        }
                                    } else {
                                        $response = ['status' => 'error', 'message' => 'Error en la consulta de detalles del carrito.'];
                                    }
                                } else {
                                    $response = ['status' => 'error', 'message' => 'Error en la consulta de carrito.'];
                                }
                            } else {
                                $response = ['status' => 'error', 'message' => 'Error en la consulta de verificación del carrito.'];
                            }
                        } catch (Exception $e) {
                            $conectar->rollback();
                            $response = ['status' => 'error', 'message' => $e->getMessage()];
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
    $response = ['status' => 'error', 'message' => 'Datos incompletos.'];
}

echo json_encode($response);
?>
