<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(6);



include('../layout/parte1.php');

include('../app/controllers/matriculas/listado_de_matriculas.php');

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
              <h1 class="m-0 flex-grow-1">Matriculas registradas
                <button type="button" class="btn btn-primary ml-2"
                  onclick="location.href='<?php echo $URL; ?>/matriculas/create.php'">
                  <i class="fa fa-plus"></i> Agregar Nuevo
                </button>
              </h1>
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
                      <center>Nombre del cliente</center>
                    </th>
                    <th>
                      <center>Plan</center>
                    </th>
                    <th>
                      <center>Fecha Inicio</center>
                    </th>
                    <th>
                      <center>Fecha Fin</center>
                    </th>
                    <th>
                      <center>Tiempo Restante</center>
                    </th>
                    <th>
                      <center>Descuento</center>
                    </th>
                    <th>
                      <center>Monto final (S/.)</center>
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
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  foreach ($matriculas_datos as $matriculas_dato) {
                    $id_matricula = $matriculas_dato['id_matricula'];
                    $id_cliente_matricula = $matriculas_dato['id_cliente_matricula']; // Obtener ID del cliente
                    $nombre_completo_cliente = $matriculas_dato['nombre_completo_cliente'];
                    $nombre_plan = $matriculas_dato['nombre_plan'];

                    // Calcular el estado de pago
                    $monto_final = $matriculas_dato['monto_final_matricula'];
                    $total_pagado = $matriculas_dato['total_pagado'] ?? 0; // Usar 0 si es NULL (no hay pagos)
                  
                    $estado_pago = '';
                    $clase_estado = '';
                    $monto_pendiente = $monto_final - $total_pagado;

                    $estado = $matriculas_dato['estado'] ?? 1; // Default to 1 (active) if not set
                  
                    if ($estado == 0) {
                      $estado_pago = 'Anulado';
                      $clase_estado = 'badge badge-danger';
                    } else if ($total_pagado >= $monto_final) {
                      $estado_pago = 'Pagado';
                      $clase_estado = 'badge badge-success'; // Clase para un badge verde
                    } else if ($total_pagado > 0 && $total_pagado < $monto_final) {
                      $estado_pago = 'Parcial (Falta S/. ' . number_format($monto_pendiente, 2) . ')';
                      $clase_estado = 'badge badge-warning'; // Clase para un badge amarillo
                    } else {
                      $estado_pago = 'Pendiente (S/. ' . number_format($monto_final, 2) . ')';
                      $clase_estado = 'badge badge-danger'; // Clase para un badge rojo
                    }
                    $row_style = ($estado == 0) ? 'style="background-color: #f8d7da; color: #721c24; text-decoration: line-through;"' : '';

                    // Cálculo de días restantes
                    $fecha_fin = $matriculas_dato['fecha_fin_matricula'];
                    $fecha_actual = date('Y-m-d');
                    $fecha_actual_obj = new DateTime($fecha_actual);
                    $fecha_fin_obj = new DateTime($fecha_fin);
                    $diferencia = $fecha_actual_obj->diff($fecha_fin_obj);
                    $dias_restantes = (int) $diferencia->format('%r%a');

                    $fecha_fin_congelamiento = $matriculas_dato['fecha_fin_congelamiento'] ?? null;
                    $esta_congelado = !empty($fecha_fin_congelamiento);

                    if ($estado == 0) {
                      $texto_tiempo = 'Anulado';
                      $clase_tiempo = 'badge badge-secondary';
                    } else if ($esta_congelado) {
                      $fecha_descongelamiento = date('d/m/Y', strtotime($fecha_fin_congelamiento));
                      $texto_tiempo = '<i class="fas fa-snowflake"></i> CONGELADO (Hasta: ' . $fecha_descongelamiento . ')';
                      $clase_tiempo = 'badge badge-info';
                    } else if ($dias_restantes < 0) {
                      $dias_vencidos = abs($dias_restantes);
                      $texto_tiempo = 'Vencido hace ' . $dias_vencidos . ' días';
                      $clase_tiempo = 'badge badge-danger';
                    } elseif ($dias_restantes == 0) {
                      $texto_tiempo = 'Vence Hoy';
                      $clase_tiempo = 'badge badge-warning';
                    } elseif ($dias_restantes <= 3) {
                      $texto_tiempo = 'Quedan ' . $dias_restantes . ' días';
                      $clase_tiempo = 'badge badge-warning';
                    } else {
                      $texto_tiempo = 'Quedan ' . $dias_restantes . ' días';
                      $clase_tiempo = 'badge badge-success';
                    }

                    // Ajuste de estilo para fila congelada 
                    if ($esta_congelado) {
                      $row_style = 'style="background-color: #e3f2fd;"'; // Azul muy clarito
                    }

                    ?>
                    <tr <?php echo $row_style; ?>>
                      <td>
                        <center><?php echo $contador = $contador + 1; ?></center>
                      </td>
                      <td>
                        <center><?php echo $nombre_completo_cliente; ?></center>
                      </td>
                      <td>
                        <center><?php echo $nombre_plan; ?></center>
                      </td>
                      <td>
                        <center><?php echo $matriculas_dato['fecha_inicio_matricula']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $matriculas_dato['fecha_fin_matricula']; ?></center>
                      </td>
                      <td>
                        <center>
                          <span class="<?php echo $clase_tiempo; ?>"><?php echo $texto_tiempo; ?></span>
                        </center>
                      </td>
                      <td>
                        <center><?php echo $matriculas_dato['descuento_matricula']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $matriculas_dato['monto_final_matricula']; ?></center>
                      </td>
                      <td>
                        <center><span class="<?php echo $clase_estado; ?>"><?php echo $estado_pago; ?></span></center>
                      </td>


                      <td>
                        <center>
                          <div class="btn-group">
                            <?php if ($estado == 1) {
                              if ($total_pagado == 0) { ?>
                                <a href="update.php?id=<?php echo $id_matricula; ?>" type="button" class="btn btn-success"><i
                                    class="fa fa-pencil-alt"></i> Editar</a>
                              <?php }
                            } else { ?>
                              <span class="badge badge-danger">ANULADO</span>
                            <?php } ?>

                            <?php if ($estado == 1): ?>
                              <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#modal-freeze<?php echo $id_matricula; ?>" title="Congelar Membresía">
                                <i class="far fa-snowflake"></i>
                              </button>
                              <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#modal-delete<?php echo $id_matricula; ?>" title="Anular Matrícula">
                                <i class="fa fa-ban"></i>
                              </button>
                            <?php endif; ?>
                            <!-- modal para eliminar matrícula -->
                            <div class="modal fade" id="modal-delete<?php echo $id_matricula; ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color: #CC3E3E; color: white">
                                    <h4 class="modal-title">Anulación de matrícula</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">

                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="">Matrícula de cliente</label>
                                          <input type="text"
                                            value="<?php echo $nombre_completo_cliente; ?> (Plan: <?php echo $nombre_plan; ?>)"
                                            class="form-control" disabled>
                                          <p class="text-danger mt-2"><strong>¡Atención!</strong> Al anular la matrícula,
                                            también se anularán todos los pagos asociados.</p>

                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <form action="../app/controllers/matriculas/delete_matricula.php" method="post">
                                      <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">
                                      <button type="submit" class="btn btn-danger">Anular</button>
                                    </form>

                                  </div>
                                </div>
                                <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                            </div>
                          </div>
                          <!-- /.modal -->

                          <!-- Modal para Congelar/Descongelar Matrícula -->
                          <div class="modal fade" id="modal-freeze<?php echo $id_matricula; ?>">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <?php if ($esta_congelado): ?>
                                  <!-- ESTADO: YA CONGELADO - MOSTRAR OPCIONES DE DESCONGELAR -->
                                  <div class="modal-header bg-info">
                                    <h4 class="modal-title">Administrar Congelamiento</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="alert alert-warning">
                                      <h5><i class="icon fas fa-exclamation-triangle"></i> ¡Estado Congelado!</h5>
                                      Esta matrícula se encuentra congelada hasta el
                                      <strong><?php echo date('d/m/Y', strtotime($fecha_fin_congelamiento)); ?></strong>.
                                    </div>
                                    <p class="text-center">¿Qué desea hacer?</p>

                                    <div class="d-flex justify-content-center mt-4">
                                      <!-- Formulario para Descongelar Ahora -->
                                      <form action="../app/controllers/matriculas/descongelar_matricula.php" method="post"
                                        onsubmit="return confirm('¿Está seguro de reactivar la membresía hoy? Se ajustará la fecha de vencimiento.');"
                                        class="mr-3">
                                        <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">
                                        <input type="hidden" name="accion" value="descongelar">
                                        <button type="submit" class="btn btn-success btn-lg">
                                          <i class="fas fa-play"></i> Reactivar Ahora
                                        </button>
                                        <div class="text-muted text-center mt-2 px-2" style="font-size: 0.8rem;">
                                          Finaliza el congelamiento hoy.
                                        </div>
                                      </form>

                                      <!-- Formulario para Cancelar/Anular -->
                                      <form action="../app/controllers/matriculas/descongelar_matricula.php" method="post"
                                        onsubmit="return confirm('¿Está seguro de ANULAR este congelamiento? La fecha de vencimiento volverá a su estado original.');">
                                        <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">
                                        <input type="hidden" name="accion" value="cancelar">
                                        <button type="submit" class="btn btn-danger btn-lg">
                                          <i class="fas fa-times"></i> Anular
                                        </button>
                                        <div class="text-muted text-center mt-2 px-2" style="font-size: 0.8rem;">
                                          Elimina el congelamiento (Corrección).
                                        </div>
                                      </form>
                                    </div>

                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                  </div>

                                <?php else: ?>
                                  <!-- ESTADO: NORMAL - MOSTRAR FORMULARIO DE CONGELAR -->
                                  <div class="modal-header bg-info">
                                    <h4 class="modal-title">Congelar Matrícula</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form action="../app/controllers/matriculas/congelar_matricula.php" method="post">
                                    <div class="modal-body">
                                      <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">

                                      <div class="form-group">
                                        <label>Cliente:</label>
                                        <input type="text" class="form-control"
                                          value="<?php echo $nombre_completo_cliente; ?>" disabled>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label>Fecha Inicio Congelamiento:</label>
                                            <input type="date" name="fecha_inicio" class="form-control" required
                                              min="<?php echo date('Y-m-d'); ?>">
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label>Fecha Fin Congelamiento:</label>
                                            <input type="date" name="fecha_fin" class="form-control" required
                                              min="<?php echo date('Y-m-d'); ?>">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label>Motivo:</label>
                                        <textarea name="motivo" class="form-control" rows="2"
                                          placeholder="Ej. Viaje, Salud..."></textarea>
                                      </div>

                                      <div class="alert alert-info">
                                        <i class="icon fas fa-info"></i> Al congelar, la fecha de vencimiento se extenderá
                                        automáticamente por los días congelados y el acceso será bloqueado durante este
                                        periodo.
                                      </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                      <button type="submit" class="btn btn-warning">Congelar</button>
                                    </div>
                                  </form>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                          <!-- /Modal Freeze -->
                        </center>
                      </td>
                      <td>
                        <center>
                          <?php if ($estado == 1): ?>
                            <?php if (!empty($matriculas_dato['tiene_cronograma']) && $matriculas_dato['tiene_cronograma'] > 0): ?>
                              <!-- Si tiene cronograma, SOLO mostramos botón Cronograma -->
                              <button type="button" class="btn btn-primary btn-sm btn-cronograma"
                                data-id="<?php echo $id_matricula; ?>">
                                <i class="fas fa-list-alt"></i> Cronograma
                              </button>
                            <?php else: ?>
                              <!-- Si NO tiene cronograma (matrícula normal o antigua), mostramos Agregar Pago si falta saldo -->
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#modal-add-payment<?php echo $id_matricula; ?>">
                                <i class="fa fa-dollar-sign"></i> Agregar Pago
                              </button>
                            <?php endif; ?>

                          <?php else: ?>
                            <button type="button" class="btn btn-info btn-sm" disabled>
                              <i class="fa fa-dollar-sign"></i> Agregar Pago
                            </button>
                          <?php endif; ?>
                        </center>

                        <!-- Modal para Agregar Pago -->
                        <div class="modal fade" id="modal-add-payment<?php echo $id_matricula; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header" style="background-color: #17a2b8; color: white">
                                <h4 class="modal-title">Registrar Pago para Matrícula</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form action="../app/controllers/matriculas/add_payment_to_matricula.php" method="post">
                                <div class="modal-body">
                                  <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">
                                  <input type="hidden" name="id_cliente" value="<?php echo $id_cliente_matricula; ?>">

                                  <div class="form-group">
                                    <label>Matrícula de Cliente:</label>
                                    <input type="text" class="form-control"
                                      value="<?php echo $nombre_completo_cliente; ?> (Plan: <?php echo $nombre_plan; ?>)"
                                      disabled>
                                  </div>
                                  <div class="form-group">
                                    <label>Monto Final Matrícula (S/.):</label>
                                    <input type="text" class="form-control"
                                      value="<?php echo number_format($monto_final, 2); ?>" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label>Total Pagado hasta ahora (S/.):</label>
                                    <input type="text" class="form-control"
                                      value="<?php echo number_format($total_pagado, 2); ?>" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="monto_pago_<?php echo $id_matricula; ?>">Monto a Pagar (S/.):</label>
                                    <input type="number" name="monto_pagado" class="form-control"
                                      id="monto_pago_<?php echo $id_matricula; ?>" placeholder="Ingrese el monto del pago"
                                      step="0.01" required
                                      value="<?php echo number_format($monto_pendiente > 0 ? $monto_pendiente : 0, 2, '.', ''); ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="metodo_pago_modal_<?php echo $id_matricula; ?>">Método de Pago:</label>
                                    <select name="metodo_pago" id="metodo_pago_modal_<?php echo $id_matricula; ?>"
                                      class="form-control" required>
                                      <option value="">Seleccione un método</option>
                                      <option value="efectivo">Efectivo</option>
                                      <option value="tarjeta_debito">Tarjeta de Débito</option>
                                      <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                      <option value="yape">Yape</option>
                                      <option value="plin">Plin</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-info">Registrar Pago</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <center>
                          <a href="../app/controllers/matriculas/generar_boleta_matricula.php?id=<?php echo $id_matricula; ?>"
                            target="_blank" class="btn btn-dark btn-sm">
                            <i class="fa fa-print"></i> Boleta
                          </a>
                        </center>
                      </td>
                    </tr>

                    <?php
                  }
                  ?>
                </tbody>

                <tfoot>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombre del cliente</center>
                    </th>
                    <th>
                      <center>Plan</center>
                    </th>
                    <th>
                      <center>Fecha Inicio</center>
                    </th>
                    <th>
                      <center>Fecha Fin</center>
                    </th>
                    <th>
                      <center>Tiempo Restante</center>
                    </th>
                    <th>
                      <center>Descuento</center>
                    </th>
                    <th>
                      <center>Monto final (S/.)</center>
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

<?php include('../layout/parte2.php'); ?>
<?php include('../layout/mensajes.php'); ?>

<script>
  $(document).ready(function () {
    // Delegación de eventos para botones generados dinámicamente o existentes
    $(document).on('click', '.btn-cronograma', function () {
      var idMatricula = $(this).data('id');
      $('#modal-cronograma').modal('show');
      $('#cronograma-content').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando información...</p></div>');

      $.ajax({
        url: '../app/controllers/matriculas/get_cronograma.php',
        type: 'GET',
        data: { id_matricula: idMatricula },
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

<!-- Script para DateRangePicker -->
<script>
  $(function () {
    $("#example1").DataTable({
      /* cambio de idiomas de datatable */
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Matrículas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Matrículas",
        "infoFiltered": "(Filtrado de _MAX_ total Matrículas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Matrículas",
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
      /* fin de idiomas */

      "responsive": true, "lengthChange": true, "autoWidth": false,
      /* Ajuste de botones */
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
        }
        ]
      },
      {
        extend: 'colvis',
        text: 'Visor de columnas'
      }
      ],
      /*Fin de ajuste de botones*/
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