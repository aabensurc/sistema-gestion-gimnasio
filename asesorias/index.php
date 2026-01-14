<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(9);



include('../layout/parte1.php');

// Incluir el controlador para listar las asesorías (lo crearemos a continuación)
include('../app/controllers/asesorias/listado_de_asesorias.php');

// La fecha_inicio y fecha_fin vienen del controlador incluido arriba
// No es necesario re-definirlas aquí abajo, ya están en scope

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Filtro reemplazado por DateRangePicker en Header -->

            <div class="row">
                <div class="col-md-12">

                    <div class="card card-outline card-primary">
                        <div class="card-header d-flex align-items-center">
                            <h1 class="m-0 flex-grow-1">Asesorías registradas
                                <button type="button" class="btn btn-primary ml-2"
                                    onclick="location.href='<?php echo $URL; ?>/asesorias/create.php'">
                                    <i class="fa fa-plus"></i> Nueva Asesoría
                                </button>
                            </h1>
                            <!-- Filtro Fecha (Movido aquí) -->
                            <form action="" method="GET" id="filter_form" class="form-inline mr-2">
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

                        <div class="card-body" style="display: block;">

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>Nro</center>
                                        </th>
                                        <th>
                                            <center>Cliente</center>
                                        </th>
                                        <th>
                                            <center>Entrenador</center>
                                        </th>
                                        <th>
                                            <center>Fecha Inicio</center>
                                        </th>
                                        <th>
                                            <center>Fecha Fin</center>
                                        </th>
                                        <th>
                                            <center>Monto Final (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Estado de Pago</center>
                                        </th>
                                        <th>
                                            <center>Acciones</center>
                                        </th>
                                        <th>
                                            <center>Pagos</center>
                                        </th> <!-- Columna para el botón de pago -->
                                        <th>
                                            <center>Boleta</center>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $contador = 0;
                                    if (isset($asesorias_datos) && is_array($asesorias_datos)) {
                                        foreach ($asesorias_datos as $asesoria_dato) {
                                            $contador++;
                                            $id_asesoria = $asesoria_dato['id_asesoria'];
                                            $id_cliente_asesoria = $asesoria_dato['id_cliente_asesoria']; // ID del cliente asociado a la asesoría
                                            $nombre_completo_cliente = $asesoria_dato['nombre_completo_cliente'] ?? 'Cliente Eliminado/Anónimo';
                                            $nombre_completo_entrenador = $asesoria_dato['nombre_completo_entrenador'] ?? 'Entrenador Eliminado/N/A';
                                            $monto_final_asesoria = $asesoria_dato['monto_final'];
                                            $total_pagado_asesoria = $asesoria_dato['total_pagado'] ?? 0; // Usar 0 si es NULL (no hay pagos)
                                    
                                            $estado_pago_asesoria = '';
                                            $clase_estado_asesoria = '';
                                            $monto_pendiente_asesoria = $monto_final_asesoria - $total_pagado_asesoria;

                                            $estado = $asesoria_dato['estado'] ?? 1; // Default to 1 (active) if not set
                                    
                                            if ($estado == 0) {
                                                $estado_pago_asesoria = 'Anulado';
                                                $clase_estado_asesoria = 'badge badge-danger';
                                            } else if ($total_pagado_asesoria >= $monto_final_asesoria) {
                                                $estado_pago_asesoria = 'Pagado';
                                                $clase_estado_asesoria = 'badge badge-success';
                                            } else if ($total_pagado_asesoria > 0 && $total_pagado_asesoria < $monto_final_asesoria) {
                                                $estado_pago_asesoria = 'Parcial (Falta S/. ' . number_format($monto_pendiente_asesoria, 2) . ')';
                                                $clase_estado_asesoria = 'badge badge-warning';
                                            } else {
                                                $estado_pago_asesoria = 'Pendiente (S/. ' . number_format($monto_final_asesoria, 2) . ')';
                                                $clase_estado_asesoria = 'badge badge-danger';
                                            }

                                            $row_style = ($estado == 0) ? 'style="background-color: #f8d7da; color: #721c24; text-decoration: line-through;"' : '';
                                            ?>
                                            <tr <?php echo $row_style; ?>>
                                                <td>
                                                    <center><?php echo $contador; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $nombre_completo_cliente; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $nombre_completo_entrenador; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $asesoria_dato['fecha_inicio_asesoria']; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $asesoria_dato['fecha_fin_asesoria']; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo number_format($asesoria_dato['monto_final'], 2); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><span
                                                            class="<?php echo $clase_estado_asesoria; ?>"><?php echo $estado_pago_asesoria; ?></span>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <div class="btn-group">
                                                            <a href="show.php?id=<?php echo $id_asesoria; ?>" type="button"
                                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver
                                                                Detalle</a>
                                                            <?php if ($estado == 1) {
                                                                if ($total_pagado_asesoria == 0) { ?>
                                                                    <a href="edit.php?id=<?php echo $id_asesoria; ?>" type="button"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="fa fa-pencil-alt"></i> Editar
                                                                    </a>
                                                                <?php } ?>
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#modal-delete-asesoria<?php echo $id_asesoria; ?>">
                                                                    <i class="fa fa-ban"></i> Anular
                                                                </button>
                                                            <?php } else { ?>
                                                                <span class="badge badge-danger">ANULADO</span>
                                                            <?php } ?>
                                                        </div>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php if ($estado == 1): ?>
                                                            <?php if (!empty($asesoria_dato['tiene_cronograma']) && $asesoria_dato['tiene_cronograma'] > 0): ?>
                                                                <!-- Si tiene cronograma -->
                                                                <button type="button" class="btn btn-primary btn-sm btn-cronograma"
                                                                    data-id="<?php echo $id_asesoria; ?>">
                                                                    <i class="fas fa-list-alt"></i> Cronograma
                                                                </button>
                                                            <?php else: ?>
                                                                <!-- Si NO tiene cronograma -->
                                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                                    data-target="#modal-add-payment-asesoria<?php echo $id_asesoria; ?>">
                                                                    <i class="fa fa-dollar-sign"></i> Agregar Pago
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-info btn-sm" disabled>
                                                                <i class="fa fa-dollar-sign"></i> Agregar Pago
                                                            </button>
                                                        <?php endif; ?>
                                                    </center>

                                                    <!-- Modal para Agregar Pago a Asesoría -->
                                                    <div class="modal fade"
                                                        id="modal-add-payment-asesoria<?php echo $id_asesoria; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color: #17a2b8; color: white">
                                                                    <h4 class="modal-title">Registrar Pago para Asesoría
                                                                        #<?php echo $id_asesoria; ?></h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="../app/controllers/asesorias/add_payment_to_asesoria.php"
                                                                    method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id_asesoria"
                                                                            value="<?php echo $id_asesoria; ?>">
                                                                        <input type="hidden" name="id_cliente"
                                                                            value="<?php echo $id_cliente_asesoria; ?>">

                                                                        <div class="form-group">
                                                                            <label>Asesoría de Cliente:</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo $nombre_completo_cliente; ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Entrenador:</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo $nombre_completo_entrenador; ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Monto Final Asesoría (S/.):</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo number_format($monto_final_asesoria, 2); ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Total Pagado hasta ahora (S/.):</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo number_format($total_pagado_asesoria, 2); ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="monto_pago_asesoria_<?php echo $id_asesoria; ?>">Monto
                                                                                a Pagar (S/.):</label>
                                                                            <input type="number" name="monto_pagado"
                                                                                class="form-control"
                                                                                id="monto_pago_asesoria_<?php echo $id_asesoria; ?>"
                                                                                placeholder="Ingrese el monto del pago"
                                                                                step="0.01" required
                                                                                value="<?php echo number_format($monto_pendiente_asesoria > 0 ? $monto_pendiente_asesoria : 0, 2, '.', ''); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="metodo_pago_asesoria_modal_<?php echo $id_asesoria; ?>">Método
                                                                                de Pago:</label>
                                                                            <select name="metodo_pago"
                                                                                id="metodo_pago_asesoria_modal_<?php echo $id_asesoria; ?>"
                                                                                class="form-control" required>
                                                                                <option value="">Seleccione un método</option>
                                                                                <option value="efectivo">Efectivo</option>
                                                                                <option value="tarjeta_debito">Tarjeta de Débito
                                                                                </option>
                                                                                <option value="tarjeta_credito">Tarjeta de
                                                                                    Crédito</option>
                                                                                <option value="yape">Yape</option>
                                                                                <option value="plin">Plin</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Cancelar</button>
                                                                        <button type="submit" class="btn btn-info">Registrar
                                                                            Pago</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <center>
                                                        <a href="../app/controllers/asesorias/generar_boleta_asesoria.php?id=<?php echo $id_asesoria; ?>"
                                                            target="_blank" class="btn btn-dark btn-sm">
                                                            <i class="fa fa-print"></i> Boleta
                                                        </a>
                                                    </center>
                                                </td>
                                            </tr>
                                            <!-- Modal para Eliminar Asesoría -->
                                            <div class="modal fade" id="modal-delete-asesoria<?php echo $id_asesoria; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #CC3E3E; color: white">
                                                            <h4 class="modal-title">Anulación de Asesoría</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../app/controllers/asesorias/delete_asesoria.php"
                                                            method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_asesoria"
                                                                    value="<?php echo $id_asesoria; ?>">
                                                                <p>¿Está seguro de que desea anular la asesoría
                                                                    #<?php echo $id_asesoria; ?> del cliente
                                                                    <?php echo $nombre_completo_cliente; ?> con el entrenador
                                                                    <?php echo $nombre_completo_entrenador; ?>?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-danger">Anular</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>
                                            <center>Nro</center>
                                        </th>
                                        <th>
                                            <center>Cliente</center>
                                        </th>
                                        <th>
                                            <center>Entrenador</center>
                                        </th>
                                        <th>
                                            <center>Fecha Inicio</center>
                                        </th>
                                        <th>
                                            <center>Fecha Fin</center>
                                        </th>
                                        <th>
                                            <center>Monto Final (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Estado de Pago</center>
                                        </th>
                                        <th>
                                            <center>Acciones</center>
                                        </th>
                                        <th>
                                            <center>Pagos</center>
                                        </th>
                                        <th>
                                            <center>Boleta</center>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include('../layout/parte2.php'); ?>

<!-- Modal Genérico para Cronograma -->
<div class="modal fade" id="modal-cronograma">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Cronograma de Pagos</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="cronograma-content" class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i> Cargando...
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Delegación de eventos para botones generados dinámicamente
        $(document).on('click', '.btn-cronograma', function () {
            var idAsesoria = $(this).data('id');
            $('#modal-cronograma').modal('show');
            $('#cronograma-content').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando información...</p></div>');

            $.ajax({
                url: '../app/controllers/matriculas/get_cronograma.php', // Usamos el mismo controlador compartido
                type: 'GET',
                data: {
                    id_origen: idAsesoria,
                    tipo_origen: 'asesoria'
                },
                success: function (response) {
                    $('#cronograma-content').html(response);
                },
                error: function () {
                    $('#cronograma-content').html('<div class="alert alert-danger">Error al cargar el cronograma.</div>');
                }
            });
        });
    });
</script>

<?php include('../layout/mensajes.php'); ?>

<!-- Script para DateRangePicker -->
<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 10,
            language: {
                "emptyTable": "No hay información",
                "decimal": "",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Asesorías",
                "infoEmpty": "Mostrando 0 to 0 of 0 Asesorías",
                "infoFiltered": "(Filtrado de _MAX_ total Asesorías)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Asesorías",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            buttons: [{
                extend: 'collection',
                text: 'Reportes',
                orientation: 'landscape',
                buttons: [{
                    text: 'Copiar',
                    extend: 'copy'
                }, {
                    extend: 'pdf',
                }, {
                    extend: 'csv',
                }, {
                    extend: 'excel',
                }, {
                    text: 'Imprimir',
                    extend: 'print'
                }]
            },
            {
                extend: 'colvis',
                text: 'Visor de columnas'
            }
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // --- Configurar DaterangePicker ---
        var startDate = '<?php echo date("d/m/Y", strtotime($fecha_inicio)); ?>';
        var endDate = '<?php echo date("d/m/Y", strtotime($fecha_fin)); ?>';

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
            $('#filter_form').submit();
        });

        cb(startDate, endDate);

    });
</script>