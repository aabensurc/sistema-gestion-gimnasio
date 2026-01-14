<?php

// Este archivo se incluirá en update.php para cargar los datos de la matrícula

// Obtener el ID de la matrícula de la URL
$id_matricula_get = $_GET['id'];

// Consulta para obtener los datos de la matrícula, incluyendo cliente y plan
$sql_matricula = "SELECT
                    m.id_matricula,
                    m.id_cliente,
                    m.id_plan,
                    m.fecha_inicio,
                    m.fecha_fin,
                    m.descuento,
                    m.monto_final,
                    c.nombres AS nombre_cliente,
                    c.ape_pat AS ape_pat_cliente,
                    c.ape_mat AS ape_mat_cliente,
                    p.nombre AS nombre_plan,
                    p.precio AS precio_plan,
                    p.tipo_duracion,
                    p.duracion_meses,
                    p.duracion_dias,
                    p.fecha_fin AS fecha_fin_plan_fija
                  FROM
                    tb_matriculas AS m
                  LEFT JOIN
                    tb_clientes AS c ON m.id_cliente = c.id_cliente
                  LEFT JOIN
                    tb_planes AS p ON m.id_plan = p.id_plan
                  WHERE m.id_matricula = :id_matricula_get
                  AND m.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"; // <-- FILTRO AÑADIDO

$query_matricula = $pdo->prepare($sql_matricula);
$query_matricula->bindParam(':id_matricula_get', $id_matricula_get, PDO::PARAM_INT);
$query_matricula->execute();
$matricula_data = $query_matricula->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró la matrícula
if (!$matricula_data) {
    // Si no se encuentra la matrícula, redirigir o mostrar un error
    session_start();
    $_SESSION['mensaje'] = "Error: Matrícula no encontrada.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
    exit();
}

// Las variables como $matricula_data['id_cliente'], etc.,
// ahora estarán disponibles en la interfaz update.php
