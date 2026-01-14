<?php

// Este archivo se incluye en productos/index.php, productos/create.php, productos/edit.php, productos/show.php
// y también en ventas/create.php, ventas/edit.php para obtener el listado de productos.
// Se asume que config.php ya ha sido incluido por el archivo principal
// y que la variable $pdo está disponible.

$sql_productos = "SELECT
    id_producto,
    nombre,
    descripcion,
    precio_venta,
    stock,
    foto, -- Columna 'foto' incluida
    fyh_creacion,
    fyh_actualizacion
FROM
    tb_productos
WHERE
    id_gimnasio = '$_SESSION[id_gimnasio_sesion]' 
ORDER BY nombre ASC;"; // Ordenar por nombre alfabéticamente (se muestran todos los productos)

try {
    $query_productos = $pdo->prepare($sql_productos);
    $query_productos->execute();
    $productos_datos = $query_productos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error al obtener listado de productos: " . $e->getMessage());
    $productos_datos = []; // Retornar un array vacío
}

?>
