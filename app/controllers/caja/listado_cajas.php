<?php
// listado_cajas.php
// Para el reporte de cajas

// DATES
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_reporte = "SELECT 
                    caja.*,
                    CONCAT(u.nombres) as nombre_usuario
                FROM tb_caja caja
                INNER JOIN tb_usuarios u ON caja.id_usuario = u.id_usuario
                WHERE caja.id_gimnasio = :id_gimnasio
                AND DATE(caja.fecha_apertura) BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY caja.fecha_apertura DESC";

try {
    $query_reporte = $pdo->prepare($sql_reporte);
    $query_reporte->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_reporte->bindParam(':fecha_inicio', $fecha_inicio);
    $query_reporte->bindParam(':fecha_fin', $fecha_fin);
    $query_reporte->execute();
    $reporte_cajas = $query_reporte->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $reporte_cajas = [];
}
?>