<?php

// Conectar a la base de datos
$conectar = mysqli_connect('localhost:3307', 'root', '', 'bling');

// Obtener los valores de los atributos del formulario
$cedula = $_POST['cedula'];
$numero_Tarjeta = $_POST['numero_Tarjeta'];
$codigo_seguridad = $_POST['codigo_seguridad'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];

// Obtener el ID de pago de la transacción
$id_pago = mysqli_insert_id($conectar);

// Crear un registro en la tabla pago

$resultado = mysqli_query($conectar, $sql);

// Comprobar si la consulta se ha ejecutado correctamente
if ($resultado) {
echo "El registro se ha creado correctamente.";
} else {
echo "Error al crear el registro.";
}

// Cerrar la conexión a la base de datos


// Preparar la consulta SQL
$sql = "INSERT INTO transacciones (cedula, numero_Tarjeta, codigo_seguridad, fecha_vencimiento, fk_id_pago)
VALUES ('$cedula', '$numero_Tarjeta', '$codigo_seguridad', '$fecha_vencimiento', $id_pago)";

// Ejecutar la consulta SQL
$resultado = mysqli_query($conectar, $sql);

// Cerrar la conexión a la base de datos
mysqli_close($conectar);

// Comprobar si la consulta se ha ejecutado correctamente
if ($resultado) {
 echo "Los datos se han guardado correctamente.";
 header('Location: ventas_list.php'); 
} else {
  echo "Error al guardar los datos.";
}
?>
