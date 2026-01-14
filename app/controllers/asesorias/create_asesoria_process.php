<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos de la asesoría desde el formulario
$id_cliente = $_POST['id_cliente'];
$id_entrenador = $_POST['id_entrenador'];
$monto_final = $_POST['monto_final'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$descuento = isset($_POST['descuento']) ? $_POST['descuento'] : 0;

// Obtener los datos del pago
$metodo_pago = $_POST['metodo_pago'];
$monto_pagado = $_POST['monto_pagado'];

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // 1. Insertar la nueva asesoría en tb_asesorias
    $sentencia_asesoria = $pdo->prepare("INSERT INTO tb_asesorias
                                        (id_cliente, id_entrenador, monto_final, descuento, fecha_inicio, fecha_fin, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                        VALUES (:id_cliente, :id_entrenador, :monto_final, :descuento, :fecha_inicio, :fecha_fin, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)");

    $sentencia_asesoria->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_asesoria->bindParam(':id_entrenador', $id_entrenador, PDO::PARAM_INT);
    $sentencia_asesoria->bindParam(':monto_final', $monto_final);
    $sentencia_asesoria->bindParam(':descuento', $descuento);
    $sentencia_asesoria->bindParam(':fecha_inicio', $fecha_inicio);
    $sentencia_asesoria->bindParam(':fecha_fin', $fecha_fin);
    $sentencia_asesoria->bindParam(':fyh_creacion', $fechaHora);
    $sentencia_asesoria->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia_asesoria->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_asesoria->execute();

    // Obtener el ID de la asesoría recién insertada
    $id_asesoria_nueva = $pdo->lastInsertId();

    // 2. Insertar el registro de pago en tb_pagos
    $sentencia_pago = $pdo->prepare("INSERT INTO tb_pagos
                                    (id_cliente, id_usuario, tipo_pago, id_matricula_fk, id_venta_fk, id_asesoria_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                    VALUES (:id_cliente, :id_usuario, :tipo_pago, :id_matricula_fk, :id_venta_fk, :id_asesoria_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

    $tipo_pago = 'asesoria'; // Tipo de pago fijo para asesoría
    $id_matricula_fk = NULL; // No aplica para pagos de asesoría
    $id_venta_fk = NULL; // No aplica para pagos de asesoría
    $id_usuario_sesion = $_SESSION['id_usuario_global'];

    $sentencia_pago->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':tipo_pago', $tipo_pago);
    $sentencia_pago->bindParam(':id_matricula_fk', $id_matricula_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_venta_fk', $id_venta_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_asesoria_fk', $id_asesoria_nueva, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':metodo_pago', $metodo_pago);
    $sentencia_pago->bindParam(':monto', $monto_pagado);
    $sentencia_pago->bindParam(':fecha_hora', $fechaHora);
    $sentencia_pago->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_pago->execute();

    $id_pago_recien_creado = $pdo->lastInsertId();

    // -------------------------------------------------------------------------
    // 3. INSERTAR CRONOGRAMA DE PAGOS (tb_cronograma_pagos)
    // -------------------------------------------------------------------------

    // Preparar arrays
    $fechas_cuotas = isset($_POST['fechas_cuotas']) ? $_POST['fechas_cuotas'] : [];
    $montos_cuotas = isset($_POST['montos_cuotas']) ? $_POST['montos_cuotas'] : [];

    // Agregar la Cuota 1 (Inicial) al inicio de los arrays
    // Nota: La fecha de la cuota 1 es HOY (o fecha inicio)
    // El monto es lo que se pagó hoy ($monto_pagado)
    // Usamos $fechaHora (hoy) o $fecha_inicio? Generalmente el pago inicial es hoy.
    // Usaremos la fecha de pago real para el registro.
    array_unshift($fechas_cuotas, date('Y-m-d'));
    array_unshift($montos_cuotas, $monto_pagado);

    $nro_cuota = 1;

    foreach ($fechas_cuotas as $index => $fecha_venc) {
        $monto_cuota = $montos_cuotas[$index];

        // Determinar estado
        // La cuota 1 (index 0) es la que se acaba de pagar
        $estado_cuota = ($index === 0) ? 'Pagado' : 'Pendiente';
        $fecha_pago_real = ($index === 0) ? $fechaHora : NULL;
        $id_pago_link = ($index === 0) ? $id_pago_recien_creado : NULL;

        $sentencia_crono = $pdo->prepare("INSERT INTO tb_cronograma_pagos 
            (id_matricula_fk, id_venta_fk, id_asesoria_fk, nro_cuota, monto_programado, fecha_vencimiento, estado_cuota, fecha_pago_completado, id_pago_fk, fyh_creacion, fyh_actualizacion, id_gimnasio) 
            VALUES (NULL, NULL, :id_asesoria, :nro_cuota, :monto, :fecha_venc, :estado, :fecha_pago, :id_pago_fk, :fecha_hora, :fecha_hora, :id_gimnasio)");

        $sentencia_crono->bindParam(':id_asesoria', $id_asesoria_nueva);
        $sentencia_crono->bindParam(':nro_cuota', $nro_cuota);
        $sentencia_crono->bindParam(':monto', $monto_cuota);
        $sentencia_crono->bindParam(':fecha_venc', $fecha_venc);
        $sentencia_crono->bindParam(':estado', $estado_cuota);
        $sentencia_crono->bindParam(':fecha_pago', $fecha_pago_real);
        $sentencia_crono->bindParam(':id_pago_fk', $id_pago_link);
        $sentencia_crono->bindParam(':fecha_hora', $fechaHora);
        $sentencia_crono->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
        $sentencia_crono->execute();

        $nro_cuota++;
    }

    // Si todas las operaciones fueron exitosas, confirmar la transacción
    $pdo->commit();

    $_SESSION['mensaje'] = "Asesoría y cronograma registrados correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/asesorias/'); // Redirigir al listado de asesorías
    exit();

} catch (PDOException $e) {
    // Si algo falla, revertir la transacción
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['mensaje'] = "Error de base de datos al registrar la asesoría: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/create.php'); // Redirigir de vuelta al formulario de creación
    exit();
}

?>