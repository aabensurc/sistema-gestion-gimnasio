<?php

// Este archivo se incluye en asesorias/show.php para cargar los datos de una asesoría específica.
// Se asume que config.php ya ha sido incluido por el archivo principal (show.php)
// y que la variable $pdo está disponible.

// Obtener el ID de la asesoría de la URL
$id_asesoria_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_asesoria_get)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de asesoría no proporcionado.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/');
    exit();
}

try {
    // Consulta para obtener los datos principales de la asesoría
    $sql_asesoria = "SELECT
                    a.id_asesoria,
                    a.id_cliente,
                    CONCAT_WS(' ', c.nombres, c.ape_pat, c.ape_mat) AS nombre_completo_cliente,
                    a.id_entrenador,
                    CONCAT_WS(' ', e.nombre, e.ape_pat, e.ape_mat) AS nombre_completo_entrenador,
                    a.monto_final,
                    a.fecha_inicio,
                    a.fecha_fin,
                    -- Subconsulta para sumar todos los montos de los pagos de esta asesoría
                    (SELECT SUM(pago.monto)
                     FROM tb_pagos AS pago
                     WHERE pago.id_asesoria_fk = a.id_asesoria
                     AND pago.tipo_pago = 'asesoria') AS total_pagado
                  FROM
                    tb_asesorias AS a
                  LEFT JOIN
                    tb_clientes AS c ON a.id_cliente = c.id_cliente
                  LEFT JOIN
                    tb_entrenadores AS e ON a.id_entrenador = e.id_entrenador
                  WHERE a.id_asesoria = :id_asesoria_get
                  AND c.id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"; // Asegurar que la asesoría pertenece al gimnasio de la sesión

    $query_asesoria = $pdo->prepare($sql_asesoria);
    $query_asesoria->bindParam(':id_asesoria_get', $id_asesoria_get, PDO::PARAM_INT);
    $query_asesoria->execute();
    $asesoria_data = $query_asesoria->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró la asesoría, redirigir
    if (!$asesoria_data) {
        session_start();
        $_SESSION['mensaje'] = "Error: Asesoría no encontrada.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/');
        exit();
    }

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al cargar la asesoría: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/');
    exit();
}

// La variable $asesoria_data ahora está disponible para la interfaz show.php
