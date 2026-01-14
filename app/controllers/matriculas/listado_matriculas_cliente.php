<?php
// Este archivo obtiene el historial de matrículas de un cliente específico ($id_cliente_get)
// Recibe opcionalmente filtros de fecha por GET

$fecha_inicio_filtro = $_GET['fecha_inicio'] ?? '';
$fecha_fin_filtro = $_GET['fecha_fin'] ?? '';

// Preparar la consulta base
$sql_matriculas_cliente = "SELECT 
                            m.id_matricula, 
                            m.fecha_inicio, 
                            m.fecha_fin, 
                            m.monto_final, 
                            p.nombre as nombre_plan, 
                            p.duracion_meses, 
                            p.duracion_dias
                           FROM tb_matriculas m
                           INNER JOIN tb_planes p ON m.id_plan = p.id_plan 
                           WHERE m.id_cliente = :id_cliente 
                           AND m.id_gimnasio = :id_gimnasio";

// Agregar filtros de fecha si existen
if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
    $sql_matriculas_cliente .= " AND m.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin ";
}

$sql_matriculas_cliente .= " ORDER BY m.fecha_inicio DESC";

try {
    $query_mat_cliente = $pdo->prepare($sql_matriculas_cliente);
    $query_mat_cliente->bindParam(':id_cliente', $id_cliente_get, PDO::PARAM_INT);
    $query_mat_cliente->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    if (!empty($fecha_inicio_filtro) && !empty($fecha_fin_filtro)) {
        $query_mat_cliente->bindParam(':fecha_inicio', $fecha_inicio_filtro);
        $query_mat_cliente->bindParam(':fecha_fin', $fecha_fin_filtro);
    }

    $query_mat_cliente->execute();
    $historial_matriculas = $query_mat_cliente->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al listar matrículas del cliente: " . $e->getMessage());
    $historial_matriculas = [];
}
?>