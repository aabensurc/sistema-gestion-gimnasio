<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID del cliente desde la URL (se envía por GET desde el modal de confirmación)
$id_cliente = $_GET['id_cliente'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_cliente)) {
   
    $_SESSION['mensaje'] = "Error: ID de cliente no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    ?>
    <script>
        location.href = "<?php echo $URL;?>/clientes";
    </script>
    <?php
    exit();
}

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // Primero, obtener el nombre de la foto del cliente antes de eliminarlo
    $sql_get_foto = "SELECT foto FROM tb_clientes WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio";
    $query_get_foto = $pdo->prepare($sql_get_foto);
    $query_get_foto->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $query_get_foto->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $query_get_foto->execute();
    $cliente_data = $query_get_foto->fetch(PDO::FETCH_ASSOC);
    $foto_a_eliminar = $cliente_data['foto'] ?? 'default_image.jpg'; // Obtener la foto actual

    // Preparar la sentencia SQL para eliminar el cliente
    $sentencia_delete = $pdo->prepare("DELETE FROM tb_clientes WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio");
    $sentencia_delete->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_delete->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia de eliminación
    if ($sentencia_delete->execute()) {
        // Si la eliminación de la base de datos fue exitosa, eliminar la foto del servidor
        // Solo eliminar si no es la imagen por defecto
        if ($foto_a_eliminar != 'default_image.jpg' && file_exists('../../../public/images/clientes/' . $foto_a_eliminar)) {
            unlink('../../../public/images/clientes/' . $foto_a_eliminar);
        }

        // Confirmar la transacción
        $pdo->commit();
       
        $_SESSION['mensaje'] = "Se eliminó el cliente de la manera correcta.";
        $_SESSION['icono'] = "success";
        ?>
        <script>
            location.href = "<?php echo $URL;?>/clientes";
        </script>
        <?php
    } else {
        // Si no se pudo eliminar de la base de datos, revertir la transacción
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
       
        $_SESSION['mensaje'] = "Error: No se pudo eliminar el cliente de la base de datos.";
        $_SESSION['icono'] = "error";
        ?>
        <script>
            location.href = "<?php echo $URL;?>/clientes";
        </script>
        <?php
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
  
    $_SESSION['mensaje'] = "Error de base de datos al eliminar cliente: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    ?>
    <script>
        location.href = "<?php echo $URL;?>/clientes";
    </script>
    <?php
}

?>
