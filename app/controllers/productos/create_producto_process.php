<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio_venta = $_POST['precio_venta'];
$stock = $_POST['stock'];

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

// Manejo de la foto
$foto_nombre = 'default_product.png'; // Nombre de la imagen por defecto
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
    $nombre_archivo = $_FILES['image']['name'];
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $foto_nombre = uniqid() . '.' . $extension; // Generar un nombre único para la foto
    $ruta_destino = '../../../public/images/productos/' . $foto_nombre; // Ruta donde se guardará la foto

    // Mover el archivo subido al directorio de destino
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $ruta_destino)) {
       
        $_SESSION['mensaje'] = "Error al subir la imagen del producto. Verifica los permisos de la carpeta: " . $ruta_destino;
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/create.php');
        exit();
    }
}

try {
    // Preparar la sentencia SQL para insertar el nuevo producto
    $sentencia = $pdo->prepare("INSERT INTO tb_productos
                                (nombre, descripcion, precio_venta, stock, foto, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                VALUES (:nombre, :descripcion, :precio_venta, :stock, :foto, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)");

    // Vincular los parámetros
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':descripcion', $descripcion);
    $sentencia->bindParam(':precio_venta', $precio_venta);
    $sentencia->bindParam(':stock', $stock);
    $sentencia->bindParam(':foto', $foto_nombre); // Usar el nombre de la foto (por defecto o subida)
    $sentencia->bindParam(':fyh_creacion', $fechaHora);
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Producto registrado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/productos/'); // Redirigir al listado de productos
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo registrar el producto en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/create.php');
        exit();
    }
} catch (PDOException $e) {
    
    $_SESSION['mensaje'] = "Error de base de datos al registrar producto: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/create.php');
    exit();
}

?>
