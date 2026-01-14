<?php

// Este archivo se incluye en entrenadores/show.php para cargar los datos de un entrenador específico.
// Se asume que config.php ya ha sido incluido por el archivo principal (show.php)
// y que la variable $pdo está disponible.

// Obtener el ID del entrenador de la URL
$id_entrenador_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_entrenador_get)) {
    $_SESSION['mensaje'] = "Error: ID de entrenador no proporcionado.";
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
                      WHERE id_entrenador = :id_entrenador_get";

    $query_entrenador = $pdo->prepare($sql_entrenador);
    $query_entrenador->bindParam(':id_entrenador_get', $id_entrenador_get, PDO::PARAM_INT);
    $query_entrenador->execute();
    $entrenador_data = $query_entrenador->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró el entrenador, redirigir
    if (!$entrenador_data) {
        $_SESSION['mensaje'] = "Error: Entrenador no encontrado.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/entrenadores/');
        exit();
    }

    // Consulta para obtener los clientes asignados a este entrenador
    $sql_clientes = "SELECT 
                        CONCAT(c.nombres, ' ', c.ape_pat, ' ', c.ape_mat) as nombre_cliente,
                        a.fecha_inicio,
                        a.fecha_fin
                     FROM tb_asesorias a
                     INNER JOIN tb_clientes c ON a.id_cliente = c.id_cliente
                     WHERE a.id_entrenador = :id_entrenador AND a.estado = 1
                     ORDER BY a.fecha_fin DESC";

    $query_clientes = $pdo->prepare($sql_clientes);
    $query_clientes->bindParam(':id_entrenador', $id_entrenador_get, PDO::PARAM_INT);
    $query_clientes->execute();
    $clientes_asignados = $query_clientes->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // Manejo de errores de base de datos
    $_SESSION['mensaje'] = "Error de base de datos al cargar el entrenador: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/entrenadores/');
    exit();
}

// La variable $entrenador_data ahora está disponible para la interfaz show.php
