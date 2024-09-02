<?php
include '../conexion.php'; // Incluye tu archivo de conexión

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $situacion = $_POST['situacion'];
    $fk_id_usuario = $_POST['fk_id_usuario'];

    // Inserta el pedido en la tabla `pedido`
    $insertPedido = "INSERT INTO pedido (fecha, situacion, fk_id_usuario) VALUES ('$fecha', '$situacion', '$fk_id_usuario')";
    $queryInsert = mysqli_query($conectar, $insertPedido);

    if ($queryInsert) {
        echo "<script>alert('Pedido creado exitosamente.'); window.location.href = 'verPedidos.php';</script>";
    } else {
        echo "<script>alert('Error al crear el pedido.'); window.history.back();</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido</title>
</head>
<body>
    <h2>Crear Pedido</h2>
    <form action="crearPedido.php" method="post">
        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required><br><br>

        <label for="situacion">Situación:</label>
        <input type="text" name="situacion" required><br><br>

        <label for="fk_id_usuario">ID Usuario:</label>
        <input type="number" name="fk_id_usuario" required><br><br>

        <input type="submit" value="Crear Pedido">
    </form>

    <h3>Productos disponibles:</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Talla</th>
                <th>Categoría</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta para obtener los productos
            $queryProductos = "SELECT nombre, cantidad, talla, categorias FROM producto";
            $resultProductos = mysqli_query($conectar, $queryProductos);

            if (mysqli_num_rows($resultProductos) > 0) {
                while ($row = mysqli_fetch_assoc($resultProductos)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['talla']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['categorias']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay productos disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
