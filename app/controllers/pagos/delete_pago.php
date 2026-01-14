<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID del pago desde el formulario (se envía por POST desde el modal de confirmación)
$id_pago = $_POST['id_pago'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_pago)) {

    $_SESSION['mensaje'] = "Error: ID de pago no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/pagos/');
    exit();
}

try {
    // Preparar la sentencia SQL para anular el pago (Soft Delete)
    $sentencia = $pdo->prepare("UPDATE tb_pagos SET estado = 0 WHERE id_pago = :id_pago AND id_gimnasio = :id_gimnasio");

    // Vincular el parámetro
    $sentencia->bindParam(':id_pago', $id_pago, PDO::PARAM_INT);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {

        // REVERTIR el estado de la cuota en el cronograma si existe
        $sql_revert = "UPDATE tb_cronograma_pagos 
                       SET estado_cuota = 'Pendiente', 
                           fecha_pago_completado = NULL, 
                           id_pago_fk = NULL 
                       WHERE id_pago_fk = :id_pago";
        $stmt_revert = $pdo->prepare($sql_revert);
        $stmt_revert->execute([':id_pago' => $id_pago]);

        $_SESSION['mensaje'] = "Pago anulado correctamente. Cuota revertida a Pendiente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/pagos/'); // Redirigir de vuelta al listado de pagos
        exit();
    } else {

        $_SESSION['mensaje'] = "Error: No se pudo eliminar el pago de la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/pagos/');
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)

    $_SESSION['mensaje'] = "Error de base de datos al eliminar el pago: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/pagos/');
    exit();
}

?>