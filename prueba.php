<?php
require "conexion.php";

// Verificar si las variables están definidas
if (isset($_POST["cantidad"]) && isset($_GET['idUsuario']) && isset($_GET['idProducto'])) {
    $unidades = $_POST["cantidad"];
    $idUsuario = $_GET['idUsuario'];
    $idProducto = $_GET['idProducto'];
    $fecha = date("Y-m-d");
    $situacion = "En proceso";

    // Obtener el precio unitario del producto
    $sqlPrecioUnitario = "SELECT precio_unitario FROM producto WHERE id_producto = '$idProducto'";
    $resultadoPrecioUnitario = mysqli_query($conectar, $sqlPrecioUnitario);

    if ($resultadoPrecioUnitario) {
        $filaPrecioUnitario = mysqli_fetch_assoc($resultadoPrecioUnitario);
        
        if ($filaPrecioUnitario) {
            $precioUnitario = $filaPrecioUnitario['precio_unitario'];

            // Validar el precio unitario por la cantidad
            $totalPrecio = $precioUnitario * $unidades;

            // Creamos la sentencia SQL para guardar pedido
            $insertPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES('$fecha', '$situacion','$idUsuario')";
            $queryPedido = mysqli_query($conectar, $insertPedido);

            if ($queryPedido) {
                $idGeneradoPedido = mysqli_insert_id($conectar);
                echo "Pedido generado exitosamente ID: $idGeneradoPedido <br><br>";

                // Creamos la sentencia SQL para guardar el detalle del pedido
                $insertDetalle = "INSERT INTO detalles_pedido (unidades, precio_total, fk_id_producto, fk_id_pedido) VALUES('$unidades', '$totalPrecio', '$idProducto', '$idGeneradoPedido')";
                $queryDetalle = mysqli_query($conectar, $insertDetalle);

                if ($queryDetalle) {
                    $idGeneradoDetallePedido = mysqli_insert_id($conectar);
                    echo "Detalle de pedido generado exitosamente ID: $idGeneradoDetallePedido";
                } else {
                    echo "Error al insertar el detalle del pedido: " . mysqli_error($conectar);
                }
            } else {
                echo "Error al insertar el pedido: " . mysqli_error($conectar);
            }
        } else {
            echo "No se encontró el producto con ID: $idProducto";
        }
    } else {
        echo "Error al obtener el precio unitario del producto: " . mysqli_error($conectar);
    }
} else {
    echo "Error: Datos incompletos.";
}
?>