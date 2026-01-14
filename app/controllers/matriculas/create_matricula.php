<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos enviados desde el formulario de matrícula
$id_cliente = $_POST['id_cliente'];
$id_plan = $_POST['id_plan'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$descuento = $_POST['descuento'];
$monto_final = $_POST['monto_final']; // Este es el monto a pagar de la matrícula

// Nuevos campos para el pago
$metodo_pago = $_POST['metodo_pago'];
$monto_pagado = $_POST['monto_pagado']; // Este es el monto que el cliente realmente pagó

// Obtener la fecha y hora actual para fyh_creacion y fecha_hora del pago
$fechaHora = date('Y-m-d H:i:s');

try {
    // Iniciar una transacción para asegurar que ambas inserciones (matrícula y pago) se completen o ninguna lo haga
    $pdo->beginTransaction();

    // 1. Insertar la nueva matrícula en tb_matriculas
    $sentencia_matricula = $pdo->prepare("INSERT INTO tb_matriculas
                                        (id_cliente, id_plan, fecha_inicio, fecha_fin, descuento, monto_final, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                        VALUES (:id_cliente, :id_plan, :fecha_inicio, :fecha_fin, :descuento, :monto_final, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)");

    $sentencia_matricula->bindParam(':id_cliente', $id_cliente);
    $sentencia_matricula->bindParam(':id_plan', $id_plan);
    $sentencia_matricula->bindParam(':fecha_inicio', $fecha_inicio);
    $sentencia_matricula->bindParam(':fecha_fin', $fecha_fin);
    $sentencia_matricula->bindParam(':descuento', $descuento);
    $sentencia_matricula->bindParam(':monto_final', $monto_final);
    $sentencia_matricula->bindParam(':fyh_creacion', $fechaHora);
    $sentencia_matricula->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia_matricula->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    if ($sentencia_matricula->execute()) {
        // Obtener el ID de la matrícula recién insertada
        $id_matricula_nueva = $pdo->lastInsertId();

        // 2. Insertar el registro de pago en tb_pagos
        $sentencia_pago = $pdo->prepare("INSERT INTO tb_pagos
                                        (id_cliente, id_usuario, tipo_pago, id_matricula_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                        VALUES (:id_cliente, :id_usuario, :tipo_pago, :id_matricula_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

        // Vincular los parámetros para el pago
        $tipo_pago = 'matricula'; // Tipo de pago fijo para matrícula
        $id_usuario_sesion = $_SESSION['id_usuario_global'];

        $sentencia_pago->bindParam(':id_cliente', $id_cliente);
        $sentencia_pago->bindParam(':id_usuario', $id_usuario_sesion);
        $sentencia_pago->bindParam(':tipo_pago', $tipo_pago);
        $sentencia_pago->bindParam(':id_matricula_fk', $id_matricula_nueva);
        $sentencia_pago->bindParam(':metodo_pago', $metodo_pago);
        $sentencia_pago->bindParam(':monto', $monto_pagado); // Usar el monto pagado del formulario
        $sentencia_pago->bindParam(':fecha_hora', $fechaHora);
        $sentencia_pago->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

        if ($sentencia_pago->execute()) {

            $id_pago_nuevo = $pdo->lastInsertId();

            // 3. Lógica para Agendar Cuotas (Cronograma)

            // SIEMPRE insertamos la Cuota 1 (que es el pago inicial realizado ahora)
            // Para 'Contado', será la única cuota (100% pagado).
            // Para 'Crédito', será la inicial.

            $sql_cuota1 = "INSERT INTO tb_cronograma_pagos 
                           (id_matricula_fk, id_pago_fk, nro_cuota, monto_programado, fecha_vencimiento, estado_cuota, fecha_pago_completado, fyh_creacion, fyh_actualizacion, id_gimnasio) 
                           VALUES (:id_matricula, :id_pago, 1, :monto, :fecha_venc, 'Pagado', :fecha_pago, :fyh, :fyh, :id_gimnasio)";
            $stmt_c1 = $pdo->prepare($sql_cuota1);
            $date_now = date('Y-m-d'); // Fecha vencimiento hoy para la inicial
            $stmt_c1->execute([
                ':id_matricula' => $id_matricula_nueva,
                ':id_pago' => $id_pago_nuevo,
                ':monto' => $monto_pagado,
                ':fecha_venc' => $date_now,
                ':fecha_pago' => $fechaHora,
                ':fyh' => $fechaHora,
                ':id_gimnasio' => $_SESSION['id_gimnasio_sesion']
            ]);

            // Si es crédito, insertamos las cuotas futuras
            if (isset($_POST['pago_en_cuotas']) && $_POST['pago_en_cuotas'] == '1') {
                $fechas_cuotas = $_POST['fechas_cuotas'] ?? [];
                $montos_cuotas = $_POST['montos_cuotas'] ?? [];

                // Insertar Cuotas Restantes (Pendientes)
                $nro_cuota = 2;
                $sql_cuota_futura = "INSERT INTO tb_cronograma_pagos 
                               (id_matricula_fk, nro_cuota, monto_programado, fecha_vencimiento, estado_cuota, fyh_creacion, fyh_actualizacion, id_gimnasio) 
                               VALUES (:id_matricula, :nro_cuota, :monto, :fecha_venc, 'Pendiente', :fyh, :fyh, :id_gimnasio)";
                $stmt_futura = $pdo->prepare($sql_cuota_futura);

                foreach ($fechas_cuotas as $index => $fecha_venc) {
                    $monto_programado = $montos_cuotas[$index];
                    $stmt_futura->execute([
                        ':id_matricula' => $id_matricula_nueva,
                        ':nro_cuota' => $nro_cuota,
                        ':monto' => $monto_programado,
                        ':fecha_venc' => $fecha_venc,
                        ':fyh' => $fechaHora,
                        ':id_gimnasio' => $_SESSION['id_gimnasio_sesion']
                    ]);
                    $nro_cuota++;
                }
            }

            // Si ambas inserciones fueron exitosas, confirmar la transacción
            $pdo->commit();

            $_SESSION['mensaje'] = "Se registró la matrícula y el pago correctamente";
            $_SESSION['icono'] = "success";
            header('Location: ' . $URL . '/matriculas/'); // Redirigir a la página principal de matrículas
        } else {
            // Si la inserción del pago falla, revertir la matrícula también
            $pdo->rollBack();

            $_SESSION['mensaje'] = "Error: La matrícula se registró, pero no se pudo registrar el pago. Transacción revertida.";
            $_SESSION['icono'] = "error";
            header('Location: ' . $URL . '/matriculas/create.php');
        }
    } else {
        // Si la inserción de la matrícula falla, no hay necesidad de rollBack si no se inició el pago

        $_SESSION['mensaje'] = "Error: No se pudo registrar la matrícula en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/matriculas/create.php');
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO y revertir la transacción si está activa
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/create.php');
}

?>