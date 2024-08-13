<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estiloCategorias.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Sección Caballero</title>
</head>

<body>
    <div id="container">
        <div id="header">
            <div id="logo-container">
                <img src="../imgs/logo.jpeg" alt="logo" id="logo">
            </div>

            <div id="welcome-text text-center">
                <h1 class="text-center">Sección de Caballero</h1>
                <a href="../menuC.html">Volver</a>
            </div>
        </div>

        <div id="zapatos">
            <!-- Primera fila de productos -->
            <div class="fila">
                <!-- Producto 1 -->
                <div class="producto">
                    <img src="../imgs/hombre1.jpg" alt="Zapato 1">
                    <br>
                    <br>
                    <form action="../prueba.php?idUsuario=<?php echo $idUsuario; ?>&idProducto=1" method="post">
                        <div class="info">
                            <p><strong>Nombre del Producto:</strong> Zapatilla bota Nike for One</p>
                            <p><strong>Talla:</strong> 40</p>
                            <p><strong>Precio:</strong> $100.00</p>
                            <label for="cantidad1">Cantidad:</label>
                            <input type="number" id="cantidad1" name="cantidad" min="1" required><br><br>
                            <input type="submit" value="Añadir al carrito">
                        </div>
                    </form>
                </div>

                <!-- Producto 2 -->
                <div class="producto">
                    <img src="../imgs/hombre3.jpg" alt="Zapato 2">
                    <br>
                    <br>
                    <form action="../prueba.php?idUsuario=<?php echo $idUsuario; ?>&idProducto=2" method="post">
                        <div class="info">
                            <p><strong>Nombre del Producto:</strong> Zapatillas Puma Hombre</p>
                            <p><strong>Talla:</strong> 38</p>
                            <p><strong>Precio:</strong> $180.00</p>
                            <label for="cantidad2">Cantidad:</label>
                            <input type="number" id="cantidad2" name="cantidad" min="1" required><br><br>
                            <input type="submit" value="Añadir al carrito">
                        </div>
                    </form>
                </div>

                <!-- Producto 3 -->
                <div class="producto">
                    <img src="../imgs/hombre2.jpg" alt="Zapato 3">
                    <br>
                    <br>
                    <form action="../prueba.php?idUsuario=<?php echo $idUsuario; ?>&idProducto=3" method="post">
                        <div class="info">
                            <p><strong>Nombre del Producto:</strong> Zapatillas Adidas Fashion</p>
                            <p><strong>Talla:</strong> 39</p>
                            <p><strong>Precio:</strong> $215.00</p>
                            <label for="cantidad3">Cantidad:</label>
                            <input type="number" id="cantidad3" name="cantidad" min="1" required><br><br>
                            <input type="submit" value="Añadir al carrito">
                        </div>
                    </form>
                </div>

                <!-- Producto 4 -->
                <div class="producto">
                    <img src="../imgs/hombre4.jpg" alt="Zapato 4">
                    <br>
                    <br>
                    <form action="../prueba.php?idUsuario=<?php echo $idUsuario; ?>&idProducto=4" method="post">
                        <div class="info">
                            <p><strong>Nombre del Producto:</strong> Zapatillas Adidas Zamba</p>
                            <p><strong>Talla:</strong> 42</p>
                            <p><strong>Precio:</strong> $130.00</p>
                            <label for="cantidad4">Cantidad:</label>
                            <input type="number" id="cantidad4" name="cantidad" min="1" required><br><br>
                            <input type="submit" value="Añadir al carrito">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
