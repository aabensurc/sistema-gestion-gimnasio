<?php
// Este archivo obtiene el historial de ventas de un cliente específico ($id_cliente_get)
// Recibe opcionalmente filtros de fecha por GET

$fecha_inicio_filtro = $_GET['fecha_inicio'] ?? '';
$fecha_fin_filtro = $_GET['fecha_fin'] ?? '';

// Preparar la consulta base
$sql_ventas_cliente = "SELECT 
                        v.id_venta, 
                        v.fecha_venta, 
                        v.monto_total, 
                        v.descuento_total
                       FROM tb_ventas v 
                       WHERE v.id_cliente = :id_cliente 
                       AND v.id_gimnasio = :id_gimnasio";

// Agregar filtros de fecha si existen
if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
    // Nota: Como fecha_venta suele ser DATETIME, usamos BETWEEN
    $sql_ventas_cliente .= " AND v.fecha_venta BETWEEN :fecha_inicio AND :fecha_fin ";
}

$sql_ventas_cliente .= " ORDER BY v.fecha_venta DESC";

try {
    $query_ven_cliente = $pdo->prepare($sql_ventas_cliente);
    $query_ven_cliente->bindParam(':id_cliente', $id_cliente_get, PDO::PARAM_INT);
    $query_ven_cliente->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
        // Aseguramos que la fecha fin cubra todo el día (hasta las 23:59:59)
        $fecha_fin_inclusive = $fecha_fin_filtro . " 23:59:59";
        
        $query_ven_cliente->bindParam(':fecha_inicio', $fecha_inicio_filtro);
        $query_ven_cliente->bindParam(':fecha_fin', $fecha_fin_inclusive);
    }

    $query_ven_cliente->execute();
    $historial_ventas = $query_ven_cliente->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al listar ventas del cliente: " . $e->getMessage());
    $historial_ventas = [];
}
?>