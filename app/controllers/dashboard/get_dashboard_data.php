<?php
// app/controllers/dashboard/get_dashboard_data.php

// Inicializar array de datos
$dashboard_data = [];

// 1. Obtener Fechas del Filtro (o defaults)
// Por defecto: Mes Actual
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

// Validar formato (simple)
if (!$fecha_inicio)
    $fecha_inicio = date('Y-m-01');
if (!$fecha_fin)
    $fecha_fin = date('Y-m-t');

$dashboard_data['fecha_inicio'] = $fecha_inicio;
$dashboard_data['fecha_fin'] = $fecha_fin;

// --- KPI 1: Ingresos Totales en el Periodo (DINERO) ---
try {
    $sql_ingresos = "SELECT SUM(monto) AS total_ingresos 
                     FROM tb_pagos 
                     WHERE DATE(fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin 
                     AND id_gimnasio = :id_gimnasio";
    $query_ingresos = $pdo->prepare($sql_ingresos);
    $query_ingresos->bindParam(':fecha_inicio', $fecha_inicio);
    $query_ingresos->bindParam(':fecha_fin', $fecha_fin);
    $query_ingresos->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_ingresos->execute();
    $result = $query_ingresos->fetch(PDO::FETCH_ASSOC);
    $dashboard_data['total_ingresos'] = $result['total_ingresos'] ?? 0;
} catch (PDOException $e) {
    $dashboard_data['total_ingresos'] = 0;
}

// --- KPI 2: Clientes Activos (SALUD) ---
// Interpretación: Clientes con al menos una matrícula vigente "hoy" (Snapshot actual).
// Es confuso filtrar "Clientes Activos" por fechas pasadas (¿Cuántos activos HABÍA?).
// Mantendremos "Activos HOY" como métrica de salud del negocio en tiempo real.
try {
    $hoy = date('Y-m-d');
    $sql_activos = "SELECT COUNT(DISTINCT id_cliente) AS total_activos
                    FROM tb_matriculas
                    WHERE fecha_inicio <= :hoy AND fecha_fin >= :hoy 
                    AND id_gimnasio = :id_gimnasio";
    $query_activos = $pdo->prepare($sql_activos);
    $query_activos->bindParam(':hoy', $hoy);
    $query_activos->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_activos->execute();
    $result = $query_activos->fetch(PDO::FETCH_ASSOC);
    $dashboard_data['clientes_activos'] = $result['total_activos'] ?? 0;
} catch (PDOException $e) {
    $dashboard_data['clientes_activos'] = 0;
}

// --- KPI 3: Pagos Pendientes Totales (FLUJO DE CAJA) ---
// Snapshot actual de deuda. No depende del filtro de fechas (la deuda existe hoy).
try {
    // Suma de saldos pendientes en Matriculas, Ventas y Asesorias
    $sql_deuda_matriculas = "SELECT SUM(m.monto_final - COALESCE(tp.total_pagado, 0)) AS deuda
                             FROM tb_matriculas m
                             LEFT JOIN (SELECT id_matricula_fk, SUM(monto) as total_pagado FROM tb_pagos WHERE tipo_pago='matricula' GROUP BY id_matricula_fk) tp 
                             ON m.id_matricula = tp.id_matricula_fk 
                             WHERE (m.monto_final - COALESCE(tp.total_pagado, 0)) > 0 AND m.id_gimnasio = :id_gimnasio";

    $sql_deuda_ventas = "SELECT SUM(v.monto_total - COALESCE(tp.total_pagado, 0)) AS deuda
                         FROM tb_ventas v
                         LEFT JOIN (SELECT id_venta_fk, SUM(monto) as total_pagado FROM tb_pagos WHERE tipo_pago='venta' GROUP BY id_venta_fk) tp 
                         ON v.id_venta = tp.id_venta_fk 
                         WHERE (v.monto_total - COALESCE(tp.total_pagado, 0)) > 0 AND v.id_gimnasio = :id_gimnasio";

    $sql_deuda_asesorias = "SELECT SUM(a.monto_final - COALESCE(tp.total_pagado, 0)) AS deuda
                            FROM tb_asesorias a
                            LEFT JOIN (SELECT id_asesoria_fk, SUM(monto) as total_pagado FROM tb_pagos WHERE tipo_pago='asesoria' GROUP BY id_asesoria_fk) tp 
                            ON a.id_asesoria = tp.id_asesoria_fk 
                            WHERE (a.monto_final - COALESCE(tp.total_pagado, 0)) > 0 AND a.id_gimnasio = :id_gimnasio";

    // Ejecutar consultas
    $q1 = $pdo->prepare($sql_deuda_matriculas);
    $q1->execute([':id_gimnasio' => $_SESSION['id_gimnasio_sesion']]);
    $deuda_m = $q1->fetchColumn() ?: 0;

    $q2 = $pdo->prepare($sql_deuda_ventas);
    $q2->execute([':id_gimnasio' => $_SESSION['id_gimnasio_sesion']]);
    $deuda_v = $q2->fetchColumn() ?: 0;

    $q3 = $pdo->prepare($sql_deuda_asesorias);
    $q3->execute([':id_gimnasio' => $_SESSION['id_gimnasio_sesion']]);
    $deuda_a = $q3->fetchColumn() ?: 0;

    $dashboard_data['pagos_pendientes'] = $deuda_m + $deuda_v + $deuda_a;

} catch (PDOException $e) {
    $dashboard_data['pagos_pendientes'] = 0;
}

// --- KPI 4: Próximos Vencimientos (ACCIÓN) ---
// Clientes cuya matrícula vence dentro del rango seleccionado (útil para ver quién vence este mes).
try {
    $sql_vencimientos = "SELECT COUNT(id_matricula) AS total_vencimientos
                         FROM tb_matriculas
                         WHERE fecha_fin BETWEEN :fecha_inicio AND :fecha_fin
                         AND id_gimnasio = :id_gimnasio";
    $query_vencimientos = $pdo->prepare($sql_vencimientos);
    $query_vencimientos->bindParam(':fecha_inicio', $fecha_inicio);
    $query_vencimientos->bindParam(':fecha_fin', $fecha_fin);
    $query_vencimientos->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_vencimientos->execute();
    $dashboard_data['proximos_vencimientos'] = $query_vencimientos->fetchColumn() ?: 0;
} catch (PDOException $e) {
    $dashboard_data['proximos_vencimientos'] = 0;
}

// --- Gráfico 1: Tendencia de Ingresos (Diario dentro del rango) ---
// Si el rango es <= 31 días, mostrar por días. Si es mayor, mostrar por meses o semanas? 
// Simplificación: Mostrar por días si el rango es corto (< 90 días), por meses si es largo.
$dias_diferencia = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / (60 * 60 * 24);

$dashboard_data['chart_ingresos_labels'] = [];
$dashboard_data['chart_ingresos_values'] = [];

try {
    if ($dias_diferencia <= 60) {
        // Agrupar por DÍA
        $sql_chart = "SELECT DATE(fecha_hora) as periodo, SUM(monto) as total 
                      FROM tb_pagos 
                      WHERE DATE(fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin 
                      AND id_gimnasio = :id_gimnasio
                      GROUP BY DATE(fecha_hora) ORDER BY DATE(fecha_hora) ASC";
    } else {
        // Agrupar por MES
        $sql_chart = "SELECT DATE_FORMAT(fecha_hora, '%Y-%m') as periodo, SUM(monto) as total 
                      FROM tb_pagos 
                      WHERE DATE(fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin 
                      AND id_gimnasio = :id_gimnasio
                      GROUP BY DATE_FORMAT(fecha_hora, '%Y-%m') ORDER BY periodo ASC";
    }

    $query_chart = $pdo->prepare($sql_chart);
    $query_chart->bindParam(':fecha_inicio', $fecha_inicio);
    $query_chart->bindParam(':fecha_fin', $fecha_fin);
    $query_chart->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_chart->execute();
    $chart_results = $query_chart->fetchAll(PDO::FETCH_ASSOC);

    foreach ($chart_results as $row) {
        $dashboard_data['chart_ingresos_labels'][] = $row['periodo'];
        $dashboard_data['chart_ingresos_values'][] = $row['total'];
    }

} catch (PDOException $e) {
    // Chart vacio
}

// --- Gráfico 2: Distribución por Tipo de Pago (Pie Chart) ---
// Basado en el filtro de fechas
try {
    $sql_pie = "SELECT tipo_pago, SUM(monto) as total 
                FROM tb_pagos 
                WHERE DATE(fecha_hora) BETWEEN :fecha_inicio AND :fecha_fin 
                AND id_gimnasio = :id_gimnasio
                GROUP BY tipo_pago";
    $query_pie = $pdo->prepare($sql_pie);
    $query_pie->bindParam(':fecha_inicio', $fecha_inicio);
    $query_pie->bindParam(':fecha_fin', $fecha_fin);
    $query_pie->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_pie->execute();
    $pie_results = $query_pie->fetchAll(PDO::FETCH_ASSOC);

    $dashboard_data['pie_labels'] = [];
    $dashboard_data['pie_values'] = [];

    foreach ($pie_results as $row) {
        $dashboard_data['pie_labels'][] = ucfirst($row['tipo_pago']);
        $dashboard_data['pie_values'][] = $row['total'];
    }

} catch (PDOException $e) {
    // Vacio
}

// --- Extra: Nuevos Clientes (filtrado) ---
try {
    $sql_nuevos = "SELECT COUNT(*) as nuevos 
                   FROM tb_clientes 
                   WHERE DATE(fyh_creacion) BETWEEN :fecha_inicio AND :fecha_fin
                   AND id_gimnasio = :id_gimnasio";
    $q_nuevos = $pdo->prepare($sql_nuevos);
    $q_nuevos->bindParam(':fecha_inicio', $fecha_inicio);
    $q_nuevos->bindParam(':fecha_fin', $fecha_fin);
    $q_nuevos->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $q_nuevos->execute();
    $dashboard_data['nuevos_clientes'] = $q_nuevos->fetchColumn() ?: 0;
} catch (PDOException $e) {
    $dashboard_data['nuevos_clientes'] = 0;
}
?>