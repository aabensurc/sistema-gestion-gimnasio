<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// VERIFICAR CAJA ABIERTA
include('../caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de agregar un pago.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/asesorias/');
    exit;
}

// Obtener los datos enviados desde el formulario del modal
$id_asesoria = $_POST['id_asesoria'];
$id_cliente = $_POST['id_cliente'] === '' ? NULL : $_POST['id_cliente']; // Puede ser NULL si el cliente de la asesoría es NULL
$monto_pagado = $_POST['monto_pagado'];
$metodo_pago = $_POST['metodo_pago'];

// Tipo de pago fijo para asesorías
$tipo_pago = 'asesoria';

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

try {
    // 0. Validar que el monto sea mayor a 0
    if ($monto_pagado <= 0) {
        $_SESSION['mensaje'] = "Error: El monto de pago debe ser mayor a 0.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/');
        exit();
    }

    // 1. Validar que el monto no exceda la deuda
    $sql_validacion = "SELECT 
        a.monto_final,
        (SELECT COALESCE(SUM(p.monto), 0) FROM tb_pagos p WHERE p.id_asesoria_fk = a.id_asesoria AND p.estado = 1) as total_pagado
    FROM tb_asesorias a
    WHERE a.id_asesoria = :id_asesoria";

    $query_val = $pdo->prepare($sql_validacion);
    $query_val->bindParam(':id_asesoria', $id_asesoria);
    $query_val->execute();
    $datos_asesoria = $query_val->fetch(PDO::FETCH_ASSOC);

    if ($datos_asesoria) {
        $monto_final = $datos_asesoria['monto_final'];
        $total_pagado = $datos_asesoria['total_pagado'];
        $deuda_pendiente = $monto_final - $total_pagado;

        if ($monto_pagado > $deuda_pendiente) {
            $_SESSION['mensaje'] = "Error: El monto ingresado (S/. $monto_pagado) supera la deuda pendiente (S/. " . number_format($deuda_pendiente, 2) . ").";
            $_SESSION['icono'] = "error";
            header('Location: ' . $URL . '/asesorias/');
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "Error: No se encontró la asesoría.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/');
        exit();
    }

    // Preparar la sentencia SQL para insertar un nuevo pago
    // Actualizado para módulo Caja: Se inserta id_usuario
    $sentencia = $pdo->prepare("INSERT INTO tb_pagos
                                (id_cliente, id_usuario, tipo_pago, id_matricula_fk, id_venta_fk, id_asesoria_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                VALUES (:id_cliente, :id_usuario, :tipo_pago, :id_matricula_fk, :id_venta_fk, :id_asesoria_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

    // Valores para FKs que no aplican a pagos de asesoría
    $id_matricula_fk = NULL;
    $id_venta_fk = NULL;

    // Vincular los parámetros
    $id_usuario_sesion = $_SESSION['id_usuario_global'];
    $sentencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia->bindParam(':id_usuario', $id_usuario_sesion, PDO::PARAM_INT);
    $sentencia->bindParam(':tipo_pago', $tipo_pago);
    $sentencia->bindParam(':id_matricula_fk', $id_matricula_fk, PDO::PARAM_INT);
    $sentencia->bindParam(':id_venta_fk', $id_venta_fk, PDO::PARAM_INT);
    $sentencia->bindParam(':id_asesoria_fk', $id_asesoria, PDO::PARAM_INT);
    $sentencia->bindParam(':metodo_pago', $metodo_pago);
    $sentencia->bindParam(':monto', $monto_pagado);
    $sentencia->bindParam(':fecha_hora', $fechaHora);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        session_start();
        $_SESSION['mensaje'] = "Pago registrado correctamente para la asesoría.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asesorias/'); // Redirigir de vuelta al listado
    } else {
        session_start();
        $_SESSION['mensaje'] = "Error: No se pudo registrar el pago en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asesorias/');
    }
} catch (PDOException $e) {
    session_start();
    $_SESSION['mensaje'] = "Error de base de datos al registrar pago de asesoría: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asesorias/');
}

?>