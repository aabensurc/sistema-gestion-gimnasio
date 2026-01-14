<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php'); 

// Incluir controladores para cargar datos de clientes y planes
include('../app/controllers/clientes/listado_de_clientes.php'); // Para el select de clientes
include('../app/controllers/planes/listado_de_planes.php');     // Para el select de planes

// Controlador para cargar los datos de la matrícula específica a editar
// Este archivo debe obtener el ID de la URL y cargar los datos de la matrícula
include('../app/controllers/matriculas/edit_matricula_data.php'); 

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
                        <form action="../app/controllers/matriculas/update_matricula_process.php" method="post">
                            <!-- Campo oculto para el ID de la matrícula que se va a actualizar -->
                            <input type="hidden" name="id_matricula" value="<?php echo $id_matricula_get; ?>">
                            
                            <div class="form-group">
                              <label for="id_cliente">Nombre del cliente</label>
                              <select name="id_cliente" id="id_cliente" class="form-control select2bs4" required>
                                <option value="">Seleccione un cliente</option>
                                <?php
                                  foreach($clientes_datos as $clientes_dato){ ?>
                                    <option value="<?php echo $clientes_dato['id_cliente'];?>"
                                        <?php if(isset($matricula_data['id_cliente']) && $clientes_dato['id_cliente'] == $matricula_data['id_cliente']) echo 'selected'; ?>>
                                        <?php echo $clientes_dato['nombres'].' '.$clientes_dato['ape_pat'].' '.$clientes_dato['ape_mat'];?>
                                    </option>
                                  <?php }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="id_plan">Nombre del Plan</label>
                              <select name="id_plan" id="id_plan" class="form-control select2bs4" required>
                                <option value="">Seleccione un plan</option>
                                <?php
                                  foreach($planes_datos as $planes_dato){ ?>
                                    <option value="<?php echo $planes_dato['id_plan'];?>"
                                            data-tipo-duracion="<?php echo $planes_dato['tipo_duracion'];?>"
                                            data-duracion-meses="<?php echo $planes_dato['duracion_meses'];?>"
                                            data-duracion-dias="<?php echo $planes_dato['duracion_dias'];?>"
                                            data-fecha-fin-fija="<?php echo $planes_dato['fecha_fin'];?>"
                                            data-precio="<?php echo $planes_dato['precio'];?>"
                                            <?php if(isset($matricula_data['id_plan']) && $planes_dato['id_plan'] == $matricula_data['id_plan']) echo 'selected'; ?>>
                                        <?php echo $planes_dato['nombre'];?> - S/.<?php echo $planes_dato['precio'];?>
                                    </option>
                                  <?php }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_inicio_matricula">Fecha Inicio</label>
                              <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio_matricula" 
                                     value="<?php echo isset($matricula_data['fecha_inicio']) ? $matricula_data['fecha_inicio'] : ''; ?>" required>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_fin_matricula">Fecha Fin</label>
                              <input type="date" name="fecha_fin" class="form-control" id="fecha_fin_matricula" 
                                     value="<?php echo isset($matricula_data['fecha_fin']) ? $matricula_data['fecha_fin'] : ''; ?>" required>
                            </div>

                            <div class="form-group ">          
                              <label for="descuento_matricula">Descuento</label>
                              <input type="number" name="descuento" class="form-control" id="descuento_matricula" 
                                     placeholder="Escriba aquí el número de descuento a aplicar..." 
                                     value="<?php echo isset($matricula_data['descuento']) ? $matricula_data['descuento'] : '0'; ?>" step="0.01">
                            </div>

                            <div class="form-group ">          
                              <label for="monto_final_matricula">Monto Final (a pagar)</label>
                              <input type="number" name="monto_final" class="form-control" id="monto_final_matricula" 
                                     placeholder="Monto final a pagar..." readonly step="0.01"
                                     value="<?php echo isset($matricula_data['monto_final']) ? $matricula_data['monto_final'] : ''; ?>">
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

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPlan = document.getElementById('id_plan');
        const fechaInicioInput = document.getElementById('fecha_inicio_matricula');
        const fechaFinInput = document.getElementById('fecha_fin_matricula');
        const descuentoInput = document.getElementById('descuento_matricula');
        const montoFinalInput = document.getElementById('monto_final_matricula');

        // Función para obtener la fecha de hoy en formato YYYY-MM-DD
        function getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses son 0-11
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Función para calcular la fecha de fin y el monto final
        function calculateDatesAndMonto() {
            const selectedOption = selectPlan.options[selectPlan.selectedIndex];
            // Si no hay un plan seleccionado, limpiar y salir
            if (!selectedOption || !selectedOption.value) {
                fechaFinInput.value = '';
                montoFinalInput.value = '';
                return;
            }

            const tipoDuracion = selectedOption.dataset.tipoDuracion;
            const duracionMeses = parseInt(selectedOption.dataset.duracionMeses);
            const duracionDias = parseInt(selectedOption.dataset.duracionDias);
            const fechaFinFija = selectedOption.dataset.fechaFinFija; // Formato YYYY-MM-DD
            const precioPlan = parseFloat(selectedOption.dataset.precio);
            const descuento = parseFloat(descuentoInput.value) || 0; // Obtener descuento, default 0 si es NaN

            // La fecha de inicio no se auto-establece a 'hoy' en edición, se usa la existente
            // o se permite al usuario cambiarla.
            // Si el campo de fecha de inicio está vacío, podríamos establecerlo a hoy como fallback.
            if (!fechaInicioInput.value) {
                fechaInicioInput.value = getTodayDate();
            }

            let calculatedMonto = precioPlan - descuento;
            if (calculatedMonto < 0) {
                calculatedMonto = 0; // Asegura que el monto final no sea negativo
            }
            montoFinalInput.value = calculatedMonto.toFixed(2); // Formatear a 2 decimales

            // Recalcular fecha fin solo si el tipo de duración es relativa
            if (tipoDuracion === 'relativa') {
                let baseDate = new Date(fechaInicioInput.value + 'T00:00:00'); // Usar fecha_inicio del input como base

                // Sumar meses
                if (!isNaN(duracionMeses) && duracionMeses > 0) {
                    baseDate.setMonth(baseDate.getMonth() + duracionMeses);
                }

                // Sumar días (después de sumar meses)
                if (!isNaN(duracionDias) && duracionDias > 0) {
                    baseDate.setDate(baseDate.getDate() + duracionDias);
                }

                // Formatear la fecha calculada a YYYY-MM-DD
                const year = baseDate.getFullYear();
                const month = String(baseDate.getMonth() + 1).padStart(2, '0');
                const day = String(baseDate.getDate()).padStart(2, '0');
                fechaFinInput.value = `${year}-${month}-${day}`;

            } else if (tipoDuracion === 'fija') {
                // Para duración fija, la fecha fin viene directamente del plan
                fechaFinInput.value = fechaFinFija;
            } else {
                // Si no se selecciona un plan o tipo de duración desconocido, limpiar o resetear
                // No limpiamos si es fija y ya tiene un valor, solo si no hay plan o es relativa sin datos
                // Esto es para no borrar una fecha_fin fija que ya estaba cargada.
            }
        }

        // Event Listeners
        selectPlan.addEventListener('change', calculateDatesAndMonto);
        descuentoInput.addEventListener('input', calculateDatesAndMonto); // Recalcular al cambiar el descuento
        fechaInicioInput.addEventListener('change', calculateDatesAndMonto); // Recalcular al cambiar la fecha de inicio

        // Ejecutar la lógica al cargar la página para precargar los campos
        // Solo si hay un plan seleccionado (es decir, si se cargaron datos de una matrícula existente)
        if (selectPlan.value) {
            calculateDatesAndMonto();
        }
    });
</script>
