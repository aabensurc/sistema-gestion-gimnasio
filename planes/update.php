<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

include('../app/controllers/planes/edit_plan_data.php');

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
              <h3 class="card-title">Llene los datos con cuidado</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/planes/update_plan_process.php" method="post">
                    <input type="text" name="id_plan" value="<?php echo $id_plan_get; ?>" hidden>

                    <div class="form-group">
                      <label for="">Nombre del plan</label>
                      <input type="text" name="nombre" class="form-control"
                        placeholder="Escriba aquí el nombre del plan..." required value="<?php echo $nombre; ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Precio</label>
                      <input type="number" name="precio" class="form-control"
                        placeholder="Escriba aquí el nombre del plan..." required step="0.01" min="0"
                        value="<?php echo $precio; ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Tipo de Duración</label>
                      <select name="tipo_duracion" id="tipo_duracion_select" class="form-control" required>
                        <option value="">Seleccione una opción</option>
                        <option value="relativa" <?php if ($tipo_duracion == 'relativa')
                          echo 'selected'; ?>>Relativa
                          (Meses/Días)</option>
                        <option value="fija" <?php if ($tipo_duracion == 'fija')
                          echo 'selected'; ?>>Fija (Fecha Fin)
                        </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Duración en meses</label>
                      <input type="number" name="duracion_meses" id="duracion_meses_input" class="form-control"
                        placeholder="Escriba aquí la duración en meses del plan..." min="0"
                        value="<?php echo $duracion_meses; ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Duración en días</label>
                      <input type="number" name="duracion_dias" id="duracion_dias_input" class="form-control"
                        placeholder="Escriba aquí la duración en días..." min="0" value="<?php echo $duracion_dias; ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Fecha de inicio</label>
                      <input type="date" name="fecha_inicio" id="fecha_inicio_input" class="form-control"
                        value="<?php echo $fecha_inicio; ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Fecha de fin</label>
                      <input type="date" name="fecha_fin" id="fecha_fin_input" class="form-control"
                        value="<?php echo $fecha_fin; ?>">
                    </div>

                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-success">Actualizar</button>
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


<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Obtenemos los elementos del DOM usando los IDs añadidos
    const tipoDuracionSelect = document.getElementById('tipo_duracion_select');
    const duracionMesesInput = document.getElementById('duracion_meses_input');
    const duracionDiasInput = document.getElementById('duracion_dias_input');
    const fechaInicioInput = document.getElementById('fecha_inicio_input');
    const fechaFinInput = document.getElementById('fecha_fin_input');

    // Función para bloquear/desbloquear y limpiar los campos
    function toggleFields(selectedType) {

      // Determinar los tipos de duración
      const isRelativa = selectedType === 'relativa';
      const isFija = selectedType === 'fija';

      // 1. Campos de Duración (Meses y Días)
      // Se habilitan si es Relativa.
      duracionMesesInput.disabled = !isRelativa;
      duracionDiasInput.disabled = !isRelativa;

      // Limpiar valores si se deshabilita
      if (!isRelativa) {
        // Al editar, NO limpiamos, solo deshabilitamos, para que el valor original se mantenga en el input.
        // Sin embargo, el valor no será enviado si está 'disabled'. 
        // Para Update, lo mejor es solo deshabilitar para que el usuario vea el valor guardado, pero no pueda cambiarlo a menos que cambie el tipo.
        // Si el tipo es Fija, estos campos DEBEN estar deshabilitados.
      }

      // 2. Fecha de Inicio (Siempre Deshabilitado para la configuración del plan)
      fechaInicioInput.disabled = true;

      // Limpiar valor si se deshabilita (aunque siempre estará deshabilitado, lo limpiamos por si acaso)
      if (selectedType !== 'fija') {
        fechaInicioInput.value = '';
      }

      // 3. Fecha de Fin
      // Se habilita si es Fija.
      fechaFinInput.disabled = !isFija;

      // Limpiar valores si se deshabilita
      if (!isFija) {
        // Si el tipo es Relativa, el campo de fecha fin DEBE estar deshabilitado.
      }
    }

    // 1. Listener para el cambio en el selector
    tipoDuracionSelect.addEventListener('change', function () {
      toggleFields(this.value);
    });

    // 2. Inicialización al cargar la página: ejecuta la lógica inmediatamente 
    // para establecer el estado inicial de los campos basado en el valor PHP cargado.
    toggleFields(tipoDuracionSelect.value);
  });
</script>





<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>