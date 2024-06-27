<?php
if (isset($_POST['id_usuario']) && isset($_POST['adminPassword'])) {
    $id_usuario = $_POST['id_usuario'];
    $adminPassword = $_POST['adminPassword'];

    // Verificar la contraseña
    if ($adminPassword !== 'BLINGADMIN2024') {
        echo "<script language='javascript'>";
        echo "alert('Contraseña incorrecta. No se puede eliminar el usuario.');";
        echo "location.assign('validarusuario.php');";
        echo "</script>";
        exit;
    }

    include("conexion.php");

    // Eliminar registros dependientes de la tabla 'administrador'
    $sqlEliminarAdministrador = "DELETE FROM administrador WHERE fk_id_usuario = '".$id_usuario."'";
    $resultadoEliminarAdministrador = mysqli_query($conectar, $sqlEliminarAdministrador);

    // Eliminar registros dependientes de la tabla 'detalles_pedido'
    $sqlEliminarDetallesPedido = "DELETE FROM detalles_pedido WHERE fk_id_pedido IN (SELECT id_pedido FROM pedido WHERE fk_id_usuario = '".$id_usuario."')";
    $resultadoEliminarDetallesPedido = mysqli_query($conectar, $sqlEliminarDetallesPedido);

    // Luego, eliminar el usuario
    $sqlEliminarUsuario = "DELETE FROM usuario WHERE id_usuario = '".$id_usuario."'";
    $resultadoEliminarUsuario = mysqli_query($conectar, $sqlEliminarUsuario);

    // Verificar resultados y mostrar mensajes
    if ($resultadoEliminarAdministrador && $resultadoEliminarDetallesPedido && $resultadoEliminarUsuario) {
        echo "<script language='javascript'>";
        echo "alert('Los datos se eliminaron correctamente');";
        echo "location.assign('validarusuario.php');";
        echo "</script>";
    } else {
        echo "<script language='javascript'>";
        echo "alert('Los datos NO se eliminaron correctamente');";
        echo "location.assign('validarusuario.php');";
        echo "</script>";
    }

    mysqli_close($conectar);
} else {
    echo "Error: No se proporcionó el ID del usuario o la contraseña.";
}
?>