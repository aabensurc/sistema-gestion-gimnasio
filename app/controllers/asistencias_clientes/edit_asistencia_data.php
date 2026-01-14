<?php

// Este archivo se incluye en asistencias_clientes/edit.php para cargar los datos de una asistencia específica.
// Se asume que config.php ya ha sido incluido por el archivo principal (edit.php)
// y que la variable $pdo está disponible.

// Obtener el ID de la asistencia de la URL
$id_asistencia_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_asistencia_get)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de asistencia no proporcionado para edición.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/');
    exit();
}

try {
    // Consulta para obtener los datos de la asistencia
    $sql_asistencia = "SELECT
                        id_asistencia,
                        id_cliente,
                        fecha_asistencia,
                        hora_entrada,
                        fyh_creacion,
                        fyh_actualizacion
                      FROM
                        tb_asistencias_clientes
                      WHERE id_asistencia = :id_asistencia_get AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"; // <-- FILTRO DE GIMNASIO AÑADIDO

    $query_asistencia = $pdo->prepare($sql_asistencia);
    $query_asistencia->bindParam(':id_asistencia_get', $id_asistencia_get, PDO::PARAM_INT);
    $query_asistencia->execute();
    $asistencia_data = $query_asistencia->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró la asistencia, redirigir
    if (!$asistencia_data) {
        session_start();
        $_SESSION['mensaje'] = "Error: Asistencia no encontrada para edición.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asistencias_clientes/');
        exit();
    }

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al cargar la asistencia para edición: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/');
    exit();
}

// La variable $asistencia_data ahora está disponible para la interfaz edit.php
