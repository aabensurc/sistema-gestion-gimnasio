<?php

// Este archivo se incluye en productos/show.php para cargar los datos de un producto específico.
// Se asume que config.php ya ha sido incluido por el archivo principal (show.php)
// y que la variable $pdo está disponible.

// Obtener el ID del producto de la URL
$id_producto_get = $_GET['id'] ?? null;

// Verificar si se recibió un ID válido
if (empty($id_producto_get)) {
    session_start();
    $_SESSION['mensaje'] = "Error: ID de producto no proporcionado.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/');
    exit();
}

try {
    // Consulta para obtener los datos del producto
    $sql_producto = "SELECT
                        id_producto,
                        nombre,
                        descripcion,
                        precio_venta,
                        stock,
                        foto
                        -- fyh_creacion, -- Eliminado
                        -- fyh_actualizacion -- Eliminado
                      FROM
                        tb_productos
                      WHERE id_producto = :id_producto_get
                      AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"; // Asegurar que el producto pertenece al gimnasio de la sesión

    $query_producto = $pdo->prepare($sql_producto);
    $query_producto->bindParam(':id_producto_get', $id_producto_get, PDO::PARAM_INT);
    $query_producto->execute();
    $producto_data = $query_producto->fetch(PDO::FETCH_ASSOC);

    // Si no se encontró el producto, redirigir
    if (!$producto_data) {
        session_start();
        $_SESSION['mensaje'] = "Error: Producto no encontrado.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/productos/');
        exit();
    }

} catch (PDOException $e) {
    // Manejo de errores de base de datos
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al cargar el producto: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/productos/');
    exit();
}

// La variable $producto_data ahora está disponible para la interfaz show.php
