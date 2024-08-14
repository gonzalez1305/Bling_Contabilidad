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
                echo "<div style='background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; margin: 0 auto; max-width: 500px;'>
                        <h2 style='margin-bottom: 20px;'>Factura Generada</h2>
                        <p style='font-size: 18px;'>ID del Pedido: $idGeneradoPedido</p>
                        <p style='font-size: 18px;'>Fecha: $fecha</p>
                        <p style='font-size: 18px;'>Producto: $idProducto</p>
                        <p style='font-size: 18px;'>Unidades: $unidades</p>
                        <p style='font-size: 18px;'>Precio Unitario: $precioUnitario</p>
                        <p style='font-size: 18px;'>Total: $totalPrecio</p>
                        <a href='menuC.html' style='font-size: 18px;'>Volver</a>
                    </div>";
            } else {
                echo "<div style='background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; margin: 0 auto; max-width: 500px;'>
                        <h2 style='margin-bottom: 20px;'>Error al Generar la Factura</h2>
                        <p style='font-size: 18px;'>Error: " . mysqli_error($conectar) . "</p>
                        <a href='menuC.html' style='font-size: 18px;'>Volver</a>
                    </div>";
            }
        } else {
            echo "<div style='background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; margin: 0 auto; max-width: 500px;'>
                    <h2 style='margin-bottom: 20px;'>Error al Generar la Factura</h2>
                    <p style='font-size: 18px;'>No se encontró el producto con ID: $idProducto</p>
                    <a href='menuC.html' style='font-size: 18px;'>Volver</a>
                </div>";
        }
    } else {
        echo "<div style='background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; margin: 0 auto; max-width: 500px;'>
                <h2 style='margin-bottom: 20px;'>Error al Generar la Factura</h2>
                <p style='font-size: 18px;'>Error: " . mysqli_error($conectar) . "</p>
                <a href='menuC.html' style='font-size: 18px;'>Volver</a>
            </div>";
    }
} else {
    echo "<div style='background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; text-align: center; margin: 0 auto; max-width: 500px;'>
            <h2 style='margin-bottom: 20px;'>Error al Generar la Factura</h2>
            <p style='font-size: 18px;'>Error: Datos incompletos.</p>
            <a href='menuC.html' style='font-size: 18px;'>Volver</a>
        </div>";
}
?>