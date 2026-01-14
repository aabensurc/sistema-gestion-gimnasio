<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos de la asesoría desde el formulario
$id_asesoria = $_POST['id_asesoria'];
$id_cliente = $_POST['id_cliente'];
$id_entrenador = $_POST['id_entrenador'];
$monto_final = $_POST['monto_final'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

try {
    // Preparar la sentencia SQL para actualizar la asesoría
    $sentencia = $pdo->prepare("UPDATE tb_asesorias
                                SET
                                    id_cliente = :id_cliente,
                                    id_entrenador = :id_entrenador,
                                    monto_final = :monto_final,
                                    fecha_inicio = :fecha_inicio,
                                    fecha_fin = :fecha_fin,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_asesoria = :id_asesoria AND id_gimnasio = :id_gimnasio");

    // Vincular los parámetros con los valores recibidos
    $sentencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $sentencia->bindParam(':monto_final', $monto_final);
    $sentencia->bindParam(':fecha_inicio', $fecha_inicio);
    $sentencia->bindParam(':fecha_fin', $fecha_fin);
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_asesoria', $id_asesoria, PDO::PARAM_INT);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Asesoría actualizada correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asesorias/'); // Redirigir a la página principal de asesorías
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo actualizar la asesoría en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/edit.php?id=' . $id_asesoria); // Redirigir de vuelta al formulario de edición
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)
   
    $_SESSION['mensaje'] = "Error de base de datos al actualizar la asesoría: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/edit.php?id=' . $id_asesoria); // Redirigir de vuelta al formulario de edición
    exit();
}

?>
