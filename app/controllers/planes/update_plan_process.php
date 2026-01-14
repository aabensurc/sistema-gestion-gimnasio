<?php
session_start();

include('../../config.php');

$id_plan = $_POST['id_plan'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$tipo_duracion = $_POST['tipo_duracion'];
$duracion_meses = $_POST['duracion_meses'];
$duracion_dias = $_POST['duracion_dias'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];



  
    
    $sentencia = $pdo->prepare("UPDATE tb_planes 
    SET
        nombre = :nombre,
        precio = :precio,
        tipo_duracion = :tipo_duracion,
        duracion_meses = :duracion_meses,
        duracion_dias = :duracion_dias,
        fecha_inicio = :fecha_inicio,
        fecha_fin = :fecha_fin,
        fyh_actualizacion = :fyh_actualizacion
        WHERE id_plan = :id_plan
        AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ");

    $sentencia->bindParam('nombre', $nombre);
    $sentencia->bindParam('precio', $precio);
    $sentencia->bindParam('tipo_duracion', $tipo_duracion);
    $sentencia->bindParam('duracion_meses', $duracion_meses);
    $sentencia->bindParam('duracion_dias', $duracion_dias);
    $sentencia->bindParam('fecha_inicio', $fecha_inicio);
    $sentencia->bindParam('fecha_fin', $fecha_fin);
    $sentencia->bindParam('fyh_actualizacion', $fechaHora);
    $sentencia->bindParam('id_plan', $id_plan);


    if($sentencia->execute()){
        
        $_SESSION['mensaje'] = "Se actualizó el plan de manera correcta";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/planes/');

    }else{
        
        $_SESSION['mensaje'] = "Error no se puedo actualizar en la base de datos";
        $_SESSION['icono'] = "error";
        header('Location: '.$URL.'/planes/update.php?id='.$id_plan);
        exit();
    }
    


 
  



  



