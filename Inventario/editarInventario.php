<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
?>
<?php
require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $cantidad = $_POST['cantidad'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $categorias = $_POST['categorias'];
    $precio_unitario = $_POST['precio_unitario'];
    $fk_id_marca = $_POST['fk_id_marca'];

    if ($_FILES['imagen']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $imagen = $target_file;
            } else {
                echo "Error al subir la imagen.";
                exit();
            }
        } else {
            echo "El archivo no es una imagen.";
            exit();
        }
    } else {
        $imagen = $_POST['imagen_actual'];
    }

    $stmt_update = $conectar->prepare("UPDATE producto SET talla = ?, color = ?, cantidad = ?, nombre = ?, estado = ?, categorias = ?, precio_unitario = ?, imagen = ?, fk_id_marca = ? WHERE id_producto = ?");
    $stmt_update->bind_param("ssisssdssi", $talla, $color, $cantidad, $nombre, $estado, $categorias, $precio_unitario, $imagen, $fk_id_marca, $id_producto);

    if ($stmt_update->execute()) {
        echo "<script>alert('Inventario actualizado correctamente');</script>";
        echo "<script>window.location.href = '../Inventario/listaInventario.php';</script>";
    } else {
        echo "Error al actualizar el inventario: " . $stmt_update->error;
    }

    $stmt_update->close();
}

if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $stmt_select = $conectar->prepare("SELECT * FROM producto WHERE id_producto = ?");
    $stmt_select->bind_param("i", $id_producto);
    $stmt_select->execute();
    $resultado = $stmt_select->get_result();
    $producto = $resultado->fetch_assoc();
    $stmt_select->close();
} else {
    echo "ID de producto no válido.";
    exit();
}

$stmt_marca = $conectar->prepare("SELECT * FROM marca");
$stmt_marca->execute();
$marcas_resultado = $stmt_marca->get_result();
$marcas = $marcas_resultado->fetch_all(MYSQLI_ASSOC);
$stmt_marca->close();

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Inventario - Bling Compra</title>
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
        body.dark-mode .form-label,
        body.dark-mode .form-control,
        body.dark-mode .btn-primary,
        body.dark-mode .btn-secondary,
        body.dark-mode h1.h2 {
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

    <div class="container mt-5 pt-5">
        <div class="form-container">
            <h1 class="h2">Editar Inventario</h1>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($producto['imagen']); ?>">

                <div class="mb-3">
                    <label for="talla" class="form-label">Talla</label>
                    <input type="text" class="form-control" id="talla" name="talla" value="<?php echo htmlspecialchars($producto['talla']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" id="color" name="color" value="<?php echo htmlspecialchars($producto['color']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad Disponible</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" value="<?php echo htmlspecialchars($producto['estado']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="categorias" class="form-label">Categoría</label>
                    <input type="text" class="form-control" id="categorias" name="categorias" value="<?php echo htmlspecialchars($producto['categorias']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="precio_unitario" class="form-label">Precio Unitario</label>
                    <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" value="<?php echo htmlspecialchars($producto['precio_unitario']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fk_id_marca" class="form-label">Marca</label>
                    <select class="form-select" id="fk_id_marca" name="fk_id_marca" required>
                        <?php foreach ($marcas as $marca) : ?>
                            <option value="<?php echo htmlspecialchars($marca['id_marca']); ?>" <?php echo $marca['id_marca'] == $producto['fk_id_marca'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($marca['nombre_marca']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del Producto</label>
                    <input type="file" class="form-control" id="imagen" name="imagen">
                    <?php if ($producto['imagen']) : ?>
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" style="max-width: 150px;" class="mt-3">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="../Inventario/listaInventario.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>
/