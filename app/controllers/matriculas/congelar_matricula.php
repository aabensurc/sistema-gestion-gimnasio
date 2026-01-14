<?php
session_start();
include('../../config.php');

$id_matricula = $_POST['id_matricula'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$motivo = $_POST['motivo'];

$fecha_inicio_date = new DateTime($fecha_inicio);
$fecha_fin_date = new DateTime($fecha_fin);
$hoy = new DateTime();

// Validaciones básicas
if ($fecha_inicio_date > $fecha_fin_date) {
    $_SESSION['mensaje'] = "Error: La fecha de inicio no puede ser mayor a la fecha de fin.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
    exit();
}

// Calcular días de congelamiento
$diferencia = $fecha_inicio_date->diff($fecha_fin_date);
$dias_congelados = $diferencia->days + 1; // Incluir el día de inicio

if ($dias_congelados <= 0) {
    $_SESSION['mensaje'] = "Error: El rango de fechas no es válido.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Obtener la matricula actual para saber su fecha fin actual (opcional, pero bueno para logs o validaciones extras)
    $sentencia_lectura = $pdo->prepare("SELECT fecha_fin FROM tb_matriculas WHERE id_matricula = :id_matricula");
    $sentencia_lectura->bindParam(':id_matricula', $id_matricula);
    $sentencia_lectura->execute();
    $matricula = $sentencia_lectura->fetch(PDO::FETCH_ASSOC);

    if (!$matricula) {
        throw new Exception("Matrícula no encontrada.");
    }

    // 2. Insertar en tb_congelamientos
    $fyh_creacion = date('Y-m-d H:i:s');
    $fyh_actualizacion = $fyh_creacion;
    $estado = 1;

    $sentencia = $pdo->prepare("INSERT INTO tb_congelamientos 
        (id_matricula, fecha_inicio, fecha_fin, motivo, dias_congelados, fyh_creacion, fyh_actualizacion, estado) 
        VALUES (:id_matricula, :fecha_inicio, :fecha_fin, :motivo, :dias_congelados, :fyh_creacion, :fyh_actualizacion, :estado)");

    $sentencia->bindParam(':id_matricula', $id_matricula);
    $sentencia->bindParam(':fecha_inicio', $fecha_inicio);
    $sentencia->bindParam(':fecha_fin', $fecha_fin);
    $sentencia->bindParam(':motivo', $motivo);
    $sentencia->bindParam(':dias_congelados', $dias_congelados);
    $sentencia->bindParam(':fyh_creacion', $fyh_creacion);
    $sentencia->bindParam(':fyh_actualizacion', $fyh_actualizacion);
    $sentencia->bindParam(':estado', $estado);
    $sentencia->execute();

    // 3. Actualizar la fecha fin de tb_matriculas
    // Sumamos los días congelados a la fecha actual de vencimiento
    $sentencia_update = $pdo->prepare("UPDATE tb_matriculas 
                                       SET fecha_fin = DATE_ADD(fecha_fin, INTERVAL :dias_congelados DAY),
                                           fyh_actualizacion = :fyh_actualizacion 
                                       WHERE id_matricula = :id_matricula");
    $sentencia_update->bindParam(':dias_congelados', $dias_congelados, PDO::PARAM_INT);
    $sentencia_update->bindParam(':fyh_actualizacion', $fyh_actualizacion);
    $sentencia_update->bindParam(':id_matricula', $id_matricula);
    $sentencia_update->execute();

    $pdo->commit();

    $_SESSION['mensaje'] = "Membresía congelada exitosamente por $dias_congelados días. Nueva fecha de vencimiento calculada.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/matriculas/');
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "Error al congelar membresía: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
    exit();
}
?>