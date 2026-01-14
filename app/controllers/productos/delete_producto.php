<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID del producto desde el formulario (se envía por POST desde el modal de confirmación)
$id_producto = $_POST['id_producto'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_producto)) {
    
    $_SESSION['mensaje'] = "Error: ID de producto no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/');
    exit();
}

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // Primero, obtener el nombre de la foto del producto antes de eliminarlo
    $sql_get_foto = "SELECT foto FROM tb_productos WHERE id_producto = :id_producto AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"; // Asegurar que el producto pertenece al gimnasio de la sesión
    $query_get_foto = $pdo->prepare($sql_get_foto);
    $query_get_foto->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $query_get_foto->execute();
    $producto_data = $query_get_foto->fetch(PDO::FETCH_ASSOC);
    $foto_a_eliminar = $producto_data['foto'] ?? 'default_product.png'; // Obtener la foto actual

    // Preparar la sentencia SQL para eliminar el producto
    // La clave foránea en tb_detalle_ventas con ON DELETE SET NULL manejará la relación
    $sentencia_delete = $pdo->prepare("DELETE FROM tb_productos WHERE id_producto = :id_producto AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"); // Asegurar que el producto pertenece al gimnasio de la sesión
    $sentencia_delete->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

    // Ejecutar la sentencia de eliminación
    if ($sentencia_delete->execute()) {
        // Si la eliminación de la base de datos fue exitosa, eliminar la foto del servidor
        if ($foto_a_eliminar != 'default_product.png' && file_exists('../../../public/images/productos/' . $foto_a_eliminar)) {
            unlink('../../../public/images/productos/' . $foto_a_eliminar);
        }

        // Confirmar la transacción
        $pdo->commit();
        
        $_SESSION['mensaje'] = "Producto eliminado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/productos/'); // Redirigir de vuelta al listado de productos
        exit();
    } else {
        // Si no se pudo eliminar de la base de datos, revertir la transacción
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        $_SESSION['mensaje'] = "Error: No se pudo eliminar el producto de la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/');
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $_SESSION['mensaje'] = "Error de base de datos al eliminar el producto: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/');
    exit();
}

?>
