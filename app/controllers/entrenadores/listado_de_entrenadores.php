<?php

// Este archivo se incluye en entrenadores/index.php (y asesorias/create.php, edit.php)
// para obtener el listado de entrenadores.
// Se asume que config.php ya ha sido incluido por el archivo principal
// y que la variable $pdo está disponible.

$sql_entrenadores = "SELECT
    id_entrenador,
    nombre,
    ape_pat,
    ape_mat,
    dni,      -- Añadido
    telefono, -- Añadido
    email,    -- Añadido
    foto      -- Añadido
FROM
    tb_entrenadores
WHERE id_gimnasio = '$_SESSION[id_gimnasio_sesion]'
ORDER BY nombre ASC;"; // Ordenar por nombre alfabéticamente

try {
    $query_entrenadores = $pdo->prepare($sql_entrenadores);
    $query_entrenadores->execute();
    $entrenadores_datos = $query_entrenadores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error al obtener listado de entrenadores: " . $e->getMessage());
    $entrenadores_datos = []; // Retornar un array vacío
}

?>
