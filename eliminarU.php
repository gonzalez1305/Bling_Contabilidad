<?php
// Verificar si se ha proporcionado el parámetro id_usuario
if(isset($_GET['id_usuario'])){
    $id_usuario = $_GET['id_usuario'];
    
    include("conexion.php");

    // Eliminar registros dependientes de la tabla 'detalles_pedido'
    $sqlEliminarDetallesPedido = "DELETE FROM detalles_pedido WHERE fk_id_pedido IN (SELECT id_pedido FROM pedido WHERE fk_id_usuario = '".$id_usuario."')";
    $resultadoEliminarDetallesPedido = mysqli_query($conectar, $sqlEliminarDetallesPedido);

    // Luego, eliminar el usuario
    $sqlEliminarUsuario = "DELETE FROM usuario WHERE id_usuario = '".$id_usuario."'";
    $resultadoEliminarUsuario = mysqli_query($conectar, $sqlEliminarUsuario);

    // Verificar resultados y mostrar mensajes
    if($resultadoEliminarDetallesPedido && $resultadoEliminarUsuario){
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
    echo "Error: No se proporcionó el ID del usuario a eliminar.";
}
?>
