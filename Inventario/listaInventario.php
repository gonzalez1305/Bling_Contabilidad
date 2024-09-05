<?php
require '../conexion.php'; // Conexión

// Verifica si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_producto = $_POST['eliminar_id'];

    // Preparar la consulta para eliminar el registro del inventario
    $stmt_delete = $conectar->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt_delete->bind_param("i", $id_producto);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Inventario eliminado correctamente');</script>";
    } else {
        echo "Error al eliminar el inventario: " . $stmt_delete->error;
    }

    $stmt_delete->close();
}

// Verificar si la variable $where_sql está definida, si no, se define como una cadena vacía
$where_sql = isset($where_sql) ? $where_sql : "";

// Consulta de selección para incluir la marca
$sql_select = "SELECT producto.*, marca.nombre_marca 
               FROM producto 
               JOIN marca ON producto.fk_id_marca = marca.id_marca 
               $where_sql";
$resultado = mysqli_query($conectar, $sql_select);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Inventario - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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
            margin-bottom: 20px;
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
                        <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./listaInventario.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <h1 class="h2">Listado de Inventario</h1>

            <?php
            // Verificar si hay registros
            if (mysqli_num_rows($resultado) > 0) {
                echo "<table id='tablaInventario' class='table table-striped'>";
                echo "<thead><tr><th>ID</th><th>Talla</th><th>Color</th><th>Cantidad Disponible</th><th>Nombre</th><th>Estado</th><th>Categoria</th><th>Precio</th><th>Marca</th><th>Imagen</th><th>Acciones</th></tr></thead><tbody>";
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['id_producto']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['talla']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['color']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['cantidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['categorias']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['precio_unitario']) . "</td>";
                    // Mostrar la marca
                    echo "<td>" . htmlspecialchars($fila['nombre_marca']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($fila['imagen']) . "' alt='Imagen' style='max-width: 100px;'></td>";
                    echo "<td>";
                    echo "<a href='editarInventario.php?id=" . htmlspecialchars($fila['id_producto']) . "' class='btn btn-warning btn-sm'>Editar</a> ";
                    echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
                    echo "<input type='hidden' name='eliminar_id' value='" . htmlspecialchars($fila['id_producto']) . "'>";
                    echo "<input type='submit' value='Eliminar' class='btn btn-danger btn-sm'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No se encontraron registros de inventario.</p>";
            }
            ?>

            <div class="mt-4">
                <a href="./crearInventario.php" class="btn btn-primary">Agregar Nuevo Inventario</a>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaInventario').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": activar para ordenar la columna de manera descendente"
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
