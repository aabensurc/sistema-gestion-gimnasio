<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

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
                                    <form action="../app/controllers/planes/create_plan.php" method="post">
                                        <div class="form-group">
                                            <label for="">Nombre del plan</label>
                                            <input type="text" name="nombre" class="form-control"
                                                placeholder="Escriba aquí el nombre del plan..." required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Precio</label>
                                            <input type="number" name="precio" class="form-control"
                                                placeholder="Escriba aquí el nombre del plan..." required step="0.01"
                                                min="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tipo de Duración</label>
                                            <select name="tipo_duracion" id="tipo_duracion_select" class="form-control"
                                                required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="relativa">Relativa (Meses/Días)</option>
                                                <option value="fija">Fija (Fecha Fin)</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="group_duracion_meses">
                                            <label for="">Duración en meses</label>
                                            <input type="number" name="duracion_meses" id="duracion_meses_input"
                                                class="form-control"
                                                placeholder="Escriba aquí la duración en meses del plan..." disabled
                                                min="0">
                                        </div>
                                        <div class="form-group" id="group_duracion_dias">
                                            <label for="">Duración en días</label>
                                            <input type="number" name="duracion_dias" id="duracion_dias_input"
                                                class="form-control"
                                                placeholder="Escriba aquí la duración en días del plan..." disabled
                                                min="0">
                                        </div>
                                        <div class="form-group" id="group_fecha_inicio">
                                            <label for="">Fecha de inicio</label>
                                            <input type="date" name="fecha_inicio" id="fecha_inicio_input"
                                                class="form-control"
                                                placeholder="Escriba aquí la fecha de inicio del plan..." disabled>
                                        </div>
                                        <div class="form-group" id="group_fecha_fin">
                                            <label for="">Fecha de fin</label>
                                            <input type="date" name="fecha_fin" id="fecha_fin_input"
                                                class="form-control"
                                                placeholder="Escriba aquí la fecha de finalización del plan..."
                                                disabled>
                                        </div>


                                        <hr>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
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
        // Obtenemos los elementos del DOM
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
            // Se habilitan si es Relativa, se deshabilitan y se limpian si no lo es.
            duracionMesesInput.disabled = !isRelativa;
            duracionDiasInput.disabled = !isRelativa;

            if (!isRelativa) {
                duracionMesesInput.value = '';
                duracionDiasInput.value = '';
            }

            // 2. Fecha de Inicio (NUEVO REQUERIMIENTO: SIEMPRE DESHABILITADO)
            // Este campo no es un dato de configuración del plan, solo se usa en Matrícula.
            fechaInicioInput.disabled = true;
            fechaInicioInput.value = '';

            // 3. Fecha de Fin
            // Se habilita si es Fija, se deshabilita y se limpia si no lo es.
            fechaFinInput.disabled = !isFija;
            if (!isFija) {
                fechaFinInput.value = '';
            }
        }

        // 1. Listener para el cambio en el selector
        tipoDuracionSelect.addEventListener('change', function () {
            toggleFields(this.value);
        });

        // 2. Inicialización al cargar la página
        toggleFields(tipoDuracionSelect.value);
    });
</script>








<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>