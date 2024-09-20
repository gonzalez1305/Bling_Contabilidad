<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
    exit();
}

require '../conexion.php'; // Conexión

$color = '';
$nombre = '';
$estado = '';
$categorias = '';
$precio_unitario = '';
$imagen_ruta = '';

// Verifica y crea la carpeta uploads si no existe
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Obtener las marcas desde la base de datos
$sql_marcas = "SELECT id_marca, nombre_marca FROM marca";
$resultado_marcas = mysqli_query($conectar, $sql_marcas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $color = mysqli_real_escape_string($conectar, $_POST['color']);
    $nombre = mysqli_real_escape_string($conectar, $_POST['nombre']);
    $estado = mysqli_real_escape_string($conectar, $_POST['estado']);
    $categorias = mysqli_real_escape_string($conectar, $_POST['categorias']);
    $precio_unitario = mysqli_real_escape_string($conectar, $_POST['precio_unitario']);
    $precio_entrada = mysqli_real_escape_string($conectar, $_POST['precio_entrada']);


    $fk_id_marca = mysqli_real_escape_string($conectar, $_POST['marca']);
    
    // Manejo de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_ruta = 'uploads/' . basename($imagen_nombre);
        
        if (!move_uploaded_file($imagen_tmp, $imagen_ruta)) {
            echo "Error al subir la imagen. Verifica que la carpeta exista y tenga permisos adecuados.";
            exit;
        }
    }

    // Inserción de los datos en la tabla producto
    $sql_insert_producto = "INSERT INTO producto (color, nombre, fk_id_marca, estado, categorias, precio_unitario, precio_entrada, imagen)
                        VALUES ('$color', '$nombre', '$fk_id_marca', '$estado', '$categorias', '$precio_unitario', '$precio_entrada', '$imagen_ruta')";


    if (mysqli_query($conectar, $sql_insert_producto)) {
        $id_producto = mysqli_insert_id($conectar);

        $sql_insert_marca_producto = "INSERT INTO marca_producto (fk_id_producto, fk_id_marca)
                                      VALUES ('$id_producto', '$fk_id_marca')";

        if (mysqli_query($conectar, $sql_insert_marca_producto)) {
            $tallas = $_POST['tallas'];
            $cantidades = $_POST['cantidades'];

            for ($i = 0; $i < count($tallas); $i++) {
                $talla = mysqli_real_escape_string($conectar, $tallas[$i]);
                $cantidad = mysqli_real_escape_string($conectar, $cantidades[$i]);

                $sql_insert_talla = "INSERT INTO tallas (fk_id_producto, talla, cantidad)
                                     VALUES ('$id_producto', '$talla', '$cantidad')";

                if (!mysqli_query($conectar, $sql_insert_talla)) {
                    echo "Error al registrar la talla: " . mysqli_error($conectar);
                }
            }

            echo "<script>alert('Producto y tallas registrados correctamente');</script>";
            echo "<script>window.location.href = 'listaInventario.php';</script>";
            exit;
        } else {
            echo "Error al registrar la relación marca-producto: " . mysqli_error($conectar);
        }
    } else {
        echo "Error al registrar el producto: " . mysqli_error($conectar);
    }
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Inventario - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                            <a class="nav-link" href="../Pagos/pago.php">
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
                    <h1 class="h2">Agregar Nuevo Producto</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="color" class="form-label">Color:</label>
                        <input type="text" id="color" name="color" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
    <label for="estado" class="form-label">Estado:</label>
    <select id="estado" name="estado" class="form-control" required>
        <option value="">Seleccione un estado</option>
        <option value="Disponible">Disponible</option>
       
    </select>
</div>


                    <div class="mb-3">
                        <label for="categorias" class="form-label">Categoría:</label>
                        <select id="categorias" name="categorias" class="form-control" required>
                            <option value="">Seleccione una categoría</option>
                            <option value="Caballero">Caballero</option>
                            <option value="Dama">Dama</option>
                            <option value="Niño">Niño</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca:</label>
                        <select id="marca" name="marca" class="form-control" required>
                            <option value="">Seleccione una marca</option>
                            <?php while ($fila_marca = mysqli_fetch_assoc($resultado_marcas)) { ?>
                                <option value="<?php echo $fila_marca['id_marca']; ?>"><?php echo $fila_marca['nombre_marca']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario:</label>
                        <input type="number" id="precio_unitario" name="precio_unitario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_entrada" class="form-label">Precio de Entrada:</label>
                        <input type="number" id="precio_entrada" name="precio_entrada" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" class="form-control" required>
                    </div>

                    <div id="tallas-container">
                        <div class="mb-3">
                            <label for="talla1" class="form-label">Talla:</label>
                            <select id="talla1" name="tallas[]" class="form-control" required>
                                <!-- Opciones se llenarán dinámicamente -->
                            </select>
                            <label for="cantidad1" class="form-label">Cantidad:</label>
                            <input type="number" id="cantidad1" name="cantidades[]" class="form-control" required>
                        </div>
                    </div>
                    <button type="button" id="agregar-talla" class="btn btn-secondary">Agregar otra talla</button>
                    <br><br>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const tallasPorCategoria = {
                'Caballero': Array.from({length: 7}, (_, i) => i + 38),
                'Dama': Array.from({length: 6}, (_, i) => i + 35),
                'Niño': Array.from({length: 12}, (_, i) => i + 22)
            };

            $('#categorias').on('change', function() {
                const categoriaSeleccionada = $(this).val();
                actualizarTallas('#talla1', categoriaSeleccionada);
            });

            $('#agregar-talla').on('click', function() {
                const tallaCount = $('#tallas-container').children().length + 1;
                const nuevaTalla = `
                    <div class="mb-3">
                        <label for="talla${tallaCount}" class="form-label">Talla:</label>
                        <select id="talla${tallaCount}" name="tallas[]" class="form-control" required>
                            <!-- Opciones se llenarán dinámicamente -->
                        </select>
                        <label for="cantidad${tallaCount}" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad${tallaCount}" name="cantidades[]" class="form-control" required>
                    </div>
                `;
                $('#tallas-container').append(nuevaTalla);
                actualizarTallas(`#talla${tallaCount}`, $('#categorias').val());
            });

            function actualizarTallas(selector, categoria) {
                const tallasSelect = $(selector);
                tallasSelect.empty(); // Limpia las opciones actuales

                if (tallasPorCategoria[categoria]) {
                    tallasPorCategoria[categoria].forEach(talla => {
                        tallasSelect.append(new Option(talla, talla));
                    });
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
