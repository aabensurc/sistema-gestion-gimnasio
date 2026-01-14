<?php

// Este archivo se incluye en pagos/index.php para obtener el listado de pagos.
// No necesita session_start() porque el archivo principal ya lo incluye a través de sesion.php.

$sql_pagos = "SELECT
    pago.id_pago,
    pago.estado,
    pago.id_cliente,
    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
    pago.tipo_pago,
    pago.id_matricula_fk,
    pago.id_venta_fk,
    pago.id_asesoria_fk,
    pago.metodo_pago,
    pago.monto,
    pago.fecha_hora
FROM
    tb_pagos AS pago
LEFT JOIN
    tb_clientes AS c ON pago.id_cliente = c.id_cliente
WHERE c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

// Filtro por fechas (Por defecto: Mes Actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_pagos .= " AND DATE(pago.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

$sql_pagos .= " ORDER BY pago.fecha_hora DESC;"; // Ordenar por fecha y hora, los más recientes primero

try {
    $query_pagos = $pdo->prepare($sql_pagos);
    $query_pagos->execute();
    $pagos_datos = $query_pagos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores en caso de que la consulta falle
    error_log("Error al obtener listado de pagos: " . $e->getMessage());
    $pagos_datos = []; // Retornar un array vacío para evitar errores en la vista
}

?>