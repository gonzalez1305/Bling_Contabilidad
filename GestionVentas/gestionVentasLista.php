<?php include '../session_check.php'; ?>
<?php
require '../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_gestion_venta = $_POST['eliminar_id'];


    $sql_delete_gestion_venta = "DELETE FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
    
    if (mysqli_query($conectar, $sql_delete_gestion_venta)) {
        echo "<script>alert('Gestión de Venta eliminada correctamente');</script>";
    } else {
        echo "Error al eliminar la gestión de venta: " . mysqli_error($conectar);
    }
}

$sql_select = "
    SELECT gv.id_gestion_venta, gv.id_detalles_pedido, gv.id_vendedor, gv.fecha_venta, gv.fecha_registro,
           dp.precio_total
    FROM gestion_ventas gv
    JOIN detalles_pedido dp ON gv.id_detalles_pedido = dp.id_detalles_pedido
    JOIN pedido p ON dp.fk_id_pedido = p.id_pedido
    WHERE p.situacion = 'entregado'
";
$resultado = mysqli_query($conectar, $sql_select);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../menuV.php">
                <img src="../imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                Bling Compra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button id="darkModeToggle" class="btn btn-outline-light toggle-btn">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../Usuario/validarusuario.php">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./gestionVentasLista.php">
                                <i class="fas fa-chart-line"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Inventario/listaInventario.php">
                                <i class="fas fa-box"></i> Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pedido/validarpedido.php">
                                <i class="fas fa-clipboard-list"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pagos/pago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Listado de Ventas</h1>
                </div>
                <div class="btn-back">
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>
                <a class="btn btn-success" href="../reporteV.php" role="button">Reporte Ventas</a>
                <a class="btn btn-success" href="../reporteGraficoV.html" role="button">Reporte Ventas Gráfico</a>

                <div class="table-responsive">
                    <table id="ventasTable" class="display">
                        <thead>
                            <tr>
                                <th>Fecha de Venta</th>
                                <th>Fecha de Registro</th>
                                <th>Precio Total</th>
                                <th class="column-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($row = mysqli_fetch_assoc($resultado)) {
                                    echo "<tr id='row-" . $row['id_gestion_venta'] . "'>";
                                    echo "<td style='color: black;'>" . htmlspecialchars($row['fecha_venta']) . "</td>";
                                    echo "<td style='color: black;'>" . htmlspecialchars($row['fecha_registro']) . "</td>";
                                    echo "<td style='color: black;'>" . number_format($row['precio_total'], 2) . " COP</td>";
                                    echo "<td class='column-actions'>";
                                    echo "<div class='btn-group' role='group'>";
                                    echo "<a href='gestionVentasEditar.php?id=" . $row['id_gestion_venta'] . "' class='btn btn-warning btn-sm'>Editar</a>";
                                    echo "<button class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id_gestion_venta'] . ")'>Eliminar</button>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay ventas registradas</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" method="POST" action="">
                        <input type="hidden" id="eliminar_id" name="eliminar_id">
                        ¿Está seguro de que desea eliminar esta venta?
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="deleteForm" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
    <script>
        $(document).ready(function() {
            $('#ventasTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                }
            });
        });

        function confirmDelete(id_gestion_venta) {
            $('#eliminar_id').val(id_gestion_venta);
            $('#confirmDeleteModal').modal('show');
        }
    </script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conectar);
?>