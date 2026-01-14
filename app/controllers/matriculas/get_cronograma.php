<?php
include('../../config.php');

$id_origen = $_GET['id_origen'] ?? $_GET['id_matricula'] ?? null;
$tipo_origen = $_GET['tipo_origen'] ?? 'matricula';

if (!$id_origen) {
    echo '<div class="alert alert-danger">Error: ID no especificado.</div>';
    exit;
}

// Configurar Query según origen
$condicion_fk = "";
switch ($tipo_origen) {
    case 'matricula':
        $condicion_fk = "id_matricula_fk = :id";
        break;
    case 'venta':
        $condicion_fk = "id_venta_fk = :id";
        break;
    case 'asesoria':
        $condicion_fk = "id_asesoria_fk = :id";
        break;
    default:
        echo '<div class="alert alert-danger">Error: Tipo de origen desconocido.</div>';
        exit;
}

// Obtener cronograma
$sql = "SELECT * FROM tb_cronograma_pagos WHERE $condicion_fk ORDER BY nro_cuota ASC";
$query = $pdo->prepare($sql);
$query->execute([':id' => $id_origen]);
$cuotas = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$cuotas) {
    echo '<div class="alert alert-warning">No se encontró cronograma para esta operación.</div>';
    exit;
}
?>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Vencimiento</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Fecha Pago</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cuotas as $key => $cuota): ?>
                <?php
                $estadoClass = '';
                $estadoTexto = $cuota['estado_cuota'];

                if ($estadoTexto == 'Pagado') {
                    $estadoClass = 'badge badge-success';
                } elseif ($estadoTexto == 'Pendiente') {
                    // Verificar si está vencida
                    if ($cuota['fecha_vencimiento'] < date('Y-m-d')) {
                        $estadoClass = 'badge badge-danger';
                        $estadoTexto = 'Vencido';
                    } else {
                        $estadoClass = 'badge badge-warning';
                    }
                } else {
                    $estadoClass = 'badge badge-secondary';
                }
                ?>
                <?php
                // Lógica de visualización para cuotas fraccionadas
                $displayNro = $cuota['nro_cuota'];
                $isPartial = false;
                $isBalance = false;

                // Check previous
                if ($key > 0 && $cuotas[$key - 1]['nro_cuota'] == $cuota['nro_cuota']) {
                    $isBalance = true;
                }
                // Check next
                if (isset($cuotas[$key + 1]) && $cuotas[$key + 1]['nro_cuota'] == $cuota['nro_cuota']) {
                    $isPartial = true;
                }

                if ($isPartial)
                    $displayNro .= ' <small class="text-muted">(Parcial)</small>';
                if ($isBalance)
                    $displayNro .= ' <small class="text-danger">(Saldo)</small>';
                ?>
                <tr>
                    <td><?php echo $displayNro; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cuota['fecha_vencimiento'])); ?></td>
                    <td>S/. <?php echo number_format($cuota['monto_programado'], 2); ?></td>
                    <td><span class="<?php echo $estadoClass; ?>"><?php echo $estadoTexto; ?></span></td>
                    <td>
                        <?php echo $cuota['fecha_pago_completado'] ? date('d/m/Y H:i', strtotime($cuota['fecha_pago_completado'])) : '-'; ?>
                    </td>
                    <td>
                        <?php if ($cuota['estado_cuota'] == 'Pendiente'): ?>
                            <button type="button" class="btn btn-sm btn-success btn-pagar-cuota"
                                data-id-cuota="<?php echo $cuota['id_cronograma']; ?>"
                                data-monto="<?php echo $cuota['monto_programado']; ?>"
                                data-nro="<?php echo $cuota['nro_cuota']; ?>">
                                <i class="fas fa-money-bill-wave"></i> Pagar
                            </button>
                        <?php else: ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Formulario oculto/modal para pagar cuota específica -->
<div id="form-pago-cuota-container"
    style="display:none; margin-top: 20px; border-top: 1px solid #ccc; padding-top: 15px;">
    <h5 class="text-primary">Registrar Pago de Cuota #<span id="lbl-nro-cuota"></span></h5>
    <form action="../app/controllers/matriculas/pagar_cuota.php" method="POST" id="form-pagar-cuota">
        <input type="hidden" name="id_cronograma" id="input-id-cuota">
        <input type="hidden" name="id_origen" value="<?php echo $id_origen; ?>">
        <input type="hidden" name="tipo_origen" value="<?php echo $tipo_origen; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Monto a Pagar</label>
                    <input type="number" step="0.01" class="form-control" name="monto" id="input-monto-cuota" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Método de Pago</label>
                    <select name="metodo_pago" class="form-control" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta_debito">Tarjeta de Débito</option>
                        <option value="tarjeta_credito">Tarjeta de Crédito</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="button" class="btn btn-secondary"
                onclick="$('#form-pago-cuota-container').hide()">Cancelar</button>
            <button type="submit" class="btn btn-primary">Confirmar Pago</button>
        </div>
    </form>
</div>

<script>
    $('.btn-pagar-cuota').on('click', function () {
        var idCuota = $(this).data('id-cuota');
        var monto = $(this).data('monto');
        var nro = $(this).data('nro');

        $('#input-id-cuota').val(idCuota);
        $('#input-monto-cuota').val(monto);
        $('#lbl-nro-cuota').text(nro);

        $('#form-pago-cuota-container').slideDown();
    });
</script>