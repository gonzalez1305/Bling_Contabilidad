<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bling Compra - Sección Caballero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Asegúrate que esta ruta sea correcta -->
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
            <a class="nav-link" href="../menuC.html">Regresar</a>
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
  session_start();
  if (isset($_SESSION['id_usuario'])) {
      $idUsuario = $_SESSION['id_usuario'];
  }
  include '../conexion.php';

  // Consulta para obtener los productos de la categoría 'caballero' y que estén disponibles
  $query = "SELECT * FROM producto WHERE categorias = 'caballero' AND estado = 'disponible'";
  $result = mysqli_query($conectar, $query);
  ?>

  <div class="container mt-4">
    <div class="row">
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
                  echo '<form method="POST" class="add-to-cart-form">';
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
                    echo '<form method="POST" action="../eliminarProductoCarrito.php">';
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
          <a href="../menuC.html" class="btn btn-primary mt-3">Seguir comprando</a>
          <!-- Confirmar Pedido -->
          <form method="POST" action="../Pedido/verPedido.php">
            <button type="submit" name="confirmarPedido" class="btn btn-danger mt-3">Confirmar Pedido</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="notification" class="notification alert" role="alert" style="display: none;"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.add-to-cart-form').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
          url: '../carrito.php',
          type: 'POST',
          data: form.serialize(),
          success: function(response) {
            $('#notification').removeClass('alert-danger').addClass('alert-success').text('Producto agregado al carrito').show();
            setTimeout(function() {
              $('#notification').fadeOut();
            }, 3000);
            // Actualizar los detalles del carrito
            $('#cart-details').load(location.href + ' #cart-details>*', "");
          },
          error: function() {
            $('#notification').removeClass('alert-success').addClass('alert-danger').text('Error al agregar al carrito').show();
            setTimeout(function() {
              $('#notification').fadeOut();
            }, 3000);
          }
        });
      });
    });
  </script>

</body>
</html>
