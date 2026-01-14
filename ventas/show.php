<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Controlador para cargar los datos de la venta específica y sus detalles
include('../app/controllers/ventas/show_venta_data.php');

// Redirigir si no se encontraron datos de la venta (esto ya lo maneja show_venta_data.php)
if (!isset($venta_data) || !isset($detalle_venta_data)) {
    // Esto ya debería ser manejado por show_venta_data.php, pero como fallback
    header('Location: ' . $URL . '/ventas/');
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
                    <h1 class="m-0"></h1>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Detalle de Venta #<?php echo $venta_data['id_venta']; ?></h3>


                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>ID Venta:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $venta_data['id_venta']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Cliente:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $venta_data['nombre_completo_cliente'] ?? 'Cliente Eliminado/Anónimo'; ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de Venta:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $venta_data['fecha_venta']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Monto Total (S/.):</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo number_format($venta_data['monto_total'], 2); ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Descuento Total (S/.):</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo number_format($venta_data['descuento_total'], 2); ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Estado de Pago:</label>
                                        <?php
                                        $monto_total_venta = $venta_data['monto_total'];
                                        $total_pagado_venta = $venta_data['total_pagado'] ?? 0;
                                        $estado_pago_venta = '';
                                        $clase_estado_venta = '';
                                        $monto_pendiente_venta = $monto_total_venta - $total_pagado_venta;

                                        if ($total_pagado_venta >= $monto_total_venta) {
                                            $estado_pago_venta = 'Pagado';
                                            $clase_estado_venta = 'badge badge-success';
                                        } else if ($total_pagado_venta > 0 && $total_pagado_venta < $monto_total_venta) {
                                            $estado_pago_venta = 'Parcial (Falta S/. ' . number_format($monto_pendiente_venta, 2) . ')';
                                            $clase_estado_venta = 'badge badge-warning';
                                        } else {
                                            $estado_pago_venta = 'Pendiente (S/. ' . number_format($monto_total_venta, 2) . ')';
                                            $clase_estado_venta = 'badge badge-danger';
                                        }
                                        ?>
                                        <span
                                            class="<?php echo $clase_estado_venta; ?>"><?php echo $estado_pago_venta; ?></span>
                                    </div>

                                    <hr>
                                    <h4>Productos de la Venta</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unitario (S/.)</th>
                                                <th>Subtotal (S/.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($detalle_venta_data)) {
                                                foreach ($detalle_venta_data as $detalle_item) { ?>
                                                    <tr>
                                                        <td><?php echo $detalle_item['nombre_producto'] ?? 'Producto Eliminado'; ?>
                                                        </td>
                                                        <td><?php echo $detalle_item['cantidad']; ?></td>
                                                        <td><?php echo number_format($detalle_item['precio_unitario'], 2); ?>
                                                        </td>
                                                        <td><?php echo number_format($detalle_item['subtotal'], 2); ?></td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td colspan="4">
                                                        <center>No hay productos registrados para esta venta.</center>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <hr>
                                    <div class="form-group">
                                        <a href="index.php" class="btn btn-secondary">Volver</a>
                                    </div>
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