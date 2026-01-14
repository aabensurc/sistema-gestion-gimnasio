<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(5);


include('../layout/parte1.php');

include('../app/controllers/planes/listado_de_planes.php');


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
              <h1 class="m-0">Planes registrados
                <button type="button" class="btn btn-primary"
                  onclick="location.href='<?php echo $URL; ?>/planes/create.php'">
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
                      <center>Nombre del plan</center>
                    </th>
                    <th>
                      <center>Precio</center>
                    </th>
                    <th>
                      <center>Tipo de duración</center>
                    </th>
                    <th>
                      <center>Duración meses</center>
                    </th>
                    <th>
                      <center>Duración días</center>
                    </th>
                    <th>
                      <center>Fecha inicio</center>
                    </th>
                    <th>
                      <center>Fecha fin</center>
                    </th>
                    <th>
                      <center>Estado</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  foreach ($planes_datos as $planes_dato) {
                    $id_plan = $planes_dato['id_plan'];
                    $nombre = $planes_dato['nombre'];
                    $estado = isset($planes_dato['estado']) ? $planes_dato['estado'] : '1'; // Default to 1 if not set in old query
                    ?>
                    <tr>
                      <td>
                        <center><?php echo $contador = $contador + 1; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['nombre']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['precio']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['tipo_duracion']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['duracion_meses']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['duracion_dias']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['fecha_inicio']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $planes_dato['fecha_fin']; ?></center>
                      </td>
                      <td>
                        <center>
                          <?php if ($estado == '1'): ?>
                            <span class="badge badge-success">Activo</span>
                          <?php else: ?>
                            <span class="badge badge-danger">Inactivo</span>
                          <?php endif; ?>
                        </center>
                      </td>


                      <td>
                        <center>
                          <div class="btn-group">

                            <a href="update.php?id=<?php echo $id_plan; ?>" type="button" class="btn btn-success"><i
                                class="fa fa-pencil-alt"></i> Editar</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                              data-target="#modal-delete<?php echo $id_plan; ?>">
                              <i class="fa fa-trash-alt"></i> Eliminar
                            </button>

                            <!-- Botón de Estado -->
                            <?php if ($estado == '1'): ?>
                              <button class="btn btn-secondary" onclick="cambiarEstado(<?php echo $id_plan; ?>, 1)">
                                <i class="fas fa-eye-slash"></i> Desactivar
                              </button>
                            <?php else: ?>
                              <button class="btn btn-primary" onclick="cambiarEstado(<?php echo $id_plan; ?>, 0)">
                                <i class="fas fa-eye"></i> Activar
                              </button>
                            <?php endif; ?>
                            <!-- modal para eliminar usuario -->
                            <div class="modal fade" id="modal-delete<?php echo $id_plan; ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color: #CC3E3E; color: white">
                                    <h4 class="modal-title">Eliminación de plan</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">

                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="">Nombre del plan</label>
                                          <input type="text" id="nombre<?php echo $id_plan; ?>"
                                            value="<?php echo $nombre; ?>" class="form-control" disabled>

                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger"
                                      id="btn_delete<?php echo $id_plan; ?>">Eliminar</button>

                                  </div>
                                </div>
                                <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <script>
                              $('#btn_delete<?php echo $id_plan; ?>').click(function () {

                                var nombre = $('#nombre<?php echo $id_plan; ?>').val();
                                var id_plan = '<?php echo $id_plan; ?>';

                                var url = "../app/controllers/planes/delete_plan.php";
                                $.get(url, { id_plan: id_plan }, function (datos) {
                                  $('#respuesta_update<?php echo $id_plan; ?>').html(datos);
                                });
                              });
                            </script>
                            <div id="respuesta_update<?php echo $id_plan; ?>"></div>


                          </div>
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
                      <center>Nombre del plan</center>
                    </th>
                    <th>
                      <center>Precio</center>
                    </th>
                    <th>
                      <center>Tipo de duración</center>
                    </th>
                    <th>
                      <center>Duración meses</center>
                    </th>
                    <th>
                      <center>Duración días</center>
                    </th>
                    <th>
                      <center>Fecha inicio</center>
                    </th>
                    <th>
                      <center>Fecha fin</center>
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

<script>
  $(function () {
    $("#example1").DataTable({
      /* cambio de idiomas de datatable */
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Planes",
        "infoEmpty": "Mostrando 0 to 0 of 0 Planes",
        "infoFiltered": "(Filtrado de _MAX_ total Planes)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Planes",
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

  function cambiarEstado(id_plan, estado_actual) {
    var url = "../app/controllers/planes/update_status.php";
    $.get(url, { id_plan: id_plan, estado: estado_actual }, function (response) {
      if (response == "success") {
        location.reload();
      } else {
        alert("Error al cambiar el estado");
      }
    });
  }
</script>