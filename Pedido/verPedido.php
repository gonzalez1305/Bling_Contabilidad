<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

include '../conexion.php';
$idUsuario = $_SESSION['id_usuario'];

// Consulta para obtener los pedidos del usuario
$query = "SELECT * FROM pedido WHERE fk_id_usuario = ?";
$stmt = $conectar->prepare($query);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" href="../imgs/logo.png">
    <title>Ver Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        #header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        #header img {
            width: 150px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #117a8b;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div id="header" class="bg-primary text-white text-center p-3 rounded">
            <img src="../imgs/logo.jpeg" alt="logo" id="logo" class="mb-2">
            <h1>Mis Pedidos</h1>
            <a href="../menuC.html" class="btn btn-light">Volver</a>
        </div>

        <div class="mt-4">
            <?php
            if ($result->num_rows > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>ID Pedido</th><th>Fecha</th><th>Situación</th></tr></thead>';
                echo '<tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id_pedido']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['situacion']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No tienes pedidos.</p>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
