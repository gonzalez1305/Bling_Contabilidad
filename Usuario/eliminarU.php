<?php
include("../conexion.php");

// Verificar si la solicitud es un POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el ID del usuario a eliminar desde el formulario
    $id_usuario = $_POST['id_usuario'];

    // Validar que el ID de usuario no esté vacío
    if (!empty($id_usuario)) {
        // Consultar la imagen del usuario antes de eliminar
        $sql = "SELECT imagen FROM usuario WHERE id_usuario = ?";
        $stmt = $conectar->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conectar->error);
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

     

        // Eliminar el usuario de la base de datos
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $conectar->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conectar->error);
        }
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            // Verificar si la imagen existe y eliminar el archivo si es necesario
            if (!empty($user['imagen']) && file_exists("../usuariofoto/" . $user['imagen'])) {
                if (!unlink("../usuariofoto/" . $user['imagen'])) {
                    echo "<script>
                            alert('Error al eliminar la imagen del usuario');
                            window.location.href = './validarusuario.php';
                          </script>";
                    exit();
                }
            }
            echo "<script>
                    alert('Usuario eliminado con éxito');
                    window.location.href = './validarusuario.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al eliminar el usuario: " . $conectar->error . "');
                    window.location.href = './validarusuario.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('ID de usuario no válido');
                window.location.href = './validarusuario.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Método de solicitud no válido');
            window.location.href = './validarusuario.php';
          </script>";
}
?>