<?php
// Este archivo se incluye para obtener solo los planes ACTIVOS.
// Uso principal: Selectores de planes en Matrículas/Ventas.

$sql_planes = "SELECT * FROM tb_planes WHERE id_gimnasio = '$_SESSION[id_gimnasio_sesion]' AND estado = '1'";
$query_planes = $pdo->prepare($sql_planes);
$query_planes->execute();
$planes_datos = $query_planes->fetchAll(PDO::FETCH_ASSOC);

?>