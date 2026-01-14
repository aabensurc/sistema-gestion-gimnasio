<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

$id_cliente = $_POST['id_cliente'] ?? null;

$response = [
  'has_membership' => false,
  'nombre_completo_cliente' => 'N/A',
  'codigo_dni' => 'N/A',
  'plan_name' => 'N/A',
  'fecha_inicio' => 'N/A',
  'fecha_fin' => 'N/A',
  'status_text' => 'Sin matrícula activa',
  'status_class' => 'bg-warning', // Clase por defecto para sin matrícula
  'header_class' => 'bg-warning' // Clase por defecto para el header del modal
];

if (!empty($id_cliente)) {
  try {
    // Obtener datos del cliente
    $sql_cliente = "SELECT nombres, ape_pat, ape_mat, id_cliente, dni FROM tb_clientes WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio";
    $query_cliente = $pdo->prepare($sql_cliente);
    $query_cliente->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $query_cliente->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_cliente->execute();
    $cliente_data = $query_cliente->fetch(PDO::FETCH_ASSOC);

    if ($cliente_data) {
      $response['nombre_completo_cliente'] = $cliente_data['nombres'] . ' ' . $cliente_data['ape_pat'] . ' ' . $cliente_data['ape_mat'];
      $response['codigo_dni'] = !empty($cliente_data['dni']) ? 'DNI: ' . $cliente_data['dni'] : 'Código: ' . $cliente_data['id_cliente'];
    }

    // Obtener la última matrícula del cliente
    $sql_matricula = "SELECT
                            m.id_matricula,
                            m.fecha_inicio,
                            m.fecha_fin,
                            p.nombre AS plan_name
                          FROM
                            tb_matriculas AS m
                          LEFT JOIN
                            tb_planes AS p ON m.id_plan = p.id_plan
                          WHERE
                            m.id_cliente = :id_cliente
                          AND
                            m.id_gimnasio = :id_gimnasio
                          ORDER BY
                            m.fecha_fin DESC, m.fyh_creacion DESC
                          LIMIT 1"; // Obtener la última matrícula

    $query_matricula = $pdo->prepare($sql_matricula);
    $query_matricula->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $query_matricula->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_matricula->execute();
    $matricula_data = $query_matricula->fetch(PDO::FETCH_ASSOC);

    if ($matricula_data) {
      $response['has_membership'] = true;
      $response['plan_name'] = $matricula_data['plan_name'] ?? 'Plan Desconocido';
      $response['fecha_inicio'] = $matricula_data['fecha_inicio'];
      $response['fecha_fin'] = $matricula_data['fecha_fin'];

      $fecha_fin_timestamp = strtotime($matricula_data['fecha_fin']);
      $today_timestamp = strtotime(date('Y-m-d'));
      $diff_days = round(($fecha_fin_timestamp - $today_timestamp) / (60 * 60 * 24));

      // --- VERIFICAR CONGELAMIENTO ---
      $id_matricula_actual = $matricula_data['id_matricula'];
      $sql_congelamiento = "SELECT * FROM tb_congelamientos 
                                  WHERE id_matricula = :id_matricula 
                                  AND estado = 1 
                                  AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
                                  LIMIT 1";
      $query_freeze = $pdo->prepare($sql_congelamiento);
      $query_freeze->bindParam(':id_matricula', $id_matricula_actual);
      $query_freeze->execute();
      $freeze_data = $query_freeze->fetch(PDO::FETCH_ASSOC);
      // -------------------------------

      if ($freeze_data) {
        $fecha_descongelamiento = date('d/m/Y', strtotime($freeze_data['fecha_fin']));
        $response['status_text'] = '❄️ CONGELADO (Hasta: ' . $fecha_descongelamiento . ')';
        $response['status_class'] = 'badge-info';
        $response['header_class'] = 'bg-info';
      } elseif ($diff_days < 0) {
        $response['status_text'] = 'Vencida';
        $response['status_class'] = 'badge-danger';
        $response['header_class'] = 'bg-danger';
      } elseif ($diff_days <= 7) { // A punto de vencer (7 días o menos)
        $response['status_text'] = 'A punto de vencer';
        $response['status_class'] = 'badge-warning';
        $response['header_class'] = 'bg-warning';
      } else {
        $response['status_text'] = 'Activa';
        $response['status_class'] = 'badge-success';
        $response['header_class'] = 'bg-success';
      }
    }

  } catch (PDOException $e) {
    error_log("Error al verificar matrícula del cliente: " . $e->getMessage());
    $response['status_text'] = 'Error al consultar matrícula';
    $response['status_class'] = 'badge-secondary';
    $response['header_class'] = 'bg-secondary';
  }
}

header('Content-Type: application/json');
echo json_encode($response);

?>