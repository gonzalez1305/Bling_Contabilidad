<?php
session_start();
require 'conexion.php'; // Asegúrate de tener el archivo de conexión

if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
}

// Obtener productos de la categoría "Dama"
$query = "SELECT * FROM producto WHERE categorias = 'dama'";
$result = $conectar->query($query);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../imgs/logo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estiloCategorias.css">
    <title>Sección Dama</title>
</head>

<body>
    <div id="container">
        <div id="header">
            <div id="logo-container">
                <img src="../imgs/logo.jpeg" alt="logo" id="logo">
            </div>
            <div id="welcome-text text-center">
                <h1 class="text-center">Sección de Dama</h1>
                <a href="../menuC.html">Volver</a>
            </div>
        </div>

        <div id="zapatos">
            <!-- Primera fila de productos -->
            <div class="fila">
                <?php
                // Verificar si hay productos
                if ($result->num_rows > 0) {
                    // Mostrar productos
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="producto">';
                        // Mostrar imagen desde la base de datos
                        echo '<img src="../imgs/' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nombre']) . '">';
                        echo '<br><br>';
                        echo '<form action="../prueba.php?idUsuario=' . $idUsuario . '&idProducto=' . $row['id_producto'] . '" method="post">';
                        echo '<div class="info">';
                        echo '<p><strong>Nombre del Producto:</strong> ' . htmlspecialchars($row['nombre']) . '</p>';
                        echo '<p><strong>Talla:</strong> ' . htmlspecialchars($row['talla']) . '</p>';
                        echo '<p><strong>Precio:</strong> $' . htmlspecialchars($row['precio_unitario']) . '</p>';
                        echo '<label for="cantidad' . $row['id_producto'] . '">Cantidad:</label>';
                        echo '<input type="number" id="cantidad' . $row['id_producto'] . '" name="cantidad" min="1" required><br><br>';
                        echo '<input type="submit" value="Añadir al carrito">';
                        echo '</div>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay productos disponibles en esta categoría.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
