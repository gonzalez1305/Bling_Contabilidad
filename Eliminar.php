<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id_detalles_pedido'])) {
        if (is_numeric($_GET['id_detalles_pedido'])) {
            $id_detalles_pedido = $_GET['id_detalles_pedido'];

            // Obtener el id_pedido relacionado
            $sqlObtenerPedido = "SELECT fk_id_pedido FROM detalles_pedido WHERE id_detalles_pedido = $id_detalles_pedido";
            $resultadoPedido = mysqli_query($conectar, $sqlObtenerPedido);
            
            if ($resultadoPedido && mysqli_num_rows($resultadoPedido) > 0) {
                $filaPedido = mysqli_fetch_assoc($resultadoPedido);
                $id_pedido = $filaPedido['fk_id_pedido'];
                
                // Eliminar el detalle del pedido
                $eliminar_detalle = "DELETE FROM detalles_pedido WHERE id_detalles_pedido = $id_detalles_pedido";
                $resultado_eliminar_detalle = mysqli_query($conectar, $eliminar_detalle);

                if ($resultado_eliminar_detalle) {
                    // Verificar si quedan más detalles para el pedido
                    $sqlVerificarDetalles = "SELECT COUNT(*) as total FROM detalles_pedido WHERE fk_id_pedido = $id_pedido";
                    $resultadoVerificarDetalles = mysqli_query($conectar, $sqlVerificarDetalles);
                    $filaDetalles = mysqli_fetch_assoc($resultadoVerificarDetalles);

                    if ($filaDetalles['total'] == 0) {
                        // No quedan más detalles, eliminar el pedido
                        $eliminar_pedido = "DELETE FROM pedido WHERE id_pedido = $id_pedido";
                        $resultado_eliminar_pedido = mysqli_query($conectar, $eliminar_pedido);

                        if ($resultado_eliminar_pedido) {
                            header("Location: visualizar.php");
                            exit();
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error al intentar eliminar el pedido.</div>';
                        }
                    } else {
                        // Solo se eliminó el detalle del pedido
                        header("Location: visualizar.php");
                        exit();
                    }
                } 
            } 
        } 
    } 
}

mysqli_close($conectar);
?>
