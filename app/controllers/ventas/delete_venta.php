<?php

include('../../config.php');

// VERIFICAR CAJA ABIERTA
include('../caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de anular un registro.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/ventas/');
    exit;
} // Asegúrate de que la ruta a config.php sea correcta

// Obtener el ID de la venta desde el formulario (se envía por POST desde el modal de confirmación)
$id_venta = $_POST['id_venta'] ?? null;

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

// Verificar si se recibió un ID válido
if (empty($id_venta)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de venta no proporcionado para eliminar.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

try {
    // Iniciar una transacción para asegurar la atomicidad de las operaciones
    $pdo->beginTransaction();

    // 1. Obtener los productos y cantidades de tb_detalle_ventas antes de eliminar la venta
    $sql_get_detalle_productos = "SELECT id_producto, cantidad
                                  FROM tb_detalle_ventas
                                  WHERE id_venta = :id_venta";
    $query_get_detalle_productos = $pdo->prepare($sql_get_detalle_productos);
    $query_get_detalle_productos->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $query_get_detalle_productos->execute();
    $productos_a_devolver = $query_get_detalle_productos->fetchAll(PDO::FETCH_ASSOC);

    // 2. Devolver los productos al stock en tb_productos
    foreach ($productos_a_devolver as $item) {
        $id_producto = $item['id_producto'];
        $cantidad = $item['cantidad'];

        // Solo actualizar stock si el id_producto no es NULL (es decir, el producto aún existe)
        if ($id_producto !== null) {
            $sentencia_stock_return = $pdo->prepare("UPDATE tb_productos
                                                    SET stock = stock + :cantidad, fyh_actualizacion = :fyh_actualizacion
                                                    WHERE id_producto = :id_producto");
            $sentencia_stock_return->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $sentencia_stock_return->bindParam(':fyh_actualizacion', $fechaHora);
            $sentencia_stock_return->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $sentencia_stock_return->execute();
        }
    }

    // 3. Anular la venta en tb_ventas (Soft Delete) y los pagos asociados
    $sentencia_anular_venta = $pdo->prepare("UPDATE tb_ventas SET estado = 0 WHERE id_venta = :id_venta");
    $sentencia_anular_venta->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $sentencia_anular_venta->execute();

    // 4. Anular los pagos asociados a esta venta
    $sentencia_anular_pagos = $pdo->prepare("UPDATE tb_pagos SET estado = 0 WHERE id_venta_fk = :id_venta");
    $sentencia_anular_pagos->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $sentencia_anular_pagos->execute();

    // Si todas las operaciones fueron exitosas, confirmar la transacción
    $pdo->commit();
    session_start();
    $_SESSION['mensaje'] = "Venta anulada correctamente, stock devuelto y pagos anulados.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/ventas/'); // Redirigir de vuelta al listado de ventas
    exit();

} catch (PDOException $e) {
    // Si algo falla, revertir la transacción
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al eliminar la venta y actualizar stock: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

?>