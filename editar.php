<?php
include("conexion.php");

// Inicializar variables
$pedidoDetalles = array(); // Inicializa un array vacío para evitar advertencias

if(isset($_POST['enviar'])){
    // Si se ha enviado el formulario
    $unidades = $_POST["unidades"];
    $id_detalles_pedido = $_POST["id_detalles_pedido"];

    // Obtén el precio unitario del producto
    $sqlPrecioUnitario = "SELECT precio_unitario FROM producto WHERE id_producto = (SELECT fk_id_producto FROM detalles_pedido WHERE id_detalles_pedido = '".$id_detalles_pedido."')";
    $resultadoPrecioUnitario = mysqli_query($conectar, $sqlPrecioUnitario);

    if ($resultadoPrecioUnitario && mysqli_num_rows($resultadoPrecioUnitario) > 0) {
        $filaPrecioUnitario = mysqli_fetch_assoc($resultadoPrecioUnitario);
        $precioUnitario = $filaPrecioUnitario['precio_unitario'];

        // Calcula el nuevo precio total
        $nuevoPrecioTotal = $precioUnitario * $unidades;

        // Actualiza las unidades y el precio total en la base de datos
        $sql = "UPDATE detalles_pedido SET unidades='".$unidades."', precio_total='".$nuevoPrecioTotal."' WHERE id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultado = mysqli_query($conectar, $sql);

        if($resultado){
            echo "<script language='javascript'>";
            echo "alert('Los datos se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        } else {
            echo "<script language='javascript'>";
            echo "alert('Los datos NO se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        }
    } else {
        echo "Error al obtener el precio unitario del producto.";
    }

    mysqli_close($conectar);

} else {
    // Si no se ha enviado el formulario
    if(isset($_GET['id_detalles_pedido'])){
        $id_detalles_pedido = $_GET['id_detalles_pedido'];
        $sql = "SELECT * FROM pedido
                INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
                INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto
                WHERE detalles_pedido.id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultado = mysqli_query($conectar, $sql);

        if($resultado && mysqli_num_rows($resultado) > 0){
            $pedidoDetalles = mysqli_fetch_assoc($resultado);
            $id_detalles_pedido = $pedidoDetalles['id_detalles_pedido'];
            $unidades = $pedidoDetalles['unidades'];
        } else {
            echo "Error al recuperar datos de la base de datos.";
        }
    } else {
        echo "Error: No se proporcionó el ID del pedido a editar.";
        exit; // Termina el script si no hay ID de pedido
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <link rel="stylesheet" href="css/estilopedido.css">
</head>
<body>
    <div class="pedido-container">
        <h1> EDITAR PEDIDO</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="id_detalles_pedido" value="<?php echo $id_detalles_pedido; ?>">
            <p><strong>ID Pedido:</strong><?php echo $pedidoDetalles['id_pedido']; ?></p>
            <p><strong>ID Usuario:</strong><?php echo $pedidoDetalles['fk_id_usuario']; ?></p>
            <p><strong>Fecha:</strong><?php echo $pedidoDetalles['fecha']; ?></p>
            <p><strong>Situacion:</strong><?php echo $pedidoDetalles['situacion']; ?></p>
            <p><strong>Nombre:</strong><?php echo $pedidoDetalles['nombre']; ?></p>
            <p><strong>Precio:</strong><?php echo $pedidoDetalles['precio_total']; ?></p>
            <label><strong>Unidades:</strong></label>
            <input type="text" name="unidades" value="<?php echo $unidades; ?>"><br><br>
            <input type="submit" name="enviar" value="ACTUALIZAR">
            <a href="validarpedido.php">Regresar</a>  
        </form>
    </div>
</body>
</html>
