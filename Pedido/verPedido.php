<?php
include '../conexion.php'; // Incluye tu archivo de conexión

$query = "SELECT p.id_pedido, p.fecha, p.situacion, u.nombre as usuario 
          FROM pedido p 
          JOIN usuario u ON p.fk_id_usuario = u.id_usuario";
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Pedidos</title>
</head>
<body>
    <h2>Lista de Pedidos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Situación</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id_pedido']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['situacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                    echo "<td>
                            <a href='editarPedido.php?id=" . $row['id_pedido'] . "'>Editar</a> | 
                            <a href='eliminarPedido.php?id=" . $row['id_pedido'] . "' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay pedidos registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="crearPedido.php">Crear Nuevo Pedido</a>
</body>
</html>
