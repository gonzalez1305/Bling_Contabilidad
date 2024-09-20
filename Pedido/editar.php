<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}

include("../conexion.php");

// Inicializar variables
$pedidoDetalles = array(); // Inicializa un array vacío para evitar advertencias

if (isset($_POST['enviar'])) {
    // Si se ha enviado el formulario
    $unidades = $_POST["unidades"];
    $id_detalles_pedido = $_POST["id_detalles_pedido"];
    $situacion = $_POST["situacion"];
    $id_pedido = $_POST["id_pedido"];

    // Obtén el precio unitario del producto
    $sqlPrecioUnitario = "SELECT precio_unitario FROM producto WHERE id_producto = (SELECT fk_id_producto FROM detalles_pedido WHERE id_detalles_pedido = '".$id_detalles_pedido."')";
    $resultadoPrecioUnitario = mysqli_query($conectar, $sqlPrecioUnitario);

    if ($resultadoPrecioUnitario && mysqli_num_rows($resultadoPrecioUnitario) > 0) {
        $filaPrecioUnitario = mysqli_fetch_assoc($resultadoPrecioUnitario);
        $precioUnitario = $filaPrecioUnitario['precio_unitario'];

        // Calcula el nuevo precio total
        $nuevoPrecioTotal = $precioUnitario * $unidades;

        // Actualiza las unidades y el precio total en la base de datos
        $sqlDetallesPedido = "UPDATE detalles_pedido SET unidades='".$unidades."', precio_total='".$nuevoPrecioTotal."' WHERE id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultadoDetalles = mysqli_query($conectar, $sqlDetallesPedido);

        // Actualiza la situación del pedido en la base de datos
        $sqlSituacionPedido = "UPDATE pedido SET situacion='".$situacion."' WHERE id_pedido='".$id_pedido."'";
        $resultadoSituacion = mysqli_query($conectar, $sqlSituacionPedido);

        if ($resultadoDetalles && $resultadoSituacion) {
            echo "<script language='javascript'>";
            echo "alert('Los datos se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        } else {
            echo "<script language='javascript'>";
            echo "alert('Los datos NO se actualizaron correctamente');";
            echo "location.assign('validarpedido.php');";
            echo "</script>";
        }
    } else {
        echo "Error al obtener el precio unitario del producto.";
    }

    mysqli_close($conectar);
} else {
    // Si no se ha enviado el formulario
    if (isset($_GET['id_detalles_pedido'])) {
        $id_detalles_pedido = $_GET['id_detalles_pedido'];
        $sql = "SELECT * FROM pedido
                INNER JOIN detalles_pedido ON pedido.id_pedido = detalles_pedido.fk_id_pedido
                INNER JOIN producto ON detalles_pedido.fk_id_producto = producto.id_producto
                WHERE detalles_pedido.id_detalles_pedido = '".$id_detalles_pedido."'";
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $pedidoDetalles = mysqli_fetch_assoc($resultado);
            $id_detalles_pedido = $pedidoDetalles['id_detalles_pedido'];
            $unidades = $pedidoDetalles['unidades'];
        } else {
            echo "Error al recuperar datos de la base de datos.";
        }
    } else {
        echo "Error: No se proporcionó el ID del pedido a editar.";
        exit; // Termina el script si no hay ID de pedido
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido - Bling Compra</title>
    <link rel="icon" href="../imgs/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
            color: black; /* Mantener el color del texto negro */
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .h2 {
            color: black; /* Mantener el color del título negro */
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

    <div class="container mt-5 pt-5">
        <div class="form-container">
            <h1 class="h2">Editar Pedido</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="p-4 border rounded-3 bg-light">
                <input type="hidden" name="id_detalles_pedido" value="<?php echo $id_detalles_pedido; ?>">
                <div class="mb-3">
                    <label class="form-label"><strong>ID Pedido:</strong></label>
                    <input type="text" name="id_pedido" value="<?php echo $pedidoDetalles['id_pedido']; ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>ID Usuario:</strong></label>
                    <input type="text" name="fk_id_usuario" value="<?php echo $pedidoDetalles['fk_id_usuario']; ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Fecha:</strong></label>
                    <input type="text" name="fecha" value="<?php echo $pedidoDetalles['fecha']; ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Situación:</strong></label>
                    <select name="situacion" class="form-control" required>
                        <option value="En proceso" <?php echo ($pedidoDetalles['situacion'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                        <option value="Entregado" <?php echo ($pedidoDetalles['situacion'] == 'Entregado') ? 'selected' : ''; ?>>Entregado</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Nombre del Producto:</strong></label>
                    <input type="text" name="nombre" value="<?php echo $pedidoDetalles['nombre']; ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Precio Total:</strong></label>
                    <input type="text" name="precio_total" value="<?php echo $pedidoDetalles['precio_total']; ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label for="unidades" class="form-label"><strong>Unidades:</strong></label>
                    <input type="text" name="unidades" value="<?php echo $unidades; ?>" class="form-control" readonly>
                </div>
                <button type="submit" name="enviar" class="btn btn-primary">ACTUALIZAR</button>
                <a href="validarpedido.php" class="btn btn-secondary">CANCELAR</a>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script>
        // Código para el modo oscuro (si lo necesitas)
        document.getElementById('darkModeToggle').onclick = function() {
            document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-white');
        };
    </script>
</body>
</html>
