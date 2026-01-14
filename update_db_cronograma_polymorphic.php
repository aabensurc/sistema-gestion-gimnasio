<?php
include('app/config.php');

try {
    // Add id_venta_fk
    $sql1 = "ALTER TABLE tb_cronograma_pagos ADD COLUMN id_venta_fk INT(11) NULL AFTER id_pago_fk";
    try {
        $pdo->exec($sql1);
        echo "Columna id_venta_fk agregada.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false)
            echo "id_venta_fk ya existe.\n";
        else
            echo "Error id_venta_fk: " . $e->getMessage() . "\n";
    }

    // Add id_asesoria_fk
    $sql2 = "ALTER TABLE tb_cronograma_pagos ADD COLUMN id_asesoria_fk INT(11) NULL AFTER id_venta_fk";
    try {
        $pdo->exec($sql2);
        echo "Columna id_asesoria_fk agregada.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false)
            echo "id_asesoria_fk ya existe.\n";
        else
            echo "Error id_asesoria_fk: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error general: " . $e->getMessage();
}
?>