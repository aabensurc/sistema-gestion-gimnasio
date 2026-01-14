<?php

// Este archivo se incluye en ventas/index.php para obtener el listado de ventas.
// Se asume que config.php ya ha sido incluido por el archivo principal (index.php)
// y que la variable $pdo está disponible.

$sql_ventas = "SELECT
    v.id_venta AS id_venta,
    v.estado,
    v.id_cliente AS id_cliente_venta,
    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
    v.fecha_venta AS fecha_venta,
    v.monto_total AS monto_total,
    v.descuento_total AS descuento_total,
     -- Subconsulta para sumar los montos de los pagos de cada venta
    (SELECT SUM(pago.monto)
     FROM tb_pagos AS pago
     WHERE pago.id_venta_fk = v.id_venta
     AND pago.tipo_pago = 'venta'
     AND pago.estado = 1) AS total_pagado,
     -- Verificar cronograma
    (SELECT COUNT(*) FROM tb_cronograma_pagos WHERE id_venta_fk = v.id_venta) AS tiene_cronograma
FROM
    tb_ventas AS v
LEFT JOIN
    tb_clientes AS c ON v.id_cliente = c.id_cliente
WHERE 
    c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

// Filtro por fechas (Por defecto: Mes Actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_ventas .= " AND v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'";

$sql_ventas .= " ORDER BY v.fecha_venta DESC;"; // Ordenar por fecha de venta, las más recientes primero

try {
    $query_ventas = $pdo->prepare($sql_ventas);
    $query_ventas->execute();
    $ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores en caso de que la consulta falle
    error_log("Error al obtener listado de ventas: " . $e->getMessage());
    $ventas_datos = []; // Retornar un array vacío para evitar errores en la vista
    // Opcional: podrías redirigir o mostrar un mensaje de error al usuario
    // session_start();
    // $_SESSION['mensaje'] = "Error al cargar las ventas.";
    // $_SESSION['icono'] = "error";
    // header('Location: ' . $URL . '/error_page.php');
    // exit();
}

?>