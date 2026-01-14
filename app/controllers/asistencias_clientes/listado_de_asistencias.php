<?php

// Este archivo se incluye en asistencias_clientes/index.php
// para obtener el listado de asistencias de clientes.
// Se asume que config.php ya ha sido incluido por el archivo principal
// y que la variable $pdo está disponible.

$sql_asistencias = "SELECT
    ac.id_asistencia,
    ac.id_cliente,
    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
    ac.fecha_asistencia,
    ac.hora_entrada,
    ac.fyh_creacion,    -- Cambiado de fyh_registro a fyh_creacion
    ac.fyh_actualizacion
FROM
    tb_asistencias_clientes AS ac
LEFT JOIN
    tb_clientes AS c ON ac.id_cliente = c.id_cliente
WHERE
    c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

// Filtro por fechas (Por defecto: Mes Actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_asistencias .= " AND ac.fecha_asistencia BETWEEN '$fecha_inicio' AND '$fecha_fin'";

$sql_asistencias .= " ORDER BY ac.fecha_asistencia DESC, ac.hora_entrada DESC;"; // Ordenar por fecha y hora de entrada, las más recientes primero

try {
    $query_asistencias = $pdo->prepare($sql_asistencias);
    $query_asistencias->execute();
    $asistencias_datos = $query_asistencias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error al obtener listado de asistencias de clientes: " . $e->getMessage());
    $asistencias_datos = []; // Retornar un array vacío
}

?>