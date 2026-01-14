<?php
include('app/config.php');
$id_rol = 1;
$sql = "SELECT permisos FROM tb_roles WHERE id_rol = :id_rol";
$query = $pdo->prepare($sql);
$query->bindParam(':id_rol', $id_rol);
$query->execute();
$role = $query->fetch(PDO::FETCH_ASSOC);
print_r($role);
?>