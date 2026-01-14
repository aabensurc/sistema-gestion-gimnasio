<?php

// Este archivo se incluye en entrenadores/edit.php para cargar los datos de un entrenador específico.
// Se asume que config.php ya ha sido incluido por el archivo principal (edit.php)
// y que la variable $pdo está disponible.

// Obtener el ID del entrenador de la URL
$id_entrenador_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_entrenador_get)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de entrenador no proporcionado para edición.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/');
    exit();
}

try {
    // Consulta para obtener los datos del entrenador
    $sql_entrenador = "SELECT
                        id_entrenador,
                        nombre,
                        ape_pat,
                        ape_mat,
                        dni,
                        telefono,
                        email,
                        foto,
                        fyh_creacion,
                        fyh_actualizacion
                      FROM
                        tb_entrenadores
                      WHERE id_entrenador = :id_entrenador_get AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'";

    $query_entrenador = $pdo->prepare($sql_entrenador);
    $query_entrenador->bindParam(':id_entrenador_get', $id_entrenador_get, PDO::PARAM_INT);
    $query_entrenador->execute();
    $entrenador_data = $query_entrenador->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró el entrenador, redirigir
    if (!$entrenador_data) {
        session_start();
        $_SESSION['mensaje'] = "Error: Entrenador no encontrado para edición.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/');
        exit();
    }

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al cargar el entrenador para edición: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/');
    exit();
}

// La variable $entrenador_data ahora está disponible para la interfaz edit.php
