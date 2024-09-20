<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}
require '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $tallas = $_POST['tallas']; // Array de tallas
    $cantidades = $_POST['cantidades']; // Array de cantidades

    // Validación adicional si es necesario
    foreach ($tallas as $index => $talla) {
        $cantidad = $cantidades[$index];

        // Asegúrate de que $id_producto, $talla y $cantidad sean seguros
        $talla = mysqli_real_escape_string($conectar, $talla);
        $cantidad = mysqli_real_escape_string($conectar, $cantidad);

        // Inserción de tallas en la tabla
        $sql = "INSERT INTO tallas (fk_id_producto, talla, cantidad) VALUES ('$id_producto', '$talla', '$cantidad')";
        
        if (!mysqli_query($conectar, $sql)) {
            echo "Error: " . mysqli_error($conectar);
        }
    }

    echo "<script>alert('Tallas y cantidades agregadas exitosamente'); window.location.href='listaInventario.php';</script>";
}

// Obtener productos existentes junto con la columna `categorias` de la tabla `producto`
$sql_productos = "SELECT id_producto, nombre, categorias FROM producto";
$result_productos = mysqli_query($conectar, $sql_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tallas a Producto Existente - Bling Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Tallas a Producto Existente</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="id_producto" class="form-label">Selecciona un Producto:</label>
                <select id="id_producto" name="id_producto" class="form-control" required>
                    <option value="">Seleccione un producto</option>
                    <?php while ($producto = mysqli_fetch_assoc($result_productos)) { ?>
                        <option value="<?php echo $producto['id_producto']; ?>" data-categoria="<?php echo $producto['categorias']; ?>">
                            <?php echo $producto['nombre'] . " - " . $producto['categorias']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
           
            <div id="tallas-container">
                <h5>Tallas y Cantidades</h5>
                <div class="mb-3">
                    <label for="tallas[]" class="form-label">Talla:</label>
                    <select name="tallas[]" class="form-control" required>
                        <!-- Opciones se llenarán dinámicamente -->
                    </select>
                    <label for="cantidades[]" class="form-label">Cantidad:</label>
                    <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" min="1" required>
                </div>
            </div>
            <button type="button" id="agregar-talla" class="btn btn-secondary">Agregar Talla</button>
            <button type="submit" class="btn btn-primary">Agregar Tallas</button>
        </form>
    </div>

    <script>
        // Definición de tallas por categoría
        const tallasPorCategoria = {
            'Caballero': Array.from({ length: 7 }, (_, i) => i + 38), // Tallas desde 38 hasta 44
            'Dama': Array.from({ length: 6 }, (_, i) => i + 35), // Tallas desde 35 hasta 40
            'Niño': Array.from({ length: 12 }, (_, i) => i + 22) // Tallas desde 22 hasta 33
        };

        document.getElementById('id_producto').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const categoria = selectedOption.getAttribute('data-categoria');
            actualizarTallas('select[name="tallas[]"]', categoria);
        });

        document.getElementById('agregar-talla').addEventListener('click', function () {
            const container = document.getElementById('tallas-container');
            const newDiv = document.createElement('div');
            newDiv.classList.add('mb-3');
            newDiv.innerHTML = `
                <label for="tallas[]" class="form-label">Talla:</label>
                <select name="tallas[]" class="form-control" required>
                    <!-- Opciones se llenarán dinámicamente -->
                </select>
                <label for="cantidades[]" class="form-label">Cantidad:</label>
                <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" min="1" required>
            `;
            container.appendChild(newDiv);

            // Agregar opciones de talla según la categoría seleccionada
            const categoria = document.getElementById('id_producto').options[document.getElementById('id_producto').selectedIndex].getAttribute('data-categoria');
            actualizarTallas(newDiv.querySelector('select[name="tallas[]"]'), categoria);
        });

        function actualizarTallas(selector, categoria) {
            const tallasSelect = document.querySelector(selector);
            tallasSelect.innerHTML = ''; // Limpia las opciones actuales

            if (tallasPorCategoria[categoria]) {
                tallasPorCategoria[categoria].forEach(talla => {
                    const option = new Option(talla, talla);
                    tallasSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>

<?php
mysqli_close($conectar);
?>
