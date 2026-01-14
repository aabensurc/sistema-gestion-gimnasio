<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php'); 

// Incluir el controlador para cargar los datos del cliente (para el select)
include('../app/controllers/clientes/listado_de_clientes.php');

// Incluir el controlador para cargar los datos de la asistencia específica a editar
include("../app/controllers/asistencias_clientes/edit_asistencia_data.php");

// Redirigir si no se encontraron datos de la asistencia
if (!isset($asistencia_data)) {
    header('Location: ' . $URL . '/asistencias_clientes/');
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
          <div class="col-md-5">
          <div class="card card-success">
          <div class="card-header">
          <h3 class="card-title">Modifique los datos con cuidado</h3>

    
          </div>

          <div class="card-body" style="display: block;">
                <div class="row">
                  <div class="col-md-12">
                    
                  <form action="../app/controllers/asistencias_clientes/update_asistencia_process.php" method="post">
                  <input type="hidden" name="id_asistencia" value="<?php echo $asistencia_data['id_asistencia']; ?>">
                  
                        <div class="form-group">
                          <label for="id_cliente">Cliente</label>
                          <select name="id_cliente" id="id_cliente" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            <?php
                              if (isset($clientes_datos) && is_array($clientes_datos)) {
                                  foreach($clientes_datos as $clientes_dato){ ?>
                                    <option value="<?php echo $clientes_dato['id_cliente'];?>"
                                        <?php if(isset($asistencia_data['id_cliente']) && $clientes_dato['id_cliente'] == $asistencia_data['id_cliente']) echo 'selected'; ?>>
                                        <?php echo $clientes_dato['nombres'].' '.$clientes_dato['ape_pat'].' '.$clientes_dato['ape_mat'];?>
                                    </option>
                                  <?php }
                              }
                             ?>
                          </select>
                        </div>

                        <div class="form-group">              
                          <label for="fecha_asistencia">Fecha de Asistencia</label>
                          <input type="date" name="fecha_asistencia" class="form-control" id="fecha_asistencia" 
                                 value="<?php echo $asistencia_data['fecha_asistencia']; ?>" required>
                        </div>

                        <div class="form-group">              
                          <label for="hora_entrada">Hora de Entrada</label>
                          <input type="time" name="hora_entrada" class="form-control" id="hora_entrada" 
                                 value="<?php echo $asistencia_data['hora_entrada']; ?>" required>
                        </div>

                        <hr>
                        <div class="form-group">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Actualizar Asistencia</button>
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
