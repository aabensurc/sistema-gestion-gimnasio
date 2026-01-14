<?php

// Este archivo se incluye en asesorias/index.php para obtener el listado de asesorías.
// Se asume que config.php ya ha sido incluido por el archivo principal (index.php)
// y que la variable $pdo está disponible.

$sql_asesorias = "SELECT
    a.id_asesoria AS id_asesoria,
    a.estado,
    a.id_cliente AS id_cliente_asesoria,
    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
    a.id_entrenador AS id_entrenador_asesoria,
    CONCAT_WS(' ', e.nombre, e.ape_pat, e.ape_mat) AS nombre_completo_entrenador,
    a.monto_final AS monto_final,
    a.fecha_inicio AS fecha_inicio_asesoria,
    a.fecha_fin AS fecha_fin_asesoria,
     -- Subconsulta para sumar los montos de los pagos de cada asesoría
    (SELECT SUM(pago.monto)
     FROM tb_pagos AS pago
     WHERE pago.id_asesoria_fk = a.id_asesoria
     AND pago.tipo_pago = 'asesoria'
     AND pago.estado = 1) AS total_pagado,
    -- Verificar cronograma
    (SELECT COUNT(*) FROM tb_cronograma_pagos WHERE id_asesoria_fk = a.id_asesoria) AS tiene_cronograma
FROM
    tb_asesorias AS a
LEFT JOIN
    tb_clientes AS c ON a.id_cliente = c.id_cliente
LEFT JOIN
    tb_entrenadores AS e ON a.id_entrenador = e.id_entrenador
WHERE c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

// Filtro por fechas (Por defecto: Mes Actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_asesorias .= " AND a.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin'";

$sql_asesorias .= " ORDER BY a.fecha_inicio DESC;"; // Ordenar por fecha de inicio, las más recientes primero

try {
    $query_asesorias = $pdo->prepare($sql_asesorias);
    $query_asesorias->execute();
    $asesorias_datos = $query_asesorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores en caso de que la consulta falle
    error_log("Error al obtener listado de asesorías: " . $e->getMessage());
    $asesorias_datos = []; // Retornar un array vacío para evitar errores en la vista
}

?>