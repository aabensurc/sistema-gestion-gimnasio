<?php
// =========================================================
// CONFIGURACIÓN DE ERRORES
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
$id_asesoria_get = $_GET['id'] ?? null;

if (empty($id_asesoria_get)) {
    echo "Error: ID de asesoría no proporcionado.";
    exit();
}

try {
    // =============================================
    // CONSULTA DE DATOS DE LA ASESORÍA
    // =============================================
    $sql_asesoria = "SELECT 
                        a.id_asesoria, 
                        a.fecha_inicio, 
                        a.fecha_fin, 
                        a.monto_final, 
                        -- Datos del Cliente
                        CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_cliente,
                        -- Datos del Entrenador
                        CONCAT_WS(' ', e.nombre, e.ape_pat, e.ape_mat) AS nombre_entrenador,
                        -- Subconsulta para el total pagado
                        (SELECT SUM(pago.monto) 
                         FROM tb_pagos AS pago 
                         WHERE pago.id_asesoria_fk = a.id_asesoria 
                         AND pago.tipo_pago = 'asesoria') AS total_pagado
                      FROM tb_asesorias a
                      LEFT JOIN tb_clientes c ON a.id_cliente = c.id_cliente
                      LEFT JOIN tb_entrenadores e ON a.id_entrenador = e.id_entrenador
                      WHERE a.id_asesoria = :id_asesoria_get 
                      AND a.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";

    $query_asesoria = $pdo->prepare($sql_asesoria);
    $query_asesoria->bindParam(':id_asesoria_get', $id_asesoria_get, PDO::PARAM_INT);
    $query_asesoria->execute();
    $asesoria_data = $query_asesoria->fetch(PDO::FETCH_ASSOC);

    if (!$asesoria_data) {
        echo "Error: Asesoría no encontrada o no pertenece a este gimnasio.";
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
    include('generar_boleta_asesoria_html.php'); 
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    
    // Tamaño de papel: 80mm ancho (227 puntos) x Alto dinámico
    $dompdf->setPaper(array(0, 0, 227, 840), 'portrait'); 
    $dompdf->render();

    // Enviar al navegador
    $dompdf->stream("boleta_asesoria_".$id_asesoria_get.".pdf", array("Attachment" => false));

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