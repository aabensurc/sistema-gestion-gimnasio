<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$ape_pat = $_POST['ape_pat'];
$ape_mat = $_POST['ape_mat'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

// Manejo de la foto
$foto_nombre = 'default_trainer.png'; // Nombre de la imagen por defecto
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $nombre_archivo = $_FILES['image']['name'];
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $foto_nombre = uniqid() . '.' . $extension; // Generar un nombre único para la foto
    
    // RUTA CORREGIDA: Asegúrate de que esta ruta sea la correcta en tu servidor
    $ruta_destino = '../../../public/images/entrenadores/' . $foto_nombre; 

    // Mover el archivo subido al directorio de destino
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $ruta_destino)) {
        
        $_SESSION['mensaje'] = "Error al subir la imagen. Verifica los permisos de la carpeta: " . $ruta_destino;
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/create.php');
        exit();
    }
}

try {
    // Preparar la sentencia SQL para insertar el nuevo entrenador
    $sentencia = $pdo->prepare("INSERT INTO tb_entrenadores
                                (nombre, ape_pat, ape_mat, dni, telefono, email, foto, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                VALUES (:nombre, :ape_pat, :ape_mat, :dni, :telefono, :email, :foto, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)");

    // Vincular los parámetros
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':ape_pat', $ape_pat);
    $sentencia->bindParam(':ape_mat', $ape_mat);
    $sentencia->bindParam(':dni', $dni);
    $sentencia->bindParam(':telefono', $telefono);
    $sentencia->bindParam(':email', $email);
    $sentencia->bindParam(':foto', $foto_nombre); // Usar el nombre de la foto (por defecto o subida)
    $sentencia->bindParam(':fyh_creacion', $fechaHora);
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Entrenador registrado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/entrenadores/'); // Redirigir al listado de entrenadores
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo registrar el entrenador en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/create.php');
        exit();
    }
} catch (PDOException $e) {
    
    $_SESSION['mensaje'] = "Error de base de datos al registrar entrenador: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/create.php');
    exit();
}

?>
