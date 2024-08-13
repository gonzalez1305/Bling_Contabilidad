<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos - Bling Compra</title>
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
        }
        .navbar-brand {
            color: #ffffff;
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .sidebar {
            height: calc(100vh - 56px); /* Ajusta la altura teniendo en cuenta el encabezado */
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            top: 56px; /* Ajusta para que la barra lateral esté debajo del encabezado */
            left: 0;
            width: 250px; /* Ajusta el ancho de la barra lateral si es necesario */
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
            margin-left: 250px; /* Ajusta el margen izquierdo para que el contenido no quede debajo de la barra lateral */
            padding: 20px;
            margin-top: 56px; /* Ajusta para que el contenido no quede debajo del encabezado */
        }
        .btn-back {
            margin: 20px 0;
        }
        .btn-group .btn {
            margin-right: 5px;
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
                        <a class="nav-link" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="btn-back">
                <a class="btn btn-light text-primary" href="../menuv.html" role="button">Volver al Menú</a>
            </div>
            <h1 class="h2">Usuarios Registrados</h1>
            
            <?php
                include("../conexion.php");
                $sql = "SELECT id_usuario, nombre, apellido, telefono, direccion, fecha_de_nacimiento, correo, estado, tipo_usuario FROM usuario";
                $resultado = mysqli_query($conectar, $sql);
            ?>

            <table id="pedidosTable" class="display">
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Tipo Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($filas = mysqli_fetch_assoc($resultado)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($filas['id_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($filas['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($filas['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($filas['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($filas['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($filas['fecha_de_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($filas['correo']); ?></td>
                            <td><?php echo htmlspecialchars($filas['estado']); ?></td>
                            <td><?php echo htmlspecialchars($filas['tipo_usuario']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href='./editarU.php?id_usuario=<?php echo $filas['id_usuario']; ?>' class="btn btn-warning btn-sm">Editar</a>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $filas['id_usuario']; ?>)">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteForm" method="post" action="eliminarU.php">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Contraseña de Administrador</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#pedidosTable').DataTable();
    });

    function confirmDelete(id_usuario) {
        $('#id_usuario').val(id_usuario);
        $('#confirmDeleteModal').modal('show');
    }
</script>
</body>
</html>
