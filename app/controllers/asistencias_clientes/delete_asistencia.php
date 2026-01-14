<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID de la asistencia desde el formulario (se envía por POST desde el modal de confirmación)
$id_asistencia = $_POST['id_asistencia'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_asistencia)) {
    
    $_SESSION['mensaje'] = "Error: ID de asistencia no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/');
    exit();
}

try {
    // Preparar la sentencia SQL para eliminar la asistencia
    $sentencia_delete = $pdo->prepare("DELETE FROM tb_asistencias_clientes WHERE id_asistencia = :id_asistencia AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"); // <-- FILTRO DE GIMNASIO AÑADIDO
    $sentencia_delete->bindParam(':id_asistencia', $id_asistencia, PDO::PARAM_INT);

    // Ejecutar la sentencia de eliminación
    if ($sentencia_delete->execute()) {
        
        $_SESSION['mensaje'] = "Asistencia eliminada correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asistencias_clientes/'); // Redirigir de vuelta al listado de asistencias
        exit();
    } else {
        
        $_SESSION['mensaje'] = "Error: No se pudo eliminar la asistencia de la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asistencias_clientes/');
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)
    
    $_SESSION['mensaje'] = "Error de base de datos al eliminar la asistencia: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/');
    exit();
}

?>
