<?php

include('../app/config.php');
include('../layout/sesion.php');

// VERIFICAR CAJA ABIERTA
include('../app/controllers/caja/verificar_estado_caja.php');
if (!$caja_abierta) {
    $_SESSION['mensaje'] = "Debe abrir caja antes de realizar una nueva venta.";
    $_SESSION['icono'] = "warning";
    header('Location: ' . $URL . '/caja/');
    exit;
}

include('../layout/parte1.php');

// Incluir controladores para cargar datos de clientes y productos
include('../app/controllers/clientes/listado_de_clientes.php'); // Para el select de clientes
include('../app/controllers/productos/listado_de_productos.php'); // Para el select de productos

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registrar Nueva Venta</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8"> <!-- Aumentado el tamaño para el detalle de venta -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos de la venta y agregue productos</h3>


                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/ventas/create_venta_process.php" method="post"
                                        id="form_nueva_venta">

                                        <div class="form-group">
                                            <label for="id_cliente">Cliente</label>
                                            <select name="id_cliente" id="id_cliente" class="form-control select2bs4">
                                                <option value="">Seleccione un cliente (Obligatorio)</option>
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
                                            <label for="fecha_venta">Fecha de Venta</label>
                                            <input type="date" name="fecha_venta" class="form-control" id="fecha_venta"
                                                required readonly>
                                        </div>

                                        <hr>
                                        <h4>Detalle de Productos</h4>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="id_producto">Producto</label>
                                                <select id="id_producto" class="form-control select2bs4">
                                                    <option value="">Seleccione un producto</option>
                                                    <?php
                                                    if (isset($productos_datos) && is_array($productos_datos)) {
                                                        foreach ($productos_datos as $producto) { ?>
                                                            <option value="<?php echo $producto['id_producto']; ?>"
                                                                data-precio="<?php echo $producto['precio_venta']; ?>"
                                                                data-stock="<?php echo $producto['stock']; ?>">
                                                                <?php echo $producto['nombre']; ?> (Stock:
                                                                <?php echo $producto['stock']; ?>)
                                                            </option>
                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="cantidad_producto">Cantidad</label>
                                                <input type="number" id="cantidad_producto" class="form-control"
                                                    value="1" min="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="precio_unitario_display">Precio Unitario</label>
                                                <input type="text" id="precio_unitario_display" class="form-control"
                                                    readonly>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-primary btn-block"
                                                    id="btn_add_product">
                                                    <i class="fa fa-plus"></i> Agregar
                                                </button>
                                            </div>
                                        </div>

                                        <table class="table table-bordered table-striped" id="detalle_productos_table">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario (S/.)</th>
                                                    <th>Subtotal (S/.)</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Filas de productos agregados dinámicamente -->
                                            </tbody>
                                        </table>

                                        <div class="form-group mt-3">
                                            <label for="descuento_total">Descuento Total (S/.)</label>
                                            <input type="number" name="descuento_total" class="form-control"
                                                id="descuento_total" value="0" step="0.01" readonly>
                                        </div>

                                        <div class="form-group ">
                                            <label for="monto_total_venta">Monto Total de la Venta (S/.)</label>
                                            <input type="number" name="monto_total" class="form-control"
                                                id="monto_total_venta" readonly step="0.01">
                                        </div>

                                        <hr>
                                        <h4>Detalle de Pago</h4>
                                        <hr>
                                        <h4>Detalle de Pago</h4>

                                        <!-- MODO DE PAGO -->
                                        <div class="form-group">
                                            <label>Forma de Pago:</label>
                                            <div class="d-flex align-items-center">
                                                <div class="custom-control custom-radio mr-3">
                                                    <input class="custom-control-input" type="radio" id="pago_contado"
                                                        name="tipo_pago_modalidad" value="contado" checked>
                                                    <label for="pago_contado" class="custom-control-label">Contado (1
                                                        solo pago)</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="pago_credito"
                                                        name="tipo_pago_modalidad" value="credito">
                                                    <label for="pago_credito" class="custom-control-label">Crédito / En
                                                        Cuotas</label>
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
                                                        <input type="number" id="nro_cuotas" class="form-control"
                                                            value="1" min="1">
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
                                            <button type="button" class="btn btn-info btn-sm"
                                                id="btn_generar_cronograma">
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
                                                        <td colspan="2" class="text-right"><strong>TOTAL
                                                                PROGRAMADO:</strong></td>
                                                        <td colspan="2">
                                                            <strong id="label_total_crono">0.00</strong>
                                                            <span id="label_diff_crono"
                                                                style="margin-left: 10px; font-size: 0.9em;"></span>
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
                                        <!-- Este reemplaza al visible -->
                                        <input type="hidden" name="pago_en_cuotas" id="hidden_pago_en_cuotas" value="0">
                                        <!-- Arrays para cuotas futuras -->
                                        <div id="hidden_cuotas_container"></div>

                                        <hr>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">Guardar Venta</button>
                                        </div>
                                        <!-- Campo oculto para enviar los detalles de los productos como JSON -->
                                        <input type="hidden" name="productos_seleccionados_json"
                                            id="productos_seleccionados_json">
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
    // Variables globales
    var productosSeleccionados = [];
    var subtotalVenta = 0;

    $(document).ready(function () {
        // -------------------------------------------------------------------------
        // 1. INICIALIZACIÓN DE FECHA
        // -------------------------------------------------------------------------
        function getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        $('#fecha_venta').val(getTodayDate());

        // -------------------------------------------------------------------------
        // 2. LOGICA DE CARRITO DE COMPRAS (PRODUCTOS)
        // -------------------------------------------------------------------------

        // Al cambiar producto, actualizar precio y stock disponible
        $('#id_producto').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const precio = selectedOption.data('precio');
            const stock = selectedOption.data('stock');

            if (selectedOption.val()) {
                $('#precio_unitario_display').val(precio);
                $('#cantidad_producto').attr('max', stock);
            } else {
                $('#precio_unitario_display').val('');
                $('#cantidad_producto').removeAttr('max');
            }
        });

        // Agregar producto
        $('#btn_add_product').on('click', function () {
            const idProducto = $('#id_producto').val();
            const nombreProducto = $('#id_producto option:selected').text();
            const cantidad = parseInt($('#cantidad_producto').val());
            const precioUnitario = parseFloat($('#precio_unitario_display').val());
            const stockMax = parseInt($('#id_producto option:selected').data('stock'));

            // Validaciones
            if (!idProducto) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Seleccione un producto.' });
                return;
            }
            if (isNaN(cantidad) || cantidad <= 0) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ingrese una cantidad válida.' });
                return;
            }
            if (cantidad > stockMax) {
                Swal.fire({ icon: 'error', title: 'Stock Insuficiente', text: `Solo quedan ${stockMax} unidades.` });
                return;
            }

            // Verificar si ya existe en el array
            const existingIndex = productosSeleccionados.findIndex(p => p.id_producto == idProducto);
            if (existingIndex !== -1) {
                Swal.fire({ icon: 'warning', title: 'Producto ya agregado', text: 'Este producto ya está en la lista. Bórrelo para cambiar la cantidad.' });
                return;
            }

            const subtotal = cantidad * precioUnitario;

            // Agregar al array
            productosSeleccionados.push({
                id_producto: idProducto,
                nombre: nombreProducto,
                cantidad: cantidad,
                precio_unitario: precioUnitario,
                subtotal: subtotal
            });

            renderizarTablaProductos();
            calculateMontoTotalVenta();

            // Limpiar campos
            $('#id_producto').val('').trigger('change');
            $('#cantidad_producto').val(1);
            $('#precio_unitario_display').val('');
        });

        // Borrar producto
        window.borrarProducto = function (index) {
            productosSeleccionados.splice(index, 1);
            renderizarTablaProductos();
            calculateMontoTotalVenta();
        };

        function renderizarTablaProductos() {
            const tbody = $('#detalle_productos_table tbody');
            tbody.empty();

            productosSeleccionados.forEach((prod, index) => {
                const row = `
                    <tr>
                        <td>${prod.nombre}</td>
                        <td>${prod.cantidad}</td>
                        <td>S/. ${prod.precio_unitario.toFixed(2)}</td>
                        <td>S/. ${prod.subtotal.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="borrarProducto(${index})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Actualizar JSON Hidden
            $('#productos_seleccionados_json').val(JSON.stringify(productosSeleccionados));
        }

        // Cambio en Descuento
        $('#descuento_total').on('input', function () {
            calculateMontoTotalVenta();
        });


        // Función Global para recalcular total (usada también por Cronograma)
        window.calculateMontoTotalVenta = function () {
            let total = productosSeleccionados.reduce((sum, item) => sum + item.subtotal, 0);
            const descuento = parseFloat($('#descuento_total').val()) || 0;

            total = total - descuento;
            if (total < 0) total = 0;

            $('#monto_total_venta').val(total.toFixed(2));

            // Si está activa la función de cronograma, llamarla
            if (typeof generatePaymentTable === 'function') {
                generatePaymentTable();
            }
        };

        // -------------------------------------------------------------------------
        // 9. LÓGICA DE CRONOGRAMA / CUOTAS (Copiada y adaptada de Matriculas)
        // -------------------------------------------------------------------------
        const montoTotalVentaInput = document.getElementById('monto_total_venta');
        const fechaVentaInput = document.getElementById('fecha_venta');
        const descuentoTotalInput = document.getElementById('descuento_total');

        const montoFinalInput = montoTotalVentaInput; // Alias para compatibilidad
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

        // Mapeo de fechas
        function addDays(date, days) {
            const result = new Date(date);
            result.setDate(result.getDate() + days);
            return result;
        }

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
                generatePaymentTable(); // Regenerar al cambiar
            } else {
                $(divConfigCuotas).slideUp();
                hiddenPagoEnCuotas.value = '0';
                generatePaymentTable(); // Regenerar al cambiar
            }
        });

        // Botón Generar Cronograma
        $(btnGenerarCrono).on('click', generatePaymentTable);

        // Recalcular tabla si cambia el monto total
        // La función calculateMontoTotalVenta ya llama a generatePaymentTable()
        // por lo que no es necesario redefinirla aquí.

        function generatePaymentTable() {
            const mode = document.querySelector('input[name="tipo_pago_modalidad"]:checked').value;
            const totalPagar = parseFloat(montoFinalInput.value) || 0;
            const startDateStr = fechaVentaInput.value || getTodayDate();

            // Limpiar tabla
            tablaCronoBody.innerHTML = '';

            let dateNow = new Date(startDateStr + 'T00:00:00'); // Fix timezone issue simple
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

                let currentDate = new Date(startDateStr + 'T12:00:00'); // Evitar salto dia

                for (let i = 1; i <= n; i++) {
                    let styleClass = (i === 1) ? 'table-success' : ''; // Primera cuota se paga hoy (o se asume inicial)
                    let inputClass = (i === 1) ? 'input-monto-row' : 'input-monto-row';
                    let readonly = (i === 0) ? '' : ''; // Todas editables para ajustar

                    // Fila 1 = Inicial = Hoy
                    // Filas > 1 = Futuro
                    let fechaMostrar = formatDate(currentDate);
                    let estadoLabel = (i === 1) ? '<span class="badge badge-success">Inicial</span>' : '<span class="badge badge-warning">Pendiente</span>';

                    if (i === 1) {
                        // Inicial hoy
                    } else {
                        // Calcular siguiente fecha
                        let daysToAdd = 30;
                        if (freq === 'quincenal') daysToAdd = 15;
                        if (freq === 'semanal') daysToAdd = 7;
                        if (freq === 'diario') daysToAdd = 1;
                        currentDate.setDate(currentDate.getDate() + daysToAdd);
                        fechaMostrar = formatDate(currentDate);
                    }

                    // Determinar valor
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
            // Eliminar fila
            $('.btn-remove-row').off('click').on('click', function () {
                $(this).closest('tr').remove();
                // Renumerar
                let rows = tablaCronoBody.querySelectorAll('tr');
                rows.forEach((r, idx) => {
                    r.querySelector('td:first-child').childNodes[0].textContent = idx + 1 + " ";
                });
                updateTableTotals();
            });

            // Change inputs
            $('.input-monto-row').off('input').on('input', updateTableTotals);
        }

        function updateTableTotals() {
            let sum = 0;
            const rows = tablaCronoBody.querySelectorAll('tr');

            rows.forEach((row, index) => {
                const inputVal = row.querySelector('.input-monto-row').value;
                const val = parseFloat(inputVal) || 0;
                sum += val;

                // SYNC logic:
                // Solo nos importa llenar el hidden container al final, 
                // pero necesitamos actualizar montoPagadoHidden con la PRIMERA cuota (Inicial)
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

        // Intercept Form Submit para llenar Hidden Fields
        $('#form_nueva_venta').off('submit').on('submit', function (event) {
            // 1. Validar Cliente
            const idCliente = $('#id_cliente').val();
            if (!idCliente) {
                event.preventDefault(); Swal.fire({ icon: 'error', title: 'Falta Cliente', text: 'Seleccione un cliente.' }); return false;
            }
            // 2. Validar Productos
            if (productosSeleccionados.length === 0) {
                event.preventDefault(); Swal.fire({ icon: 'error', title: 'Cesta vacía', text: 'Agregue productos.' }); return false;
            }

            // 3. Validar Totales Cronograma
            const totalCrono = parseFloat(labelTotalCrono.textContent) || 0;
            const totalVenta = parseFloat(montoFinalInput.value) || 0;
            // Tolerancia de 0.1
            if (Math.abs(totalVenta - totalCrono) > 0.1) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Montos no coinciden',
                    text: `El total del cronograma (S/. ${totalCrono}) no coincide con el total de la venta (S/. ${totalVenta}). Ajuste los montos de las cuotas.`
                });
                return false;
            }

            // 4. Generar Arrays para Backend
            hiddenCuotasContainer.innerHTML = ''; // Limpiar
            const rows = tablaCronoBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                if (index > 0) { // Solo cuotas futuras (Index 0 es la inicial que va en monto_pagado y se inserta directo)
                    const fecha = row.querySelector('.input-fecha-row').value;
                    const monto = row.querySelector('.input-monto-row').value;

                    // Inputs array
                    hiddenCuotasContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="fechas_cuotas[]" value="${fecha}">`);
                    hiddenCuotasContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="montos_cuotas[]" value="${monto}">`);
                }
            });

            // Si pasa todo, deja enviar
        });

        // Inicializar cálculo (Llama a generatePaymentTable)
        calculateMontoTotalVenta();

        // -------------------------------------------------------------------------
        // LOGICA DE DESCUENTO (Igual a Matrículas)
        // -------------------------------------------------------------------------
        const CLAVE_AUTORIZADA = <?php echo json_encode($_SESSION['clave_descuento_sesion'] ?? '12345'); ?>;

        $('#descuento_total').on('click', function () {
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
    });

</script>