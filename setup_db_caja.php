<?php
include('app/config.php');

try {
    echo "Iniciando configuración de Base de Datos para Módulo Caja...<br>";

    // 1. Crear tabla tb_caja
    $sql_create_table = "
    CREATE TABLE IF NOT EXISTS `tb_caja` (
      `id_caja` INT(11) NOT NULL AUTO_INCREMENT,
      `id_usuario` INT(11) NOT NULL,
      `id_gimnasio` INT(11) NOT NULL,
      `fecha_apertura` DATETIME NOT NULL,
      `fecha_cierre` DATETIME DEFAULT NULL,
      `monto_apertura` DECIMAL(10,2) NOT NULL,
      `monto_cierre` DECIMAL(10,2) DEFAULT NULL,
      `monto_sistema` DECIMAL(10,2) DEFAULT NULL,
      `diferencia` DECIMAL(10,2) DEFAULT NULL,
      `cantidad_pagos` INT(11) DEFAULT 0,
      `estado` INT(1) DEFAULT 1, 
      `observaciones` TEXT DEFAULT NULL,
      `fyh_creacion` DATETIME NOT NULL,
      `fyh_actualizacion` DATETIME NOT NULL,
      PRIMARY KEY (`id_caja`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    $pdo->exec($sql_create_table);
    echo "Tabla 'tb_caja' creada o verificada correctamente.<br>";

    // 2. Modificar tb_pagos para agregar id_usuario
    // Verificamos si la columna ya existe para evitar error
    $column_exists = false;
    $sql_check_col = "SHOW COLUMNS FROM `tb_pagos` LIKE 'id_usuario'";
    $stmt = $pdo->prepare($sql_check_col);
    $stmt->execute();
    if ($stmt->fetch()) {
        $column_exists = true;
        echo "La columna 'id_usuario' ya existe en 'tb_pagos'.<br>";
    }

    if (!$column_exists) {
        $sql_alter = "ALTER TABLE `tb_pagos` ADD COLUMN `id_usuario` INT(11) DEFAULT NULL after `id_cliente`";
        $pdo->exec($sql_alter);
        echo "Columna 'id_usuario' agregada a 'tb_pagos'.<br>";
    }

    echo "<b>Migración de Caja completada con éxito.</b>";

} catch (PDOException $e) {
    echo "Error en la migración: " . $e->getMessage();
}
?>