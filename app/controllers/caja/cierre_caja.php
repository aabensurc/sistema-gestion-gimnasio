<?php
include('../../config.php');
session_start();

$id_usuario_sesion = $_SESSION['id_usuario_global'];
$id_gimnasio_sesion = $_SESSION['id_gimnasio_sesion'];
$id_caja = $_POST['id_caja']; // ID oculto
$monto_cierre_usuario = $_POST['monto_cierre']; // Lo que contó el usuario
$observaciones = $_POST['observaciones'] ?? '';
$fecha_cierre = date('Y-m-d H:i:s');

try {
    // 1. Obtener datos de la caja actual para saber la fecha de apertura
    $sql_caja = "SELECT fecha_apertura FROM tb_caja WHERE id_caja = :id_caja AND id_usuario = :id_usuario";
    $stmt_caja = $pdo->prepare($sql_caja);
    $stmt_caja->bindParam(':id_caja', $id_caja);
    $stmt_caja->bindParam(':id_usuario', $id_usuario_sesion);
    $stmt_caja->execute();
    $datos_caja = $stmt_caja->fetch(PDO::FETCH_ASSOC);

    if (!$datos_caja) {
        $_SESSION['mensaje'] = "No se encontró la caja a cerrar.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/caja/');
        exit;
    }

    $fecha_apertura = $datos_caja['fecha_apertura'];

    // 2. Calcular monto del sistema (Suma de pagos realizados por ESTE usuario desde la apertura hasta ahora)
    // Solo sumamos pagos activos (estado = 1)
    $sql_pagos = "SELECT SUM(monto) as total_sistema, COUNT(*) as cantidad 
                  FROM tb_pagos 
                  WHERE id_usuario = :id_usuario 
                  AND fecha_hora >= :fecha_apertura 
                  AND estado = 1";

    $stmt_pagos = $pdo->prepare($sql_pagos);
    $stmt_pagos->bindParam(':id_usuario', $id_usuario_sesion);
    $stmt_pagos->bindParam(':fecha_apertura', $fecha_apertura);
    $stmt_pagos->execute();
    $data_pagos = $stmt_pagos->fetch(PDO::FETCH_ASSOC);

    $monto_sistema = $data_pagos['total_sistema'] ?? 0;
    $cantidad_pagos = $data_pagos['cantidad'] ?? 0;

    // 3. Calcular diferencia
    // Diferencia = Lo que hay fisicamente (monto_cierre_usuario) - Lo que deberia haber (monto_sistema)
    // Nota: El monto inicial no se suma usualmente al "total vendido", se maneja aparte como "fondo de caja".
    // Pero la diferencia suele ser sobre la recaudación.
    // Asumiremos: Diferencia = Monto Cierre - (Monto Apertura + Monto Ventas Sistema)
    // Si el usuario cuenta TODO el dinero en caja incluyendo el inicial.
    // OJO: Depende de como cuente el usuario. Usualmente cuenta todo.
    // Validemos con el usuario o usemos estandar: Diferencia = (Monto Cierre Real) - (Monto Apertura + Ventas Sistema)

    // Obtener monto apertura para el calculo
    $sql_apertura = "SELECT monto_apertura FROM tb_caja WHERE id_caja = :id_caja";
    $stmt_ap = $pdo->prepare($sql_apertura);
    $stmt_ap->bindParam(':id_caja', $id_caja);
    $stmt_ap->execute();
    $m_ap = $stmt_ap->fetchColumn();

    $total_esperado = $m_ap + $monto_sistema;
    $diferencia = $monto_cierre_usuario - $total_esperado;

    // 4. Actualizar cierre
    $sql_update = "UPDATE tb_caja 
                   SET fecha_cierre = :fecha_cierre,
                       monto_cierre = :monto_cierre,
                       monto_sistema = :monto_sistema,
                       diferencia = :diferencia,
                       cantidad_pagos = :cantidad_pagos,
                       observaciones = :observaciones,
                       estado = 0, -- Cerrada
                       fyh_actualizacion = :fecha_cierre
                   WHERE id_caja = :id_caja";

    $stmt_up = $pdo->prepare($sql_update);
    $stmt_up->bindParam(':fecha_cierre', $fecha_cierre);
    $stmt_up->bindParam(':monto_cierre', $monto_cierre_usuario);
    $stmt_up->bindParam(':monto_sistema', $monto_sistema); // Guardamos lo que se vendió
    $stmt_up->bindParam(':diferencia', $diferencia);
    $stmt_up->bindParam(':cantidad_pagos', $cantidad_pagos);
    $stmt_up->bindParam(':observaciones', $observaciones);
    $stmt_up->bindParam(':id_caja', $id_caja);

    if ($stmt_up->execute()) {
        $_SESSION['mensaje'] = "Caja cerrada correctamente. Diferencia: S/. " . number_format($diferencia, 2);
        // Alertar si hay diferencia grande?
        $_SESSION['icono'] = ($diferencia == 0) ? "success" : "warning";
    } else {
        $_SESSION['mensaje'] = "Error al cerrar la caja.";
        $_SESSION['icono'] = "error";
    }

} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['icono'] = "error";
}

header('Location: ' . $URL . '/caja/');
?>