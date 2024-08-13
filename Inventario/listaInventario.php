<?php
require '../conexion.php'; // Conexion

// Verifica si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_inventario = $_POST['eliminar_id'];

    // Eliminar el registro del inventario 
    $sql_delete = "DELETE FROM inventario WHERE id_inventario = '$id_inventario'";

    if (mysqli_query($conectar, $sql_delete)) {
        echo "<script>alert('Inventario eliminado correctamente');</script>";
    } else {
        echo "Error al eliminar el inventario: " . mysqli_error($conectar);
    }
}

// Construir consulta con filtros
$where_clauses = [];
if (isset($_GET['cantidad']) && $_GET['cantidad'] !== '') {
    $cantidad = mysqli_real_escape_string($conectar, $_GET['cantidad']);
    $where_clauses[] = "cantidad = '$cantidad'";
}
if (isset($_GET['fecha']) && $_GET['fecha'] !== '') {
    $fecha = mysqli_real_escape_string($conectar, $_GET['fecha']);
    $where_clauses[] = "fecha = '$fecha'";
}
if (isset($_GET['referencia']) && $_GET['referencia'] !== '') {
    $referencia = mysqli_real_escape_string($conectar, $_GET['referencia']);
    $where_clauses[] = "referencia LIKE '%$referencia%'";
}
if (isset($_GET['id_vendedor']) && $_GET['id_vendedor'] !== '') {
    $id_vendedor = mysqli_real_escape_string($conectar, $_GET['id_vendedor']);
    $where_clauses[] = "id_vendedor = '$id_vendedor'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Consulta 
$sql_select = "SELECT * FROM inventario $where_sql";
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
                        <a class="nav-link" href="../dashboard_v.html">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../dashboard_I.html">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Pedido/validarpedido.php">Pedidos</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <h1 class="h2">Listado de Inventario</h1>
            
            <form method="GET" action="" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" value="<?php echo isset($_GET['cantidad']) ? $_GET['cantidad'] : ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="referencia" class="form-label">Referencia:</label>
                        <input type="text" id="referencia" name="referencia" class="form-control" value="<?php echo isset($_GET['referencia']) ? $_GET['referencia'] : ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="id_vendedor" class="form-label">ID del Vendedor:</label>
                        <input type="number" id="id_vendedor" name="id_vendedor" class="form-control" value="<?php echo isset($_GET['id_vendedor']) ? $_GET['id_vendedor'] : ''; ?>">
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>

            <?php
            // Verificar si hay registros
            if (mysqli_num_rows($resultado) > 0) {
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>ID</th><th>Cantidad</th><th>Fecha</th><th>Cantidad Disponible</th><th>Referencia</th><th>ID Vendedor</th><th>Imagen</th><th>Acciones</th></tr></thead><tbody>";
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $fila['id_inventario'] . "</td>";
                    echo "<td>" . $fila['cantidad'] . "</td>";
                    echo "<td>" . $fila['fecha'] . "</td>";
                    echo "<td>" . $fila['cantidad_disponible'] . "</td>";
                    echo "<td>" . $fila['referencia'] . "</td>";
                    echo "<td>" . $fila['id_vendedor'] . "</td>";
                    echo "<td><img src='" . $fila['imagen'] . "' alt='Imagen' style='max-width: 100px;'></td>";
                    echo "<td>";
                    echo "<a href='editarInventario.php?id=" . $fila['id_inventario'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
                    echo "<form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>";
                    echo "<input type='hidden' name='eliminar_id' value='" . $fila['id_inventario'] . "'>";
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
</body>
</html>

<?php
mysqli_close($conectar);
?>