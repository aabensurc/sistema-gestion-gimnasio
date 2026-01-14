<?php
// app/controllers/asistencias_clientes/listado_asistencias_cliente.php

$fecha_inicio_filtro = $_GET['fecha_inicio'] ?? '';
$fecha_fin_filtro = $_GET['fecha_fin'] ?? '';

// Consulta base
$sql_asistencias = "SELECT * FROM tb_asistencias_clientes 
                    WHERE id_cliente = :id_cliente 
                    AND id_gimnasio = :id_gimnasio";

// Aplicar filtros si existen
if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
    $sql_asistencias .= " AND fecha_asistencia BETWEEN :fecha_inicio AND :fecha_fin ";
}

$sql_asistencias .= " ORDER BY fecha_asistencia DESC, hora_entrada DESC";

try {
    $query_asist = $pdo->prepare($sql_asistencias);
    $query_asist->bindParam(':id_cliente', $id_cliente_get, PDO::PARAM_INT);
    $query_asist->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
        $query_asist->bindParam(':fecha_inicio', $fecha_inicio_filtro);
        $query_asist->bindParam(':fecha_fin', $fecha_fin_filtro);
    }

    $query_asist->execute();
    $historial_asistencias = $query_asist->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al listar asistencias: " . $e->getMessage());
    $historial_asistencias = [];
}
?>