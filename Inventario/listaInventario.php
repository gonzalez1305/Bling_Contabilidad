<?php
require '../conexion.php'; // Conexion

// Verifica si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_inventario = $_POST['eliminar_id'];

    // Eliminar el registro del inventario 
    $sql_delete = "DELETE FROM inventario WHERE id_inventario = '$id_inventario'";

    if (mysqli_query($conectar, $sql_delete)) {
        
        echo "<script>alert('Inventario eliminado correctamente');</script>";
    } else {
        
        echo "Error al eliminar el inventario: " . mysqli_error($conectar);
    }
}

// Consulta 
$sql_select = "SELECT * FROM inventario";
$resultado = mysqli_query($conectar, $sql_select);

// Verificar si hay registros
if (mysqli_num_rows($resultado) > 0) {
    
    echo "<h1>Listado de Inventario</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Cantidad</th><th>Fecha</th><th>Cantidad Disponible</th><th>Referencia</th><th>ID Vendedor</th><th>Acciones</th></tr>";
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>" . $fila['id_inventario'] . "</td>";
        echo "<td>" . $fila['cantidad'] . "</td>";
        echo "<td>" . $fila['fecha'] . "</td>";
        echo "<td>" . $fila['cantidad_disponible'] . "</td>";
        echo "<td>" . $fila['referencia'] . "</td>";
        echo "<td>" . $fila['id_vendedor'] . "</td>";
        echo "<td>";
        echo "<a href='editarInventario.php?id=" . $fila['id_inventario'] . "'>Editar</a> | ";
        echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
        echo "<input type='hidden' name='eliminar_id' value='" . $fila['id_inventario'] . "'>";
        echo "<input type='submit' value='Eliminar'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros de inventario.";
}


mysqli_close($conectar);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Inventario</title>
  <link rel="stylesheet" href="../bling/css/style_inventario.css">
</head>
<body>

<div class="container">
  <a href="crearInventario.php" class="btn-agregar">Agregar Nuevo Inventario</a>
  <a href="../Bling/dashboard_v.html" class="volver-btn">Volver al Dashboard</a>
</div>

</body>
</html>
