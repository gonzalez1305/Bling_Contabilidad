<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dash.css">
    <link rel="icon" href="../imgs/logo.png">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .navbar {
            background-color: #007bff;
            margin-bottom: 10px;
        }
        .navbar-brand {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .sidebar {
            height: calc(100vh - 56px);
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            top: 56px;
            left: 0;
            width: 200px;
            overflow-y: auto;
        }
        .sidebar a {
            color: #ffffff;
            padding: 8px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            margin-left: 200px;
            padding: 10px;
            margin-top: 10px;
        }
        .btn-back {
            margin: 10px 0;
        }
        .btn-group .btn {
            margin-right: 4px;
            padding: 4px 8px;
            font-size: 12px;
        }
        .profile-image {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .profile-image img {
            width: 100%;
            height: auto;
        }
        .profile-image .bi {
            font-size: 18px;
            color: #6c757d;
        }
        /* Estilo para la tabla */
        table {
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
        }
        th, td {
            white-space: nowrap;
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid #dee2e6;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        th {
            background-color: #f1f1f1;
            font-size: 12px;
        }
        .column-tipo-usuario {
            width: 100px;
        }
        .column-actions {
            width: 120px;
        }
        /* Estilo para la tabla dentro del contenedor */
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
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
                        <a class="nav-link active" href="validarusuario.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../GestionVentas/gestionVentasLista.php">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Inventario/listaInventario.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pagos/verPago.php">Pagos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="btn-back">
                <a class="btn btn-light text-primary" href="../menuv.html" role="button">Volver al Menú</a>
            </div>
            <h1 class="h2">Usuarios Registrados</h1>

            <a class="btn btn-success" href="../reporteU.php" role="button">Reporte Usuarios</a>
            <a class="btn btn-success" href="../reporteGraficoU.html" role="button">Reporte Usuarios Gráfico</a>

            <?php
                include("../conexion.php");
                $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, tipo_usuario, imagen FROM usuario";
                $resultado = mysqli_query($conectar, $sql);
            ?>

            <div class="table-responsive">
                <table id="pedidosTable" class="display">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Imagen</th>
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
                                <td>
                                    <div class="profile-image">
                                        <?php if (!empty($filas['imagen']) && file_exists("../usuariofoto/" . $filas['imagen'])): ?>
                                            <img src="../usuariofoto/<?php echo htmlspecialchars($filas['imagen']); ?>" alt="Imagen de perfil">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                             
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
                    ¿Está seguro de que desea eliminar este usuario?
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
<script>
    $(document).ready(function() {
        $('#pedidosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            }
        });
    });

    function confirmDelete(id_usuario) {
        $('#id_usuario').val(id_usuario);
        $('#confirmDeleteModal').modal('show');
    }
</script>

</body>
</html>
