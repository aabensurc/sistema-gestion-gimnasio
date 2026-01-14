<?php
// Incluye la configuración y sesión (ajusta las rutas si es necesario)

include('../../../app/config.php');
include('../../../layout/sesion.php');

// Define el ID del cliente y la fecha actual
$id_cliente = $_POST['id_cliente'] ?? null;
$fecha_hoy = date('Y-m-d');
$fecha_inicio_valor = $fecha_hoy; // Valor por defecto: hoy

if ($id_cliente) {
    // 1. Obtener la fecha fin de la última matrícula del cliente
    $sql_ultima_matricula = "SELECT MAX(fecha_fin) AS fecha_fin FROM tb_matriculas WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio";
    $query_ultima_matricula = $pdo->prepare($sql_ultima_matricula);
    $query_ultima_matricula->bindParam(':id_cliente', $id_cliente);
    $query_ultima_matricula->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_ultima_matricula->execute();
    $resultado = $query_ultima_matricula->fetch(PDO::FETCH_ASSOC);

    if ($resultado && $resultado['fecha_fin']) {
        $fecha_fin_ultima_matricula = $resultado['fecha_fin'];
        
        // 2. Determinar la fecha de inicio inicial
        if ($fecha_fin_ultima_matricula >= $fecha_hoy) {
            // Caso: Matrícula Vigente -> Fecha inicio es el día siguiente a la fecha fin
            $fecha_inicio_valor = date('Y-m-d', strtotime($fecha_fin_ultima_matricula . ' +1 day'));
        } else {
            // Caso: Matrícula Vencida -> Fecha inicio es Hoy
            $fecha_inicio_valor = $fecha_hoy;
        }
    } 
    // Caso Nuevo Cliente: $fecha_fin_ultima_matricula es NULL, $fecha_inicio_valor se mantiene como $fecha_hoy.
}

// Devuelve la fecha de inicio calculada (solo la cadena de fecha)
echo $fecha_inicio_valor;
?>