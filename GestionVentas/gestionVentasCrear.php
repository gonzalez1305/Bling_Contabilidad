<?php
require '../conexion.php'; // Conexion
?>

<?php

$estado = '';
$fecha_venta = '';
$cantidad = '';
$precio_unitario = '';
$id_vendedor = ''; 
$id_venta = ''; 
$id_producto = ''; 
$comprobante = ''; 
// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera y limpia los datos del formulario
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $fecha_venta = mysqli_real_escape_string($conectar, $_POST['fecha_venta']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);
    $id_venta = mysqli_real_escape_string($conectar, $_POST['id_venta']);
    $id_producto = mysqli_real_escape_string($conectar, $_POST['id_producto']);
    $comprobante = mysqli_real_escape_string($conectar, $_POST['comprobante']);

    
    $fecha_registro = date('Y-m-d'); 
    $fecha_actualizacion = date('Y-m-d'); 

    $sql_insert = "INSERT INTO gestion_ventas 
                   (id_venta, id_vendedor, id_producto, cantidad, precio_unitario, fecha_venta, estado, comprobante, fecha_registro, fecha_actualizacion)
                   VALUES ('$id_venta', '$id_vendedor', '$id_producto', '$cantidad', '$precio_unitario', '$fecha_venta', '$estado', '$comprobante', '$fecha_registro', '$fecha_actualizacion')";

    if (mysqli_query($conectar, $sql_insert)) {
        
        echo "<script>alert('Venta registrada correctamente');</script>";
        echo "<script>window.location.href = 'gestionVlista.php';</script>";
        exit;
    } else {
        
        echo "Error al registrar la venta: " . mysqli_error($conectar);
    }
}


mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Venta</title>
  <link rel="stylesheet" href="../bling/css/style_pago.css">
</head>
<body>

<div class="container">
  <h1>Agregar Nueva Venta</h1>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" required><br><br>

    <label for="fecha_venta">Fecha de Venta:</label>
    <input type="date" id="fecha_venta" name="fecha_venta" required><br><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" required><br><br>

    <label for="precio_unitario">Precio Unitario:</label>
    <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" required><br><br>

    <label for="id_vendedor">ID del Vendedor:</label>
    <input type="number" id="id_vendedor" name="id_vendedor" required><br><br>

    <label for="id_venta">ID de la Venta:</label>
    <input type="number" id="id_venta" name="id_venta" required><br><br>

    <label for="id_producto">ID del Producto:</label>
    <input type="number" id="id_producto" name="id_producto" required><br><br>

    <label for="comprobante">Comprobante:</label>
    <input type="text" id="comprobante" name="comprobante"><br><br>

    <input type="submit" value="Registrar Venta">
  </form>

  <a href="gestionVlista.php" class="volver-btn">Volver al Listado de Ventas</a>
  <a href="../Bling/dashboard_v.html" class="volver-btn">Volver al Dashboard</a>
  <a href="../Bling/col_pago_list.php" class="volver-btn">Ver Pagos Realizados</a>
</div>

</body>
</html>
