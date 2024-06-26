<?php
require '../conexion.php'; // Asegúrate de incluir correctamente el archivo de conexión

// Variables para almacenar los valores del formulario
$estado = '';
$fecha_venta = '';
$cantidad = '';
$precio_unitario = '';
$id_vendedor = ''; // Asegúrate de obtener correctamente el ID del vendedor desde tu sistema
$id_venta = ''; // Asegúrate de obtener correctamente el ID de la venta desde tu sistema
$id_producto = ''; // Asegúrate de obtener correctamente el ID del producto desde tu sistema
$comprobante = ''; // Asegúrate de obtener correctamente el nombre o ruta del comprobante desde tu sistema
$id_gestion_venta = ''; // Variable para almacenar el ID de gestión venta

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y limpiar los datos del formulario
    $id_gestion_venta = mysqli_real_escape_string($conectar, $_POST['id_gestion_venta']);
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $fecha_venta = mysqli_real_escape_string($conectar, $_POST['fecha_venta']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);
    $id_venta = mysqli_real_escape_string($conectar, $_POST['id_venta']);
    $id_producto = mysqli_real_escape_string($conectar, $_POST['id_producto']);
    $comprobante = mysqli_real_escape_string($conectar, $_POST['comprobante']);

    // Actualizar los datos en la base de datos
    $fecha_actualizacion = date('Y-m-d'); // Fecha actualización

    $sql_update = "UPDATE gestion_ventas SET 
                   estado = '$estado', 
                   fecha_venta = '$fecha_venta', 
                   cantidad = '$cantidad', 
                   precio_unitario = '$precio_unitario', 
                   id_vendedor = '$id_vendedor', 
                   id_venta = '$id_venta', 
                   id_producto = '$id_producto', 
                   comprobante = '$comprobante', 
                   fecha_actualizacion = '$fecha_actualizacion' 
                   WHERE id_gestion_venta = '$id_gestion_venta'";

    if (mysqli_query($conectar, $sql_update)) {
        // Éxito al actualizar
        echo "<script>alert('Venta actualizada correctamente');</script>";
        echo "<script>window.location.href = 'gestionVlista.php';</script>";
        exit;
    } else {
        // Error al actualizar
        echo "Error al actualizar la venta: " . mysqli_error($conectar);
    }
} else {
    // Si no se envió el formulario, recuperar el id_gestion_venta de la URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id_gestion_venta = mysqli_real_escape_string($conectar, $_GET['id']);

        // Consultar la base de datos para obtener los datos del registro a editar
        $sql_select = "SELECT * FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
        $resultado = mysqli_query($conectar, $sql_select);

        if ($resultado) {
            $fila = mysqli_fetch_assoc($resultado);
            // Asignar los valores de la consulta a las variables del formulario
            $estado = $fila['estado'];
            $fecha_venta = $fila['fecha_venta'];
            $cantidad = $fila['cantidad'];
            $precio_unitario = $fila['precio_unitario'];
            $id_vendedor = $fila['id_vendedor'];
            $id_venta = $fila['id_venta'];
            $id_producto = $fila['id_producto'];
            $comprobante = $fila['comprobante'];
        } else {
            echo "Error al consultar la base de datos: " . mysqli_error($conectar);
        }
    } else {
        echo "ID de venta no especificado.";
    }
}

// Cerrar conexión
mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Venta</title>
  <link rel="stylesheet" href="../bling/css/style_pago.css">
</head>
<body>

<div class="container">
  <h1>Editar Venta</h1>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="id_gestion_venta" value="<?php echo $id_gestion_venta; ?>">
    <!-- Campos del formulario con valores prellenados -->
    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" value="<?php echo $estado; ?>" required><br><br>

    <label for="fecha_venta">Fecha de Venta:</label>
    <input type="date" id="fecha_venta" name="fecha_venta" value="<?php echo $fecha_venta; ?>" required><br><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>" required><br><br>

    <label for="precio_unitario">Precio Unitario:</label>
    <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" value="<?php echo $precio_unitario; ?>" required><br><br>

    <label for="id_vendedor">ID del Vendedor:</label>
    <input type="number" id="id_vendedor" name="id_vendedor" value="<?php echo $id_vendedor; ?>" required><br><br>

    <label for="id_venta">ID de la Venta:</label>
    <input type="number" id="id_venta" name="id_venta" value="<?php echo $id_venta; ?>" required><br><br>

    <label for="id_producto">ID del Producto:</label>
    <input type="number" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>" required><br><br>

    <label for="comprobante">Comprobante:</label>
    <input type="text" id="comprobante" name="comprobante" value="<?php echo $comprobante; ?>"><br><br>

    <input type="submit" value="Actualizar Venta">
  </form>

  <a href="gestionVlista.php" class="volver-btn">Volver al Listado de Ventas</a>
  <a href="../menuV.html" class="volver-btn">Volver al Dashboard</a>
  <a href="../Bling/col_pago_list.php" class="volver-btn">Ver Pagos Realizados</a>
</div>

</body>
</html>
