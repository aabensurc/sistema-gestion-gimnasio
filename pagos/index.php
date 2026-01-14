<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(11);



include('../layout/parte1.php');

// Incluir el controlador para listar los pagos (lo crearemos a continuación)
include('../app/controllers/pagos/listado_de_pagos.php');

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
              <h1 class="m-0 flex-grow-1">Pagos registrados</h1>
              <!-- Filtro Fecha (Movido aquí) -->
              <form action="" method="GET" id="filter_form" class="form-inline mr-2">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="daterange-btn" style="border-radius: 5px;">
                    <i class="far fa-calendar-alt mr-2"></i>
                    <span id="reportrange-span"></span>
                    <i class="fas fa-caret-down ml-2"></i>
                  </button>
                  <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                  <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_fin; ?>">
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
                      <center>Tipo de Pago</center>
                    </th>
                    <th>
                      <center>ID Referencia</center>
                    </th>
                    <th>
                      <center>Método de Pago</center>
                    </th>
                    <th>
                      <center>Monto (S/.)</center>
                    </th>
                    <th>
                      <center>Fecha y Hora</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  if (isset($pagos_datos) && is_array($pagos_datos)) {
                    foreach ($pagos_datos as $pago_dato) {
                      $contador++;
                      $id_pago = $pago_dato['id_pago'];
                      $nombre_completo_cliente = $pago_dato['nombre_completo_cliente'] ?? 'Cliente Eliminado/Anónimo';
                      $tipo_pago = $pago_dato['tipo_pago'];
                      $metodo_pago = $pago_dato['metodo_pago'];
                      $monto = $pago_dato['monto'];
                      $fecha_hora = $pago_dato['fecha_hora'];

                      $id_referencia = 'N/A';
                      if ($pago_dato['id_matricula_fk']) {
                        $id_referencia = 'Matrícula #' . $pago_dato['id_matricula_fk'];
                      } elseif ($pago_dato['id_venta_fk']) {
                        $id_referencia = 'Venta #' . $pago_dato['id_venta_fk'];
                      } elseif ($pago_dato['id_asesoria_fk']) {
                        $id_referencia = 'Asesoría #' . $pago_dato['id_asesoria_fk'];
                      }
                      $estado = $pago_dato['estado'] ?? 1; // Default to 1 (active) if not set
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
                          <center><?php echo ucfirst($tipo_pago); ?></center>
                        </td>
                        <td>
                          <center><?php echo $id_referencia; ?></center>
                        </td>
                        <td>
                          <center><?php echo ucfirst(str_replace('_', ' ', $metodo_pago)); ?></center>
                        </td>
                        <td>
                          <center><?php echo number_format($monto, 2); ?></center>
                        </td>
                        <td>
                          <center><?php echo $fecha_hora; ?></center>
                        </td>
                        <td>
                          <center>
                            <div class="btn-group">
                              <?php if ($estado == 1): ?>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                  data-target="#modal-delete-pago<?php echo $id_pago; ?>">
                                  <i class="fa fa-ban"></i> Anular
                                </button>
                              <?php else: ?>
                                <span class="badge badge-danger">ANULADO</span>
                              <?php endif; ?>
                            </div>
                          </center>
                        </td>
                      </tr>
                      <!-- Modal para Anular Pago -->
                      <div class="modal fade" id="modal-delete-pago<?php echo $id_pago; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #CC3E3E; color: white">
                              <h4 class="modal-title">Anulación de Pago</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="../app/controllers/pagos/delete_pago.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_pago" value="<?php echo $id_pago; ?>">
                                <p>¿Está seguro de que desea anular el pago #<?php echo $id_pago; ?> de
                                  S/.<?php echo number_format($monto, 2); ?> (<?php echo ucfirst($tipo_pago); ?> -
                                  <?php echo $id_referencia; ?>)?
                                </p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                      <center>Tipo de Pago</center>
                    </th>
                    <th>
                      <center>ID Referencia</center>
                    </th>
                    <th>
                      <center>Método de Pago</center>
                    </th>
                    <th>
                      <center>Monto (S/.)</center>
                    </th>
                    <th>
                      <center>Fecha y Hora</center>
                    </th>
                    <th>
                      <center>Acciones</center>
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
<?php include('../layout/mensajes.php'); ?>

<!-- Script para DateRangePicker -->
<script>
  $(function () {
    $("#example1").DataTable({
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Pagos",
        "infoEmpty": "Mostrando 0 to 0 of 0 Pagos",
        "infoFiltered": "(Filtrado de _MAX_ total Pagos)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Pagos",
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