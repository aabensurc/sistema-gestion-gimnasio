<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$id_cliente = $_POST['id_cliente'];
// ELIMINADA la línea: $codigo = $_POST['codigo']; 
$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$ape_pat = $_POST['ape_pat'];
$ape_mat = $_POST['ape_mat'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

// Obtener la foto actual del cliente de la base de datos para manejar su eliminación si se sube una nueva
$foto_actual_db = '';
try {
    $sql_get_foto_actual = "SELECT foto FROM tb_clientes WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio";
    $query_get_foto_actual = $pdo->prepare($sql_get_foto_actual);
    $query_get_foto_actual->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $query_get_foto_actual->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $query_get_foto_actual->execute();
    $result_foto_actual = $query_get_foto_actual->fetch(PDO::FETCH_ASSOC);
    if ($result_foto_actual) {
        $foto_actual_db = $result_foto_actual['foto'];
    }
} catch (PDOException $e) {
    // Manejo de error si no se puede obtener la foto actual
    
    $_SESSION['mensaje'] = "Error al obtener la foto actual del cliente: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/clientes/update.php?id=' . $id_cliente);
    exit();
}

$foto_nombre = $foto_actual_db; // Por defecto, mantener la foto actual de la DB

// Manejo de la foto: Si se sube una nueva imagen
// Se verifica si el archivo fue subido y si no hay errores
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
    $nombre_archivo = $_FILES['image']['name'];
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $foto_nombre = uniqid() . '.' . $extension; // Generar un nombre único para la nueva foto
    $ruta_destino = '../../../public/images/clientes/' . $foto_nombre;

    // Mover el nuevo archivo subido
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $ruta_destino)) {
        
        $_SESSION['mensaje'] = "Error al subir la nueva imagen. Verifica los permisos de la carpeta: " . $ruta_destino;
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/clientes/update.php?id=' . $id_cliente);
        exit();
    }

    // Opcional: Eliminar la foto antigua si no es la por defecto y se subió una nueva
    if ($foto_actual_db != 'default_image.jpg' && file_exists('../../../public/images/clientes/' . $foto_actual_db)) {
        unlink('../../../public/images/clientes/' . $foto_actual_db);
    }
}

try {
    // Preparar la sentencia SQL para actualizar el cliente
    // ELIMINADA la actualización de 'codigo = :codigo'
    $sentencia = $pdo->prepare("UPDATE tb_clientes
                                SET
                                    dni = :dni,
                                    nombres = :nombres,
                                    ape_pat = :ape_pat,
                                    ape_mat = :ape_mat,
                                    telefono = :telefono,
                                    email = :email,
                                    foto = :foto,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio");

    // Vincular los parámetros
    // ELIMINADA la vinculación: $sentencia->bindParam(':codigo', $codigo);
    $sentencia->bindParam(':dni', $dni);
    $sentencia->bindParam(':nombres', $nombres);
    $sentencia->bindParam(':ape_pat', $ape_pat);
    $sentencia->bindParam(':ape_mat', $ape_mat);
    $sentencia->bindParam(':telefono', $telefono);
    $sentencia->bindParam(':email', $email);
    $sentencia->bindParam(':foto', $foto_nombre); // Usar el nombre de la foto (nueva o actual)
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Se actualizó el cliente correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/clientes/');
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo actualizar el cliente en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/clientes/update.php?id=' . $id_cliente);
        exit();
    }
} catch (PDOException $e) {
    
    $_SESSION['mensaje'] = "Error de base de datos al actualizar cliente: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/clientes/update.php?id=' . $id_cliente);
    exit();
}

?>