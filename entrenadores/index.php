<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(10);



include('../layout/parte1.php');

// Incluir el controlador para listar los entrenadores
include('../app/controllers/entrenadores/listado_de_entrenadores.php');

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
              <h1 class="m-0">Entrenadores registrados
                <button type="button" class="btn btn-primary"
                  onclick="location.href='<?php echo $URL; ?>/entrenadores/create.php'">
                  <i class="fa fa-plus"></i> Nuevo Entrenador
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
                    <!-- <th><center>Foto</center></th> --><!-- Columna de foto eliminada -->
                    <th>
                      <center>Nombre Completo</center>
                    </th>
                    <th>
                      <center>DNI</center>
                    </th>
                    <th>
                      <center>Teléfono</center>
                    </th>
                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  if (isset($entrenadores_datos) && is_array($entrenadores_datos)) {
                    foreach ($entrenadores_datos as $entrenador_dato) {
                      $contador++;
                      $id_entrenador = $entrenador_dato['id_entrenador'];
                      $nombre_completo = $entrenador_dato['nombre'] . ' ' . $entrenador_dato['ape_pat'] . ' ' . $entrenador_dato['ape_mat'];
                      $foto_entrenador = $entrenador_dato['foto'];
                      ?>
                      <tr>
                        <td>
                          <center><?php echo $contador; ?></center>
                        </td>
                        <!-- <td><center>
                            <img src="<?php echo $URL . '/public/img/entrenadores/' . $foto_entrenador; ?>" 
                                 alt="Foto Entrenador" width="50px" height="50px" style="border-radius: 50%;">
                        </center></td> -->
                        <td>
                          <center><?php echo $nombre_completo; ?></center>
                        </td>
                        <td>
                          <center><?php echo $entrenador_dato['dni']; ?></center>
                        </td>
                        <td>
                          <center><?php echo $entrenador_dato['telefono']; ?></center>
                        </td>
                        <td>
                          <center><?php echo $entrenador_dato['email']; ?></center>
                        </td>
                        <td>
                          <center>
                            <div class="btn-group">
                              <a href="show.php?id=<?php echo $id_entrenador; ?>" type="button"
                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver</a>
                              <a href="edit.php?id=<?php echo $id_entrenador; ?>" type="button"
                                class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>
                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#modal-delete-entrenador<?php echo $id_entrenador; ?>">
                                <i class="fa fa-trash-alt"></i> Eliminar
                              </button>
                            </div>
                          </center>
                        </td>
                      </tr>
                      <!-- Modal para Eliminar Entrenador -->
                      <div class="modal fade" id="modal-delete-entrenador<?php echo $id_entrenador; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #CC3E3E; color: white">
                              <h4 class="modal-title">Eliminación de Entrenador</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="../app/controllers/entrenadores/delete_entrenador.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_entrenador" value="<?php echo $id_entrenador; ?>">
                                <p>¿Está seguro de que desea eliminar al entrenador **<?php echo $nombre_completo; ?>**?</p>
                                <small class="text-danger">Advertencia: Esto podría afectar asesorías vinculadas.</small>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
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
                    <!-- <th><center>Foto</center></th> --><!-- Columna de foto eliminada -->
                    <th>
                      <center>Nombre Completo</center>
                    </th>
                    <th>
                      <center>DNI</center>
                    </th>
                    <th>
                      <center>Teléfono</center>
                    </th>
                    <th>
                      <center>Email</center>
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
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entrenadores",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entrenadores",
        "infoFiltered": "(Filtrado de _MAX_ total Entrenadores)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entrenadores",
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
      "responsive": true, "lengthChange": true, "autoWidth": false,
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
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>