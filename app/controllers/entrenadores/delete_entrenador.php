<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID del entrenador desde el formulario (se envía por POST desde el modal de confirmación)
$id_entrenador = $_POST['id_entrenador'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_entrenador)) {
    
    $_SESSION['mensaje'] = "Error: ID de entrenador no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/');
    exit();
}

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // Primero, obtener el nombre de la foto del entrenador antes de eliminarlo
    $sql_get_foto = "SELECT foto FROM tb_entrenadores WHERE id_entrenador = :id_entrenador AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";
    $query_get_foto = $pdo->prepare($sql_get_foto);
    $query_get_foto->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $query_get_foto->execute();
    $entrenador_data = $query_get_foto->fetch(PDO::FETCH_ASSOC);
    $foto_a_eliminar = $entrenador_data['foto'] ?? 'default_trainer.png'; // Obtener la foto actual

    // Preparar la sentencia SQL para eliminar el entrenador
    // La clave foránea en tb_asesorias con ON DELETE SET NULL manejará la relación
    $sentencia_delete = $pdo->prepare("DELETE FROM tb_entrenadores WHERE id_entrenador = :id_entrenador AND id_gimnasio = :id_gimnasio");
    $sentencia_delete->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $sentencia_delete->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    // Ejecutar la sentencia de eliminación
    if ($sentencia_delete->execute()) {
        // Si la eliminación de la base de datos fue exitosa, eliminar la foto del servidor
        if ($foto_a_eliminar != 'default_trainer.png' && file_exists('../../../public/images/entrenadores/' . $foto_a_eliminar)) {
            unlink('../../../public/images/entrenadores/' . $foto_a_eliminar);
        }

        // Confirmar la transacción
        $pdo->commit();
        session_start();
        $_SESSION['mensaje'] = "Entrenador eliminado correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/entrenadores/'); // Redirigir de vuelta al listado de entrenadores
        exit();
    } else {
        // Si no se pudo eliminar de la base de datos, revertir la transacción
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        session_start();
        $_SESSION['mensaje'] = "Error: No se pudo eliminar el entrenador de la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/');
        exit();
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al eliminar el entrenador: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/');
    exit();
}

?>
