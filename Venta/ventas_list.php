
<?php
require "conexion.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_gestion_venta = $_POST['eliminar_id'];

    // Para eliminar el registro de gestión de ventas correspondiente
    $sql_delete_gestion_venta = "DELETE FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
    
    if (mysqli_query($conectar, $sql_delete_gestion_venta)) {

      echo "<script>alert('Gestión de Venta eliminada correctamente');</script>";
    } else {

        echo "Error al eliminar la gestión de venta: " . mysqli_error($conectar);
    }
}


$sql_select = "SELECT * FROM gestion_ventas";
$resultado = mysqli_query($conectar, $sql_select);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Ventas</title>
  <link rel="stylesheet" href="../bling/css/style_pago.css">
</head>
<body>

<div class="container">
  <h1>Listado de Ventas</h1>

  <table>
    <thead>
      <tr>
        <th>ID de Gestión de Venta</th>
        <th>ID de Venta</th>
        <th>Fecha de Venta</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php

      if (mysqli_num_rows($resultado) > 0) {
        while ($row = mysqli_fetch_assoc($resultado)) {
          echo "<tr>";
          echo "<td>" . $row['id_gestion_venta'] . "</td>";
          echo "<td>" . $row['id_venta'] . "</td>";
          echo "<td>" . $row['fecha_venta'] . "</td>";
          echo "<td>" . $row['cantidad'] . "</td>";
          echo "<td>" . $row['precio_unitario'] . "</td>";
          echo "<td>";
          echo "<a href='editarGestionVentas.php?id=" . $row['id_gestion_venta'] . "' class='editar-btn'>Editar</a>";
          echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
          echo "<input type='hidden' name='eliminar_id' value='" . $row['id_gestion_venta'] . "'>";
          echo "<input type='submit' value='Eliminar'>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='6'>No hay ventas registradas</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <a href="gestionVentasCrear.php" class="crear-btn">Agregar Nueva Venta</a>
  <a href="../Bling/dashboard_v.html" class="volver-btn">Volver al Dashboard</a>
  <a href="../Bling/col_pago_list.php" class="volver-btn">Ver Pagos Realizados</a>
</div>

</body>
</html>

<?php

mysqli_close($conectar);
?>
