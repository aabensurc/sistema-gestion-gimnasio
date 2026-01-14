<?php

// Este archivo se incluye en ventas/edit.php para cargar los datos de una venta específica.
// Se asume que config.php ya ha sido incluido por el archivo principal (edit.php)
// y que la variable $pdo está disponible.

// Obtener el ID de la venta de la URL
$id_venta_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_venta_get)) {
    
    $_SESSION['mensaje'] = "Error: ID de venta no proporcionado para edición.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

try {
    // Consulta para obtener los datos principales de la venta, incluyendo información del cliente
    // y el primer pago asociado (si existe) para mostrarlo como "pago inicial"
    $sql_venta = "SELECT
                    v.id_venta,
                    v.id_cliente,
                    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
                    v.fecha_venta,
                    v.monto_total,
                    v.descuento_total,
                    -- Subconsulta para sumar todos los montos de los pagos de esta venta
                    (SELECT SUM(pago.monto)
                     FROM tb_pagos AS pago
                     WHERE pago.id_venta_fk = v.id_venta
                     AND pago.tipo_pago = 'venta') AS total_pagado,
                    -- Subconsulta para obtener el método y monto del primer pago de esta venta (si existe)
                    (SELECT p.metodo_pago FROM tb_pagos p WHERE p.id_venta_fk = v.id_venta AND p.tipo_pago = 'venta' ORDER BY p.fecha_hora ASC LIMIT 1) AS metodo_pago_inicial,
                    (SELECT p.monto FROM tb_pagos p WHERE p.id_venta_fk = v.id_venta AND p.tipo_pago = 'venta' ORDER BY p.fecha_hora ASC LIMIT 1) AS monto_pagado_inicial
                  FROM
                    tb_ventas AS v
                  LEFT JOIN
                    tb_clientes AS c ON v.id_cliente = c.id_cliente
                  WHERE v.id_venta = :id_venta_get AND c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";

    $query_venta = $pdo->prepare($sql_venta);
    $query_venta->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_venta->execute();
    $venta_data = $query_venta->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró la venta, redirigir
    if (!$venta_data) {
       
        $_SESSION['mensaje'] = "Error: Venta no encontrada para edición.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/ventas/');
        exit();
    }

    // Consulta para obtener los detalles de los productos de esta venta
    $sql_detalle_venta_edit = "SELECT
                                dv.id_detalle_venta,
                                dv.id_producto,
                                p.nombre AS nombre_producto,
                                dv.cantidad,
                                dv.precio_unitario,
                                dv.subtotal
                              FROM
                                tb_detalle_ventas AS dv
                              LEFT JOIN
                                tb_productos AS p ON dv.id_producto = p.id_producto
                              WHERE dv.id_venta = :id_venta_get";

    $query_detalle_venta_edit = $pdo->prepare($sql_detalle_venta_edit);
    $query_detalle_venta_edit->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_detalle_venta_edit->execute();
    $detalle_venta_data_edit = $query_detalle_venta_edit->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejo de errores de base de datos
   
    $_SESSION['mensaje'] = "Error de base de datos al cargar la venta para edición: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

// Las variables $venta_data y $detalle_venta_data_edit
// ahora están disponibles para la interfaz edit.php
