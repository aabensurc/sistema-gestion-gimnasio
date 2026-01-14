<?php

include('../app/config.php');
include('../layout/sesion.php');

// VERIFICAR CAJA ABIERTA
include('../app/controllers/caja/verificar_estado_caja.php');
if (!$caja_abierta) {
  $_SESSION['mensaje'] = "Debe abrir caja antes de crear una asesoría.";
  $_SESSION['icono'] = "warning";
  header('Location: ' . $URL . '/caja/');
  exit;
}

include('../layout/parte1.php');

// Incluir controladores para cargar datos de clientes y entrenadores
include('../app/controllers/clientes/listado_de_clientes.php'); // Para el select de clientes
include('../app/controllers/entrenadores/listado_de_entrenadores.php'); // Para el select de entrenadores

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Registrar Nueva Asesoría</h1>
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
              <h3 class="card-title">Llene los datos de la asesoría</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/asesorias/create_asesoria_process.php" method="post">

                    <div class="form-group">
                      <label for="id_cliente">Cliente</label>
                      <select name="id_cliente" id="id_cliente" class="form-control select2bs4" required>
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
                      <label for="id_entrenador">Entrenador</label>
                      <select name="id_entrenador" id="id_entrenador" class="form-control select2bs4" required>
                        <option value="">Seleccione un entrenador</option>
                        <?php
                        if (isset($entrenadores_datos) && is_array($entrenadores_datos)) {
                          foreach ($entrenadores_datos as $entrenador_dato) { ?>
                            <option value="<?php echo $entrenador_dato['id_entrenador']; ?>">
                              <?php echo $entrenador_dato['nombre'] . ' ' . $entrenador_dato['ape_pat'] . ' ' . $entrenador_dato['ape_mat']; ?>
                            </option>
                          <?php }
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="monto_final">Monto Final (S/.)</label>
                      <input type="number" name="monto_final" class="form-control" id="monto_final"
                        placeholder="Ingrese el monto final de la asesoría..." step="0.01" required>
                    </div>

                    <div class="form-group">
                      <label for="descuento_asesoria">Descuento (Opcional)</label>
                      <input type="number" name="descuento" class="form-control" id="descuento_asesoria"
                        placeholder="Escriba aquí el número de descuento a aplicar..." value="0" step="0.01" readonly>
                    </div>

                    <div class="form-group">
                      <label for="fecha_inicio">Fecha Inicio</label>
                      <input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio" required>
                    </div>

                    <div class="form-group">
                      <label for="fecha_fin">Fecha Fin</label>
                      <input type="date" name="fecha_fin" class="form-control" id="fecha_fin" required>
                    </div>

                    <hr>
                    <hr>
                    <h4>Detalle de Pago</h4>

                    <!-- MODO DE PAGO -->
                    <div class="form-group">
                      <label>Forma de Pago:</label>
                      <div class="d-flex align-items-center">
                        <div class="custom-control custom-radio mr-3">
                          <input class="custom-control-input" type="radio" id="pago_contado" name="tipo_pago_modalidad"
                            value="contado" checked>
                          <label for="pago_contado" class="custom-control-label">Contado (1 solo pago)</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="pago_credito" name="tipo_pago_modalidad"
                            value="credito">
                          <label for="pago_credito" class="custom-control-label">Crédito / En Cuotas</label>
                        </div>
                      </div>
                    </div>

                    <!-- CONFIGURACIÓN DE CUOTAS (Visible solo en crédito) -->
                    <div id="div_config_cuotas"
                      style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Nro. de Cuotas:</label>
                            <input type="number" id="nro_cuotas" class="form-control" value="1" min="1">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Frecuencia de Pagos:</label>
                            <select id="frecuencia_pagos" class="form-control">
                              <option value="mensual">Mensual (30 días)</option>
                              <option value="quincenal">Quincenal (15 días)</option>
                              <option value="semanal">Semanal (7 días)</option>
                              <option value="diario">Diario</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <button type="button" class="btn btn-info btn-sm" id="btn_generar_cronograma">
                        <i class="fas fa-sync-alt"></i> Generar Cronograma
                      </button>
                    </div>

                    <!-- TABLA UNIFICADA DE PAGOS -->
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered" id="tabla_cronograma_preview">
                        <thead class="thead-light">
                          <tr>
                            <th style="width: 50px;">#</th>
                            <th>Fecha Vencimiento</th>
                            <th>Monto (S/.)</th>
                            <th>Estado Incial</th>
                          </tr>
                        </thead>
                        <tbody id="tabla_crono_body">
                          <!-- Se genera con JS -->
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="2" class="text-right"><strong>TOTAL PROGRAMADO:</strong></td>
                            <td colspan="2">
                              <strong id="label_total_crono">0.00</strong>
                              <span id="label_diff_crono" style="margin-left: 10px; font-size: 0.9em;"></span>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>

                    <div class="form-group mt-3">
                      <label for="metodo_pago">Método de Pago (Para la Inicial):</label>
                      <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                        <option value="">Seleccione un método</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta_debito">Tarjeta de Débito</option>
                        <option value="tarjeta_credito">Tarjeta de Crédito</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                      </select>
                    </div>

                    <!-- CAMPOS OCULTOS PARA ENVIO AL BACKEND -->
                    <input type="hidden" name="monto_pagado" id="monto_pagado_hidden">
                    <input type="hidden" name="pago_en_cuotas" id="hidden_pago_en_cuotas" value="0">
                    <div id="hidden_cuotas_container"></div>

                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-primary">Guardar Asesoría</button>
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
    // Set fecha_inicio to today's date
    function getTodayDate() {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0');
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }
    const fechaInicioInput = document.getElementById('fecha_inicio');
    if (fechaInicioInput) fechaInicioInput.value = getTodayDate();

    // -------------------------------------------------------------------------
    // LÓGICA DE CRONOGRAMA / CUOTAS (Adaptada para Asesorías)
    // -------------------------------------------------------------------------
    const montoFinalInput = document.getElementById('monto_final');
    const divConfigCuotas = document.getElementById('div_config_cuotas');
    const tablaCronoBody = document.getElementById('tabla_crono_body');
    const labelTotalCrono = document.getElementById('label_total_crono');
    const labelDiffCrono = document.getElementById('label_diff_crono');
    const hiddenCuotasContainer = document.getElementById('hidden_cuotas_container');
    const nroCuotasInput = document.getElementById('nro_cuotas');
    const frecuenciaInput = document.getElementById('frecuencia_pagos');
    const btnGenerarCrono = document.getElementById('btn_generar_cronograma');
    const montoPagadoHidden = document.getElementById('monto_pagado_hidden');
    const hiddenPagoEnCuotas = document.getElementById('hidden_pago_en_cuotas');

    // Mapeo de fechas util
    function formatDate(date) {
      const y = date.getFullYear();
      const m = String(date.getMonth() + 1).padStart(2, '0');
      const d = String(date.getDate()).padStart(2, '0');
      return `${y}-${m}-${d}`;
    }

    // Listener Radio Buttons
    $('input[name="tipo_pago_modalidad"]').on('change', function () {
      if (this.value === 'credito') {
        $(divConfigCuotas).slideDown();
        hiddenPagoEnCuotas.value = '1';
        generatePaymentTable();
      } else {
        $(divConfigCuotas).slideUp();
        hiddenPagoEnCuotas.value = '0';
        generatePaymentTable();
      }
    });

    // Botón Generar Cronograma
    $(btnGenerarCrono).on('click', generatePaymentTable);

    // Recalcular tabla si cambia el monto final (Input Manual)
    $(montoFinalInput).on('input', generatePaymentTable);

    function generatePaymentTable() {
      const mode = document.querySelector('input[name="tipo_pago_modalidad"]:checked').value;
      const totalPagar = parseFloat(montoFinalInput.value) || 0;
      const startDateStr = fechaInicioInput.value || getTodayDate();

      // Limpiar tabla
      tablaCronoBody.innerHTML = '';

      let dateNow = new Date(startDateStr + 'T00:00:00');
      let dateNowStr = formatDate(dateNow);

      if (mode === 'contado') {
        // MODO CONTADO: 1 Sola Fila
        let row = `
                <tr class="table-success">
                    <td class="text-center">1</td>
                    <td>${dateNowStr} (Pago Inmediato)</td>
                    <td>
                        <input type="number" class="form-control form-control-sm input-monto-row text-right" 
                               value="${totalPagar.toFixed(2)}" readonly>
                    </td>
                    <td><span class="badge badge-success">Pagado</span></td>
                </tr>
            `;
        tablaCronoBody.innerHTML = row;

      } else {
        // MODO CRÉDITO
        const n = parseInt(nroCuotasInput.value) || 1;
        const freq = frecuenciaInput.value;
        let montoCuota = totalPagar / n;
        montoCuota = Math.round(montoCuota * 100) / 100; // Redondear 2 decimales

        // Ajuste de centavos en la primera cuota
        let diff = totalPagar - (montoCuota * n);
        let primerMonto = montoCuota + diff;

        let currentDate = new Date(startDateStr + 'T12:00:00');

        for (let i = 1; i <= n; i++) {
          let styleClass = (i === 1) ? 'table-success' : '';
          let estadoLabel = (i === 1) ? '<span class="badge badge-success">Inicial</span>' : '<span class="badge badge-warning">Pendiente</span>';

          let fechaMostrar = formatDate(currentDate);

          if (i === 1) {
            // Inicial hoy (o fecha inicio)
          } else {
            // Calcular siguiente fecha
            let daysToAdd = 30;
            if (freq === 'quincenal') daysToAdd = 15;
            if (freq === 'semanal') daysToAdd = 7;
            if (freq === 'diario') daysToAdd = 1;
            currentDate.setDate(currentDate.getDate() + daysToAdd);
            fechaMostrar = formatDate(currentDate);
          }

          let val = (i === 1) ? primerMonto : montoCuota;

          let row = `
                <tr class="${styleClass}">
                    <td class="text-center">
                        ${i}
                        ${i > 1 ? '<button type="button" class="btn btn-xs btn-danger btn-remove-row" title="Quitar cuota" style="margin-left:5px;">x</button>' : ''}
                    </td>
                    <td>
                        <input type="date" class="form-control form-control-sm input-fecha-row" value="${fechaMostrar}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm input-monto-row text-right" 
                               value="${val.toFixed(2)}" step="0.01">
                    </td>
                    <td>${estadoLabel}</td>
                </tr>
                `;
          tablaCronoBody.insertAdjacentHTML('beforeend', row);
        }
      }
      bindRemoveButtons();
      updateTableTotals();
    }

    function bindRemoveButtons() {
      $('.btn-remove-row').off('click').on('click', function () {
        $(this).closest('tr').remove();
        let rows = tablaCronoBody.querySelectorAll('tr');
        rows.forEach((r, idx) => {
          r.querySelector('td:first-child').childNodes[0].textContent = idx + 1 + " ";
        });
        updateTableTotals();
      });
      $('.input-monto-row').off('input').on('input', updateTableTotals);
    }

    function updateTableTotals() {
      let sum = 0;
      const rows = tablaCronoBody.querySelectorAll('tr');

      rows.forEach((row, index) => {
        const inputVal = row.querySelector('.input-monto-row').value;
        const val = parseFloat(inputVal) || 0;
        sum += val;

        if (index === 0) {
          montoPagadoHidden.value = val.toFixed(2);
        }
      });

      labelTotalCrono.textContent = sum.toFixed(2);

      const totalEsperado = parseFloat(montoFinalInput.value) || 0;
      const diff = totalEsperado - sum;

      if (diff > 0) {
        labelDiffCrono.textContent = `(Faltan S/. ${diff.toFixed(2)})`;
        labelDiffCrono.style.color = 'red';
      } else if (diff < 0) {
        labelDiffCrono.textContent = `(Sobran S/. ${Math.abs(diff).toFixed(2)})`;
        labelDiffCrono.style.color = 'red';
      } else {
        labelDiffCrono.textContent = '(Cuadrado)';
        labelDiffCrono.style.color = 'green';
      }
    }

    // Intercept Submit
    $('form').off('submit').on('submit', function (event) {
      // Validar Cronograma vs Monto Final
      const totalCrono = parseFloat(labelTotalCrono.textContent) || 0;
      const totalAsesoria = parseFloat(montoFinalInput.value) || 0;

      if (Math.abs(totalAsesoria - totalCrono) > 0.1) {
        event.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Montos no coinciden',
          text: `El total del cronograma (S/. ${totalCrono}) no coincide con el monto final (S/. ${totalAsesoria}).`
        });
        return false;
      }

      // Generar Arrays para Backend
      hiddenCuotasContainer.innerHTML = '';
      const rows = tablaCronoBody.querySelectorAll('tr');
      rows.forEach((row, index) => {
        if (index > 0) { // Solo cuotas futuras
          const fecha = row.querySelector('.input-fecha-row').value;
          const monto = row.querySelector('.input-monto-row').value;
          hiddenCuotasContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="fechas_cuotas[]" value="${fecha}">`);
          hiddenCuotasContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="montos_cuotas[]" value="${monto}">`);
        }
      });
    });

    // Init
    generatePaymentTable();

    // -------------------------------------------------------------------------
    // LOGICA DE DESCUENTO (Nuevo)
    // -------------------------------------------------------------------------
    const CLAVE_AUTORIZADA = <?php echo json_encode($_SESSION['clave_descuento_sesion'] ?? '12345'); ?>;
    const descuentoInput = document.getElementById('descuento_asesoria');

    $('#descuento_asesoria').on('click', function () {
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
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            Toast.fire({ icon: 'success', title: 'Descuento habilitado' });
          }
        });
      }
    });

    // Recalcular monto final si se aplica descuento? 
    // En Asesorías, el usuario ingresa el "Monto Final" manualmente. 
    // Si agregamos descuento, ¿debería restar al monto final ingresado o el monto final ES el resultado?
    // Usualmente: Precio Base - Descuento = Monto Final.
    // Como el input es "Monto Final", asumiremos que el descuento solo se registra informativamente O que 
    // deberíamos tener un "Precio Base".
    // PERO, para no romper la lógica existente donde ingresan "Monto Final" directo, 
    // permitiremos que el descuento se guarde pero NO alteramos la lógica de cálculo compleja.
    // Sin embargo, para que tenga sentido, si ingresan descuento, tal vez quieran que reste del monto que pusieron.
    // DADO QUE EL REQUERIMIENTO ES SOLO "AGREGAR CLAVE", no cambiar la lógica de negocio profunda...
    // Solo permitiremos ingresar el descuento validado. El backend decidirá qué hacer.
    // *PERO* si no hay campo en BD para descuento asesoria... (Revisaré schema).
  });
</script>