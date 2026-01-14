<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos enviados desde el formulario de edición
$id_matricula = $_POST['id_matricula'];
$id_cliente = $_POST['id_cliente'];
$id_plan = $_POST['id_plan'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$descuento = $_POST['descuento'];
$monto_final = $_POST['monto_final']; // Este es el monto final calculado en el frontend

// Obtener la fecha y hora actual para fyh_actualizacion
$fechaHora = date('Y-m-d H:i:s');

try {
    // Preparar la sentencia SQL para actualizar la matrícula
    $sentencia = $pdo->prepare("UPDATE tb_matriculas
                                SET
                                    id_cliente = :id_cliente,
                                    id_plan = :id_plan,
                                    fecha_inicio = :fecha_inicio,
                                    fecha_fin = :fecha_fin,
                                    descuento = :descuento,
                                    monto_final = :monto_final,
                                    fyh_actualizacion = :fyh_actualizacion
                                WHERE id_matricula = :id_matricula
                                AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'"); // <-- FILTRO AÑADIDO

    // Vincular los parámetros con los valores recibidos
    $sentencia->bindParam(':id_cliente', $id_cliente);
    $sentencia->bindParam(':id_plan', $id_plan);
    $sentencia->bindParam(':fecha_inicio', $fecha_inicio);
    $sentencia->bindParam(':fecha_fin', $fecha_fin);
    $sentencia->bindParam(':descuento', $descuento);
    $sentencia->bindParam(':monto_final', $monto_final);
    $sentencia->bindParam(':fyh_actualizacion', $fechaHora);
    $sentencia->bindParam(':id_matricula', $id_matricula, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($sentencia->execute()) {
        
        $_SESSION['mensaje'] = "Se actualizó la matrícula correctamente";
        $_SESSION['icono'] = "success";
        // Redirigir a la página principal de matrículas
        header('Location: ' . $URL . '/matriculas/');
    } else {
       
        $_SESSION['mensaje'] = "Error: No se pudo actualizar la matrícula en la base de datos.";
        $_SESSION['icono'] = "error";
        // Redirigir de vuelta al formulario de edición si hay un error
        header('Location: ' . $URL . '/matriculas/update.php?id=' . $id_matricula);
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción de PDO (errores de base de datos)

    $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/update.php?id=' . $id_matricula);
}

?>
