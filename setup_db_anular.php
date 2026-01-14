<?php
include('app/config.php');

$tablas = ['tb_pagos', 'tb_ventas', 'tb_matriculas', 'tb_asesorias'];

foreach ($tablas as $tabla) {
    try {
        // Verificar si la columna ya existe
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $tabla LIKE 'estado'");
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            // Agregar columna si no existe
            $sql = "ALTER TABLE $tabla ADD COLUMN estado INT(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado'";
            $pdo->exec($sql);
            echo "Columna 'estado' agregada correctamente a la tabla $tabla.<br>";
        } else {
            echo "La columna 'estado' ya existe en la tabla $tabla.<br>";
        }
    } catch (PDOException $e) {
        echo "Error al modificar la tabla $tabla: " . $e->getMessage() . "<br>";
    }
}
?>
