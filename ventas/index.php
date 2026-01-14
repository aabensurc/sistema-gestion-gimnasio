<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(7);



include('../layout/parte1.php');

// Incluir el controlador para listar las ventas
include('../app/controllers/ventas/listado_de_ventas.php');

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
                            <h1 class="m-0 flex-grow-1">Ventas registradas
                                <button type="button" class="btn btn-primary ml-2"
                                    onclick="location.href='<?php echo $URL; ?>/ventas/create.php'">
                                    <i class="fa fa-plus"></i> Nueva Venta
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
                                            <center>Fecha Venta</center>
                                        </th>
                                        <th>
                                            <center>Monto Total (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Descuento (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Estado de Pago</center>
                                        </th>
                                        <th>
                                            <center>Acciones</center>
                                        </th>
                                        <th>
                                            <center>Pagos</center>
                                        </th> <!-- Nueva columna para el botón de pago -->
                                        <th>
                                            <center>Boleta</center>
                                        </th>
                                    </tr>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $contador = 0;
                                    // Asegúrate de que $ventas_datos esté definido en listado_de_ventas.php
                                    if (isset($ventas_datos) && is_array($ventas_datos)) {
                                        foreach ($ventas_datos as $venta_dato) {
                                            $contador++;
                                            $id_venta = $venta_dato['id_venta'];
                                            $id_cliente_venta = $venta_dato['id_cliente_venta']; // ID del cliente asociado a la venta
                                            $nombre_completo_cliente = $venta_dato['nombre_completo_cliente'] ?? 'Cliente Eliminado/Anónimo';
                                            $monto_total_venta = $venta_dato['monto_total'];
                                            $total_pagado_venta = $venta_dato['total_pagado'] ?? 0; // Usar 0 si es NULL (no hay pagos)
                                    
                                            $estado_pago_venta = '';
                                            $clase_estado_venta = '';
                                            $monto_pendiente_venta = $monto_total_venta - $total_pagado_venta;

                                            $estado = $venta_dato['estado'] ?? 1; // Default to 1 (active) if not set
                                    
                                            if ($estado == 0) {
                                                $estado_pago_venta = 'Anulado';
                                                $clase_estado_venta = 'badge badge-danger';
                                            } else if ($total_pagado_venta >= $monto_total_venta) {
                                                $estado_pago_venta = 'Pagado';
                                                $clase_estado_venta = 'badge badge-success';
                                            } else if ($total_pagado_venta > 0 && $total_pagado_venta < $monto_total_venta) {
                                                $estado_pago_venta = 'Parcial (Falta S/. ' . number_format($monto_pendiente_venta, 2) . ')';
                                                $clase_estado_venta = 'badge badge-warning';
                                            } else {
                                                $estado_pago_venta = 'Pendiente (S/. ' . number_format($monto_total_venta, 2) . ')';
                                                $clase_estado_venta = 'badge badge-danger';
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
                                                    <center><?php echo $venta_dato['fecha_venta']; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo number_format($venta_dato['monto_total'], 2); ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo number_format($venta_dato['descuento_total'], 2); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><span
                                                            class="<?php echo $clase_estado_venta; ?>"><?php echo $estado_pago_venta; ?></span>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <div class="btn-group">
                                                            <a href="show.php?id=<?php echo $id_venta; ?>" type="button"
                                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver
                                                                Detalle</a>
                                                            <?php if ($estado == 1) {
                                                                if ($total_pagado_venta == 0) { ?>
                                                                    <a href="edit.php?id=<?php echo $id_venta; ?>" type="button"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="fa fa-pencil-alt"></i> Editar
                                                                    </a>
                                                                <?php }
                                                            } else { ?>
                                                                <span class="badge badge-danger">ANULADO</span>
                                                            <?php } ?>

                                                            <?php if ($estado == 1): ?>
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#modal-delete-venta<?php echo $id_venta; ?>">
                                                                    <i class="fa fa-ban"></i> Anular
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php if ($estado == 1): ?>
                                                            <?php if (!empty($venta_dato['tiene_cronograma']) && $venta_dato['tiene_cronograma'] > 0): ?>
                                                                <!-- Si tiene cronograma -->
                                                                <button type="button" class="btn btn-primary btn-sm btn-cronograma"
                                                                    data-id="<?php echo $id_venta; ?>">
                                                                    <i class="fas fa-list-alt"></i> Cronograma
                                                                </button>
                                                            <?php else: ?>
                                                                <!-- Si NO tiene cronograma -->
                                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                                    data-target="#modal-add-payment-venta<?php echo $id_venta; ?>">
                                                                    <i class="fa fa-dollar-sign"></i> Agregar Pago
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-info btn-sm" disabled>
                                                                <i class="fa fa-dollar-sign"></i> Agregar Pago
                                                            </button>
                                                        <?php endif; ?>
                                                    </center>

                                                    <!-- Modal para Agregar Pago a Venta -->
                                                    <div class="modal fade"
                                                        id="modal-add-payment-venta<?php echo $id_venta; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color: #17a2b8; color: white">
                                                                    <h4 class="modal-title">Registrar Pago para Venta
                                                                        #<?php echo $id_venta; ?></h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="../app/controllers/ventas/add_payment_to_venta.php"
                                                                    method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id_venta"
                                                                            value="<?php echo $id_venta; ?>">
                                                                        <input type="hidden" name="id_cliente"
                                                                            value="<?php echo $id_cliente_venta; ?>">

                                                                        <div class="form-group">
                                                                            <label>Venta de Cliente:</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo $nombre_completo_cliente; ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Monto Total Venta (S/.):</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo number_format($monto_total_venta, 2); ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Total Pagado hasta ahora (S/.):</label>
                                                                            <input type="text" class="form-control"
                                                                                value="<?php echo number_format($total_pagado_venta, 2); ?>"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="monto_pago_venta_<?php echo $id_venta; ?>">Monto
                                                                                a Pagar (S/.):</label>
                                                                            <input type="number" name="monto_pagado"
                                                                                class="form-control"
                                                                                id="monto_pago_venta_<?php echo $id_venta; ?>"
                                                                                placeholder="Ingrese el monto del pago"
                                                                                step="0.01" required
                                                                                value="<?php echo number_format($monto_pendiente_venta > 0 ? $monto_pendiente_venta : 0, 2, '.', ''); ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="metodo_pago_venta_modal_<?php echo $id_venta; ?>">Método
                                                                                de Pago:</label>
                                                                            <select name="metodo_pago"
                                                                                id="metodo_pago_venta_modal_<?php echo $id_venta; ?>"
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
                                                        <a href="../app/controllers/ventas/generar_boleta.php?id=<?php echo $id_venta; ?>"
                                                            target="_blank" class="btn btn-dark btn-sm">
                                                            <i class="fa fa-print"></i> Boleta
                                                        </a>
                                                    </center>
                                                </td>
                                            </tr>
                                            <!-- Modal para Eliminar Venta (similar al de matrículas) -->
                                            <div class="modal fade" id="modal-delete-venta<?php echo $id_venta; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #CC3E3E; color: white">
                                                            <h4 class="modal-title">Anulación de Venta</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="../app/controllers/ventas/delete_venta.php" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_venta"
                                                                    value="<?php echo $id_venta; ?>">
                                                                <p>¿Está seguro de que desea anular la venta
                                                                    #<?php echo $id_venta; ?> del cliente
                                                                    <?php echo $nombre_completo_cliente; ?>? <br>
                                                                    <strong>Esta acción devolverá los productos al stock y
                                                                        anulará los pagos asociados.</strong>
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
                                            <center>Fecha Venta</center>
                                        </th>
                                        <th>
                                            <center>Monto Total (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Descuento (S/.)</center>
                                        </th>
                                        <th>
                                            <center>Estado de Pago</center>
                                        </th>
                                        <th>
                                            <center>Acciones</center>
                                        </th>
                                        <th>
                                            <center>Pagos</center>
                                        </th> <!-- Nueva columna en el footer -->
                                        <th>
                                            <center>Boleta</center>
                                        </th>
                                    </tr>
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
            var idVenta = $(this).data('id');
            $('#modal-cronograma').modal('show');
            $('#cronograma-content').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando información...</p></div>');

            $.ajax({
                url: '../app/controllers/matriculas/get_cronograma.php', // Usamos el mismo controlador compartido
                type: 'GET',
                data: {
                    id_origen: idVenta,
                    tipo_origen: 'venta'
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
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Ventas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Ventas",
                "infoFiltered": "(Filtrado de _MAX_ total Ventas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Ventas",
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