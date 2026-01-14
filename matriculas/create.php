<?php

include('../app/config.php');
include('../layout/sesion.php');

// VERIFICAR CAJA ABIERTA
include('../app/controllers/caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de realizar una nueva matrícula.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/caja/');
    exit;
}

include('../layout/parte1.php'); 

include('../app/controllers/clientes/listado_de_clientes.php');
// Incluir controlador para listar solo planes ACTIVOS
include('../app/controllers/planes/listado_de_planes_activos.php');

// Obtener el ID del cliente de la URL si existe
$preselected_client_id = $_GET['id_cliente'] ?? null;


// =======================================================================
// INICIO: Lógica de PHP para Fechas
// =======================================================================

$fecha_hoy = date('Y-m-d');
$fecha_inicio_valor = $fecha_hoy; // Valor por defecto: hoy
$fecha_fin_ultima_matricula = null;

if ($preselected_client_id) {
    // 1. Obtener la fecha fin de la última matrícula del cliente
    $sql_ultima_matricula = "SELECT MAX(fecha_fin) AS fecha_fin FROM tb_matriculas WHERE id_cliente = :id_cliente AND id_gimnasio = :id_gimnasio";
    $query_ultima_matricula = $pdo->prepare($sql_ultima_matricula);
    $query_ultima_matricula->bindParam(':id_cliente', $preselected_client_id);
    $query_ultima_matricula->bindParam(':id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    $query_ultima_matricula->execute();
    $resultado = $query_ultima_matricula->fetch(PDO::FETCH_ASSOC);

    if ($resultado && $resultado['fecha_fin']) {
        $fecha_fin_ultima_matricula = $resultado['fecha_fin'];
        
        // 2. Determinar la fecha de inicio inicial
        if ($fecha_fin_ultima_matricula >= $fecha_hoy) {
            // Caso: Matrícula Vigente -> Fecha inicio es el día siguiente a la fecha fin
            $fecha_inicio_valor = date('Y-m-d', strtotime($fecha_fin_ultima_matricula . ' +1 day'));
        } else {
            // Caso: Matrícula Vencida -> Fecha inicio es Hoy
            $fecha_inicio_valor = $fecha_hoy;
        }
    } 
    // Caso Nuevo Cliente: $fecha_fin_ultima_matricula es NULL, $fecha_inicio_valor se mantiene como $fecha_hoy.
}

// =======================================================================
// FIN: Lógica de PHP para Fechas
// =======================================================================


?>

<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">

          </div></div></div></div>
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
                        <form action="../app/controllers/matriculas/create_matricula.php" method="post">

                            <div class="form-group">
                              <label for="id_cliente">Nombre del cliente</label>
                              <select name="id_cliente" id="id_cliente" class="form-control select2bs4" required>
                                <option value="">Seleccione un cliente</option>
                                <?php
                                  foreach($clientes_datos as $clientes_dato){ ?>
                                    <option value="<?php echo $clientes_dato['id_cliente'];?>"
                                        <?php if ($preselected_client_id && $preselected_client_id == $clientes_dato['id_cliente']) echo 'selected'; ?>>
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
                                            data-precio="<?php echo $planes_dato['precio'];?>">
                                        <?php echo $planes_dato['nombre'];?> - S/.<?php echo $planes_dato['precio'];?>
                                    </option>
                                  <?php }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_inicio_matricula">Fecha Inicio</label>
                              <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio_matricula" value="<?php echo $fecha_inicio_valor; ?>" required>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_fin_matricula">Fecha Fin</label>
                              <input type="date" name="fecha_fin" class="form-control" id="fecha_fin_matricula" readonly required>
                            </div>

                           <div class="form-group">          
                                <label for="descuento_matricula">Descuento</label>
                                <input type="number" name="descuento" class="form-control" id="descuento_matricula" placeholder="Escriba aquí el número de descuento a aplicar..." value="0" step="0.01" readonly>
                            </div>

                            <div class="form-group ">          
                              <label for="monto_final_matricula">Monto Final (a pagar)</label>
                              <input type="number" name="monto_final" class="form-control" id="monto_final_matricula" placeholder="Monto final a pagar..." readonly step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="metodo_pago">Método de Pago</label>
                                <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                                    <option value="">Seleccione un método</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta_debito">Tarjeta de Débito</option>
                                    <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                    <option value="yape">Yape</option>
                                    <option value="plin">Plin</option>
                                </select>
                            </div>

                            <!-- CAMPO OCULTO PARA MONTO PAGADO (Se llena autom. desde la Fila 1 de la tabla) -->
                            <input type="hidden" name="monto_pagado" id="monto_pagado" value="0">

                            <!-- SECCIÓN FORMA DE PAGO -->
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white p-2">
                                    <h5 class="card-title m-0" style="font-size: 1rem;"><i class="fas fa-money-check-alt"></i> Forma de Pago</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="pago_contado" name="tipo_pago_modalidad" class="custom-control-input" value="contado" checked>
                                            <label class="custom-control-label" for="pago_contado">Contado (Pago Único)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="pago_credito" name="tipo_pago_modalidad" class="custom-control-input" value="credito">
                                            <label class="custom-control-label" for="pago_credito">Crédito / Cuotas</label>
                                        </div>
                                        <!-- Input hidden real para el backend que espera 'pago_en_cuotas' como 1 o 0 -->
                                        <input type="hidden" name="pago_en_cuotas" id="pago_en_cuotas_backend" value="0">
                                    </div>

                                    <!-- CONTROLES CRÉDITO (Solo visibles en modo Crédito) -->
                                    <div id="controles_credito" style="display: none;" class="mb-3 bg-light p-2 rounded">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="small">Nro. Cuotas</label>
                                                <input type="number" id="nro_cuotas" name="nro_cuotas" class="form-control form-control-sm" min="2" max="24" value="2">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small">Frecuencia</label>
                                                <select id="frecuencia_pago" name="frecuencia_pago" class="form-control form-control-sm">
                                                    <option value="mensual">Mensual (30 días)</option>
                                                    <option value="quincenal">Quincenal (15 días)</option>
                                                    <option value="personalizado">Personalizado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" id="btn_generar_cuotas" class="btn btn-sm btn-info btn-block" title="Recalcular">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" id="btn_add_manual" class="btn btn-xs btn-outline-success mt-2">
                                            <i class="fas fa-plus"></i> Agregar Cuota
                                        </button>
                                    </div>

                                    <!-- TABLA UNIFICADA DE PAGOS -->
                                    <label class="small mt-2">Cronograma de Pagos:</label>
                                    <table class="table table-sm table-bordered bg-white mb-0" id="tabla_cronograma">
                                        <thead class="bg-gray-light text-center" style="font-size: 0.85rem;">
                                            <tr>
                                                <th style="width: 40px;">#</th>
                                                <th>Vencimiento</th>
                                                <th>Monto (S/.)</th>
                                                <th style="width: 40px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 0.9rem;">
                                            <!-- Se llena con JS -->
                                        </tbody>
                                        <tfoot class="bg-light" style="font-size: 0.9rem;">
                                            <tr>
                                                <th colspan="2" class="text-right">Total:</th>
                                                <th class="text-right" id="total_cronograma">0.00</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-right">Diferencia:</th>
                                                <th class="text-right" id="diff_cronograma" style="color: green;">0.00</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                        * La <b>Cuota 1</b> es el Pago Inicial que se registra hoy.
                                    </small>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="form-group">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary" id="btn_guardar_matricula">Guardar</button>
                            </div>
                        
                        </form>
                    </div>
                </div>
          </div>

          </div>
          </div>
        </div>

      </div></div>
    </div>
  <?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // =========================================================
        // VARIABLES GLOBALES DE ELEMENTOS
        // =========================================================
        const CLAVE_AUTORIZADA = <?php echo json_encode($_SESSION['clave_descuento_sesion'] ?? '12345'); ?>;

        const formulario = document.querySelector('form[action="../app/controllers/matriculas/create_matricula.php"]'); 
        const selectCliente = document.getElementById('id_cliente'); 
        const selectPlan = document.getElementById('id_plan');
        const fechaInicioInput = document.getElementById('fecha_inicio_matricula');
        const fechaFinInput = document.getElementById('fecha_fin_matricula');
        const descuentoInput = document.getElementById('descuento_matricula');
        const montoFinalInput = document.getElementById('monto_final_matricula');
        // El input visible monto_pagado YA NO EXISTE (es hidden ahora)
        const montoPagadoHidden = document.getElementById('monto_pagado');

        // Variables de la sección de Pagos
        const radioContado = document.getElementById('pago_contado');
        const radioCredito = document.getElementById('pago_credito');
        const controlesCredito = document.getElementById('controles_credito');
        const inputBackendCuotas = document.getElementById('pago_en_cuotas_backend');
        const tablaCronoBody = document.querySelector('#tabla_cronograma tbody');
        const labelTotalCrono = document.getElementById('total_cronograma');
        const labelDiffCrono = document.getElementById('diff_cronograma');
        const btnGenerar = document.getElementById('btn_generar_cuotas');
        const btnAddManual = document.getElementById('btn_add_manual');

        const FECHA_HOY_PHP = '<?php echo $fecha_hoy; ?>';

        // =========================================================
        // LÓGICA DE FECHAS (PLAN)
        // =========================================================
        function addRelativeDate(dateStr, durationMonths, durationDays) {
            let date = new Date(dateStr + 'T00:00:00'); 
            if (durationMonths > 0) date.setMonth(date.getMonth() + durationMonths); 
            
            if (durationDays > 0) date.setDate(date.getDate() + durationDays - 1); 
            else if (durationMonths > 0) date.setDate(date.getDate() - 1); 
            
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function calculateDatesAndMonto() {
            const selectedOption = selectPlan.options[selectPlan.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                fechaFinInput.value = '';
                montoFinalInput.value = '';
                generatePaymentTable(); // Regenerar tabla (quedará vacía o ceros)
                return;
            }

            const tipoDuracion = selectedOption.dataset.tipoDuracion;
            const duracionMeses = parseInt(selectedOption.dataset.duracionMeses) || 0;
            const duracionDias = parseInt(selectedOption.dataset.duracionDias) || 0;
            const fechaFinFija = selectedOption.dataset.fechaFinFija; 
            const precioPlan = parseFloat(selectedOption.dataset.precio) || 0;
            const descuento = parseFloat(descuentoInput.value) || 0; 
            const fechaInicioStr = fechaInicioInput.value;

            // Fecha Fin
            if (tipoDuracion === 'relativa') {
                if (fechaInicioStr) fechaFinInput.value = addRelativeDate(fechaInicioStr, duracionMeses, duracionDias);
                else fechaFinInput.value = '';
            } else if (tipoDuracion === 'fija') {
                fechaFinInput.value = fechaFinFija;
            } else {
                fechaFinInput.value = '';
            }
            
            // Monto Final
            let calculatedMonto = precioPlan - descuento;
            if (calculatedMonto < 0) calculatedMonto = 0; 
            montoFinalInput.value = calculatedMonto.toFixed(2); 
            
            // Al cambiar el monto final, debemos regenerar la tabla de pagos
            generatePaymentTable(); 
        }

        // =========================================================
        // LÓGICA DE PAGOS (UNIFICADA)
        // =========================================================
        
        // Listeners de Cambio de Modo
        document.getElementsByName('tipo_pago_modalidad').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'credito') {
                    controlesCredito.style.display = 'block';
                    inputBackendCuotas.value = '1';
                } else {
                    controlesCredito.style.display = 'none';
                    inputBackendCuotas.value = '0';
                }
                generatePaymentTable();
            });
        });

        // Listeners para recalcular cuotas
        if(btnGenerar) btnGenerar.addEventListener('click', generatePaymentTable);
        if(btnAddManual) btnAddManual.addEventListener('click', addManualRow);

        // Listener global tabla (recalcular totales al editar)
        tablaCronoBody.addEventListener('input', function(e) {
            if(e.target.classList.contains('input-monto-row')) {
                updateTableTotals();
            }
        });

        // Función Principal: Generar Tabla
        function generatePaymentTable() {
            const mode = document.querySelector('input[name="tipo_pago_modalidad"]:checked').value;
            const totalPagar = parseFloat(montoFinalInput.value) || 0;
            const fechaBase = new Date(); // Cuota 1 siempre es hoy (o fecha inicio? Usualmente hoy porque se paga al matricular)
            
            // Formatear fecha para inputs date
            const yearNow = fechaBase.getFullYear();
            const monthNow = String(fechaBase.getMonth() + 1).padStart(2, '0');
            const dayNow = String(fechaBase.getDate()).padStart(2, '0');
            const dateNowStr = `${yearNow}-${monthNow}-${dayNow}`;

            tablaCronoBody.innerHTML = '';

            if (mode === 'contado') {
                // MODO CONTADO: 1 Sola Fila
                let row = `
                    <tr class="table-success">
                        <td class="text-center">1</td>
                        <td>${dateNowStr} (Pago Inmediato)</td>
                        <td>
                            <input type="number" class="form-control form-control-sm input-monto-row text-right" 
                                   value="${totalPagar.toFixed(2)}" readonly>
                            <!-- Hidden para enviar al backend como primera cuota (aunque en contado backend usa monto_pagado) -->
                        </td>
                        <td></td>
                    </tr>
                `;
                tablaCronoBody.innerHTML = row;

            } else {
                // MODO CRÉDITO
                const nroCuotas = parseInt(document.getElementById('nro_cuotas').value) || 2;
                const frecuencia = document.getElementById('frecuencia_pago').value;
                const fechaIniMat = fechaInicioInput.value; // Para cuotas siguientes tomamos fecha inicio

                let saldo = totalPagar;
                
                // Dividir monto equitativamente por defecto
                let montoCuota = totalPagar / nroCuotas;
                // Ajustar decimales
                montoCuota = Math.round(montoCuota * 100) / 100; 

                let fechaPivot = new Date(fechaIniMat + 'T00:00:00'); 
                if(isNaN(fechaPivot.getTime())) fechaPivot = new Date();

                let html = '';
                for (let i = 0; i < nroCuotas; i++) {
                    let fechaStr = '';
                    let esInicial = (i === 0);
                    let rowClass = esInicial ? 'table-warning' : '';
                    let labelFecha = '';

                    if (esInicial) {
                        // Cuota 1: Hoy
                        fechaStr = dateNowStr;
                        // Ajuste de centavos en la primera cuota para que cuadre exacto? 
                        // O mejor: Cuotas iguales y la ultima ajusta? Haremos iguales
                    } else {
                        // Cuotas Futuras
                        let diasSumar = 0;
                        if(frecuencia === 'mensual') diasSumar = 30 * i;
                        else if(frecuencia === 'quincenal') diasSumar = 15 * i;
                        else diasSumar = 30 * i;
                        
                        let f = new Date(fechaPivot);
                        f.setDate(f.getDate() + diasSumar);
                        
                        const y = f.getFullYear();
                        const m = String(f.getMonth() + 1).padStart(2, '0');
                        const d = String(f.getDate()).padStart(2, '0');
                        fechaStr = `${y}-${m}-${d}`;
                    }

                    // Botón remove solo para cuotas > 1 (no borrar la inicial)
                    // Para simplificar, la inicial es obligatoria.
                    let btnRemove = esInicial ? '' : '<button type="button" class="btn btn-xs btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>';
                    let nameFecha = (i > 0) ? 'fechas_cuotas[]' : ''; // Solo cuotas futuras se envian en el array antiguo?
                    // EL BACKEND ESPERA: 
                    // 1. monto_pagado (Hidden que llenaremos)
                    // 2. fechas_cuotas[] y montos_cuotas[] PARA LAS SIGUIENTES (index 2 onwards)
                    // ASÍ LO CONSTRUIMOS ANTES. MANTENDREMOS ESA LÓGICA.
                    
                    // inputs hidden PARA el envio
                    // Fila 1 no lleva name array, se sincroniza manual.
                    // Filas 2..N llevan name array.
                    
                    let inputsHiddenArr = '';
                    if (!esInicial) {
                         inputsHiddenArr = `<input type="date" name="fechas_cuotas[]" value="${fechaStr}" class="d-none"> <input type="hidden" name="montos_cuotas[]" value="${montoCuota.toFixed(2)}" class="sync-amount">`;
                    }

                    html += `
                        <tr class="${rowClass}">
                            <td class="text-center counter-cell">${i + 1}</td>
                            <td>
                                ${esInicial ? 'Hoy (Inicial)' : `<input type="date" class="form-control form-control-sm input-date-row" value="${fechaStr}" onchange="syncHiddenDate(this)">`}
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm input-monto-row text-right" 
                                       value="${montoCuota.toFixed(2)}" step="0.01">
                                ${inputsHiddenArr}
                            </td>
                            <td class="text-center">
                                ${btnRemove}
                            </td>
                        </tr>
                    `;
                }
                tablaCronoBody.innerHTML = html;
            }
            bindRemoveButtons();
            updateTableTotals();
        }

        // Helpers Tabla
        function addManualRow() {
            const rowCount = tablaCronoBody.rows.length + 1;
            let row = `
                <tr>
                    <td class="text-center counter-cell">${rowCount}</td>
                    <td>
                        <input type="date" class="form-control form-control-sm input-date-row" onchange="syncHiddenDate(this)">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm input-monto-row text-right" value="0.00" step="0.01">
                        <input type="date" name="fechas_cuotas[]" class="d-none"> 
                        <input type="hidden" name="montos_cuotas[]" value="0.00" class="sync-amount">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            // Insertar antes del total? Append
            tablaCronoBody.insertAdjacentHTML('beforeend', row);
            bindRemoveButtons();
            updateTableTotals();
        }

        function bindRemoveButtons() {
            tablaCronoBody.querySelectorAll('.btn-remove-row').forEach(btn => {
                btn.onclick = function() {
                    this.closest('tr').remove();
                    reindexRows();
                    updateTableTotals();
                }
            });
        }

        function reindexRows() {
            tablaCronoBody.querySelectorAll('tr').forEach((row, index) => {
                row.querySelector('.counter-cell').textContent = index + 1;
            });
        }

        function updateTableTotals() {
            let sum = 0;
            const rows = tablaCronoBody.querySelectorAll('tr');
            
            rows.forEach((row, index) => {
                const inputVal = row.querySelector('.input-monto-row').value;
                const val = parseFloat(inputVal) || 0;
                sum += val;

                // SYNC logic:
                // Si es fila 1 (index 0), actualizar el hidden #monto_pagado
                if (index === 0) {
                    montoPagadoHidden.value = val.toFixed(2);
                } else {
                    // Si es fila > 0, actualizar el hidden input correspondiente al array montos_cuotas[]
                    const hiddenAmount = row.querySelector('.sync-amount');
                    if(hiddenAmount) hiddenAmount.value = val.toFixed(2);
                }
            });

            labelTotalCrono.textContent = sum.toFixed(2);
            
            const totalEsperado = parseFloat(montoFinalInput.value) || 0;
            const diff = totalEsperado - sum;
            labelDiffCrono.textContent = diff.toFixed(2);

            if (Math.abs(diff) < 0.1) {
                labelDiffCrono.style.color = 'green';
                document.getElementById('btn_guardar_matricula').disabled = false;
            } else {
                labelDiffCrono.style.color = 'red';
                // Bloquear guardado si no cuadra? Mejor advertir
                // document.getElementById('btn_guardar_matricula').disabled = true; 
            }
        }
        
        // Función global para sync fecha manual
        window.syncHiddenDate = function(inputDate) {
            const hidden = inputDate.parentElement.nextElementSibling.querySelector('input[name="fechas_cuotas[]"]');
            if(hidden) hidden.value = inputDate.value;
        };

        // =========================================================
        // LISTENERS ORIGINALES (Cliente, Plan, etc)
        // =========================================================
        $(selectCliente).on('change', function() {
            const id_cliente = $(this).val();
            if (id_cliente) {
                $.ajax({
                    url: '../app/controllers/matriculas/get_fecha_inicio_cliente.php', 
                    type: 'POST',
                    data: { id_cliente: id_cliente },
                    success: function(response) {
                        fechaInicioInput.value = response.trim(); 
                        calculateDatesAndMonto();
                    },
                    error: function() {
                        fechaInicioInput.value = FECHA_HOY_PHP; 
                        calculateDatesAndMonto();
                    }
                });
            } else {
                fechaInicioInput.value = FECHA_HOY_PHP;
                calculateDatesAndMonto();
            }
        });

        $(selectPlan).on('change', calculateDatesAndMonto); 
        fechaInicioInput.addEventListener('change', calculateDatesAndMonto); 
        descuentoInput.addEventListener('input', calculateDatesAndMonto); 

        // Submit Validation
        formulario.addEventListener('submit', function(e) {
            const diff = parseFloat(labelDiffCrono.textContent) || 0;
            if (Math.abs(diff) > 0.5) { // Tolerancia 0.50 céntimos
                e.preventDefault();
                alert('ERROR: La suma de los pagos no coincide con el Monto Final. Por favor ajuste los montos.');
                return false;
            }
            if(!selectCliente.value) {
                e.preventDefault(); alert('Seleccione cliente'); return false;
            }
            // Validar fechas
            const dInicio = new Date(fechaInicioInput.value + 'T00:00:00');
            const dFin = new Date(fechaFinInput.value + 'T00:00:00');
            if(dFin <= dInicio) {
                 e.preventDefault(); alert('Fecha Fin menor a Inicio'); return false;
            }
            return true;
        });

        // Init
        setTimeout(calculateDatesAndMonto, 500);
    // Script Descuento
    $('#descuento_matricula').on('click', function() {
        if ($(this).attr('readonly')) {
            Swal.fire({
                title: 'Autorización Requerida',
                text: "Ingrese el PIN para aplicar descuentos",
                input: 'password',
                showCancelButton: true,
                confirmButtonText: 'Autorizar',
                inputValidator: (value) => {
                    if (value !== CLAVE_AUTORIZADA) {
                        return 'PIN incorrecto';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).removeAttr('readonly');
                    $(this).select(); 
                    const Toast = Swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000});
                    Toast.fire({icon: 'success', title: 'Descuento habilitado'});
                }
            });
        }
    });

    });




</script>