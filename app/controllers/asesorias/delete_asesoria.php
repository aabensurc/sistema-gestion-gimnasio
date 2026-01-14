<?php
session_start();
include('../../config.php');

// VERIFICAR CAJA ABIERTA
include('../caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de anular un registro.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/asesorias/');
    exit;
} // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID de la asesoría desde el formulario (se envía por POST desde el modal de confirmación)
$id_asesoria = $_POST['id_asesoria'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_asesoria)) {

    $_SESSION['mensaje'] = "Error: ID de asesoría no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/');
    exit();
}

try {
    // Preparar la sentencia SQL para eliminar la asesoría
    // Las eliminaciones en tb_pagos se manejan automáticamente por ON DELETE CASCADE
    // Preparar la sentencia SQL para anular la asesoría (Soft Delete)
    // Las eliminaciones en tb_pagos se manejan automáticamente por ON DELETE CASCADE
    $sentencia = $pdo->prepare("UPDATE tb_asesorias SET estado = 0 WHERE id_asesoria = :id_asesoria AND id_gimnasio = :id_gimnasio");

    // Vincular el parámetro
    $sentencia->bindParam(':id_asesoria', $id_asesoria, PDO::PARAM_INT);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        // Anular pagos asociados
        $sentencia_pagos = $pdo->prepare("UPDATE tb_pagos SET estado = 0 WHERE id_asesoria_fk = :id_asesoria");
        $sentencia_pagos->bindParam(':id_asesoria', $id_asesoria);
        $sentencia_pagos->execute();

        $_SESSION['mensaje'] = "Asesoría anulada correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asesorias/'); // Redirigir de vuelta al listado de asesorías
        exit();
    } else {

        $_SESSION['mensaje'] = "Error: No se pudo eliminar la asesoría de la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/');
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)

    $_SESSION['mensaje'] = "Error de base de datos al eliminar la asesoría: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/');
    exit();
}

?>