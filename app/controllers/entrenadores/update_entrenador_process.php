<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$id_entrenador = $_POST['id_entrenador'];
$nombre = $_POST['nombre'];
$ape_pat = $_POST['ape_pat'];
$ape_mat = $_POST['ape_mat'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$foto_actual = $_POST['foto_actual']; // Nombre de la foto actual para mantenerla si no se sube una nueva

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

$foto_nombre = $foto_actual; // Por defecto, mantener la foto actual

// Manejo de la foto: Si se sube una nueva imagen
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $nombre_archivo = $_FILES['image']['name'];
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $foto_nombre = uniqid() . '.' . $extension; // Generar un nombre único para la nueva foto
    $ruta_destino = '../../../public/images/entrenadores/' . $foto_nombre;

    // Mover el nuevo archivo subido
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $ruta_destino)) {
        
        $_SESSION['mensaje'] = "Error al subir la nueva imagen. Verifica los permisos de la carpeta: " . $ruta_destino;
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/edit.php?id=' . $id_entrenador);
        exit();
    }

    // Opcional: Eliminar la foto antigua si no es la por defecto
    if ($foto_actual != 'default_trainer.png' && file_exists('../../../public/images/entrenadores/' . $foto_actual)) {
        unlink('../../../public/images/entrenadores/' . $foto_actual);
    }
}

try {
    // Preparar la sentencia SQL para actualizar el entrenador
    $sentencia = $pdo->prepare("UPDATE tb_entrenadores
                                SET
                                    nombre = :nombre,
                                    ape_pat = :ape_pat,
                                    ape_mat = :ape_mat,
                                    dni = :dni,
                                    telefono = :telefono,
                                    email = :email,
                                    foto = :foto,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_entrenador = :id_entrenador
                                AND id_gimnasio = :id_gimnasio");

    // Vincular los parámetros
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':ape_pat', $ape_pat);
    $sentencia->bindParam(':ape_mat', $ape_mat);
    $sentencia->bindParam(':dni', $dni);
    $sentencia->bindParam(':telefono', $telefono);
    $sentencia->bindParam(':email', $email);
    $sentencia->bindParam(':foto', $foto_nombre); // Usar el nombre de la foto (nueva o actual)
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
       
        $_SESSION['mensaje'] = "Entrenador actualizado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/entrenadores/'); // Redirigir al listado de entrenadores
        exit();
    } else {
      
        $_SESSION['mensaje'] = "Error: No se pudo actualizar el entrenador en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/edit.php?id=' . $id_entrenador);
        exit();
    }
} catch (PDOException $e) {
  
    $_SESSION['mensaje'] = "Error de base de datos al actualizar entrenador: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/edit.php?id=' . $id_entrenador);
    exit();
}

?>
