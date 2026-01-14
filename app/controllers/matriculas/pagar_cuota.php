<?php
session_start();
include('../../config.php');

$id_cronograma = $_POST['id_cronograma'];
$id_cronograma = $_POST['id_cronograma'];
$id_origen = $_POST['id_origen'];
$tipo_origen = $_POST['tipo_origen'];
$monto = $_POST['monto'];
$metodo_pago = $_POST['metodo_pago'];
$fechaHora = date('Y-m-d H:i:s');
$id_usuario = $_SESSION['id_usuario_global'];
$id_gimnasio = $_SESSION['id_gimnasio_sesion'];

try {
    $pdo->beginTransaction();

    // Configurar variables dinámicas según origen
    $columna_fk = "";
    $tabla_origen = "";
    $pk_origen = "";
    $tipo_pago_enum = $tipo_origen; // matricula, venta, asesoria coinciden con ENUM de db

    switch ($tipo_origen) {
        case 'matricula':
            $columna_fk = "id_matricula_fk";
            $tabla_origen = "tb_matriculas";
            $pk_origen = "id_matricula";
            break;
        case 'venta':
            $columna_fk = "id_venta_fk";
            $tabla_origen = "tb_ventas";
            $pk_origen = "id_venta";
            break;
        case 'asesoria':
            $columna_fk = "id_asesoria_fk";
            $tabla_origen = "tb_asesorias";
            $pk_origen = "id_asesoria";
            break;
        default:
            throw new Exception("Tipo de origen inválido");
    }

    // 1. Registrar el pago en tb_pagos (Polimórfico)
    // Obtenemos id_cliente de la tabla origen
    $sql_pago = "INSERT INTO tb_pagos (id_cliente, id_usuario, tipo_pago, $columna_fk, metodo_pago, monto, fecha_hora, id_gimnasio) 
                 SELECT id_cliente, :id_usuario, :tipo_pago, :id_origen, :metodo_pago, :monto, :fecha_hora, :id_gimnasio 
                 FROM $tabla_origen WHERE $pk_origen = :id_origen_select";

    $stmt_pago = $pdo->prepare($sql_pago);
    $stmt_pago->execute([
        ':id_usuario' => $id_usuario,
        ':tipo_pago' => $tipo_pago_enum,
        ':id_origen' => $id_origen,
        ':metodo_pago' => $metodo_pago,
        ':monto' => $monto,
        ':fecha_hora' => $fechaHora,
        ':id_gimnasio' => $id_gimnasio,
        ':id_origen_select' => $id_origen
    ]);

    $id_pago_nuevo = $pdo->lastInsertId();

    // 2. Obtener datos originales de la cuota para saber si es parcial
    $sql_info = "SELECT monto_programado, nro_cuota, fecha_vencimiento FROM tb_cronograma_pagos WHERE id_cronograma = :id_cronograma";
    $query_info = $pdo->prepare($sql_info);
    $query_info->execute([':id_cronograma' => $id_cronograma]);
    $cuota_original = $query_info->fetch(PDO::FETCH_ASSOC);

    $monto_programado = floatval($cuota_original['monto_programado']);
    $monto_pagado_actual = floatval($monto);
    $nro_cuota = $cuota_original['nro_cuota'];
    $fecha_venc = $cuota_original['fecha_vencimiento'];

    // 3. Actualizar la cuota actual (La convertimos en lo que se pagó efectivanente)
    // Si pagó todo, monto_programado queda igual. Si pagó parcial, cambiamos monto_programado al pagado.
    $nuevo_monto_registrado = ($monto_pagado_actual < $monto_programado) ? $monto_pagado_actual : $monto_programado;

    $sql_update = "UPDATE tb_cronograma_pagos 
                   SET estado_cuota = 'Pagado', 
                       id_pago_fk = :id_pago,
                       monto_programado = :monto_real,
                       fecha_pago_completado = :fecha_hora,
                       fyh_actualizacion = :fecha_hora
                   WHERE id_cronograma = :id_cronograma";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        ':monto_real' => $nuevo_monto_registrado,
        ':id_pago' => $id_pago_nuevo,
        ':fecha_hora' => $fechaHora,
        ':id_cronograma' => $id_cronograma
    ]);

    // 4. Si fue pago parcial, crear la nueva cuota por la diferencia
    if ($monto_pagado_actual < $monto_programado) {
        $diferencia = $monto_programado - $monto_pagado_actual;

        // Insertar el saldo pendiente (misma fecha vencimiento, mismo nro cuota o sufijo si se quisiera)
        $sql_saldo = "INSERT INTO tb_cronograma_pagos 
                      ($columna_fk, nro_cuota, monto_programado, fecha_vencimiento, estado_cuota, fyh_creacion, fyh_actualizacion, id_gimnasio)
                      VALUES (:id_origen, :nro_cuota, :monto_saldo, :fecha_venc, 'Pendiente', :fecha_hora, :fecha_hora, :id_gimnasio)";
        $stmt_saldo = $pdo->prepare($sql_saldo);
        $stmt_saldo->execute([
            ':id_origen' => $id_origen,
            ':nro_cuota' => $nro_cuota, // Mantenemos el mismo nro para indicar que pertenece al mismo periodo original
            ':monto_saldo' => $diferencia,
            ':fecha_venc' => $fecha_venc,
            ':fecha_hora' => $fechaHora,
            ':id_gimnasio' => $id_gimnasio
        ]);
    }

    $pdo->commit();

    $_SESSION['mensaje'] = "Pago de cuota registrado exitosamente";
    $_SESSION['icono'] = "success";
    header('Location: ' . $_SERVER['HTTP_REFERER']); // Regresa a la vista desde donde se llamó

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['mensaje'] = "Error al registrar el pago: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>