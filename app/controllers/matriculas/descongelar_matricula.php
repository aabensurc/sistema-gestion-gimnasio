<?php
session_start();
include('../../config.php');

$id_matricula = $_POST['id_matricula'];
$accion = $_POST['accion']; // 'cancelar' o 'descongelar'

try {
    $pdo->beginTransaction();

    // 1. Obtener el congelamiento activo
    $sql_freeze = "SELECT * FROM tb_congelamientos WHERE id_matricula = :id_matricula AND estado = 1 LIMIT 1";
    $query_freeze = $pdo->prepare($sql_freeze);
    $query_freeze->bindParam(':id_matricula', $id_matricula);
    $query_freeze->execute();
    $congelamiento = $query_freeze->fetch(PDO::FETCH_ASSOC);

    if (!$congelamiento) {
        throw new Exception("No se encontró un congelamiento activo para esta matrícula.");
    }

    $dias_congelados_original = $congelamiento['dias_congelados'];
    $id_congelamiento = $congelamiento['id_congelamiento'];

    if ($accion == 'cancelar') {
        // --- CASO 1: CANCELAR (ANULAR) ---
        // Se revierte TODO. Como si nunca hubiera pasado.
        // Restamos el TOTAL de días que se sumaron originalmente.

        $dias_a_restar = $dias_congelados_original;

        // Actualizar estado del congelamiento a 0 (Anulado)
        $update_freeze = $pdo->prepare("UPDATE tb_congelamientos SET estado = 0, fyh_actualizacion = NOW() WHERE id_congelamiento = :id_congelamiento");
        $update_freeze->bindParam(':id_congelamiento', $id_congelamiento);
        $update_freeze->execute();

        $_SESSION['mensaje'] = "El congelamiento ha sido anulado correctamente.";

    } elseif ($accion == 'descongelar') {
        // --- CASO 2: DESCONGELAR AHORA ---
        // El cliente regresa hoy. Ajustamos la fecha fin solo por los días que REALMENTE usó.

        $fecha_inicio = new DateTime($congelamiento['fecha_inicio']);
        $hoy = new DateTime();

        // Si hoy es ANTES de la fecha de inicio, es lo mismo que cancelar
        if ($hoy < $fecha_inicio) {
            // Redirigir lógica a cancelar o lanzar error, pero asumamos comportamiento inteligente: cancelar
            $dias_a_restar = $dias_congelados_original;
            $update_freeze = $pdo->prepare("UPDATE tb_congelamientos SET estado = 0, fyh_actualizacion = NOW() WHERE id_congelamiento = :id_congelamiento");
            $update_freeze->bindParam(':id_congelamiento', $id_congelamiento);
            $update_freeze->execute();
            $_SESSION['mensaje'] = "El congelamiento aún no iniciaba, se ha anulado completamente.";
        } else {
            // Calcular días reales transcurridos (incluyendo hoy como descongelado? No, si vuelve hoy, hoy paga/usa. 
            // Entonces congelado fue hasta ayer. O si desbloquea ahora, ya puede entrar.
            // Asumamos: Dias usados = DATEDIFF(hoy, inicio). 
            // Ej: Inicio 01, Hoy 05. 5-1 = 4 días congelados. (01, 02, 03, 04). Hoy 05 ya usa.

            $diff_real = $fecha_inicio->diff($hoy);
            $dias_reales_usados = $diff_real->days;

            // Si dias_reales_usados es 0 (mismo día), es como cancelar.

            // Días que SOBRARON del regalo original
            // Ej: Regalamos 10. Usó 4. Sobran 6.
            // Esos 6 hay que RESTARLOS de la fecha fin, porque se los dimos por adelantado.
            $dias_a_restar = $dias_congelados_original - $dias_reales_usados;

            // Actualizar el congelamiento para reflejar la realidad
            // Fecha fin pasa a ser HOY (o ayer), y dias pasa a ser lo real.
            $update_freeze = $pdo->prepare("UPDATE tb_congelamientos 
                                            SET fecha_fin = CURDATE(), 
                                                dias_congelados = :dias_reales, 
                                                estado = 2, 
                                                fyh_actualizacion = NOW() 
                                            WHERE id_congelamiento = :id_congelamiento");
            // Estado 2 = Finalizado Anticipadamente (Opcional, o dejamos en 1 si consideramos historial valido)
            // Usemos 1 (Activo/Histórico) o 2. Dejémoslo en 0 para 'Anulado', 1 para 'Cumplido Total', 2 para 'Editado/Parcial'. 
            // Simplifiquemos: Estado 1 (Valido). Solo actualizamos fechas.
            // Pero si ya pasó, ya no "bloquea". El bloqueo es por FECHAS. Asi que si fecha_fin es hoy, ya mañana no bloquea.

            $update_freeze = $pdo->prepare("UPDATE tb_congelamientos 
                                            SET fecha_fin = CURDATE(), 
                                                dias_congelados = :dias_reales, 
                                                fyh_actualizacion = NOW() 
                                            WHERE id_congelamiento = :id_congelamiento");

            $update_freeze->bindParam(':dias_reales', $dias_reales_usados);
            $update_freeze->bindParam(':id_congelamiento', $id_congelamiento);
            $update_freeze->execute();

            $_SESSION['mensaje'] = "Membresía descongelada. Se ajustó la fecha de vencimiento.";
        }
    }

    // 2. Actualizar la Matrícula (Restar los días excedentes)
    if ($dias_a_restar > 0) {
        $sentencia_update = $pdo->prepare("UPDATE tb_matriculas 
                                           SET fecha_fin = DATE_SUB(fecha_fin, INTERVAL :dias_a_restar DAY),
                                               fyh_actualizacion = NOW() 
                                           WHERE id_matricula = :id_matricula");
        $sentencia_update->bindParam(':dias_a_restar', $dias_a_restar, PDO::PARAM_INT);
        $sentencia_update->bindParam(':id_matricula', $id_matricula);
        $sentencia_update->execute();
    }

    $pdo->commit();
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/matriculas/');
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/matriculas/');
    exit();
}
?>