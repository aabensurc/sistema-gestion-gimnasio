<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// CRÍTICO: Asegurarse de que no hay ningún espacio ni caracter antes de <?php
session_start();
// OJO: Es mejor incluir config.php antes, pero si genera salida, mueve session_start() al inicio.
include('../../../app/config.php'); 
include('../../../layout/sesion.php'); // Necesario para $URL y la sesión del gimnasio

// =========================================================
// 1. Cargar la librería Dompdf
// =========================================================
require_once '../../../vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;
// =========================================================

$id_venta_get = $_GET['id'] ?? null;

if (empty($id_venta_get)) {
    echo "Error: ID de venta no proporcionado.";
    exit();
}

try {
    // =============================================
    // 2. Obtener los datos de la Venta y su Detalle
    // =============================================
    
    // Consulta de la Venta Principal 
    $sql_venta = "SELECT v.id_venta, v.id_cliente, CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
                         v.fecha_venta, v.monto_total, v.descuento_total,
                         (SELECT SUM(pago.monto) FROM tb_pagos AS pago WHERE pago.id_venta_fk = v.id_venta AND pago.tipo_pago = 'venta') AS total_pagado
                  FROM tb_ventas AS v
                  LEFT JOIN tb_clientes AS c ON v.id_cliente = c.id_cliente
                  WHERE v.id_venta = :id_venta_get AND v.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";

    $query_venta = $pdo->prepare($sql_venta);
    $query_venta->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_venta->execute();
    $venta_data = $query_venta->fetch(PDO::FETCH_ASSOC);

    if (!$venta_data) {
        echo "Error: Venta no encontrada o no pertenece a este gimnasio.";
        exit();
    }

    // Consulta de Detalle de Productos
    $sql_detalle_venta = "SELECT dv.cantidad, dv.precio_unitario, dv.subtotal, p.nombre AS nombre_producto
                          FROM tb_detalle_ventas AS dv
                          LEFT JOIN tb_productos AS p ON dv.id_producto = p.id_producto
                          WHERE dv.id_venta = :id_venta_get";

    $query_detalle_venta = $pdo->prepare($sql_detalle_venta);
    $query_detalle_venta->bindParam(':id_venta_get', $id_venta_get, PDO::PARAM_INT);
    $query_detalle_venta->execute();
    $detalle_venta_data = $query_detalle_venta->fetchAll(PDO::FETCH_ASSOC);

    // =============================================
    // 3. Generar el PDF usando Dompdf
    // =============================================
    
    // Configuración de Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    // CRÍTICO: Habilitar el acceso a archivos remotos/URL (necesario para el logo)
    $options->set('isRemoteEnabled', true); 
    // CRÍTICO: Establecer la base de la URL para que Dompdf pueda resolver rutas como $URL/public/...
    $options->set('basePath', $_SERVER['DOCUMENT_ROOT']); 
    
    $dompdf = new Dompdf($options);

    // Capturar la salida HTML
    ob_start();
    include('generar_boleta_html.php'); 
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    // Tamaño de papel personalizado para ticket térmico
    $dompdf->setPaper(array(0, 0, 227, 840), 'portrait'); // 227 puntos es aprox. 80mm
    $dompdf->render();

    // Enviar el PDF al navegador
    $dompdf->stream("boleta_venta_".$id_venta_get.".pdf", array("Attachment" => false));

} catch (PDOException $e) {
    // En caso de error de DB, capturar la salida
    $error_msg = "Error de base de datos al generar la boleta: " . $e->getMessage();
    error_log($error_msg);
    echo $error_msg;

} catch (Exception $e) {
    // En caso de error general (ej: Dompdf o autoload)
    $error_msg = "Error general: " . $e->getMessage();
    error_log($error_msg);
    echo $error_msg;
}

?>