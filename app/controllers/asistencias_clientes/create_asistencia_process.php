<?php
session_start();
include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

// Obtener los datos del formulario
$id_cliente = $_POST['id_cliente'];
$fecha_asistencia = $_POST['fecha_asistencia'];
$hora_entrada = $_POST['hora_entrada'];

// Obtener la fecha y hora actual para fyh_creacion
$fyh_creacion = date('Y-m-d H:i:s');

// --- VERIFICAR SI HAY CONGELAMIENTO ACTIVO ---
$sentencia_congelamiento = $pdo->prepare("
    SELECT c.fecha_fin 
    FROM tb_congelamientos c 
    INNER JOIN tb_matriculas m ON c.id_matricula = m.id_matricula 
    WHERE m.id_cliente = :id_cliente 
      AND m.estado = 1 
      AND c.estado = 1 
      AND :fecha_asistencia BETWEEN c.fecha_inicio AND c.fecha_fin
    LIMIT 1
");
$sentencia_congelamiento->bindParam(':id_cliente', $id_cliente);
$sentencia_congelamiento->bindParam(':fecha_asistencia', $fecha_asistencia);
$sentencia_congelamiento->execute();
$congelamiento = $sentencia_congelamiento->fetch(PDO::FETCH_ASSOC);

if ($congelamiento) {
    $_SESSION['mensaje'] = "ACCESO DENEGADO: La membresía está congelada hasta el " . date('d/m/Y', strtotime($congelamiento['fecha_fin']));
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/create.php');
    exit();
}
// --- FIN VERIFICACION ---
$fyh_actualizacion = NULL; // Inicialmente NULL, se actualizará si se edita

try {
    // Preparar la sentencia SQL para insertar la nueva asistencia
    $sentencia = $pdo->prepare("INSERT INTO tb_asistencias_clientes
                                (id_cliente, fecha_asistencia, hora_entrada, fyh_creacion, fyh_actualizacion, id_gimnasio)
                                VALUES (:id_cliente, :fecha_asistencia, :hora_entrada, :fyh_creacion, :fyh_actualizacion, :id_gimnasio)"); // <-- AÑADIDO id_gimnasio

    // Vincular los parámetros
    $sentencia->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $sentencia->bindParam(':fecha_asistencia', $fecha_asistencia);
    $sentencia->bindParam(':hora_entrada', $hora_entrada);
    $sentencia->bindParam(':fyh_creacion', $fyh_creacion);
    $sentencia->bindParam(':fyh_actualizacion', $fyh_actualizacion);
    $sentencia->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion'], PDO::PARAM_INT); // <-- AÑADIDO id_gimnasio

    // Ejecutar la sentencia
    if ($sentencia->execute()) {

        $_SESSION['mensaje'] = "Asistencia registrada correctamente.";
        $_SESSION['icono'] = "success";
        header('Location: ' . $URL . '/asistencias_clientes/'); // Redirigir al listado de asistencias
        exit();
    } else {

        $_SESSION['mensaje'] = "Error: No se pudo registrar la asistencia en la base de datos.";
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/asistencias_clientes/create.php');
        exit();
    }
} catch (PDOException $e) {

    $_SESSION['mensaje'] = "Error de base de datos al registrar asistencia: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/asistencias_clientes/create.php');
    exit();
}

?>