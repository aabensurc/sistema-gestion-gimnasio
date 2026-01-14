<?php



$sql_matriculas = "SELECT
    m.id_matricula AS id_matricula,
    m.estado,
    m.id_cliente AS id_cliente_matricula,
    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
    m.id_plan AS id_plan_matricula,
    p.nombre AS nombre_plan,
    m.fecha_inicio AS fecha_inicio_matricula,
    m.fecha_fin AS fecha_fin_matricula,
    m.descuento AS descuento_matricula,
    m.monto_final AS monto_final_matricula,
    -- Subconsulta para sumar los montos de los pagos de cada matrícula
     (SELECT SUM(pago.monto)
      FROM tb_pagos AS pago
      WHERE pago.id_matricula_fk = m.id_matricula
      AND pago.tipo_pago = 'matricula'
      AND pago.estado = 1) AS total_pagado,
      
     (SELECT COUNT(*) FROM tb_cronograma_pagos WHERE id_matricula_fk = m.id_matricula) AS tiene_cronograma,

     -- Subconsulta para verificar si está congelado HOY y hasta cuándo
     (SELECT fecha_fin 
      FROM tb_congelamientos 
      WHERE id_matricula = m.id_matricula 
      AND estado = 1 
      AND CURDATE() BETWEEN fecha_inicio AND fecha_fin 
      LIMIT 1) AS fecha_fin_congelamiento
FROM
    tb_matriculas AS m
LEFT JOIN
    tb_clientes AS c ON m.id_cliente = c.id_cliente
LEFT JOIN
    tb_planes AS p ON m.id_plan = p.id_plan
WHERE c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

// Filtro por fechas (Por defecto: Mes Actual)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$sql_matriculas .= " AND m.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin'";
try {
    $query_matriculas = $pdo->prepare($sql_matriculas);
    $query_matriculas->execute();
    $matriculas_datos = $query_matriculas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener listado de matriculas: " . $e->getMessage());
    $matriculas_datos = [];
}

//foreach($roles_datos as $roles_dato){
//echo $roles_dato['rol'];
//}
