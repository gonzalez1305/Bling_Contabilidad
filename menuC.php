<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bling Compra - Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="icon" href="imgs/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <div class="top-info">
    <p>Email: <a href="mailto:blingcontabilidadgaes@gmail.com" style="color: #ffffff;">blingcontabilidadgaes@gmail.com</a> | Teléfono: <a href="tel:+573222465996" style="color: #ffffff;">+57 322 2465996</a></p>
  </div>

  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="imgs/logo.png" alt="Logo">
        Bling Compra
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
              <a class="nav-link" href="logout.php">Cerrar Sesión</a>
              <a class="nav-link" href="./Usuario/infocliente.php">Mi info</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="video-container">
    <video src="imgs/VIDEO.mp4" autoplay muted loop></video>
    <div class="overlay-text">
      Lo mejor del calzado aquí
    </div>
  </div>

  <div class="container">
    <!-- Catálogo Section -->
    <section id="catalogo" class="section">
      <div class="container">
        <h2 class="text-center mb-4">Catálogo</h2>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="catalog-card bg-light text-center">
              <a href="Inventario/niño.php" class="text-decoration-none">
                <img src="imgs/niño1.jpg" class="img-fluid" alt="Zapatillas para Niños">
                <h3>Niños</h3>
                <p>Descubre nuestra selección de zapatillas para los más pequeños.</p>
              </a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="catalog-card bg-light text-center">
              <a href="Inventario/caballero.php" class="text-decoration-none">
                <img src="imgs/hombre1.jpg" class="img-fluid" alt="Zapatillas para Hombres">
                <h3>Hombres</h3>
                <p>Lo mejor del calzado aquí.</p>
              </a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="catalog-card bg-light text-center">
              <a href="Inventario/dama.php" class="text-decoration-none">
                <img src="imgs/menuzapato2.jpg" class="img-fluid" alt="Zapatillas para Mujer">
                <h3>Mujer</h3>
                <p>Explora nuestra colección de zapatillas para mujer.</p>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bienvenido a Bling Compra Section -->
    <section id="inicio" class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="blue-table">
              <table>
                <tr>
                  <th>Bienvenido a Bling Compra</th>
                </tr>
                <tr>
                  <td>Somos una microempresa dedicada a la venta de todo tipo de zapatillas. Nuestro objetivo es ofrecer productos de calidad a precios accesibles, asegurando una experiencia de compra satisfactoria para todos nuestros clientes.</td>
                </tr>
              </table>
            </div>
          </div>

          <!-- Acerca de Nosotros Section -->
          <div class="col-md-6 mb-4">
            <div class="blue-table">
              <table>
                <tr>
                  <th>Acerca de Nosotros</th>
                </tr>
                <tr>
                  <td>En Bling Compra, nos especializamos en ofrecer una amplia gama de calzado para todas las edades y gustos. Nuestra misión es garantizar la satisfacción total de nuestros clientes a través de productos de alta calidad y un excelente servicio al cliente.</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
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
                <ul class="social-icons list-unstyled d-flex flex-column align-items-medium">
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
                  <a href="Ayuda/TerminosUso.html" class="text-decoration-none">Términos de uso</a><br><br>
                  <a href="Ayuda/TerminosVenta.html" class="text-decoration-none">Términos de venta</a><br><br>
                  <a href="Ayuda/AvisoLegal.html" class="text-decoration-none">Aviso Legal</a><br><br>
                  <a href="Ayuda/PoliticaPrivacidad.html" class="text-decoration-none">Política de privacidad y cookies</a><br><br>
                  <a href="manualusuarioc.php" class="text-decoration-none">Manual usuario</a>
                </p>
              </div>
              
      <p>&copy; 2023 Bling Compra, Inc. Todos los derechos reservados</p>
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
  <script src="js/menu.js"></script>
  <script src="js/theme-switch.js"></script>

</body>
</html>