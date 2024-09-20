<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}

require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_producto = $_POST['eliminar_id'];

    $stmt_delete = $conectar->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt_delete->bind_param("i", $id_producto);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Inventario eliminado correctamente');</script>";
    } else {
        echo "Error al eliminar el inventario: " . $stmt_delete->error;
    }

    $stmt_delete->close();
}

$sql_select = "SELECT p.id_producto, p.nombre, p.estado, p.precio_unitario, p.imagen, m.nombre_marca, t.talla, t.cantidad 
               FROM producto p 
               JOIN marca m ON p.fk_id_marca = m.id_marca 
               JOIN tallas t ON p.id_producto = t.fk_id_producto";

$resultado = mysqli_query($conectar, $sql_select);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Inventario - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <style>
        #confirmDeleteModal .modal-body,
        #confirmDeleteModal .modal-title {
            color: black !important;}
            .dark-mode .dataTables_length label,
.dark-mode .dataTables_info,
.dark-mode .dataTables_filter label {
    color: white;
}


.dark-mode .dataTables_length select,
.dark-mode .dataTables_filter input {
    color: white;
    background-color: #333; 
    border-color: #555;

.dark-mode .dataTables_length label,
.dark-mode .dataTables_info,
.dark-mode .dataTables_filter label,
.dark-mode .dataTables_paginate a {
    color: white;
}


.dark-mode .dataTables_length select,
.dark-mode .dataTables_filter input {
    color: white;
    background-color: #333; 
    border-color: #555; 
}

.dark-mode .dataTables_paginate .paginate_button {
    color: white;
    background-color: #333;
    border: 1px solid #555; 
}

.dark-mode .dataTables_paginate .paginate_button:hover {
    background-color: #555; 
    color: white;
}

    </style>
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
                            <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">
                                <i class="fas fa-chart-line"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./listaInventario.php">
                                <i class="fas fa-box"></i> Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pedido/validarpedido.php">
                                <i class="fas fa-clipboard-list"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-credit-card"></i> Marca</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Listado de Productos</h1>
                </div>
                <div class="btn-back">
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>
                <a class="btn btn-success" href="../reporteP.php" role="button">Reporte Productos</a>
                <a class="btn btn-success" href="../reporteGraficoP.php" role="button">Reporte Productos Gráfico</a>

                <div class="table-responsive">
                    <?php
                    $productos = [];
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $id_producto = $fila['id_producto'];

                        if (!isset($productos[$id_producto])) {
                            $productos[$id_producto] = [
                                'nombre' => htmlspecialchars($fila['nombre']),
                                'estado' => htmlspecialchars($fila['estado']),
                                'precio' => htmlspecialchars($fila['precio_unitario']),
                                'imagen' => htmlspecialchars($fila['imagen']),
                                'marca' => htmlspecialchars($fila['nombre_marca']),
                                'tallas' => []
                            ];
                        }

                        $productos[$id_producto]['tallas'][] = [
                            'talla' => htmlspecialchars($fila['talla']),
                            'cantidad' => htmlspecialchars($fila['cantidad'])
                        ];
                    }

                    if (count($productos) > 0) {
                        echo "<table id='tablaInventario' class='display'>";
                        echo "<thead><tr><th>Imagen</th><th>Nombre</th><th>Estado</th><th>Precio</th><th>Marca</th><th>Tallas y Cantidades</th></thead><tbody>";

                        foreach ($productos as $id => $producto) {
                            echo "<tr id='row-" . $id . "'>";
                            echo "<td><img src='" . $producto['imagen'] . "' alt='" . $producto['nombre'] . "' style='width: 50px; height: 50px;'></td>";
                            echo "<td style='color: black;'>" . $producto['nombre'] . "</td>";
                            echo "<td style='color: black;'>" . $producto['estado'] . "</td>";
                            echo "<td style='color: black;'>" . $producto['precio'] . "</td>";
                            echo "<td style='color: black;'>" . $producto['marca'] . "</td>";

                            $tallas_html = "";
                            foreach ($producto['tallas'] as $talla) {
                                $tallas_html .= "Talla: " . $talla['talla'] . ", Cantidad: " . $talla['cantidad'] . "<br>";
                            }

                            echo "<td style='color: black;'>" . $tallas_html . "</td>";
                            echo "<td class='column-actions'>";
                            echo "<div class='btn-group' role='group'>";
                            
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p>No se encontraron registros de Productos.</p>";
                    }
                    ?>
                </div>

                <div class="mt-4">
                    <a href="./crearInventario.php" class="btn btn-primary">Agregar Nuevo Producto</a>
                </div>
                <div class="mt-4">
                    <a href="./crearProductoExistente.php" class="btn btn-primary">Agregar Producto Existente</a>
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
                    ¿Estás seguro de que deseas eliminar este producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="post" id="deleteForm">
                        <input type="hidden" name="eliminar_id" id="eliminar_id" value="">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaInventario').DataTable();
        });

        function confirmDelete(id) {
            $('#eliminar_id').val(id);
            $('#confirmDeleteModal').modal('show');
        }
    </script>
</body>
</html>
