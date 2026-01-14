<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

// Validar Permisos (Solo rol ADMIN o SUPERVISOR deberian ver esto)
// Usamos $_SESSION['rol_base'] o verificamos permisos especificos si existen.
// Por simplicidad, asumimos que rol 'ADMINISTRADOR' tiene acceso.
// Si deseas usar permisos, deberiamos agregar uno nuevo al array de permisos.
// Por ahora, validación simple por nombre de rol o todos con acceso a ver reportes.
// El usuario pidio: "Que solamente le aparezca al administrador o el que tenga usuario que tenga rol de administrador"

// roles permitidos
$roles_permitidos = ['ADMINISTRADOR', 'Administrador'];
$rol_usuario = $_SESSION['rol_base'];

if (!in_array($rol_usuario, $roles_permitidos)) {
    // Si quieres restringir el acceso a nivel de backend:
    // echo "Acceso Denegado"; exit;
    // O mejor, mostrar alerta y redirigir.
}

include('../app/controllers/caja/listado_cajas.php');
?>

<div class="content-wrapper">


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title flex-grow-1" style="font-size: 1.8rem; font-weight: 700;">Listado de
                                Cierres de Caja</h3>
                            <!-- Filtro Fecha (Movido aquí) -->
                            <form action="" method="GET" id="report_filter_form" class="form-inline mr-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="daterange-btn"
                                        style="border-radius: 5px;">
                                        <i class="far fa-calendar-alt mr-2"></i>
                                        <span id="reportrange-span"></span>
                                        <i class="fas fa-caret-down ml-2"></i>
                                    </button>
                                    <input type="hidden" name="fecha_inicio" id="fecha_inicio"
                                        value="<?php echo $fecha_inicio; ?>">
                                    <input type="hidden" name="fecha_fin" id="fecha_fin"
                                        value="<?php echo $fecha_fin; ?>">
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="text-center">
                                        <th>Nro</th>
                                        <th>Usuario</th>
                                        <th>F. Apertura</th>
                                        <th>F. Cierre</th>
                                        <th>Monto Inicial</th>
                                        <th>Ventas Sistema</th>
                                        <th>Total Esperado</th>
                                        <th>Monto Real</th>
                                        <th>Diferencia</th>
                                        <th>Estado</th>
                                        <th>Obs.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contador = 0;
                                    foreach ($reporte_cajas as $caja) {
                                        $contador++;
                                        $id_caja = $caja['id_caja'];
                                        $total_esperado = $caja['monto_apertura'] + ($caja['monto_sistema'] ?? 0);
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $contador; ?></td>
                                            <td><?php echo $caja['nombre_usuario']; ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($caja['fecha_apertura'])); ?></td>
                                            <td><?php echo $caja['fecha_cierre'] ? date('d/m/Y H:i', strtotime($caja['fecha_cierre'])) : '-'; ?>
                                            </td>
                                            <td>S/. <?php echo number_format($caja['monto_apertura'], 2); ?></td>
                                            <td>S/. <?php echo number_format($caja['monto_sistema'] ?? 0, 2); ?></td>
                                            <td><b>S/. <?php echo number_format($total_esperado, 2); ?></b></td>
                                            <td>S/. <?php echo number_format($caja['monto_cierre'] ?? 0, 2); ?></td>
                                            <td>
                                                <?php
                                                $dif = $caja['diferencia'];
                                                if ($dif < 0)
                                                    echo "<span class='badge badge-danger'>S/. " . number_format($dif, 2) . "</span>";
                                                else if ($dif > 0)
                                                    echo "<span class='badge badge-success'>+ S/. " . number_format($dif, 2) . "</span>";
                                                else
                                                    echo "<span class='badge badge-secondary'>OK</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($caja['estado'] == 1)
                                                    echo '<span class="badge badge-success">Abierta</span>';
                                                else
                                                    echo '<span class="badge badge-secondary">Cerrada</span>';
                                                ?>
                                            </td>
                                            <td><small><?php echo $caja['observaciones']; ?></small></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>

<script>
    $(function () {
        // --- Configurar DaterangePicker ---
        var startDate = moment('<?php echo $fecha_inicio; ?>');
        var endDate = moment('<?php echo $fecha_fin; ?>');

        function cb(start, end) {
            $('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $('#fecha_inicio').val(start.format('YYYY-MM-DD'));
            $('#fecha_fin').val(end.format('YYYY-MM-DD'));
        }

        $('#daterange-btn').daterangepicker({
            startDate: startDate,
            endDate: endDate,
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                fromLabel: "Desde",
                toLabel: "Hasta",
                customRangeLabel: "Personalizado",
                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                firstDay: 1
            },
            opens: 'left'
        }, function (start, end) {
            cb(start, end);
            $('#report_filter_form').submit();
        });

        cb(startDate, endDate);



        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>