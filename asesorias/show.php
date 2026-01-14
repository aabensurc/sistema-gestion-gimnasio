<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Incluir el controlador para cargar los datos de la asesoría específica
include('../app/controllers/asesorias/show_asesoria_data.php');

// Redirigir si no se encontraron datos de la asesoría (esto ya lo maneja show_asesoria_data.php)
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
                            <h3 class="card-title">Información de la Asesoría
                                #<?php echo $asesoria_data['id_asesoria']; ?></h3>


                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>ID Asesoría:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $asesoria_data['id_asesoria']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Cliente:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $asesoria_data['nombre_completo_cliente'] ?? 'Cliente Eliminado/Anónimo'; ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Entrenador:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $asesoria_data['nombre_completo_entrenador'] ?? 'Entrenador Eliminado/N/A'; ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Monto Final (S/.):</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo number_format($asesoria_data['monto_final'], 2); ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha Inicio:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $asesoria_data['fecha_inicio']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha Fin:</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo $asesoria_data['fecha_fin']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Estado de Pago:</label>
                                        <?php
                                        $monto_final_asesoria = $asesoria_data['monto_final'];
                                        $total_pagado_asesoria = $asesoria_data['total_pagado'] ?? 0;
                                        $estado_pago_asesoria = '';
                                        $clase_estado_asesoria = '';
                                        $monto_pendiente_asesoria = $monto_final_asesoria - $total_pagado_asesoria;

                                        if ($total_pagado_asesoria >= $monto_final_asesoria) {
                                            $estado_pago_asesoria = 'Pagado';
                                            $clase_estado_asesoria = 'badge badge-success';
                                        } else if ($total_pagado_asesoria > 0 && $total_pagado_asesoria < $monto_final_asesoria) {
                                            $estado_pago_asesoria = 'Parcial (Falta S/. ' . number_format($monto_pendiente_asesoria, 2) . ')';
                                            $clase_estado_asesoria = 'badge badge-warning';
                                        } else {
                                            $estado_pago_asesoria = 'Pendiente (S/. ' . number_format($monto_final_asesoria, 2) . ')';
                                            $clase_estado_asesoria = 'badge badge-danger';
                                        }
                                        ?>
                                        <span
                                            class="<?php echo $clase_estado_asesoria; ?>"><?php echo $estado_pago_asesoria; ?></span>
                                    </div>

                                    <hr>
                                    <div class="form-group">
                                        <a href="index.php" class="btn btn-secondary">Volver al Listado</a>
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