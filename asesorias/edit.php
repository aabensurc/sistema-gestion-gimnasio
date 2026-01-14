<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php'); 

// Incluir controladores para cargar datos de clientes y entrenadores
include('../app/controllers/clientes/listado_de_clientes.php'); // Para el select de clientes
include('../app/controllers/entrenadores/listado_de_entrenadores.php'); // Para el select de entrenadores

// Controlador para cargar los datos de la asesoría específica a editar
include('../app/controllers/asesorias/edit_asesoria_data.php'); 

// Redirigir si no se encontraron datos de la asesoría (esto ya lo maneja edit_asesoria_data.php)
if (!isset($asesoria_data)) {
    header('Location: ' . $URL . '/asesorias/');
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
          <div class="col-md-6">
          <div class="card card-primary">
          <div class="card-header">
          <h3 class="card-title">Modifique los datos de la asesoría #<?php echo $asesoria_data['id_asesoria']; ?></h3>

    
          </div>

          <div class="card-body" style="display: block;">
                <div class="row">
                    <div class="col-md-12">
                        <form action="../app/controllers/asesorias/update_asesoria_process.php" method="post">
                            <input type="hidden" name="id_asesoria" value="<?php echo $asesoria_data['id_asesoria']; ?>">

                            <div class="form-group">
                              <label for="id_cliente">Cliente</label>
                              <select name="id_cliente" id="id_cliente" class="form-control select2bs4" required>
                                <option value="">Seleccione un cliente</option>
                                <?php
                                  if (isset($clientes_datos) && is_array($clientes_datos)) {
                                      foreach($clientes_datos as $clientes_dato){ ?>
                                        <option value="<?php echo $clientes_dato['id_cliente'];?>"
                                            <?php if(isset($asesoria_data['id_cliente']) && $clientes_dato['id_cliente'] == $asesoria_data['id_cliente']) echo 'selected'; ?>>
                                            <?php echo $clientes_dato['nombres'].' '.$clientes_dato['ape_pat'].' '.$clientes_dato['ape_mat'];?>
                                        </option>
                                      <?php }
                                  }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="id_entrenador">Entrenador</label>
                              <select name="id_entrenador" id="id_entrenador" class="form-control select2bs4" required>
                                <option value="">Seleccione un entrenador</option>
                                <?php
                                  if (isset($entrenadores_datos) && is_array($entrenadores_datos)) {
                                      foreach($entrenadores_datos as $entrenador_dato){ ?>
                                        <option value="<?php echo $entrenador_dato['id_entrenador'];?>"
                                            <?php if(isset($asesoria_data['id_entrenador']) && $entrenador_dato['id_entrenador'] == $asesoria_data['id_entrenador']) echo 'selected'; ?>>
                                            <?php echo $entrenador_dato['nombre'].' '.$entrenador_dato['ape_pat'].' '.$entrenador_dato['ape_mat'];?>
                                        </option>
                                      <?php }
                                  }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">          
                              <label for="monto_final">Monto Final (S/.)</label>
                              <input type="number" name="monto_final" class="form-control" id="monto_final" 
                                     placeholder="Ingrese el monto final de la asesoría..." step="0.01" required
                                     value="<?php echo number_format($asesoria_data['monto_final'], 2, '.', ''); ?>">
                            </div>

                            <div class="form-group">          
                              <label for="fecha_inicio">Fecha Inicio</label>
                              <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio" 
                                     value="<?php echo $asesoria_data['fecha_inicio']; ?>" required>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_fin">Fecha Fin</label>
                              <input type="date" name="fecha_fin" class="form-control" id="fecha_fin" 
                                     value="<?php echo $asesoria_data['fecha_fin']; ?>" required>
                            </div>

                            <hr>
                            <h4>Detalle de Pago (Solo Informativo en Edición)</h4>
                            <div class="form-group">
                                <label>Monto Pagado hasta ahora (S/.):</label>
                                <input type="text" class="form-control" value="<?php echo number_format($asesoria_data['total_pagado'] ?? 0, 2); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Monto Pendiente (S/.):</label>
                                <input type="text" class="form-control" value="<?php echo number_format($asesoria_data['monto_final'] - ($asesoria_data['total_pagado'] ?? 0), 2); ?>" disabled>
                            </div>
                            
                            <hr>
                            <div class="form-group">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Actualizar Asesoría</button>
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
