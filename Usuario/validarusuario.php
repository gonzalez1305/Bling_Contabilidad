<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-responsive {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
        }
        .table tbody td {
            color: black;
        }
        .btn-back {
            margin-bottom: 10px;
        }
        .dataTables_length label,
        .dataTables_info {
            color: black !important;
        }
        .dataTables_filter label {
            color: black !important;
        }
        body.dark-mode .dataTables_length label,
        body.dark-mode .dataTables_info,
        body.dark-mode .dataTables_filter label {
            color: white !important;
        }
        .modal-content {
            color: black;
        }
        body.dark-mode .modal-content {
            color: white;
        }
        .modal-body {
            color: black;
        }
        body.dark-mode .modal-body {
            color: white;
        }
        #confirmDeleteModal .modal-body,
        #confirmDeleteModal .modal-title {
            color: black !important;
        }
    </style>
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
                            <a class="nav-link active" href="validarusuario.php">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">
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
                            <a class="nav-link" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-tag"></i> Marca</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Usuarios Registrados</h1>
                </div>
                <div class="btn-back">
                    <a class="btn btn-light text-primary" href="../menuV.php" role="button">Volver al Menú</a>
                </div>
                <a class="btn btn-success" href="../reporteU.php" role="button">Reporte Usuarios</a>
                <a class="btn btn-success" href="../reporteGraficoU.html" role="button">Reporte Usuarios Gráfico</a>

                <?php
                    include("../conexion.php");
                    $sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.telefono, u.direccion, u.fecha_de_nacimiento, u.correo, u.tipo_usuario, r.nombre AS nombre_rol 
                            FROM usuario u 
                            JOIN roles r ON u.tipo_usuario = r.id_rol";
                    $resultado = mysqli_query($conectar, $sql);
                ?>

                <div class="table-responsive">
                    <table id="pedidosTable" class="display table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de Usuario</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Correo</th>
                                <th class="column-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($filas = mysqli_fetch_assoc($resultado)) { ?>
                                <tr id="row-<?php echo $filas['id_usuario']; ?>">
                                    <td><?php echo htmlspecialchars($filas['nombre_rol']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['direccion']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['fecha_de_nacimiento']); ?></td>
                                    <td><?php echo htmlspecialchars($filas['correo']); ?></td>
                                    <td class="column-actions">
                                        <div class="btn-group" role="group">
                                            <a href='./editarU.php?id_usuario=<?php echo $filas['id_usuario']; ?>' class="btn btn-warning btn-sm">Editar</a>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $filas['id_usuario']; ?>)">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
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
                    <form id="deleteForm" method="POST" action="eliminarU.php">
                        <input type="hidden" id="id_usuario" name="id_usuario">
                        <p>¿Está seguro de que desea eliminar este usuario?</p>
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
            $('#pedidosTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es.json'
                }
            });
        });

        function confirmDelete(id) {
            $('#id_usuario').val(id);
            $('#confirmDeleteModal').modal('show');
        }

        $('#darkModeToggle').click(function() {
            $('body').toggleClass('dark-mode');
            $(this).find('i').toggleClass('fa-moon fa-sun');
        });
    </script>
</body>
</html>
