<?php
include('app/config.php');

try {
    $sql = "ALTER TABLE tb_planes ADD COLUMN estado TINYINT(1) DEFAULT 1 AFTER precio";
    $pdo->exec($sql);
    echo "Columna 'estado' agregada correctamente a tb_planes.";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') { // Duplicate column error
        echo "La columna 'estado' ya existe.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>