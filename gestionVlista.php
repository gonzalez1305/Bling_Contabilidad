<?php
require './conexion.php'; // Incluir archivo de conexión

// Verificar si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_gestion_venta = $_POST['eliminar_id'];

    // Consulta para eliminar la gestión de ventas correspondiente
    $sql_delete_gestion_venta = "DELETE FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
    
    if (mysqli_query($conectar, $sql_delete_gestion_venta)) {
        echo "<script>alert('Gestión de Venta eliminada correctamente');</script>";
    } else {
        echo "Error al eliminar la gestión de venta: " . mysqli_error($conectar);
    }
}

// Consulta para seleccionar todas las gestiones de ventas
$sql_select = "SELECT * FROM gestion_ventas";
$resultado = mysqli_query($conectar, $sql_select);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Ventas</title>
    <link rel="stylesheet" href="../bling/css/style_pago.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .editar-btn, .eliminar-btn {
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 3px;
            color: #fff;
        }
        .editar-btn {
            background-color: #007bff;
        }
        .eliminar-btn {
            background-color: #dc3545;
        }
        .crear-btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Listado de Ventas</h1>

    <table id="ventasTable" class="table table-striped">
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
                    echo "<td>" . htmlspecialchars($row['id_gestion_venta']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['id_venta']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_venta']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['precio_unitario']) . "</td>";
                    echo "<td>";
                    echo "<a href='editarGestionVentas.php?id=" . $row['id_gestion_venta'] . "' class='editar-btn'>Editar</a>";
                    echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
                    echo "<input type='hidden' name='eliminar_id' value='" . $row['id_gestion_venta'] . "'>";
                    echo "<input type='submit' class='eliminar-btn' value='Eliminar'>";
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
    <div class="REPORTES">
            <!-- Enlace para imprimir reportes -->
            <a class="btn" href="reportev.php">Imprimir Reportes</a>
        
            
            <!-- Botón para generar reporte estadístico con gráfica -->
            <button class="btn" onclick="window.location.href='reporteGraficoV.html'">Generar Reporte Estadístico con Gráfica</button>
        </div>
    <a href="gestionVentasCrear.php" class="crear-btn">Agregar Nueva Venta</a>
    <a href="menuV.html" class="volver-btn">Volver al Menú Principal</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#ventasTable').DataTable();
    });
</script>

</body>
</html>

<?php
mysqli_close($conectar); // Cerrar conexión
?>
