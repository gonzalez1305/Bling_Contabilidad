<?php
session_start();
include '../conexion.php'; 

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php'); // Redirigir si no está logueado
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

$query = "SELECT p.id_pedido, p.fecha, p.situacion 
          FROM pedido p 
          WHERE p.fk_id_usuario = '$idUsuario'";
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ver Pedidos</title>
</head>
<body>
    <div class="container">
        <h2>Lista de Pedidos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Situación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id_pedido']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['situacion']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No hay pedidos.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="../menuC.html" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>
