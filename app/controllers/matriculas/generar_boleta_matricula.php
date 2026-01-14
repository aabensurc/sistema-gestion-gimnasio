<?php
// =========================================================
// CONFIGURACIÓN DE ERRORES PARA EVITAR FALLOS EN PDF
// =========================================================
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// INICIO DE SESIÓN Y CONFIGURACIÓN
session_start();
include('../../../app/config.php'); 
include('../../../layout/sesion.php'); 

// =========================================================
// CARGA DE DOMPDF
// =========================================================
require_once '../../../vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

// VALIDACIÓN DE ID
$id_matricula_get = $_GET['id'] ?? null;

if (empty($id_matricula_get)) {
    echo "Error: ID de matrícula no proporcionado.";
    exit();
}

try {
    // =============================================
    // CONSULTA DE DATOS DE LA MATRÍCULA
    // =============================================
    $sql_matricula = "SELECT 
                        m.id_matricula, 
                        m.fecha_inicio, 
                        m.fecha_fin, 
                        m.monto_final, 
                        m.descuento,
                        p.nombre as nombre_plan,
                        p.precio as precio_regular_plan,
                        CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
                        -- Subconsulta para el total pagado
                        (SELECT SUM(pago.monto) 
                         FROM tb_pagos AS pago 
                         WHERE pago.id_matricula_fk = m.id_matricula 
                         AND pago.tipo_pago = 'matricula') AS total_pagado
                      FROM tb_matriculas m
                      INNER JOIN tb_clientes c ON m.id_cliente = c.id_cliente
                      INNER JOIN tb_planes p ON m.id_plan = p.id_plan
                      WHERE m.id_matricula = :id_matricula_get 
                      AND m.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";

    $query_matricula = $pdo->prepare($sql_matricula);
    $query_matricula->bindParam(':id_matricula_get', $id_matricula_get, PDO::PARAM_INT);
    $query_matricula->execute();
    $matricula_data = $query_matricula->fetch(PDO::FETCH_ASSOC);

    if (!$matricula_data) {
        echo "Error: Matrícula no encontrada o no pertenece a este gimnasio.";
        exit();
    }

    // =============================================
    // GENERACIÓN DEL PDF
    // =============================================
    
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); 
    $options->set('basePath', $_SERVER['DOCUMENT_ROOT']); 
    
    $dompdf = new Dompdf($options);

    // Capturar HTML
    ob_start();
    include('generar_boleta_matricula_html.php'); 
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    
    // Tamaño de papel: 80mm ancho (227 puntos) x Alto dinámico
    $dompdf->setPaper(array(0, 0, 227, 840), 'portrait'); 
    $dompdf->render();

    // Enviar al navegador
    $dompdf->stream("boleta_matricula_".$id_matricula_get.".pdf", array("Attachment" => false));

} catch (PDOException $e) {
    $error_msg = "Error de base de datos: " . $e->getMessage();
    error_log($error_msg);
    echo $error_msg;
} catch (Exception $e) {
    $error_msg = "Error general: " . $e->getMessage();
    error_log($error_msg);
    echo $error_msg;
}
?>