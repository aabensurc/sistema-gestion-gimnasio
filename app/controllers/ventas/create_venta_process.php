<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos de la venta desde el formulario
$id_cliente = $_POST['id_cliente'] === '' ? NULL : $_POST['id_cliente']; // Permite NULL si no se selecciona cliente
$fecha_venta = $_POST['fecha_venta'];
$monto_total = $_POST['monto_total'];
$descuento_total = $_POST['descuento_total'];

// Obtener los datos del pago
$metodo_pago = $_POST['metodo_pago'];
$monto_pagado = $_POST['monto_pagado'];

// Obtener los detalles de los productos en formato JSON y decodificarlos
$productos_seleccionados_json = $_POST['productos_seleccionados_json'];
$productos_seleccionados = json_decode($productos_seleccionados_json, true);

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // 1. Insertar la nueva venta en tb_ventas
    $sentencia_venta = $pdo->prepare("INSERT INTO tb_ventas
                                    (id_cliente, fecha_venta, monto_total, descuento_total, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                    VALUES (:id_cliente, :fecha_venta, :monto_total, :descuento_total, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)");

    $sentencia_venta->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_venta->bindParam(':fecha_venta', $fechaHora);
    $sentencia_venta->bindParam(':monto_total', $monto_total);
    $sentencia_venta->bindParam(':descuento_total', $descuento_total);
    $sentencia_venta->bindParam(':fyh_creacion', $fechaHora);
    $sentencia_venta->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia_venta->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_venta->execute();

    // Obtener el ID de la venta recién insertada
    $id_venta_nueva = $pdo->lastInsertId();

    // 2. Insertar los detalles de la venta en tb_detalle_ventas y actualizar el stock
    if (!empty($productos_seleccionados)) {
        foreach ($productos_seleccionados as $item) {
            $id_producto = $item['id_producto'];
            $cantidad = $item['cantidad'];
            $precio_unitario = $item['precio_unitario'];
            $subtotal = $item['subtotal'];

            // Insertar detalle de venta
            $sentencia_detalle = $pdo->prepare("INSERT INTO tb_detalle_ventas
                                                (id_venta, id_producto, cantidad, precio_unitario, subtotal, fyh_creacion, fyh_actualizacion)
                                                VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal, :fyh_creacion, :fyh_actualizacion)");
            $sentencia_detalle->bindParam(':id_venta', $id_venta_nueva, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':precio_unitario', $precio_unitario);
            $sentencia_detalle->bindParam(':subtotal', $subtotal);
            $sentencia_detalle->bindParam(':fyh_creacion', $fechaHora);
            $sentencia_detalle->bindParam(':fyh_actualizacion', $fechaHora);
            $sentencia_detalle->execute();

            // Actualizar el stock del producto
            $sentencia_stock = $pdo->prepare("UPDATE tb_productos
                                            SET stock = stock - :cantidad, fyh_actualizacion = :fyh_actualizacion
                                            WHERE id_producto = :id_producto AND ID_gimnasio = :id_gimnasio");
            $sentencia_stock->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $sentencia_stock->bindParam(':fyh_actualizacion', $fechaHora);
            $sentencia_stock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sentencia_stock->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
            $sentencia_stock->execute();
        }
    }

    // 3. Insertar el registro de pago en tb_pagos
    $sentencia_pago = $pdo->prepare("INSERT INTO tb_pagos
                                    (id_cliente, id_usuario, tipo_pago, id_matricula_fk, id_venta_fk, id_asesoria_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                    VALUES (:id_cliente, :id_usuario, :tipo_pago, :id_matricula_fk, :id_venta_fk, :id_asesoria_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

    $tipo_pago = 'venta'; // Tipo de pago fijo para venta
    $id_matricula_fk = NULL; // No aplica para pagos de venta
    $id_asesoria_fk = NULL; // No aplica para pagos de venta
    $id_usuario_sesion = $_SESSION['id_usuario_global'];

    $sentencia_pago->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':tipo_pago', $tipo_pago);
    $sentencia_pago->bindParam(':id_matricula_fk', $id_matricula_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_venta_fk', $id_venta_nueva, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_asesoria_fk', $id_asesoria_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':metodo_pago', $metodo_pago);
    $sentencia_pago->bindParam(':monto', $monto_pagado);
    $sentencia_pago->bindParam(':fecha_hora', $fechaHora);
    $sentencia_pago->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_pago->execute();

    $id_pago_recien_creado = $pdo->lastInsertId();

    // -------------------------------------------------------------------------
    // 4. INSERTAR CRONOGRAMA DE PAGOS (tb_cronograma_pagos)
    // -------------------------------------------------------------------------

    // Preparar arrays
    $fechas_cuotas = isset($_POST['fechas_cuotas']) ? $_POST['fechas_cuotas'] : [];
    $montos_cuotas = isset($_POST['montos_cuotas']) ? $_POST['montos_cuotas'] : [];

    // Agregar la Cuota 1 (Inicial) al inicio de los arrays
    // Nota: La fecha de la cuota 1 es HOY (o la fecha de venta seleccionada)
    // El monto es lo que se pagó hoy ($monto_pagado)
    array_unshift($fechas_cuotas, $fecha_venta);
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
            VALUES (NULL, :id_venta, NULL, :nro_cuota, :monto, :fecha_venc, :estado, :fecha_pago, :id_pago_fk, :fecha_hora, :fecha_hora, :id_gimnasio)");

        $sentencia_crono->bindParam(':id_venta', $id_venta_nueva);
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

    $_SESSION['mensaje'] = "Venta y cronograma registrados correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/ventas/'); // Redirigir al listado de ventas
    exit();

} catch (PDOException $e) {
    // Si algo falla, revertir la transacción
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['mensaje'] = "Error de base de datos al registrar la venta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/create.php'); // Redirigir de vuelta al formulario de creación
    exit();
}
?>