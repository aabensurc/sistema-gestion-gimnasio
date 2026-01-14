<?php
// 1. CONFIGURACIÓN Y SESIÓN
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

// 2. OBTENER DATOS DEL CLIENTE (Controlador original)
// Esto define variables como $nombres, $dni, $foto, $id_cliente_get, etc.
include('../app/controllers/clientes/show_cliente.php');

// 3. OBTENER FECHAS DEL FILTRO (Si se enviaron)
$fecha_inicio_filtro = $_GET['fecha_inicio'] ?? '';
$fecha_fin_filtro = $_GET['fecha_fin'] ?? '';

// 4. INCLUIR CONTROLADORES DE HISTORIAL
// Estos archivos usarán $id_cliente_get y las fechas para llenar los arrays:
// $historial_matriculas, $historial_ventas, $historial_pagos
include('../app/controllers/matriculas/listado_matriculas_cliente.php');
include('../app/controllers/ventas/listado_ventas_cliente.php');
include('../app/controllers/pagos/listado_pagos_cliente.php');
include('../app/controllers/asistencias_clientes/listado_asistencias_cliente.php');

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registros del Cliente</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user"></i> Datos Personales</h3>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>Nombres Completos</label>
                                            <input type="text" class="form-control"
                                                value="<?php echo $nombres . ' ' . $ape_pat . ' ' . $ape_mat; ?>"
                                                disabled>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>DNI</label>
                                            <input type="text" class="form-control" value="<?php echo $dni; ?>"
                                                disabled>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" value="<?php echo $telefono; ?>"
                                                disabled>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="<?php echo $email; ?>"
                                                disabled>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Vencimiento Matrícula:</label>
                                            <?php
                                            $val_fecha = isset($fecha_fin_ultima_matricula) ? date('d/m/Y', strtotime($fecha_fin_ultima_matricula)) : 'Sin historial';
                                            $color_fecha = '';
                                            if (isset($fecha_fin_ultima_matricula) && $fecha_fin_ultima_matricula < date('Y-m-d')) {
                                                $color_fecha = 'style="color: red; font-weight: bold;"';
                                                $val_fecha .= " (Vencido)";
                                            }
                                            ?>
                                            <input type="text" class="form-control" <?php echo $color_fecha; ?>
                                                value="<?php echo $val_fecha; ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <center>
                                        <label>Foto de Perfil</label><br>
                                        <img src="<?php echo $URL; ?>/public/images/clientes/<?php echo $foto; ?>"
                                            width="150px" class="img-thumbnail elevation-2">
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filtrar Historial por Fechas</h3>

                        </div>
                        <div class="card-body" style="padding: 15px;">
                            <form action="" method="GET">
                                <input type="hidden" name="id" value="<?php echo $id_cliente_get; ?>">

                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label>Fecha Inicio:</label>
                                        <input type="date" name="fecha_inicio" class="form-control"
                                            value="<?php echo $fecha_inicio_filtro; ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Fecha Fin:</label>
                                        <input type="date" name="fecha_fin" class="form-control"
                                            value="<?php echo $fecha_fin_filtro; ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i>
                                            Aplicar Filtro</button>
                                        <?php if (!empty($fecha_inicio_filtro)): ?>
                                            <a href="show.php?id=<?php echo $id_cliente_get; ?>" class="btn btn-default"><i
                                                    class="fas fa-sync"></i> Ver Todo</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-dumbbell"></i> Historial de Matrículas</h3>

                        </div>
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-head-fixed text-nowrap table-striped table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Periodo</th>
                                        <th>Estado</th>
                                        <th>Boleta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial_matriculas as $hm) {
                                        $hoy = date('Y-m-d');
                                        $estado = ($hm['fecha_fin'] >= $hoy) ? '<span class="badge badge-success">Vigente</span>' : '<span class="badge badge-danger">Vencido</span>';
                                        ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $hm['nombre_plan']; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <small>Del:
                                                        <?php echo date('d/m/y', strtotime($hm['fecha_inicio'])); ?></small><br>
                                                    <small>Al:
                                                        <?php echo date('d/m/y', strtotime($hm['fecha_fin'])); ?></small>
                                                </center>
                                            </td>
                                            <td>
                                                <center><?php echo $estado; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <a href="../app/controllers/matriculas/generar_boleta_matricula.php?id=<?php echo $hm['id_matricula']; ?>"
                                                        target="_blank" class="btn btn-xs btn-dark"><i
                                                            class="fas fa-print"></i></a>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($historial_matriculas))
                                        echo "<tr><td colspan='4' class='text-center'>Sin registros</td></tr>"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shopping-cart"></i> Historial de Ventas</h3>

                        </div>
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-head-fixed text-nowrap table-striped table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Desc.</th>
                                        <th>Boleta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial_ventas as $hv) { ?>
                                        <tr>
                                            <td>
                                                <center><?php echo date('d/m/Y', strtotime($hv['fecha_venta'])); ?></center>
                                            </td>
                                            <td>
                                                <center>S/. <?php echo number_format($hv['monto_total'], 2); ?></center>
                                            </td>
                                            <td>
                                                <center>S/. <?php echo number_format($hv['descuento_total'], 2); ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <a href="../app/controllers/ventas/generar_boleta.php?id=<?php echo $hv['id_venta']; ?>"
                                                        target="_blank" class="btn btn-xs btn-dark"><i
                                                            class="fas fa-print"></i></a>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($historial_ventas))
                                        echo "<tr><td colspan='4' class='text-center'>Sin registros</td></tr>"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check"></i> Historial de Asistencias</h3>

                        </div>
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-head-fixed text-nowrap table-striped table-sm text-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>Nro</center>
                                        </th>
                                        <th>
                                            <center>Fecha Asistencia</center>
                                        </th>
                                        <th>
                                            <center>Hora Entrada</center>
                                        </th>
                                        <th>
                                            <center>Día</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cont_asist = 0;
                                    foreach ($historial_asistencias as $ha) {
                                        $cont_asist++;
                                        // Formatear día de la semana en español (opcional, visualmente útil)
                                        $dias_semana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
                                        $dia_num = date('w', strtotime($ha['fecha_asistencia']));
                                        $nombre_dia = $dias_semana[$dia_num];
                                        ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $cont_asist; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo date('d/m/Y', strtotime($ha['fecha_asistencia'])); ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center><?php echo date('h:i A', strtotime($ha['hora_entrada'])); ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center><span class="badge badge-light"><?php echo $nombre_dia; ?></span>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($historial_asistencias))
                                        echo "<tr><td colspan='4' class='text-center'>Sin asistencias registradas en este periodo</td></tr>"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-money-bill-wave"></i> Historial General de Pagos
                                (Flujo de Caja)</h3>

                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-sm text-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">Fecha y Hora</th>
                                        <th class="text-left">Concepto / Referencia</th>
                                        <th class="text-center">Método de Pago</th>
                                        <th class="text-right">Monto Pagado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_pagos_filtrado = 0;
                                    foreach ($historial_pagos as $hp) {
                                        $concepto = "";
                                        $tipo_badge = "badge-secondary";

                                        // Determinar concepto y color
                                        if ($hp['tipo_pago'] == 'matricula') {
                                            $concepto = "Matrícula (ID: " . $hp['id_matricula_fk'] . ")";
                                            $tipo_badge = "badge-warning";
                                        } elseif ($hp['tipo_pago'] == 'venta') {
                                            $concepto = "Venta de Productos (ID: " . $hp['id_venta_fk'] . ")";
                                            $tipo_badge = "badge-success";
                                        } elseif ($hp['tipo_pago'] == 'asesoria') {
                                            $concepto = "Asesoría Personalizada (ID: " . $hp['id_asesoria_fk'] . ")";
                                            $tipo_badge = "badge-primary"; // Asegúrate de tener este estilo o usa info
                                        }

                                        $total_pagos_filtrado += $hp['monto'];
                                        ?>
                                        <tr>
                                            <td>
                                                <center><?php echo date('d/m/Y H:i A', strtotime($hp['fecha_hora'])); ?>
                                                </center>
                                            </td>
                                            <td class="text-left"><span
                                                    class="badge <?php echo $tipo_badge; ?>"><?php echo strtoupper($hp['tipo_pago']); ?></span>
                                                <?php echo $concepto; ?></td>
                                            <td>
                                                <center><?php echo ucfirst(str_replace('_', ' ', $hp['metodo_pago'])); ?>
                                                </center>
                                            </td>
                                            <td class="text-right"><b>S/. <?php echo number_format($hp['monto'], 2); ?></b>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($historial_pagos))
                                        echo "<tr><td colspan='4' class='text-center'>No hay pagos registrados en este periodo</td></tr>"; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #e9ecef; font-size: 1.1em;">
                                        <td colspan="3" class="text-right"><b>TOTAL PAGADO POR EL CLIENTE (EN ESTE
                                                PERIODO):</b></td>
                                        <td class="text-right"><b style="color: green;">S/.
                                                <?php echo number_format($total_pagos_filtrado, 2); ?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 mb-4">
                    <a href="index.php" class="btn btn-secondary">Volver al listado de clientes</a>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>