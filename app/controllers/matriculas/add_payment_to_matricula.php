<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// VERIFICAR CAJA ABIERTA
include('../caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de agregar un pago.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/matriculas/');
    exit;
}

// Obtener los datos enviados desde el formulario del modal
$id_matricula = $_POST['id_matricula'];
$id_cliente = $_POST['id_cliente']; // Obtenido del campo oculto en el modal
$monto_pagado = $_POST['monto_pagado'];
$metodo_pago = $_POST['metodo_pago'];

// Tipo de pago fijo para matrículas
$tipo_pago = 'matricula';

// Obtener la fecha y hora actual
$fechaHora = date('Y-m-d H:i:s');

try {
    // 0. Validar que el monto sea mayor a 0
    if ($monto_pagado <= 0) {
        $_SESSION['mensaje'] = "Error: El monto de pago debe ser mayor a 0.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/matriculas/');
        exit();
    }

    // 1. Validar que el monto no exceda la deuda
    $sql_validacion = "SELECT 
        m.monto_final,
        (SELECT COALESCE(SUM(p.monto), 0) FROM tb_pagos p WHERE p.id_matricula_fk = m.id_matricula AND p.estado = 1) as total_pagado
    FROM tb_matriculas m
    WHERE m.id_matricula = :id_matricula";

    $query_val = $pdo->prepare($sql_validacion);
    $query_val->bindParam(':id_matricula', $id_matricula);
    $query_val->execute();
    $datos_matricula = $query_val->fetch(PDO::FETCH_ASSOC);

    if ($datos_matricula) {
        $monto_final = $datos_matricula['monto_final'];
        $total_pagado = $datos_matricula['total_pagado'];
        $deuda_pendiente = $monto_final - $total_pagado;

        // Validamos si el nuevo monto excede lo pendiente
        // Usamos una pequeña tolerancia para flotantes, o simple comparación
        if ($monto_pagado > $deuda_pendiente) {
            $_SESSION['mensaje'] = "Error: El monto ingresado (S/. $monto_pagado) supera la deuda pendiente (S/. " . number_format($deuda_pendiente, 2) . ").";
            $_SESSION['icono'] = "error";
            header('Location: ' . $URL . '/matriculas/');
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "Error: No se encontró la matrícula.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/matriculas/');
        exit();
    }

    // Preparar la sentencia SQL para insertar un nuevo pago
    // Actualizado para módulo Caja: Se inserta id_usuario
    $sentencia = $pdo->prepare("INSERT INTO tb_pagos
                                (id_cliente, id_usuario, tipo_pago, id_matricula_fk, metodo_pago, monto, fecha_hora, id_gimnasio)
                                VALUES (:id_cliente, :id_usuario, :tipo_pago, :id_matricula_fk, :metodo_pago, :monto, :fecha_hora, :id_gimnasio)");

    // Vincular los parámetros
    $id_usuario_sesion = $_SESSION['id_usuario_global']; // Variable de sesión
    $sentencia->bindParam(':id_cliente', $id_cliente);
    $sentencia->bindParam(':id_usuario', $id_usuario_sesion);
    $sentencia->bindParam(':tipo_pago', $tipo_pago);
    $sentencia->bindParam(':id_matricula_fk', $id_matricula, PDO::PARAM_INT);
    $sentencia->bindParam(':metodo_pago', $metodo_pago);
    $sentencia->bindParam(':monto', $monto_pagado);
    $sentencia->bindParam(':fecha_hora', $fechaHora);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {

        $_SESSION['mensaje'] = "Pago registrado correctamente para la matrícula.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/matriculas/'); // Redirigir de vuelta al listado
    } else {

        $_SESSION['mensaje'] = "Error: No se pudo registrar el pago en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/matriculas/');
    }
} catch (PDOException $e) {

    $_SESSION['mensaje'] = "Error de base de datos al registrar pago: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
}

?>