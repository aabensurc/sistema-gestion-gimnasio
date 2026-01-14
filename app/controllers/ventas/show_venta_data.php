<?php

// Este archivo se incluye en ventas/show.php para cargar los datos de una venta específica.
// Se asume que config.php ya ha sido incluido por el archivo principal (show.php)
// y que la variable $pdo está disponible.

// Obtener el ID de la venta de la URL
$id_venta_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_venta_get)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de venta no proporcionado.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

try {
    // Consulta para obtener los datos principales de la venta
    $sql_venta = "SELECT
                    v.id_venta,
                    v.id_cliente,
                    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
                    v.fecha_venta,
                    v.monto_total,
                    v.descuento_total,
                    -- Subconsulta para sumar los montos de los pagos de esta venta
                    (SELECT SUM(pago.monto)
                     FROM tb_pagos AS pago
                     WHERE pago.id_venta_fk = v.id_venta
                     AND pago.tipo_pago = 'venta') AS total_pagado
                  FROM
                    tb_ventas AS v
                  LEFT JOIN
                    tb_clientes AS c ON v.id_cliente = c.id_cliente
                  WHERE v.id_venta = :id_venta_get";

    $query_venta = $pdo->prepare($sql_venta);
    $query_venta->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_venta->execute();
    $venta_data = $query_venta->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró la venta, redirigir
    if (!$venta_data) {
        session_start();
        $_SESSION['mensaje'] = "Error: Venta no encontrada.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/ventas/');
        exit();
    }

    // Consulta para obtener los detalles de los productos de esta venta
    $sql_detalle_venta = "SELECT
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

    $query_detalle_venta = $pdo->prepare($sql_detalle_venta);
    $query_detalle_venta->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_detalle_venta->execute();
    $detalle_venta_data = $query_detalle_venta->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al cargar la venta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/ventas/');
    exit();
}

// Las variables $venta_data y $detalle_venta_data
// ahora están disponibles para la interfaz show.php
