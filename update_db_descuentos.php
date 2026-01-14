<?php
include('app/config.php');

try {
    // 1. Verificar si la columna ya existe
    $checkSql = "SHOW COLUMNS FROM tb_gimnasios LIKE 'clave_descuento'";
    $checkQuery = $pdo->prepare($checkSql);
    $checkQuery->execute();

    if ($checkQuery->rowCount() == 0) {
        // 2. Si no existe, agregarla
        $sql = "ALTER TABLE tb_gimnasios ADD COLUMN clave_descuento VARCHAR(255) DEFAULT '12345'";
        $pdo->exec($sql);
        echo "Columna 'clave_descuento' agregada exitosamente a 'tb_gimnasios'.<br>";

        // 3. Establecer valor por defecto '12345' para registros existentes (aunque el DEFAULT ya lo hace para nuevos, aseguramos los viejos)
        $updateSql = "UPDATE tb_gimnasios SET clave_descuento = '12345' WHERE clave_descuento IS NULL OR clave_descuento = ''";
        $pdo->exec($updateSql);
        echo "Valores por defecto actualizados.<br>";
    } else {
        echo "La columna 'clave_descuento' ya existe.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>