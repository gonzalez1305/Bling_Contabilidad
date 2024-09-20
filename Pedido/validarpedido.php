<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    // Si no está logueado o no es un administrador, redirigir al login
    header("Location: index.php");
    exit();
}
?>
<?php

require '../conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí deberías obtener los detalles del pedido que se acaba de hacer
    $cliente = $_POST['cliente'];
    $fecha = $_POST['fecha'];
    $situacion = $_POST['situacion'];
    $unidades = $_POST['unidades'];
    $precio_total = $_POST['precio_total'];
    $correo_cliente = $_POST['correo_cliente']; // Asegúrate de tener el correo del cliente

    // Enviar correo con los detalles del pedido
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_correo@gmail.com';
        $mail->Password = 'tu_contraseña'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinatario del correo
        $mail->setFrom('tu_correo@gmail.com', 'Bling Compra');
        $mail->addAddress($correo_cliente);

        // Contenido
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Detalles de tu Pedido';
        $mail->Body    = "
            <h1>Detalles de tu Pedido</h1>
            <p><strong>Cliente:</strong> $cliente</p>
            <p><strong>Fecha:</strong> $fecha</p>
            <p><strong>Situación:</strong> $situacion</p>
            <p><strong>Unidades:</strong> $unidades</p>
            <p><strong>Precio Total:</strong> $precio_total</p>
        ";

        $mail->send();

        echo "<script>";
        echo "alert('El pedido ha sido registrado y los detalles han sido enviados a tu correo.');";
        echo "window.location.href = 'validarpedido.php';";
        echo "</script>";

    } catch (Exception $e) {
        echo "<script>";
        echo "alert('Error al enviar el correo: " . $mail->ErrorInfo . "');";
        echo "window.history.back();";
        echo "</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos - Bling Compra</title>
    <!-- CSS de Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.6.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../imgs/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>


body.dark-mode .dataTables_wrapper .dataTables_filter label {
    color: #ffffff; /* Color del texto en la etiqueta de búsqueda */
}




</style>
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
                            <a class="nav-link" href="../Inventario/listaInventario.php">
                                <i class="fas fa-box"></i> Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./validarpedido.php">
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
                                <i class="fas fa-credit-card"></i> Marca</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Lista de Pedidos</h1>
                </div>
                <a class="btn btn-success" href="../reportePedido.php" role="button">Reporte Pedidos</a>
                <a class="btn btn-success" href="../reporteGraficoPedidos.html" role="button">Reporte Pedidos Gráfico</a>
                <div class="pedido-container">
                    <table id="pedidosTable" class="display">
                        <thead>
                            <tr>
                                <th>CLIENTE</th>
                                <th>FECHA</th>
                                <th>SITUACION</th>
                                <th>UNIDADES</th>
                                <th>PRECIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT 
                                u.nombre AS cliente,
                                DATE(p.fecha) AS fecha,
                                TIME(p.fecha) AS hora,
                                p.situacion,
                                SUM(dp.unidades) AS total_unidades,
                                SUM(dp.precio_total) AS total_precio,
                                MIN(dp.id_detalles_pedido) AS id_detalles_pedido
                            FROM 
                                pedido p
                            INNER JOIN 
                                detalles_pedido dp ON p.id_pedido = dp.fk_id_pedido
                            INNER JOIN 
                                usuario u ON p.fk_id_usuario = u.id_usuario
                            GROUP BY 
                                u.nombre, DATE(p.fecha), TIME(p.fecha), p.situacion
                            ORDER BY 
                                fecha DESC, hora DESC";
                                $resultado = mysqli_query($conectar, $sql);
                                while ($filas = mysqli_fetch_assoc($resultado)) {
                            ?>
                                <tr>
                                    <<td style='color: black;'><?php echo htmlspecialchars($filas['cliente']) ?></td>
                                    <td style='color: black;'><?php echo htmlspecialchars($filas['fecha']) ?></td>
                                    <td style='color: black;'><?php echo htmlspecialchars($filas['situacion']) ?></td>
                                    <td style='color: black;'><?php echo htmlspecialchars($filas['total_unidades']) ?></td>
                                    <td style='color: black;'><?php echo htmlspecialchars($filas['total_precio']) ?></td>
                                    <td>
                                        <a href='editar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>' class='btn btn-warning btn-sm'>Editar</a>
                                        <a href='eliminar.php?id_detalles_pedido=<?php echo $filas['id_detalles_pedido'] ?>' class='btn btn-danger btn-sm' onclick='return confirmar()'>Eliminar</a>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <a href="../menuV.php" class="btn btn-primary">Volver</a><br><br>
                    <a href="./detallesPedido.php" class="btn btn-primary">Ver detalles de los pedidos</a><br><br>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts dlae DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
    <script>
        $(document).ready(function () {
            $('#pedidosTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'searchBuilder', 'pageLength'
                ],
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)"
                }
            });
        });

        function confirmar() {
            return confirm('¿Está seguro de que desea eliminar este pedido?');
        }
    </script>
</body>
</html>