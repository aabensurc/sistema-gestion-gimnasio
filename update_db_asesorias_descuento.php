<?php
include('app/config.php');

try {
    $checkSql = "SHOW COLUMNS FROM tb_asesorias LIKE 'descuento'";
    $checkQuery = $pdo->prepare($checkSql);
    $checkQuery->execute();

    if ($checkQuery->rowCount() == 0) {
        $sql = "ALTER TABLE tb_asesorias ADD COLUMN descuento DECIMAL(10,2) DEFAULT 0 AFTER monto_final";
        $pdo->exec($sql);
        echo "Columna 'descuento' agregada exitosamente a 'tb_asesorias'.<br>";
    } else {
        echo "La columna 'descuento' ya existe en 'tb_asesorias'.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>