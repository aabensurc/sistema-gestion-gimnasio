<?php // Controller to toggle Plan Status
include('../../config.php');

$id_plan = $_GET['id_plan'];
$estado_actual = $_GET['estado']; // The CURRENT status passed from frontend

// Calculate new status
$nuevo_estado = ($estado_actual == '1') ? '0' : '1';

$sentencia = $pdo->prepare("UPDATE tb_planes SET estado = :estado WHERE id_plan = :id_plan");
$sentencia->bindParam(':estado', $nuevo_estado);
$sentencia->bindParam(':id_plan', $id_plan);

if ($sentencia->execute()) {
    echo "success";
} else {
    echo "error";
}
?>