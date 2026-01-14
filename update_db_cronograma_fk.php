<?php
include('app/config.php');

try {
    $sql = "ALTER TABLE tb_cronograma_pagos ADD COLUMN id_pago_fk INT(11) NULL AFTER id_matricula_fk";
    $pdo->exec($sql);
    echo "Columna id_pago_fk agregada correctamente.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "La columna ya existe.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>