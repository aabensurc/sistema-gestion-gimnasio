<?php

include('../app/config.php');
include('../layout/sesion.php');

// =========================================================================
// INICIO DE ZONA DE SEGURIDAD (DEBE IR ANTES DE CUALQUIER SALIDA HTML/parte1.php)
// =========================================================================

// 1. INCLUIR el controlador de permisos (define la función requirePermiso)
include('../app/controllers/verificar_permisos.php');

// 2. VALIDAR el acceso. El ID 2 corresponde al permiso 'Usuarios'
// Si el usuario no tiene el ID 2 en su lista de permisos, SERÁ REDIRIGIDO AQUÍ
requirePermiso(2);

// =========================================================================
// FIN DE ZONA DE SEGURIDAD
// =========================================================================

// AHORA SÍ, incluimos la PARTE HTML
include('../layout/parte1.php');

// Incluimos el controlador de datos después de parte1.php
include('../app/controllers/usuarios/listado_de_usuarios.php');


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
              <h1 class="m-0">Usuarios registrados
                <button type="button" class="btn btn-primary"
                  onclick="location.href='<?php echo $URL; ?>/usuarios/create.php'">
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
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Rol del usuario</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  foreach ($usuarios_datos as $usuarios_dato) {
                    $id_usuario = $usuarios_dato['id_usuario'];
                    $nombre_usuario = $usuarios_dato['nombres']; ?>
                    <tr>
                      <td>
                        <center><?php echo $contador = $contador + 1; ?></center>
                      </td>
                      <td><?php echo $usuarios_dato['nombres']; ?></td>
                      <td><?php echo $usuarios_dato['email']; ?></td>
                      <td>
                        <center><?php echo $usuarios_dato['rol']; ?></center>
                      </td>
                      <td>
                        <center>
                          <div class="btn-group">
                            <a href="show.php?id=<?php echo $id_usuario; ?>" type="button" class="btn btn-info"><i
                                class="fa fa-eye"></i> Ver</a>
                            <a href="update.php?id=<?php echo $id_usuario; ?>" type="button" class="btn btn-success"><i
                                class="fa fa-pencil-alt"></i> Editar</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                              data-target="#modal-delete<?php echo $id_usuario; ?>">
                              <i class="fa fa-trash-alt"></i> Eliminar
                            </button>
                            <!-- modal para eliminar usuario -->
                            <div class="modal fade" id="modal-delete<?php echo $id_usuario; ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color: #CC3E3E; color: white">
                                    <h4 class="modal-title">Eliminación de usuario</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">

                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="">Nombre del usuario</label>
                                          <input type="text" id="nombre_usuario<?php echo $id_usuario; ?>"
                                            value="<?php echo $nombre_usuario; ?>" class="form-control" disabled>

                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger"
                                      id="btn_delete<?php echo $id_usuario; ?>">Eliminar</button>

                                  </div>
                                </div>
                                <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->

                            <script>
                              $('#btn_delete<?php echo $id_usuario; ?>').click(function () {

                                var nombre_usuario = $('#nombre_usuario<?php echo $id_usuario; ?>').val();
                                var id_usuario = '<?php echo $id_usuario; ?>';

                                var url = "../app/controllers/usuarios/delete_usuario.php";
                                $.get(url, { id_usuario: id_usuario }, function (datos) {
                                  $('#respuesta_update<?php echo $id_usuario; ?>').html(datos);
                                });
                              });
                            </script>
                            <div id="respuesta_update<?php echo $id_usuario; ?>"></div>
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
                      <center>Nombres</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Rol del usuario</center>
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
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
        "infoEmpty": "Mostrando 0 to 0 of 0 Usuarios",
        "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Usuarios",
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