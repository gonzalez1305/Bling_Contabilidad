<?php
require '../conexion.php'; // Conexión a la base de datos

// Verificar si se recibió el ID del pago a editar
if (isset($_GET['id'])) {
    $id_pago = intval($_GET['id']);

    // Obtener la información actual del pago
    $pagoQuery = "SELECT fecha_pago, monto FROM pagos WHERE id_pago = $id_pago";
    $pagoResult = mysqli_query($conectar, $pagoQuery);

    // Verificar si el pago existe
    if (mysqli_num_rows($pagoResult) == 1) {
        $pago = mysqli_fetch_assoc($pagoResult);
        $fecha_pago = $pago['fecha_pago'];
        $monto = $pago['monto'];
    } else {
        $_SESSION['mensaje'] = "Pago no encontrado";
        header("Location: verPago.php");
        exit();
    }
} else {
    $_SESSION['mensaje'] = "ID de pago no proporcionado";
    header("Location: verPago.php");
    exit();
}

// Actualizar el pago si se envió el formulario
if (isset($_POST['actualizar'])) {
    $nueva_fecha_pago = mysqli_real_escape_string($conectar, $_POST['fecha_pago']);
    $nuevo_monto = floatval($_POST['monto']);

    // Validar los datos
    if (!empty($nueva_fecha_pago) && $nuevo_monto > 0) {
        // Actualizar los datos en la base de datos
        $updateQuery = "UPDATE pagos SET fecha_pago = '$nueva_fecha_pago', monto = $nuevo_monto WHERE id_pago = $id_pago";
        if (mysqli_query($conectar, $updateQuery)) {
            $_SESSION['mensaje'] = "Pago actualizado exitosamente";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el pago";
        }
    } else {
        $_SESSION['mensaje'] = "Por favor, complete todos los campos correctamente";
    }

    // Redirigir de vuelta a la lista de pagos
    header("Location: verPago.php");
    exit();
}

mysqli_close($conectar);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1 class="my-4">Editar Pago</h1>

    <!-- Mostrar formulario de edición -->
    <form method="POST" action="editarPago.php?id=<?php echo $id_pago; ?>">
        <div class="mb-3">
            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
            <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="<?php echo htmlspecialchars($fecha_pago); ?>" required>
        </div>

        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" step="0.01" class="form-control" id="monto" name="monto" value="<?php echo htmlspecialchars($monto); ?>" required>
        </div>

        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
        <a href="verPago.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
