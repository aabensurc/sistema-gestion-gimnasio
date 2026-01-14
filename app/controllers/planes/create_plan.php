<?php
session_start();
include('../../config.php');

$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$tipo_duracion = $_POST['tipo_duracion'];
$duracion_meses = $_POST['duracion_meses'];
$duracion_dias = $_POST['duracion_dias'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];




$sentencia = $pdo->prepare("INSERT INTO tb_planes 
(nombre, precio, tipo_duracion, duracion_meses, duracion_dias, fecha_inicio, fecha_fin, fyh_creacion, id_gimnasio, estado) 
VALUES (:nombre, :precio, :tipo_duracion, :duracion_meses, :duracion_dias, :fecha_inicio, :fecha_fin, :fyh_creacion, :id_gimnasio, '1')");


$sentencia->bindParam('nombre', $nombre);
$sentencia->bindParam('precio', $precio);
$sentencia->bindParam('tipo_duracion', $tipo_duracion);
$sentencia->bindParam('duracion_meses', $duracion_meses);
$sentencia->bindParam('duracion_dias', $duracion_dias);
$sentencia->bindParam('fecha_inicio', $fecha_inicio);
$sentencia->bindParam('fecha_fin', $fecha_fin);
$sentencia->bindParam('fyh_creacion', $fechaHora);
$sentencia->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);

if ($sentencia->execute()) {

    $_SESSION['mensaje'] = "Se registró el plan correctamente";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/planes/');
} else {

    $_SESSION['mensaje'] = "Error no se pudo registrar en la base de datos";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/planes/create.php');
}

















