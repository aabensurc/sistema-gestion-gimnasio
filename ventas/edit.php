<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php'); 

// Incluir controladores para cargar datos de clientes y productos
include('../app/controllers/clientes/listado_de_clientes.php'); // Para el select de clientes
include('../app/controllers/productos/listado_de_productos.php'); // Para el select de productos

// Controlador para cargar los datos de la venta específica y sus detalles
include('../app/controllers/ventas/edit_venta_data.php'); 

// Redirigir si no se encontraron datos de la venta (esto ya lo maneja edit_venta_data.php)
if (!isset($venta_data) || !isset($detalle_venta_data_edit)) {
    header('Location: ' . $URL . '/ventas/');
    exit();
}

// Formatear la fecha de venta para el input type="date"
$fecha_venta_formatted = date('Y-m-d', strtotime($venta_data['fecha_venta']));

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
        <!--     <h1 class="m-0">Editar Venta #<?php echo $venta_data['id_venta']; ?></h1> -->
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-8">
          <div class="card card-success">
          <div class="card-header">
          <h3 class="card-title">Modifique los datos de la venta y sus productos</h3>

    
          </div>

          <div class="card-body" style="display: block;">
                <div class="row">
                    <div class="col-md-12">
                        <form action="../app/controllers/ventas/update_venta_process.php" method="post" id="form_editar_venta">
                            <input type="hidden" name="id_venta" value="<?php echo $venta_data['id_venta']; ?>">

                            <div class="form-group">
                              <label for="id_cliente">Cliente</label>
                              <select name="id_cliente" id="id_cliente" class="form-control select2bs4">
                                <option value="">Seleccione un cliente (opcional)</option>
                                <?php
                                  if (isset($clientes_datos) && is_array($clientes_datos)) {
                                      foreach($clientes_datos as $clientes_dato){ ?>
                                        <option value="<?php echo $clientes_dato['id_cliente'];?>"
                                            <?php if(isset($venta_data['id_cliente']) && $clientes_dato['id_cliente'] == $venta_data['id_cliente']) echo 'selected'; ?>>
                                            <?php echo $clientes_dato['nombres'].' '.$clientes_dato['ape_pat'].' '.$clientes_dato['ape_mat'];?>
                                        </option>
                                      <?php }
                                  }
                                 ?>
                              </select>
                            </div>

                            <div class="form-group">          
                              <label for="fecha_venta">Fecha de Venta</label>
                              <input type="date" name="fecha_venta" class="form-control" id="fecha_venta" 
                                     value="<?php echo $fecha_venta_formatted; ?>" required>
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
                                            foreach($productos_datos as $producto){ ?>
                                                <option value="<?php echo $producto['id_producto'];?>"
                                                        data-precio="<?php echo $producto['precio_venta'];?>"
                                                        data-stock="<?php echo $producto['stock'];?>">
                                                    <?php echo $producto['nombre'];?> (Stock: <?php echo $producto['stock'];?>) - S/.<?php echo number_format($producto['precio_venta'], 2); ?>
                                                </option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="cantidad_producto">Cantidad</label>
                                    <input type="number" id="cantidad_producto" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-2">
                                    <label for="precio_unitario_display">Precio Unitario</label>
                                    <input type="text" id="precio_unitario_display" class="form-control" readonly>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary btn-block" id="btn_add_product">
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
                              <input type="number" name="descuento_total" class="form-control" id="descuento_total" 
                                     value="<?php echo number_format($venta_data['descuento_total'], 2, '.', ''); ?>" step="0.01">
                            </div>

                            <div class="form-group ">          
                              <label for="monto_total_venta">Monto Total de la Venta (S/.)</label>
                              <input type="number" name="monto_total" class="form-control" id="monto_total_venta" 
                                     readonly step="0.01" value="<?php echo number_format($venta_data['monto_total'], 2, '.', ''); ?>">
                            </div>

                            <hr>
                            <h4>Detalle de Pago</h4> <!-- Sección de pago activa para edición -->
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

                            <div class="form-group">
                                <label for="monto_pagado">Monto Pagado (S/.)</label>
                                <input type="number" name="monto_pagado" class="form-control" id="monto_pagado" placeholder="Monto que el cliente paga..." step="0.01" required>
                            </div>
                            
                            <hr>
                            <div class="form-group">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success">Actualizar Venta</button>
                            </div>
                            <!-- Campo oculto para enviar los detalles de los productos como JSON -->
                            <input type="hidden" name="productos_seleccionados_json" id="productos_seleccionados_json">
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
        const selectProducto = document.getElementById('id_producto');
        const cantidadProductoInput = document.getElementById('cantidad_producto');
        const precioUnitarioDisplay = document.getElementById('precio_unitario_display');
        const btnAddProduct = document.getElementById('btn_add_product');
        const detalleProductosTableBody = document.querySelector('#detalle_productos_table tbody');
        const descuentoTotalInput = document.getElementById('descuento_total');
        const montoTotalVentaInput = document.getElementById('monto_total_venta');
        const montoPagadoInput = document.getElementById('monto_pagado'); // Nuevo campo para el monto pagado
        const productosSeleccionadosJsonInput = document.getElementById('productos_seleccionados_json');

        // Initialize productosSeleccionados with existing data
        let productosSeleccionados = <?php echo json_encode($detalle_venta_data_edit); ?>.map(item => ({
            ...item,
            precio_unitario: parseFloat(item.precio_unitario),
            subtotal: parseFloat(item.subtotal)
        }));

        // Update price display when product selected
        selectProducto.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.dataset.precio;
            precioUnitarioDisplay.value = precio ? parseFloat(precio).toFixed(2) : '';
        });

        // Add product to detail table
        btnAddProduct.addEventListener('click', function() {
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const idProducto = selectedOption.value;
            const nombreProducto = selectedOption.textContent.split(' (Stock:')[0]; // Get only name
            const precioUnitario = parseFloat(selectedOption.dataset.precio);
            const stockDisponible = parseInt(selectedOption.dataset.stock);
            const cantidad = parseInt(cantidadProductoInput.value);

            if (!idProducto || isNaN(cantidad) || cantidad <= 0) {
                alert('Por favor, seleccione un producto y una cantidad válida.');
                return;
            }

            // Calculate current quantity of this product in the table
            const currentQuantityInTable = productosSeleccionados
                .filter(item => item.id_producto === idProducto)
                .reduce((sum, item) => sum + item.cantidad, 0);

            if (cantidad + currentQuantityInTable > stockDisponible) {
                alert(`No hay suficiente stock para ${nombreProducto}. Stock disponible: ${stockDisponible}. Cantidad actual en tabla: ${currentQuantityInTable}`);
                return;
            }

            // Check if product already in list, if so, update quantity
            const existingProductIndex = productosSeleccionados.findIndex(item => item.id_producto === idProducto);

            if (existingProductIndex > -1) {
                productosSeleccionados[existingProductIndex].cantidad += cantidad;
                productosSeleccionados[existingProductIndex].subtotal = productosSeleccionados[existingProductIndex].cantidad * precioUnitario;
            } else {
                productosSeleccionados.push({
                    id_producto: idProducto,
                    nombre_producto: nombreProducto,
                    cantidad: cantidad,
                    precio_unitario: precioUnitario,
                    subtotal: cantidad * precioUnitario
                });
            }
            
            renderProductosSeleccionados();
            calculateMontoTotalVenta();
            // Reset product selection and quantity
            selectProducto.value = '';
            cantidadProductoInput.value = '1';
            precioUnitarioDisplay.value = '';
        });

        // Render selected products in table
        function renderProductosSeleccionados() {
            detalleProductosTableBody.innerHTML = ''; // Clear existing rows
            productosSeleccionados.forEach((item, index) => {
                const row = detalleProductosTableBody.insertRow();
                row.innerHTML = `
                    <td>${item.nombre_producto}</td>
                    <td>${item.cantidad}</td>
                    <td>${item.precio_unitario.toFixed(2)}</td>
                    <td>${item.subtotal.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" data-index="${index}">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </td>
                `;
            });
            productosSeleccionadosJsonInput.value = JSON.stringify(productosSeleccionados); // Update hidden input
        }

        // Remove product from detail table
        detalleProductosTableBody.addEventListener('click', function(event) {
            if (event.target.tagName === 'BUTTON' && event.target.dataset.index) {
                const indexToRemove = parseInt(event.target.dataset.index);
                productosSeleccionados.splice(indexToRemove, 1); // Remove item from array
                renderProductosSeleccionados();
                calculateMontoTotalVenta();
            }
        });

        // Calculate total sale amount
        function calculateMontoTotalVenta() {
            let total = productosSeleccionados.reduce((sum, item) => sum + item.subtotal, 0);
            const descuento = parseFloat(descuentoTotalInput.value) || 0;
            total = total - descuento;
            if (total < 0) total = 0; // Prevent negative total
            montoTotalVentaInput.value = total.toFixed(2);
            montoPagadoInput.value = total.toFixed(2); // Pre-fill monto pagado with total
        }

        // Recalculate total when discount changes
        descuentoTotalInput.addEventListener('input', calculateMontoTotalVenta);

        // Initial render and calculation when page loads with existing data
        renderProductosSeleccionados();
        calculateMontoTotalVenta();
    });
</script>
