<?php

include('../app/config.php');
include('../layout/sesion.php');

// Incluir el controlador para cargar los datos del entrenador específico
include('../app/controllers/entrenadores/show_entrenador_data.php');

include('../layout/parte1.php');

// Redirigir si no se encontraron datos del entrenador
if (!isset($entrenador_data)) {
  header('Location: ' . $URL . '/entrenadores/');
  exit();
}

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
        <div class="col-md-4">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Información del Entrenador</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Foto</label>
                    <center><img src="<?php echo $URL . '/public/images/entrenadores/' . $entrenador_data['foto']; ?>"
                        name="foto" style="max-height: 200px;"></center> <!-- Se eliminó border-radius: 50%; -->
                  </div>
                  <div class="form-group">
                    <label for="">ID Entrenador</label>
                    <input type="text" name="id_entrenador" class="form-control"
                      value="<?php echo $entrenador_data['id_entrenador']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Nombres</label>
                    <input type="text" name="nombre" class="form-control"
                      value="<?php echo $entrenador_data['nombre']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Apellido Paterno</label>
                    <input type="text" name="ape_pat" class="form-control"
                      value="<?php echo $entrenador_data['ape_pat']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Apellido Materno</label>
                    <input type="text" name="ape_mat" class="form-control"
                      value="<?php echo $entrenador_data['ape_mat']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">DNI</label>
                    <input type="text" name="dni" class="form-control" value="<?php echo $entrenador_data['dni']; ?>"
                      disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                      value="<?php echo $entrenador_data['telefono']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" name="email" class="form-control"
                      value="<?php echo $entrenador_data['email']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Fecha de Creación</label>
                    <input type="text" class="form-control" value="<?php echo $entrenador_data['fyh_creacion']; ?>"
                      disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Última Actualización</label>
                    <input type="text" class="form-control" value="<?php echo $entrenador_data['fyh_actualizacion']; ?>"
                      disabled>
                  </div>

                  <hr>
                  <div class="form-group">
                    <a href="index.php" class="btn btn-secondary">Volver</a>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Nueva columna para Listado de Clientes -->
        <div class="col-md-8">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Clientes Asignados</h3>

            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Cliente</center>
                    </th>
                    <th>
                      <center>Fecha Inicio</center>
                    </th>
                    <th>
                      <center>Fecha Fin</center>
                    </th>
                    <th>
                      <center>Estado</center>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $contador_clientes = 0;
                  $fecha_actual = date('Y-m-d');
                  foreach ($clientes_asignados as $cliente) {
                    $contador_clientes++;
                    $fecha_fin = $cliente['fecha_fin'];
                    if ($fecha_fin >= $fecha_actual) {
                      $estado_asesoria = "Vigente";
                      $badge_class = "badge badge-success";
                    } else {
                      $estado_asesoria = "Vencida";
                      $badge_class = "badge badge-danger";
                    }
                    ?>
                    <tr>
                      <td>
                        <center><?php echo $contador_clientes; ?></center>
                      </td>
                      <td><?php echo $cliente['nombre_cliente']; ?></td>
                      <td>
                        <center><?php echo $cliente['fecha_inicio']; ?></center>
                      </td>
                      <td>
                        <center><?php echo $fecha_fin; ?></center>
                      </td>
                      <td>
                        <center><span class="<?php echo $badge_class; ?>"><?php echo $estado_asesoria; ?></span></center>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
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

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>