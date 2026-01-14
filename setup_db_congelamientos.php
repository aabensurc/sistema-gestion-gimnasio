<?php
include(__DIR__ . '/app/config.php');

$sql = "
CREATE TABLE IF NOT EXISTS `tb_congelamientos` (
  `id_congelamiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_matricula` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` text DEFAULT NULL,
  `dias_congelados` int(11) NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `estado` int(1) DEFAULT 1 COMMENT '1=Activo, 0=Anulado',
  PRIMARY KEY (`id_congelamiento`),
  KEY `id_matricula` (`id_matricula`),
  CONSTRAINT `fk_congelamiento_matricula` FOREIGN KEY (`id_matricula`) REFERENCES `tb_matriculas` (`id_matricula`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

try {
  $pdo->exec($sql);
  echo "Tabla tb_congelamientos creada correctamente.";
} catch (PDOException $e) {
  echo "Error al crear la tabla: " . $e->getMessage();
}
?>