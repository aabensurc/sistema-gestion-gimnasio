<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(4);


include('../layout/parte1.php');

include('../app/controllers/clientes/listado_de_clientes.php');


?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">


        </div><!-- /.col -->

      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->



  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-12">

          <div class="card card-outline card-primary">
            <div class="card-header">
              <h1 class="m-0">Clientes registrados
                <button type="button" class="btn btn-primary"
                  onclick="location.href='<?php echo $URL; ?>/clientes/create.php'">
                  <i class="fa fa-plus"></i> Agregar Nuevo
                </button>
              </h1>


            </div>

            <div class="card-body" style="display: block;">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Codigo</center>
                    </th>
                    <th>
                      <center>DNI</center>
                    </th>
                    <th>
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Apellido Paterno</center>
                    </th>
                    <th>
                      <center>Apellido Materno</center>
                    </th>
                    <th>
                      <center>Telefono</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Estado</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                    <th>
                      <center>Matrícula</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  foreach ($clientes_datos as $clientes_dato) {
                    $id_cliente = $clientes_dato['id_cliente'];
                    $nombre_ape = $clientes_dato['nombres'] . ' ' . $clientes_dato['ape_pat'];

                    // Lógica para determinar el estado de la matrícula
                    $estado_cliente = '';
                    $clase_badge = '';
                    $texto_boton_matricula = 'Matricular';
                    $clase_boton_matricula = 'btn-primary';
                    $fecha_actual = date('Y-m-d');
                    $fecha_fin_matricula = $clientes_dato['fecha_fin_ultima_matricula']; // Este campo viene de listado_de_clientes.php
                  
                    if (empty($fecha_fin_matricula)) {
                      // No tiene matrícula
                      $estado_cliente = 'Nuevo';
                      $clase_badge = 'badge-success';
                      $texto_boton_matricula = 'Matricular';
                      $clase_boton_matricula = 'btn-primary';
                    } else {
                      // Calcular días restantes
                      $fecha_actual_obj = new DateTime($fecha_actual);
                      $fecha_fin_obj = new DateTime($fecha_fin_matricula);
                      $diferencia = $fecha_actual_obj->diff($fecha_fin_obj);
                      $dias_restantes = (int) $diferencia->format('%r%a'); // %r para signo, %a para días totales
                  
                      if ($dias_restantes >= 0) {
                        // Matrícula Vigente
                        if ($dias_restantes == 0) {
                          $estado_cliente = 'Vence Hoy';
                          $clase_badge = 'badge-warning';
                        } elseif ($dias_restantes <= 3) {
                          $estado_cliente = 'Vigente (' . $dias_restantes . ' días)';
                          $clase_badge = 'badge-warning'; // Alerta visual
                        } else {
                          $estado_cliente = 'Vigente (' . $dias_restantes . ' días)';
                          $clase_badge = 'badge-primary';
                        }
                        $texto_boton_matricula = 'Renovar';
                        $clase_boton_matricula = 'btn-success';
                      } else {
                        // Matrícula Vencida
                        $dias_vencidos = abs($dias_restantes);
                        $estado_cliente = 'Vencido hace ' . $dias_vencidos . ' días';
                        $clase_badge = 'badge-danger';
                        $texto_boton_matricula = 'Renovar';
                        $clase_boton_matricula = 'btn-warning';
                      }
                    }

                    ?>
                    <tr>
                      <td>
                        <center><?php echo $contador = $contador + 1; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['id_cliente']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['dni']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['nombres']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['ape_pat']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['ape_mat']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['telefono']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $clientes_dato['email']; ?></center>
                      </td>

                      <td>
                        <center>
                          <span class="badge <?php echo $clase_badge; ?>"><?php echo $estado_cliente; ?></span>
                        </center>
                      </td>

                      <td>
                        <center>
                          <div class="btn-group">
                            <a href="show.php?id=<?php echo $id_cliente; ?>" type="button" class="btn btn-info"><i
                                class="fa fa-eye"></i> Ver</a>
                            <a href="update.php?id=<?php echo $id_cliente; ?>" type="button" class="btn btn-success"><i
                                class="fa fa-pencil-alt"></i> Editar</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                              data-target="#modal-delete<?php echo $id_cliente; ?>">
                              <i class="fa fa-trash-alt"></i> Eliminar
                            </button>
                            <div class="modal fade" id="modal-delete<?php echo $id_cliente; ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color: #CC3E3E; color: white">
                                    <h4 class="modal-title">Eliminación de cliente</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">

                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="">Nombre del cliente</label>
                                          <input type="text" id="nombre_ape<?php echo $id_cliente; ?>"
                                            value="<?php echo $nombre_ape; ?>" class="form-control" disabled>

                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger"
                                      id="btn_delete<?php echo $id_cliente; ?>">Eliminar</button>

                                  </div>
                                </div>
                              </div>
                            </div>
                            <script>
                              $('#btn_delete<?php echo $id_cliente; ?>').click(function () {

                                var nombre_ape = $('#nombre_ape<?php echo $id_cliente; ?>').val();
                                var id_cliente = '<?php echo $id_cliente; ?>';

                                var url = "../app/controllers/clientes/delete_cliente.php";
                                $.get(url, { id_cliente: id_cliente }, function (datos) {
                                  $('#respuesta_update<?php echo $id_cliente; ?>').html(datos);
                                });
                              });
                            </script>
                            <div id="respuesta_update<?php echo $id_cliente; ?>"></div>
                          </div>
                        </center>
                      </td>

                      <td>
                        <center>
                          <a href="<?php echo $URL; ?>/matriculas/create.php?id_cliente=<?php echo $id_cliente; ?>"
                            type="button" class="btn <?php echo $clase_boton_matricula; ?> btn-sm">
                            <i class="fa fa-user-plus"></i> <?php echo $texto_boton_matricula; ?>
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
                      <center>Codigo</center>
                    </th>
                    <th>
                      <center>DNI</center>
                    </th>
                    <th>
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Apellido Paterno</center>
                    </th>
                    <th>
                      <center>Apellido Materno</center>
                    </th>
                    <th>
                      <center>Telefono</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Estado</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                    <th>
                      <center>Matrícula</center>
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

<script>
  $(function () {
    $("#example1").DataTable({
      /* cambio de idiomas de datatable */
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Clientes",
        "infoEmpty": "Mostrando 0 to 0 of 0 Clientes",
        "infoFiltered": "(Filtrado de _MAX_ total Clientes)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Clientes",
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
        text: 'Visol de columnas'
      }
      ],
      /*Fin de ajuste de botones*/
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>