<?php
// verificar_estado_caja.php
// Verifica si el usuario actual tiene una caja abierta

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Se asume que $pdo y config ya estan incluidos donde se llame este archivo, 
// o se incluyen aqui si es llamado via AJAX puro.

$id_usuario_sesion = $_SESSION['id_usuario_global'];
$id_gimnasio_sesion = $_SESSION['id_gimnasio_sesion'];

$sql_caja = "SELECT * FROM tb_caja 
             WHERE id_usuario = :id_usuario 
             AND id_gimnasio = :id_gimnasio 
             AND estado = 1 
             LIMIT 1";

$query_caja = $pdo->prepare($sql_caja);
$query_caja->bindParam(':id_usuario', $id_usuario_sesion);
$query_caja->bindParam(':id_gimnasio', $id_gimnasio_sesion);
$query_caja->execute();
$caja_abierta = $query_caja->fetch(PDO::FETCH_ASSOC);

// Si $caja_abierta tiene datos, es que hay una abierta.
?>