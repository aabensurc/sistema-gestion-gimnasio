<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/caja/verificar_estado_caja.php');

// Si la caja esta abierta, obtenemos el total actual recaudado para mostrarlo en tiempo real
$monto_actual_sistema = 0;
$cantidad_pagos_actual = 0;

if ($caja_abierta) {
    // Consulta para obtener lo recaudado desde la apertura
    // Solo pagos activos
    $id_usuario_sesion = $_SESSION['id_usuario_global'];
    $fecha_apertura = $caja_abierta['fecha_apertura'];

    // Total Sistema
    $sql_resumen = "SELECT SUM(monto) as total_sistema, COUNT(*) as cantidad 
                    FROM tb_pagos 
                    WHERE id_usuario = :id_usuario 
                    AND fecha_hora >= :fecha_apertura 
                    AND estado = 1";
    $query_resumen = $pdo->prepare($sql_resumen);
    $query_resumen->bindParam(':id_usuario', $id_usuario_sesion);
    $query_resumen->bindParam(':fecha_apertura', $fecha_apertura);
    $query_resumen->execute();
    $resumen = $query_resumen->fetch(PDO::FETCH_ASSOC);
    $monto_actual_sistema = $resumen['total_sistema'] ?? 0;
    $cantidad_pagos_actual = $resumen['cantidad'] ?? 0;

    // Desglose por metodo de pago
    $sql_desglose = "SELECT metodo_pago, SUM(monto) as total 
                     FROM tb_pagos 
                     WHERE id_usuario = :id_usuario 
                     AND fecha_hora >= :fecha_apertura 
                     AND estado = 1
                     GROUP BY metodo_pago";
    $query_desglose = $pdo->prepare($sql_desglose);
    $query_desglose->bindParam(':id_usuario', $id_usuario_sesion);
    $query_desglose->bindParam(':fecha_apertura', $fecha_apertura);
    $query_desglose->execute();
    $desglose_pagos = $query_desglose->fetchAll(PDO::FETCH_ASSOC);

    $total_efectivo = 0;
    $total_digital = 0; // Tarjetas, Yape, Plin

    foreach ($desglose_pagos as $pago) {
        if ($pago['metodo_pago'] == 'efectivo') {
            $total_efectivo += $pago['total'];
        } else {
            $total_digital += $pago['total'];
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Gestión de Caja (Turno)</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <?php // Mensajes de alerta de sesión ?>
            <?php if (isset($_SESSION['mensaje'])): ?>
                <script>
                    Swal.fire({
                        position: 'center',
                        icon: '<?php echo $_SESSION['icono']; ?>',
                        title: '<?php echo $_SESSION['mensaje']; ?>',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
                <?php
                unset($_SESSION['mensaje']);
                unset($_SESSION['icono']);
            endif;
            ?>

            <div class="row justify-content-center">
                <!-- Tarjeta Principal -->
                <div class="col-md-6">
                    <?php if (!$caja_abierta): ?>
                        <!-- VISTA APERTURA DE CAJA -->
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-cash-register"></i> Apertura de Caja</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">No tienes una caja abierta actualmente. Ingresa el monto inicial para
                                    comenzar tu turno.</p>
                                <form action="../app/controllers/caja/apertura_caja.php" method="post">
                                    <div class="form-group">
                                        <label for="monto_apertura">Monto de Apertura (Fondo de Caja)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">S/.</span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control" name="monto_apertura"
                                                id="monto_apertura" required placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block btn-lg">Abrir Caja</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- VISTA CIERRE DE CAJA -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title text-primary"><i class="fas fa-door-open"></i> Caja Abierta</h3>
                                <div class="card-tools">
                                    <span class="badge badge-success">En Curso</span>
                                </div>
                            </div>
                            <div class="card-body" style="padding-bottom: 3rem !important;">
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <h5><small>Fecha Apertura:</small><br>
                                            <b><?php echo date('d/m/Y H:i', strtotime($caja_abierta['fecha_apertura'])); ?></b>
                                        </h5>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h5><small>Monto Inicial:</small><br> <b>S/.
                                                <?php echo number_format($caja_abierta['monto_apertura'], 2); ?></b></h5>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3><i class="fas fa-cash-register"></i> Total Ventas:</h3>
                                            <small><?php echo $cantidad_pagos_actual; ?> transacciones</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h3>S/. <?php echo number_format($monto_actual_sistema, 2); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6 border-right">
                                        <h5 class="text-white"><i class="fas fa-money-bill-wave"></i> Efectivo</h5>
                                        <h4>S/. <?php echo number_format($total_efectivo, 2); ?></h4>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-white"><i class="fas fa-credit-card"></i> Digital / Bancos</h5>
                                        <h4>S/. <?php echo number_format($total_digital, 2); ?></h4>
                                        <small>(Yape, Plin, Tarjeta)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 2rem 3rem;">
                            <hr>
                            <h4>Cierre de Turno</h4>
                            <p class="text-sm">Cuenta el dinero físico en caja y regístralo a continuación.</p>

                            <form action="../app/controllers/caja/cierre_caja.php" method="post" id="form_cierre">
                                <input type="hidden" name="id_caja" value="<?php echo $caja_abierta['id_caja']; ?>">

                                <div class="form-group">
                                    <label for="monto_cierre">Monto Real en Caja (Efectivo + Vouchers)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">S/.</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control form-control-lg"
                                            name="monto_cierre" id="monto_cierre" required>
                                    </div>
                                    <small class="text-muted">Debe incluir el fondo de caja inicial.</small>
                                </div>

                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea class="form-control" name="observaciones" rows="2"
                                        placeholder="Ocurrencias durante el turno..."></textarea>
                                </div>

                                <div class="form-group mb-5">
                                    <button type="button" class="btn btn-danger btn-block btn-lg"
                                        onclick="confirmarCierre()">Cerrar Caja</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
</div>

<?php include('../layout/parte2.php'); ?>

<script>
    function confirmarCierre() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se procesará el cierre de caja. Asegúrate de haber contado bien el dinero.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, Cerrar Caja',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form_cierre').submit();
            }
        })
    }
</script>