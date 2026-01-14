<?php 

$id_plan_get = $_GET['id'];

$sql_planes = "SELECT * FROM tb_planes WHERE id_plan = '$id_plan_get' AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";
  $query_planes = $pdo->prepare($sql_planes);
  $query_planes-> execute();
  $planes_datos = $query_planes->fetchAll(PDO::FETCH_ASSOC);

  foreach($planes_datos as $planes_dato) {
    $nombre = $planes_dato['nombre'];
    $precio = $planes_dato['precio'];
    $tipo_duracion = $planes_dato['tipo_duracion'];
    $duracion_meses = $planes_dato['duracion_meses'];
    $duracion_dias = $planes_dato['duracion_dias'];
    $fecha_inicio = $planes_dato['fecha_inicio'];
    $fecha_fin = $planes_dato['fecha_fin'];
    
  }