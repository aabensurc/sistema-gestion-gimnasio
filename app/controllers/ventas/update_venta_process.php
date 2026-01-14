<?php
 session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos de la venta desde el formulario
$id_venta = $_POST['id_venta'];
$id_cliente = $_POST['id_cliente'] === '' ? NULL : $_POST['id_cliente']; // Permite NULL si no se selecciona cliente
$fecha_venta = $_POST['fecha_venta'];
$monto_total = $_POST['monto_total'];
$descuento_total = $_POST['descuento_total'];

// Obtener los datos del nuevo pago
$metodo_pago = $_POST['metodo_pago'];
$monto_pagado = $_POST['monto_pagado'];

// Obtener los detalles de los productos en formato JSON y decodificarlos
$productos_seleccionados_json = $_POST['productos_seleccionados_json'];
$productos_seleccionados = json_decode($productos_seleccionados_json, true);

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // PASO ADICIONAL: Eliminar todos los pagos existentes para esta venta
    $sentencia_delete_pagos = $pdo->prepare("DELETE FROM tb_pagos WHERE id_venta_fk = :id_venta AND tipo_pago = 'venta' AND id_gimnasio = :id_gimnasio");
    $sentencia_delete_pagos->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $sentencia_delete_pagos->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_delete_pagos->execute();

    // 1. Obtener los detalles de la venta ORIGINAL antes de la actualización
    $sql_detalle_original = "SELECT id_detalle_venta, id_producto, cantidad
                             FROM tb_detalle_ventas
                             WHERE id_venta = :id_venta";
    $query_detalle_original = $pdo->prepare($sql_detalle_original);
    $query_detalle_original->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $query_detalle_original->execute();
    $detalle_original = $query_detalle_original->fetchAll(PDO::FETCH_ASSOC);

    // Convertir el detalle original a un array asociativo para fácil acceso por id_producto
    $original_products_map = [];
    foreach ($detalle_original as $item) {
        $original_products_map[$item['id_producto']] = $item;
    }

    // 2. Procesar los productos seleccionados (nuevos/modificados)
    foreach ($productos_seleccionados as $item) {
        $id_producto = $item['id_producto'];
        $cantidad_nueva = $item['cantidad'];
        $precio_unitario = $item['precio_unitario'];
        $subtotal = $item['subtotal'];

        if (isset($original_products_map[$id_producto])) {
            // El producto ya existía en la venta original, puede que su cantidad haya cambiado
            $cantidad_original = $original_products_map[$id_producto]['cantidad'];
            $id_detalle_venta_original = $original_products_map[$id_producto]['id_detalle_venta'];

            if ($cantidad_nueva != $cantidad_original) {
                // La cantidad ha cambiado, ajustar stock y actualizar detalle
                $diferencia_cantidad = $cantidad_nueva - $cantidad_original;

                // Actualizar stock: si la cantidad nueva es mayor, restar; si es menor, sumar
                $sentencia_stock = $pdo->prepare("UPDATE tb_productos
                                                SET stock = stock - :diferencia_cantidad, fyh_actualizacion = :fyh_actualizacion
                                                WHERE id_producto = :id_producto AND id_gimnasio = :id_gimnasio");
                $sentencia_stock->bindParam(':diferencia_cantidad', $diferencia_cantidad, PDO::PARAM_INT);
                $sentencia_stock->bindParam(':fyh_actualizacion', $fechaHora);
                $sentencia_stock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $sentencia_stock->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
                $sentencia_stock->execute();

                // Actualizar detalle de venta existente
                $sentencia_update_detalle = $pdo->prepare("UPDATE tb_detalle_ventas
                                                        SET cantidad = :cantidad, precio_unitario = :precio_unitario, subtotal = :subtotal, fyh_actualizacion = :fyh_actualizacion
                                                        WHERE id_detalle_venta = :id_detalle_venta");
                $sentencia_update_detalle->bindParam(':cantidad', $cantidad_nueva, PDO::PARAM_INT);
                $sentencia_update_detalle->bindParam(':precio_unitario', $precio_unitario);
                $sentencia_update_detalle->bindParam(':subtotal', $subtotal);
                $sentencia_update_detalle->bindParam(':fyh_actualizacion', $fechaHora);
                $sentencia_update_detalle->bindParam(':id_detalle_venta', $id_detalle_venta_original, PDO::PARAM_INT);
                $sentencia_update_detalle->execute();
            }
            // Marcar este producto como "procesado" para no eliminarlo
            unset($original_products_map[$id_producto]);

        } else {
            // Producto nuevo, insertar en detalle y restar del stock
            $sentencia_detalle = $pdo->prepare("INSERT INTO tb_detalle_ventas
                                                (id_venta, id_producto, cantidad, precio_unitario, subtotal, fyh_creacion, fyh_actualizacion)
                                                VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal, :fyh_creacion, :fyh_actualizacion)");
            $sentencia_detalle->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':cantidad', $cantidad_nueva, PDO::PARAM_INT);
            $sentencia_detalle->bindParam(':precio_unitario', $precio_unitario);
            $sentencia_detalle->bindParam(':subtotal', $subtotal);
            $sentencia_detalle->bindParam(':fyh_creacion', $fechaHora);
            $sentencia_detalle->bindParam(':fyh_actualizacion', $fechaHora);
            $sentencia_detalle->execute();

            // Restar del stock
            $sentencia_stock = $pdo->prepare("UPDATE tb_productos
                                            SET stock = stock - :cantidad, fyh_actualizacion = :fyh_actualizacion
                                            WHERE id_producto = :id_producto AND id_gimnasio = :id_gimnasio");
            $sentencia_stock->bindParam(':cantidad', $cantidad_nueva, PDO::PARAM_INT);
            $sentencia_stock->bindParam(':fyh_actualizacion', $fechaHora);
            $sentencia_stock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sentencia_stock->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
            $sentencia_stock->execute();
        }
    }

    // 3. Procesar productos eliminados del detalle (los que quedan en $original_products_map)
    foreach ($original_products_map as $id_producto_eliminado => $item_eliminado) {
        $cantidad_eliminada = $item_eliminado['cantidad'];
        $id_detalle_venta_eliminado = $item_eliminado['id_detalle_venta'];

        // Sumar al stock
        $sentencia_stock_return = $pdo->prepare("UPDATE tb_productos
                                                SET stock = stock + :cantidad, fyh_actualizacion = :fyh_actualizacion
                                                WHERE id_producto = :id_producto AND id_gimnasio = :id_gimnasio");
        $sentencia_stock_return->bindParam(':cantidad', $cantidad_eliminada, PDO::PARAM_INT);
        $sentencia_stock_return->bindParam(':fyh_actualizacion', $fechaHora);
        $sentencia_stock_return->bindParam(':id_producto', $id_producto_eliminado, PDO::PARAM_INT);
        $sentencia_stock_return->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
        $sentencia_stock_return->execute();

        // Eliminar el detalle de venta
        $sentencia_delete_detalle = $pdo->prepare("DELETE FROM tb_detalle_ventas WHERE id_detalle_venta = :id_detalle_venta");
        $sentencia_delete_detalle->bindParam(':id_detalle_venta', $id_detalle_venta_eliminado, PDO::PARAM_INT);
        $sentencia_delete_detalle->execute();
    }

    // 4. Actualizar la venta principal en tb_ventas
    $sentencia_venta = $pdo->prepare("UPDATE tb_ventas
                                    SET id_cliente = :id_cliente, fecha_venta = :fecha_venta, monto_total = :monto_total,
                                        descuento_total = :descuento_total, fyh_actualizacion = :fyh_actualizacion
                                    WHERE id_venta = :id_venta AND id_gimnasio = :id_gimnasio");

    $sentencia_venta->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_venta->bindParam(':fecha_venta', $fecha_venta);
    $sentencia_venta->bindParam(':monto_total', $monto_total);
    $sentencia_venta->bindParam(':descuento_total', $descuento_total);
    $sentencia_venta->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia_venta->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $sentencia_venta->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_venta->execute();

    // PASO ADICIONAL: Insertar el nuevo registro de pago en tb_pagos
    $sentencia_pago = $pdo->prepare("INSERT INTO tb_pagos
                                    (id_cliente, tipo_pago, id_matricula_fk, id_venta_fk, id_asesoria_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                    VALUES (:id_cliente, :tipo_pago, :id_matricula_fk, :id_venta_fk, :id_asesoria_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

    $tipo_pago = 'venta'; // Tipo de pago fijo para venta
    $id_matricula_fk = NULL; // No aplica para pagos de venta
    $id_asesoria_fk = NULL; // No aplica para pagos de venta

    $sentencia_pago->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':tipo_pago', $tipo_pago);
    $sentencia_pago->bindParam(':id_matricula_fk', $id_matricula_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':id_venta_fk', $id_venta, PDO::PARAM_INT); // Usar el ID de la venta que se está actualizando
    $sentencia_pago->bindParam(':id_asesoria_fk', $id_asesoria_fk, PDO::PARAM_INT);
    $sentencia_pago->bindParam(':metodo_pago', $metodo_pago);
    $sentencia_pago->bindParam(':monto', $monto_pagado);
    $sentencia_pago->bindParam(':fecha_hora', $fechaHora);
    $sentencia_pago->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);
    $sentencia_pago->execute();

    // Si todas las operaciones fueron exitosas, confirmar la transacción
    $pdo->commit();
   
    $_SESSION['mensaje'] = "Venta actualizada y pago registrado correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/ventas/'); // Redirigir al listado de ventas
    exit();

} catch (PDOException $e) {
    // Si algo falla, revertir la transacción
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
 
    $_SESSION['mensaje'] = "Error de base de datos al actualizar la venta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/edit.php?id=' . $id_venta); // Redirigir de vuelta al formulario de edición
    exit();
}

?>
