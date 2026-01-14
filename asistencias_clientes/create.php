<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Incluir el controlador para listar clientes (para el select)
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
        <div class="col-md-5">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Llene los datos con cuidado</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/asistencias_clientes/create_asistencia_process.php" method="post">

                    <div class="form-group">
                      <label for="id_cliente">Cliente</label>
                      <select name="id_cliente" id="id_cliente" class="form-control select2bs4 " required>
                        <option value="">Seleccione un cliente</option>
                        <?php
                        if (isset($clientes_datos) && is_array($clientes_datos)) {
                          foreach ($clientes_datos as $clientes_dato) { ?>
                            <option value="<?php echo $clientes_dato['id_cliente']; ?>">
                              <?php echo $clientes_dato['nombres'] . ' ' . $clientes_dato['ape_pat'] . ' ' . $clientes_dato['ape_mat']; ?>
                            </option>
                          <?php }
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="fecha_asistencia">Fecha de Asistencia</label>
                      <input type="date" name="fecha_asistencia" class="form-control" id="fecha_asistencia" required>
                    </div>

                    <div class="form-group">
                      <label for="hora_entrada">Hora de Entrada</label>
                      <input type="time" name="hora_entrada" class="form-control" id="hora_entrada" required>
                    </div>

                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-primary">Registrar Asistencia</button>
                    </div>

                  </form>
                </div>
              </div>
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Función para obtener la fecha de hoy en formato YYYY-MM-DD
    function getTodayDate() {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses son 0-11
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    // Función para obtener la hora actual en formato HH:MM
    function getCurrentTime() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      return `${hours}:${minutes}`;
    }

    // Establecer la fecha y hora por defecto al cargar la página
    document.getElementById('fecha_asistencia').value = getTodayDate();
    document.getElementById('hora_entrada').value = getCurrentTime();
  });
</script>