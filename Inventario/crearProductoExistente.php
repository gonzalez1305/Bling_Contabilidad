<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}

require '../conexion.php'; // Conexión

// Obtener productos desde la base de datos
$sql_productos = "SELECT p.id_producto, p.nombre, p.categorias FROM producto p";
$resultado_productos = mysqli_query($conectar, $sql_productos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = mysqli_real_escape_string($conectar, $_POST['producto']);
    $tallas = $_POST['tallas'];
    $cantidades = $_POST['cantidades'];

    // Inserción de las tallas en la tabla tallas
    for ($i = 0; $i < count($tallas); $i++) {
        $talla = mysqli_real_escape_string($conectar, $tallas[$i]);
        $cantidad = mysqli_real_escape_string($conectar, $cantidades[$i]);

        $sql_insert_talla = "INSERT INTO tallas (fk_id_producto, talla, cantidad) VALUES ('$id_producto', '$talla', '$cantidad')";
        mysqli_query($conectar, $sql_insert_talla);
    }

    echo "<script>alert('Tallas registradas correctamente');</script>";
    echo "<script>window.location.href = 'listaInventario.php';</script>";
    exit;
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tallas - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="icon" href="../imgs/logo.png">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            background-color: white;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-4">Agregar Tallas a Producto</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="producto" class="form-label">Producto:</label>
                <select id="producto" name="producto" class="form-control" required>
                    <option value="">Seleccione un producto</option>
                    <?php while ($producto = mysqli_fetch_assoc($resultado_productos)): ?>
                        <option value="<?php echo $producto['id_producto']; ?>" data-categoria="<?php echo $producto['categorias']; ?>">
                            <?php echo $producto['nombre'] . ' - ' . $producto['categorias']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div id="tallas-container">
                <h3>Tallas</h3>
                <div class="mb-3">
                    <label for="talla1" class="form-label">Talla:</label>
                    <select id="talla1" name="tallas[]" class="form-control" required>
                        <option value="">Seleccione una talla</option>
                    </select>
                    <label for="cantidad1" class="form-label">Cantidad:</label>
                    <input type="number" id="cantidad1" name="cantidades[]" class="form-control" required>
                </div>
            </div>
            <button type="button" id="agregar-talla" class="btn btn-secondary mb-3">Agregar Talla</button>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Registrar Tallas</button>
                <a href="./listaInventario.php" class="btn btn-primary">Volver al Menu</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const tallasPorCategoria = {
                'Caballero': Array.from({length: 7}, (_, i) => i + 38), // Tallas 38-44
                'Dama': Array.from({length: 6}, (_, i) => i + 35), // Tallas 35-40
                'Niño': Array.from({length: 12}, (_, i) => i + 22) // Tallas 22-33
            };

            $('#producto').on('change', function() {
                const categoriaSeleccionada = $(this).find(':selected').data('categoria');
                actualizarTallas('#talla1', categoriaSeleccionada);
            });

            $('#agregar-talla').on('click', function() {
                const tallaCount = $('#tallas-container').children().length + 1;
                const nuevaTalla = `
                    <div class="mb-3">
                        <label for="talla${tallaCount}" class="form-label">Talla:</label>
                        <select id="talla${tallaCount}" name="tallas[]" class="form-control" required>
                            <option value="">Seleccione una talla</option>
                        </select>
                        <label for="cantidad${tallaCount}" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad${tallaCount}" name="cantidades[]" class="form-control" required>
                    </div>
                `;
                $('#tallas-container').append(nuevaTalla);
                actualizarTallas(`#talla${tallaCount}`, $('#producto').find(':selected').data('categoria'));
            });

            function actualizarTallas(selector, categoria) {
                const tallasSelect = $(selector);
                tallasSelect.empty(); // Limpia las opciones actuales

                if (tallasPorCategoria[categoria]) {
                    tallasSelect.append(new Option('Seleccione una talla', ''));
                    tallasPorCategoria[categoria].forEach(talla => {
                        tallasSelect.append(new Option(talla, talla));
                    });
                } else {
                    tallasSelect.append(new Option('Seleccione una talla', ''));
                }
            }
        });
    </script>
</body>
</html>
