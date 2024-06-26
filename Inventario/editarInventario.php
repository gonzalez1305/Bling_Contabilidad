<?php
require '../conexion.php'; // Conexion


$id_inventario = '';
$cantidad = '';
$fecha = '';
$cantidad_disponible = '';
$referencia = '';
$id_vendedor = ''; 

// Verifica si se recibió un id para la edicion
if (isset($_GET['id'])) {
    $id_inventario = $_GET['id'];

    // Consulta del inventario
    $sql_select = "SELECT * FROM inventario WHERE id_inventario = '$id_inventario'";
    $resultado = mysqli_query($conectar, $sql_select);

    if (mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);
        $cantidad = $fila['cantidad'];
        $fecha = $fila['fecha'];
        $cantidad_disponible = $fila['cantidad_disponible'];
        $referencia = $fila['referencia'];
        $id_vendedor = $fila['id_vendedor'];
    } else {
        echo "No se encontró el inventario especificado.";
        exit;
    }
}

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_inventario = mysqli_real_escape_string($conectar, $_POST['id_inventario']);
    $cantidad = mysqli_real_escape_string($conectar, $_POST['cantidad']);
    $fecha = mysqli_real_escape_string($conectar, $_POST['fecha']);
    $cantidad_disponible = mysqli_real_escape_string($conectar, $_POST['cantidad_disponible']);
    $referencia = mysqli_real_escape_string($conectar, $_POST['referencia']);
    $id_vendedor = mysqli_real_escape_string($conectar, $_POST['id_vendedor']);

    // Actualizar los datos 
    $sql_update = "UPDATE inventario SET
                   cantidad = '$cantidad',
                   fecha = '$fecha',
                   cantidad_disponible = '$cantidad_disponible',
                   referencia = '$referencia',
                   id_vendedor = '$id_vendedor'
                   WHERE id_inventario = '$id_inventario'";

    if (mysqli_query($conectar, $sql_update)) {
        
        echo "<script>alert('Inventario actualizado correctamente');</script>";
        echo "<script>window.location.href = 'listaInventario.php';</script>";
        exit;
    } else {
        
        echo "Error al actualizar el inventario: " . mysqli_error($conectar);
    }
}


mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Inventario</title>
  <link rel="stylesheet" href="../bling/css/style_inventario.css">
</head>
<body>

<div class="container">
  <h1>Editar Inventario</h1>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="id_inventario" value="<?php echo $id_inventario; ?>">

    <label for="cantidad">Cantidad:</label>
    <input type="number" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>" required><br><br>

    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required><br><br>

    <label for="cantidad_disponible">Cantidad Disponible:</label>
    <input type="number" id="cantidad_disponible" name="cantidad_disponible" value="<?php echo $cantidad_disponible; ?>" required><br><br>

    <label for="referencia">Referencia:</label>
    <input type="text" id="referencia" name="referencia" value="<?php echo $referencia; ?>" required><br><br>

    <label for="id_vendedor">ID del Vendedor:</label>
    <input type="number" id="id_vendedor" name="id_vendedor" value="<?php echo $id_vendedor; ?>" required><br><br>

    <input type="submit" value="Actualizar Inventario">
  </form>

  <a href="listaInventario.php" class="volver-btn">Volver al Listado de Inventario</a>
  <a href="../Bling/dashboard_v.html" class="volver-btn">Volver al Dashboard</a>
  <a href="../Bling/col_pago_list.php" class="volver-btn">Ver Pagos Realizados</a>
</div>

</body>
</html>
