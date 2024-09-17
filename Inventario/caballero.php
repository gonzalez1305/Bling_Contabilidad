<?php
include '../session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bling Compra - Sección Caballero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .product-card {
            color: black;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            max-width: 100%;
            border-bottom: 1px solid #ddd;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .product-card .card-body {
            padding: 15px;
        }

        .cart-details {
            color: black;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-details h3 {
            margin-bottom: 20px;
        }

        .cart-details p {
            margin-bottom: 10px;
        }

        .cart-details h4 {
            margin-top: 20px;
        }

        .btn-primary, .btn-danger {
            width: 100%;
            margin-top: 10px;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>

  <div class="top-info">
    <p>Email: <a href="mailto:blingcontabilidadgaes@gmail.com" style="color: #ffffff;">blingcontabilidadgaes@gmail.com</a> | Teléfono: <a href="tel:+573222465996" style="color: #ffffff;">+57 322 2465996</a></p>
  </div>

  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="../imgs/logo.png" alt="Logo">
        Bling Compra
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../menuC.php">Regresar</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
              <a class="nav-link" href="../menu.html">Cerrar Sesión</a>
              <a class="nav-link" href="../Usuario/infocliente.php">Mi info</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="video-container">
    <video src="../imgs/Caballero.mp4" autoplay muted loop></video>
    <div class="overlay-text">
      Caballero
    </div>
  </div>
  <?php
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
include '../conexion.php';

// Consulta para obtener los productos de la categoría 'caballero' y que estén disponibles
$query = "SELECT * FROM producto WHERE categorias = 'caballero' AND estado = 'disponible'";
$result = mysqli_query($conectar, $query);
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <title>Productos Caballero</title>
</head>

<body>
   
       
        <div class="row mt-4">
            <!-- Contenedor de productos -->
            <div id="productos" class="col-md-8">
                <div class="row">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="col-md-6 mb-4">';
                            echo '<div class="card product-card">';
                            echo "<img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen'>";
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($row['nombre']) . '</h5>';
                            echo '<p class="card-text">Talla: ' . htmlspecialchars($row['talla']) . '</p>';
                            echo '<p class="card-text">Precio: $' . number_format($row['precio_unitario'], 2, ',', '.') . '</p>';
                            echo '<p class="card-text">Cantidad Disponible: ' . htmlspecialchars($row['cantidad']) . '</p>';
                            echo '<form method="POST" action="../pruebaCarrito.php" class="add-to-cart-form">';
                            echo '<input type="hidden" name="idProducto" value="' . htmlspecialchars($row['id_producto']) . '">';
                            echo '<input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">';
                            echo '<div class="mb-3">';
                            echo '<label for="cantidad' . $row['id_producto'] . '" class="form-label">Cantidad:</label>';
                            echo '<input type="number" name="cantidad" id="cantidad' . $row['id_producto'] . '" class="form-control" value="1" min="1" max="' . htmlspecialchars($row['cantidad']) . '" required>';
                            echo '</div>';
                            echo '<button type="submit" class="btn btn-primary">Agregar al Carrito</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hay productos disponibles.</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Contenedor Carrito en el lado derecho -->
            <div class="col-md-4">
                <div id="cart-sidebar" class="mt-5">
                    <h3>Carrito de Compras</h3>
                    <div id="cart-details" class="card p-3 cart-details">
                        <?php
                        // Consulta para obtener los detalles del carrito
                        $cartQuery = "SELECT p.id_producto, p.nombre, SUM(c.cantidad) as cantidad, p.precio_unitario
                        FROM carrito c
                        JOIN producto p ON c.fk_id_producto = p.id_producto
                        WHERE c.fk_id_usuario = ?
                        GROUP BY p.id_producto";
                        
                        $stmt = $conectar->prepare($cartQuery);
                        $stmt->bind_param('i', $idUsuario);
                        $stmt->execute();
                        $cartResult = $stmt->get_result();

                        $total = 0; // Inicializar el total del carrito
                        if ($cartResult->num_rows > 0) {
                            while ($row = $cartResult->fetch_assoc()) {
                                $subtotal = $row['precio_unitario'] * $row['cantidad'];
                                $total += $subtotal;
                        
                                // Mostrar detalles del producto en el carrito
                                echo '<p>' . htmlspecialchars($row['nombre']) . ' - Cantidad: ' . $row['cantidad'] . ' - Precio Total: $' . number_format($subtotal, 2, ',', '.') . '</p>';
                                echo '<form method="POST" action="../eliminarCarritoCaballero.php">';
                                echo '<input type="hidden" name="idProducto" value="' . $row['id_producto'] . '">';
                                echo '<button type="submit" class="btn btn-danger">Eliminar</button>';
                                echo '</form>';
                            }
                            echo '<h4>Total: $' . number_format($total, 2, ',', '.') . '</h4>';
                        } else {
                            echo '<p>El carrito está vacío.</p>';
                        }
                        $stmt->close();
                        ?>
                    </div>
                    <a href="../menuC.php" class="btn btn-primary mt-3">Seguir comprando</a>
                    <!-- Confirmar Pedido -->
                    <form id="confirmarPedidoForm" method="POST" action="../Pedido/verPedido.php">
                      <button type="button" id="confirmarPedidoBtn" class="btn btn-danger mt-3">Confirmar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="notification alert" role="alert" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Manejo del envío del formulario
            $('.add-to-cart-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: 'POST',
                    url: '../pruebaCarrito.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#notification').text(response.message).removeClass('alert-danger').addClass('alert-success').show();
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 2000);

                            // Recargar la página para actualizar el carrito
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            $('#notification').text(response.message).removeClass('alert-success').addClass('alert-danger').show();
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 2000);
                        }
                    }
                });
            });

            // Confirmar Pedido con SweetAlert2
            $('#confirmarPedidoBtn').on('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Estás seguro de que deseas confirmar el pedido?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#confirmarPedidoForm').submit();
                    }
                });
            });
        });
    </script>

  <footer class="bg-primary">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2>Encuéntranos aquí</h2>
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4049.1718945081834!2d-74.0631136444054!3d4.650984044931557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9a45d9f1654b%3A0x3d69138572d157f2!2sSENA%20-%20Centro%20De%20Servicios%20Financieros!5e1!3m2!1ses-419!2sco!4v1722806451254!5m2!1ses-419!2sco" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-md-6">
            <div class="container">
                <h2>Contacto</h2>
                <ul class="social-icons list-unstyled d-flex flex-column align-items-start">
                  <li>
                    <a href="https://www.instagram.com/blingcontabilidad/" class="text-decoration-none text-light">
                      <i class="bi bi-instagram fs-3"></i> Instagram
                    </a>
                  </li>
                  <li>
                    <a href="https://wa.me/573222465996" class="text-decoration-none text-light">
                      <i class="bi bi-whatsapp fs-3"></i> WhatsApp
                    </a>
                  </li>
                  <li>
                    <a href="https://www.youtube.com/channel/UCoJhZ0ileMMnQ2Wkp1bFnCA" class="text-decoration-none text-light">
                      <i class="bi bi-youtube fs-3"></i> YouTube
                    </a>
                  </li>
                  <li>
                    <a href="formularioCorreo.php" class="text-decoration-none text-light">
                      <i class="bi bi-envelope-fill fs-3"></i> Email
                    </a>
                  </li>
                </ul>
              
                <h2>Ayuda</h2>
                <p>
                  <a href="../Ayuda/TerminosUso.html" class="text-decoration-none">Términos de uso</a><br><br>
                  <a href="../Ayuda/TerminosVenta.html" class="text-decoration-none">Términos de venta</a><br><br>
                  <a href="../Ayuda/AvisoLegal.html" class="text-decoration-none">Aviso Legal</a><br><br>
                  <a href="../Ayuda/PoliticaPrivacidad.html" class="text-decoration-none">Política de privacidad y cookies</a>
                </p>
            </div>
              
            <p>&copy; 2023 Bling Compra, Inc. Todos los derechos reservados</p>
        </div>
      </div>
    </footer>

    <div class="theme-switch-wrapper">
      <div class="theme-switch">
        <input type="checkbox" id="theme-switch">
        <label for="theme-switch"></label>
        <i class="bi bi-sun icon"></i>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="../js/theme-switch.js"></script>
  </body>
</html>