<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$id_producto = $_POST['id_producto'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio_venta = $_POST['precio_venta'];
$stock = $_POST['stock'];
$foto_actual = $_POST['foto_actual']; // Nombre de la foto actual para mantenerla si no se sube una nueva

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

$foto_nombre = $foto_actual; // Por defecto, mantener la foto actual

// Manejo de la foto: Si se sube una nueva imagen
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
    $nombre_archivo = $_FILES['image']['name'];
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $foto_nombre = uniqid() . '.' . $extension; // Generar un nombre único para la nueva foto
    $ruta_destino = '../../../public/images/productos/' . $foto_nombre;

    // Mover el nuevo archivo subido
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $ruta_destino)) {
        
        $_SESSION['mensaje'] = "Error al subir la nueva imagen del producto. Verifica los permisos de la carpeta: " . $ruta_destino;
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/edit.php?id=' . $id_producto);
        exit();
    }

    // Opcional: Eliminar la foto antigua si no es la por defecto
    if ($foto_actual != 'default_product.png' && file_exists('../../../public/images/productos/' . $foto_actual)) {
        unlink('../../../public/images/productos/' . $foto_actual);
    }
}

try {
    // Preparar la sentencia SQL para actualizar el producto
    $sentencia = $pdo->prepare("UPDATE tb_productos
                                SET
                                    nombre = :nombre,
                                    descripcion = :descripcion,
                                    precio_venta = :precio_venta,
                                    stock = :stock,
                                    foto = :foto,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_producto = :id_producto
                                AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"); // Asegurar que el producto pertenece al gimnasio de la sesión

    // Vincular los parámetros
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':descripcion', $descripcion);
    $sentencia->bindParam(':precio_venta', $precio_venta);
    $sentencia->bindParam(':stock', $stock);
    $sentencia->bindParam(':foto', $foto_nombre); // Usar el nombre de la foto (nueva o actual)
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
       
        $_SESSION['mensaje'] = "Producto actualizado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/productos/'); // Redirigir al listado de productos
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo actualizar el producto en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/edit.php?id=' . $id_producto);
        exit();
    }
} catch (PDOException $e) {
   
    $_SESSION['mensaje'] = "Error de base de datos al actualizar producto: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/edit.php?id=' . $id_producto);
    exit();
}

?>
