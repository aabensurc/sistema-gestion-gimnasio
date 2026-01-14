<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$id_asistencia = $_POST['id_asistencia'];
$id_cliente = $_POST['id_cliente'];
$fecha_asistencia = $_POST['fecha_asistencia'];
$hora_entrada = $_POST['hora_entrada'];

// Obtener la fecha y hora actual para fyh_actualizacion
$fyh_actualizacion = date('Y-m-d H:i:s');

try {
    // Preparar la sentencia SQL para actualizar la asistencia
    $sentencia = $pdo->prepare("UPDATE tb_asistencias_clientes
                                SET
                                    id_cliente = :id_cliente,
                                    fecha_asistencia = :fecha_asistencia,
                                    hora_entrada = :hora_entrada,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_asistencia = :id_asistencia AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"); // <-- FILTRO DE GIMNASIO AÑADIDO

    // Vincular los parámetros
    $sentencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia->bindParam(':fecha_asistencia', $fecha_asistencia);
    $sentencia->bindParam(':hora_entrada', $hora_entrada);
    $sentencia->bindParam(':fyh_actualizacion', $fyh_actualizacion);
    $sentencia->bindParam(':id_asistencia', $id_asistencia, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Asistencia actualizada correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asistencias_clientes/'); // Redirigir al listado de asistencias
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo actualizar la asistencia en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asistencias_clientes/edit.php?id=' . $id_asistencia);
        exit();
    }
} catch (PDOException $e) {
    
    $_SESSION['mensaje'] = "Error de base de datos al actualizar asistencia: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/edit.php?id=' . $id_asistencia);
    exit();
}

?>
