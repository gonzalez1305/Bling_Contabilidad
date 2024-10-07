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
    <title>Agregar Tallas a Producto - Bling Compra</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body.dark-mode {
            background-color: #121212;
            color: white;
        }
        body.dark-mode .form-control {
            background-color: #333;
            color: black; /* Texto negro en modo oscuro */
            border-color: #555;
        }
        body.dark-mode .form-label,
        body.dark-mode .form-container,
        body.dark-mode .form-container h1,
        body.dark-mode .form-container .form-control,
        body.dark-mode .form-container .btn,
        body.dark-mode .form-container .mb-3,
        body.dark-mode .form-container .toggle-btn {
            color: black; /* Texto negro en modo oscuro */
        }
        body.dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        body.dark-mode .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: black; /* Texto negro */
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: black; /* Texto negro */
        }
        .form-container .form-label {
            font-weight: bold;
            color: black; /* Texto negro */
        }
        .form-container .form-control {
            border-radius: 5px;
            color: black; /* Texto negro */
        }
        .form-container .btn-container {
            display: flex;
            justify-content: space-between;
        }
        .form-container .btn {
            border-radius: 5px;
            color: black; /* Texto negro */
        }
        .form-container .mb-3 {
            margin-bottom: 1.5rem;
            color: black; /* Texto negro */
        }
        .form-container .toggle-btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black; /* Texto negro */
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
                        <!-- Menú de Navegación -->
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
                            <a class="nav-link" href="../Pagos/verPago.php">
                                <i class="fas fa-credit-card"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Marca/listaMarcas.php">
                                <i class="fas fa-tags"></i> Marca
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="form-container mt-5 pt-5">
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
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script.js"></script>

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

            // Toggle dark mode
            $('#darkModeToggle').click(function () {
                $('body').toggleClass('dark-mode');
            });
        });
    </script>
</body>
</html>