<?php
require '../conexion.php'; // Conexión a la base de datos

// Verificar si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_gestion_venta = $_POST['eliminar_id'];

    // Eliminar el registro de gestión de ventas correspondiente
    $sql_delete_gestion_venta = "DELETE FROM gestion_ventas WHERE id_gestion_venta = '$id_gestion_venta'";
    
    if (mysqli_query($conectar, $sql_delete_gestion_venta)) {
        echo "<script>alert('Gestión de Venta eliminada correctamente');</script>";
    } else {
        echo "Error al eliminar la gestión de venta: " . mysqli_error($conectar);
    }
}

// Consulta para obtener todos los registros de gestion_ventas con el precio_total
$sql_select = "
    SELECT gv.id_gestion_venta, gv.id_detalles_pedido, gv.id_vendedor, gv.fecha_venta, gv.fecha_registro,
           dp.precio_total
    FROM gestion_ventas gv
    JOIN detalles_pedido dp ON gv.id_detalles_pedido = dp.id_detalles_pedido
";
$resultado = mysqli_query($conectar, $sql_select);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Ventas - Bling Compra</title>
  <link rel="icon" href="../imgs/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <style>
      body {
          background-color: #f8f9fa;
          font-family: Arial, sans-serif;
      }
      .navbar {
          background-color: #007bff;
      }
      .navbar-brand {
          color: #ffffff;
      }
      .navbar-nav .nav-link {
          color: #ffffff;
      }
      .sidebar {
          height: 100vh;
          background-color: #343a40;
          padding-top: 20px;
      }
      .sidebar a {
          color: #ffffff;
          padding: 10px;
          text-decoration: none;
          display: block;
      }
      .sidebar a:hover {
          background-color: #007bff;
      }
      .content {
          padding: 20px;
      }
      .card {
          margin-bottom: 20px;
      }
      .volver-btn {
          margin-top: 20px;
      }
      .btn-group {
          display: flex;
          gap: 10px;
      }
      .img-thumbnail {
          max-width: 100px;
          max-height: 100px;
          object-fit: cover;
      }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Bling Compra</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link" href="../menu.html">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="../Usuario/validarusuario.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./gestionVentasLista.php">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Inventario/listaInventario.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="container">
                <h1 class="h2">Listado de Ventas</h1>
                <a class="btn btn-success" href="../reporteV.php" role="button">Reporte Ventas</a>
                <a class="btn btn-success" href="../reporteGraficoV.html" role="button">Reporte Ventas Gráfico</a>
                <table id="ventasTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID de Gestión de Venta</th>
                            <th>ID de Detalles del Pedido</th>
                            <th>ID de Vendedor</th>
                            <th>Fecha de Venta</th>
                            <th>Fecha de Registro</th>
                            <th>Precio Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                echo "<tr>";
                                echo "<td>" . $row['id_gestion_venta'] . "</td>";
                                echo "<td>" . $row['id_detalles_pedido'] . "</td>";
                                echo "<td>" . $row['id_vendedor'] . "</td>";
                                echo "<td>" . $row['fecha_venta'] . "</td>";
                                echo "<td>" . $row['fecha_registro'] . "</td>";
                                echo "<td>" . number_format($row['precio_total'], 2) . " COP</td>";
                                echo "<td class='btn-group'>";
                                echo "<a href='gestionVentasEditar.php?id=" . $row['id_gestion_venta'] . "' class='btn btn-warning'>Editar</a>";
                                echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
                                echo "<input type='hidden' name='eliminar_id' value='" . $row['id_gestion_venta'] . "'>";
                                echo "<button type='submit' class='btn btn-danger'>Eliminar</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No hay ventas registradas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <a href="gestionVentasCrear.php" class="btn btn-primary">Agregar Nueva Venta</a>
            </div>
        </main>
    </div>
</div>

<!-- Scripts de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#ventasTable').DataTable({
            "language": {
                "sProcessing":    "Procesando...",
                "sLengthMenu":    "Mostrar _MENU_ registros",
                "sZeroRecords":   "No se encontraron resultados",
                "sEmptyTable":    "Ningún dato disponible en esta tabla",
                "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":   "",
                "sSearch":        "Buscar:",
                "sUrl":           "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
</script>
</body>
</html>

<?php
mysqli_close($conectar);
?>
