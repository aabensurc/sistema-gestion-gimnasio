<?php
// Este archivo obtiene el historial de pagos de un cliente específico ($id_cliente_get)
// Recibe opcionalmente filtros de fecha por GET

$fecha_inicio_filtro = $_GET['fecha_inicio'] ?? '';
$fecha_fin_filtro = $_GET['fecha_fin'] ?? '';

// Preparar la consulta base
$sql_pagos_cliente = "SELECT 
                        id_pago, 
                        tipo_pago, 
                        id_matricula_fk, 
                        id_venta_fk, 
                        id_asesoria_fk, 
                        metodo_pago, 
                        monto, 
                        fecha_hora
                      FROM tb_pagos 
                      WHERE id_cliente = :id_cliente 
                      AND id_gimnasio = :id_gimnasio";

// Agregar filtros de fecha si existen
if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
    // Nota: fecha_hora es DATETIME, usamos BETWEEN
    $sql_pagos_cliente .= " AND fecha_hora BETWEEN :fecha_inicio AND :fecha_fin ";
}

$sql_pagos_cliente .= " ORDER BY fecha_hora DESC";

try {
    $query_pag_cliente = $pdo->prepare($sql_pagos_cliente);
    $query_pag_cliente->bindParam(':id_cliente', $id_cliente_get, PDO::PARAM_INT);
    $query_pag_cliente->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
        // Aseguramos que la fecha fin cubra todo el día (hasta las 23:59:59)
        $fecha_fin_inclusive = $fecha_fin_filtro . " 23:59:59";
        
        $query_pag_cliente->bindParam(':fecha_inicio', $fecha_inicio_filtro);
        $query_pag_cliente->bindParam(':fecha_fin', $fecha_fin_inclusive);
    }

    $query_pag_cliente->execute();
    $historial_pagos = $query_pag_cliente->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al listar pagos del cliente: " . $e->getMessage());
    $historial_pagos = [];
}
?>