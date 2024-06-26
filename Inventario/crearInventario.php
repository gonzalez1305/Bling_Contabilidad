<?php
require '../conexion.php'; // Conexion


$cantidad = '';
$fecha = '';
$cantidad_disponible = '';
$referencia = '';
$id_vendedor = ''; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $fecha = mysqli_real_escape_string($conectar, $_POST['fecha']);
    $cantidad_disponible = mysqli_real_escape_string($conectar, $_POST['cantidad_disponible']);
    $referencia = mysqli_real_escape_string($conectar, $_POST['referencia']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);

    // Inserción de los datos 
    $sql_insert = "INSERT INTO inventario (cantidad, fecha, cantidad_disponible, referencia, id_vendedor)
                   VALUES ('$cantidad', '$fecha', '$cantidad_disponible', '$referencia', '$id_vendedor')";

    if (mysqli_query($conectar, $sql_insert)) {
        
        echo "<script>alert('Inventario registrado correctamente');</script>";
        echo "<script>window.location.href = 'listaInventario.php';</script>";
        exit;
    } else {
        
        echo "Error al registrar el inventario: " . mysqli_error($conectar);
    }
}


mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Inventario</title>
  <link rel="stylesheet" href="../bling/css/style_inventario.css">
</head>
<body>

<div class="container">
  <h1>Agregar Nuevo Inventario</h1>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" required><br><br>

    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" required><br><br>

    <label for="cantidad_disponible">Cantidad Disponible:</label>
    <input type="number" id="cantidad_disponible" name="cantidad_disponible" required><br><br>

    <label for="referencia">Referencia:</label>
    <input type="text" id="referencia" name="referencia" required><br><br>

    <label for="id_vendedor">ID del Vendedor:</label>
    <input type="number" id="id_vendedor" name="id_vendedor" required><br><br>

    <input type="submit" value="Registrar Inventario">
  </form>

  <a href="listaInventario.php" class="volver-btn">Volver al Listado de Inventario</a>
  <a href="../Bling/dashboard_v.html" class="volver-btn">Volver al Dashboard</a>
  <a href="../Bling/col_pago_list.php" class="volver-btn">Ver Pagos Realizados</a>
</div>

</body>
</html>
