<?php
require '../conexion.php'; // Conexión

// Verifica si se ha enviado un formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $cantidad = $_POST['cantidad'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $categorias = $_POST['categorias'];
    $precio_unitario = $_POST['precio_unitario'];
    $fk_id_marca = $_POST['fk_id_marca']; // Obtener el FK de la marca seleccionada

    // Manejo de la imagen
    if ($_FILES['imagen']['name']) {
        $target_dir = "uploads/"; // Directorio donde se guardará la imagen
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validar si el archivo es una imagen
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            // Subir la nueva imagen
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
        $imagen = $_POST['imagen_actual']; // Si no se sube una nueva imagen, se mantiene la actual
    }

    // Preparar la consulta para actualizar el registro del inventario
    $stmt_update = $conectar->prepare("UPDATE producto SET talla = ?, color = ?, cantidad = ?, nombre = ?, estado = ?, categorias = ?, precio_unitario = ?, imagen = ?, fk_id_marca = ? WHERE id_producto = ?");
    
    // Nota: La cadena de tipo debe tener 10 caracteres (uno para cada parámetro)
    $stmt_update->bind_param("ssisssdssi", $talla, $color, $cantidad, $nombre, $estado, $categorias, $precio_unitario, $imagen, $fk_id_marca, $id_producto);

    if ($stmt_update->execute()) {
        echo "<script>alert('Inventario actualizado correctamente');</script>";
        echo "<script>window.location.href = '../Inventario/listaInventario.php';</script>"; // Redirige de nuevo al listado
    } else {
        echo "Error al actualizar el inventario: " . $stmt_update->error;
    }

    $stmt_update->close();
}

// Obtener los detalles del producto a editar
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




// Obtener los detalles del producto a editar
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

// Obtener la lista de marcas
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
</head>
<body>

<div class="container mt-5">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
