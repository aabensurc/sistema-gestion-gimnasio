<?php
include('../../config.php');
session_start();

$id_usuario_sesion = $_SESSION['id_usuario_global'];
$id_gimnasio_sesion = $_SESSION['id_gimnasio_sesion'];
$monto_apertura = $_POST['monto_apertura'];
$fecha_apertura = date('Y-m-d H:i:s');

try {
    // 1. Verificar nuevamente que no tenga caja abierta 
    $sql_check = "SELECT id_caja FROM tb_caja WHERE id_usuario = :id_usuario AND estado = 1 AND id_gimnasio = :id_gimnasio";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id_usuario', $id_usuario_sesion);
    $stmt_check->bindParam(':id_gimnasio', $id_gimnasio_sesion);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        $_SESSION['mensaje'] = "Ya tienes una caja abierta.";
        $_SESSION['icono'] = "warning";
        header('Location: ' . $URL . '/caja/');
        exit;
    }

    // 2. Insertar apertura
    $sql_insert = "INSERT INTO tb_caja (id_usuario, id_gimnasio, fecha_apertura, monto_apertura, estado, fyh_creacion, fyh_actualizacion) 
                   VALUES (:id_usuario, :id_gimnasio, :fecha_apertura, :monto_apertura, 1, :fecha_apertura, :fecha_apertura)";

    $stmt = $pdo->prepare($sql_insert);
    $stmt->bindParam(':id_usuario', $id_usuario_sesion);
    $stmt->bindParam(':id_gimnasio', $id_gimnasio_sesion);
    $stmt->bindParam(':fecha_apertura', $fecha_apertura);
    $stmt->bindParam(':monto_apertura', $monto_apertura);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Caja abierta correctamente.";
        $_SESSION['icono'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al abrir la caja.";
        $_SESSION['icono'] = "error";
    }

} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['icono'] = "error";
}

header('Location: ' . $URL . '/caja/');
?>