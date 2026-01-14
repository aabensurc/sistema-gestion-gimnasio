<?php
include('app/config.php');
include('layout/sesion.php');

// Incluir el controlador que obtiene todos los datos para el dashboard
include('app/controllers/dashboard/get_dashboard_data.php');

include('layout/parte1.php');

// Formatear valores
$total_ingresos_fmt = number_format($dashboard_data['total_ingresos'], 2);
$pagos_pendientes_fmt = number_format($dashboard_data['pagos_pendientes'], 2);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page header) & Filter -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <!-- DATE RANGER FILTER -->
          <form action="" method="GET" id="dashboard_filter_form" class="float-sm-right form-inline">
            <div class="form-group">
              <button type="button" class="btn btn-primary" id="daterange-btn" style="border-radius: 5px;">
                <i class="far fa-calendar-alt mr-2"></i>
                <span id="reportrange-span">
                  <!-- Date text will be injected here -->
                </span>
                <i class="fas fa-caret-down ml-2"></i>
              </button>
              <!-- Hidden inputs for backend -->
              <input type="hidden" name="fecha_inicio" id="fecha_inicio"
                value="<?php echo $dashboard_data['fecha_inicio']; ?>">
              <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $dashboard_data['fecha_fin']; ?>">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <!-- 4 MASTER CARDS -->
      <div class="row">

        <!-- CARD 1: INGRESOS (Filtrado) -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>S/.<?php echo $total_ingresos_fmt; ?></h3>
              <p>Ingresos (Periodo Seleccionado)</p>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="<?php echo $URL; ?>/pagos" class="small-box-footer">Ver Detalle <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- CARD 2: CLIENTES ACTIVOS (Snapshot Actual) -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo $dashboard_data['clientes_activos']; ?></h3>
              <p>Clientes Activos Hoy</p>
            </div>
            <div class="icon">
              <i class="ion ion-heart"></i>
            </div>
            <a href="<?php echo $URL; ?>/matriculas" class="small-box-footer">Ver Matriculados <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- CARD 3: PRÓXIMOS VENCIMIENTOS (Alerta) -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?php echo $dashboard_data['proximos_vencimientos']; ?></h3>
              <p>Vencen en este periodo</p>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
            <a href="<?php echo $URL; ?>/matriculas" class="small-box-footer">Gestionar <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- CARD 4: FLUJO DE CAJA (Deuda) -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>S/.<?php echo $pagos_pendientes_fmt; ?></h3>
              <p>Pagos Pendientes Total</p>
            </div>
            <div class="icon">
              <i class="ion ion-card"></i>
            </div>
            <a href="<?php echo $URL; ?>/pagos" class="small-box-footer">Cobrar Ahora <i
                class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

      </div>
      <!-- /.row -->

      <!-- Main Charts Row -->
      <div class="row">
        <!-- Ingresos Trend -->
        <section class="col-lg-8 connectedSortable">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-line mr-1"></i>
                Tendencia de Ingresos
              </h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="ingresosTrendChart"
                  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </section>

        <!-- Payment Types Pie -->
        <section class="col-lg-4 connectedSortable">
          <div class="card card-purple card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Tipos de Ingreso
              </h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="tipoPagoChart"
                  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Quick Links / Nuevos Clientes -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-secondary">
            <div class="card-header">
              <h3 class="card-title">
                Resumen de Actividad
              </h3>
            </div>
            <div class="card-body">
              <p><strong>Nuevos Clientes Registrados en el periodo:</strong> <span class="badge badge-success"
                  style="font-size: 1rem;"><?php echo $dashboard_data['nuevos_clientes']; ?></span></p>
            </div>
          </div>
        </div>
      </div>


    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include('layout/parte2.php'); ?>

<!-- Script para Chart.js -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>
<script>
  $(function () {

    // --- 1. Configurar DaterangePicker (Botón Moderno) ---
    var startDate = moment('<?php echo $dashboard_data["fecha_inicio"]; ?>');
    var endDate = moment('<?php echo $dashboard_data["fecha_fin"]; ?>');

    // Función para actualizar el texto del botón
    function cb(start, end) {
      $('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      // Actualizar inputs ocultos
      $('#fecha_inicio').val(start.format('YYYY-MM-DD'));
      $('#fecha_fin').val(end.format('YYYY-MM-DD'));
    }

    // Inicializar picker en el botón
    $('#daterange-btn').daterangepicker({
      startDate: startDate,
      endDate: endDate,
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      locale: {
        format: 'DD/MM/YYYY',
        applyLabel: "Aplicar",
        cancelLabel: "Cancelar",
        fromLabel: "Desde",
        toLabel: "Hasta",
        customRangeLabel: "Personalizado",
        daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        firstDay: 1
      },
      opens: 'left' // Abre hacia la izquierda para que no se salga
    }, function (start, end) {
      cb(start, end);
      // Al seleccionar, hacer submit
      $('#dashboard_filter_form').submit();
    });

    // Llamada inicial para poner el texto correcto al cargar
    cb(startDate, endDate);


    // --- 2. Gráfico de Tendencia (Bar Chart) ---
    var areaChartCanvas = $('#ingresosTrendChart').get(0).getContext('2d');
    var areaChartData = {
      labels: <?php echo json_encode($dashboard_data['chart_ingresos_labels']); ?>,
      datasets: [
        {
          label: 'Ingresos',
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          pointRadius: false,
          pointColor: '#3b8bba',
          pointStrokeColor: 'rgba(60,141,188,1)',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data: <?php echo json_encode($dashboard_data['chart_ingresos_values']); ?>
        }
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio: false,
      responsive: true,
      legend: { display: false },
      scales: {
        xAxes: [{ gridLines: { display: false } }],
        yAxes: [{
          gridLines: { display: false },
          ticks: {
            beginAtZero: true,
            callback: function (value) { return 'S/.' + value; }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function (item) { return 'S/.' + item.yLabel.toFixed(2); }
        }
      }
    }

    new Chart(areaChartCanvas, {
      type: 'bar',
      data: areaChartData,
      options: areaChartOptions
    });


    // --- 3. Gráfico de Tipos de Pago (Pie Chart) ---
    var pieChartCanvas = $('#tipoPagoChart').get(0).getContext('2d');
    var pieData = {
      labels: <?php echo json_encode($dashboard_data['pie_labels']); ?>,
      datasets: [
        {
          data: <?php echo json_encode($dashboard_data['pie_values']); ?>,
          backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545', '#6c757d'],
        }
      ]
    }
    var pieOptions = {
      maintainAspectRatio: false,
      responsive: true,
    }

    new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: pieData,
      options: pieOptions
    });

  });
</script>