<?php
session_start();
include('../../config.php');

// VERIFICAR CAJA ABIERTA
include('../caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de anular un registro.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/matriculas/');
    exit;
}

$id_matricula = $_POST['id_matricula'];



// Anular matrícula
$sentencia = $pdo->prepare("UPDATE tb_matriculas SET estado = 0 WHERE id_matricula = :id_matricula AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'");
$sentencia->bindParam('id_matricula', $id_matricula);

if ($sentencia->execute()) {
    // Anular pagos asociados
    $sentencia_pagos = $pdo->prepare("UPDATE tb_pagos SET estado = 0 WHERE id_matricula_fk = :id_matricula");
    $sentencia_pagos->bindParam('id_matricula', $id_matricula);
    $sentencia_pagos->execute();

    // Anular congelamientos asociados
    $sentencia_congelamientos = $pdo->prepare("UPDATE tb_congelamientos SET estado = 0 WHERE id_matricula = :id_matricula");
    $sentencia_congelamientos->bindParam('id_matricula', $id_matricula);
    $sentencia_congelamientos->execute();

    // Anular cronograma de pagos asociado (si existe)
    // Se anulan tanto las cuotas pendientes como las pagadas para reflejar la anulación total
    $sentencia_cronograma = $pdo->prepare("UPDATE tb_cronograma_pagos SET estado_cuota = 'Anulado' WHERE id_matricula_fk = :id_matricula");
    $sentencia_cronograma->bindParam('id_matricula', $id_matricula);
    $sentencia_cronograma->execute();

    $_SESSION['mensaje'] = "Se anuló la matricula de manera correcta";
    $_SESSION['icono'] = "success";
    // header('Location: '.$URL.'/bases/');

    ?>
    <script>
        location.href = "<?php echo $URL; ?>/matriculas";
    </script>
    <?php

} else {


    $_SESSION['mensaje'] = "Error no se pudo guardar en la base de datos";
    $_SESSION['icono'] = "error";
    //header('Location: '.$URL.'/bases/update.php?id='.$id_rol);
    ?>
    <script>
        location.href = "<?php echo $URL; ?>/matriculas";
    </script>
    <?php

}


