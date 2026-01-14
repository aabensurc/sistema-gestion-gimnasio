<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    By Alex Abensur
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; 2024 <a href="https://adminlte.io">Creado por ABENSUR COMPANY</a>.</strong> Todos los
  derechos reservados.
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>


<!-- DataTables  & Plugins -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables/jquery.dataTables.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/pdfmake/vfs_fonts.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- ChartJS -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/moment/moment.min.js"></script>
<!-- moment -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/moment/moment.min.js"></script>
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script
  src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- Esta ultima es la que causa conflicto con el boton para ocultar el sidebar -->
<!-- AdminLTE App -->
<!-- <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/js/adminlte.js"></script> -->





<script src="https://kit.fontawesome.com/823ad58a6d.js" crossorigin="anonymous"></script>


<!-- Select2 -->
<script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js"></script>



<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
      format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function (event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function () {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function (file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function () { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function (progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function (file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function (progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function () {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function () {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
</script>








<!-- Modal para mostrar el estado de la matrícula y registrar asistencia (GLOBAL) -->
<div class="modal fade" id="clientMembershipModal" tabindex="-1" role="dialog"
  aria-labelledby="clientMembershipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="clientMembershipModalLabel">Estado de Matrícula y Asistencia</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_client_info" class="mb-3">
          <p><strong>Cliente:</strong> <span id="modal_client_name"></span> (<span id="modal_client_code_dni"></span>)
          </p>
        </div>
        <div id="modal_membership_status_card" class="card">
          <div class="card-header" id="modal_status_header">
            <h5 class="card-title" id="modal_status_title"></h5>
          </div>
          <div class="card-body">
            <p><strong>Plan:</strong> <span id="modal_plan_name"></span></p>
            <p><strong>Fecha de Inicio:</strong> <span id="modal_fecha_inicio"></span></p>
            <p><strong>Fecha de Vencimiento:</strong> <span id="modal_fecha_fin"></span></p>
            <p><strong>Estado:</strong> <span id="modal_status_badge" class="badge"></span></p>
          </div>
        </div>
        <div id="modal_no_membership_info" class="alert alert-warning" style="display: none;">
          <i class="icon fas fa-exclamation-triangle"></i> Este cliente no tiene matrículas activas o no se encontró
          ninguna matrícula.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- Botón para Matricular/Renovar -->
        <button type="button" class="btn btn-primary" id="matricular_btn" style="display: none;">Matricular</button>
        <!-- Botón para Registrar Asistencia -->
        <button type="button" class="btn btn-success" id="confirm_attendance_btn" style="display: none;">Registrar
          Asistencia</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // --- Lógica GLOBAL para el buscador de clientes (Autocomplete) ---
    const $globalSearchInput = $('#global_search_client_input');
    const $globalSearchResults = $('#global_search_results');
    const $globalHiddenInput = $('#global_search_client_select');

    // 1. Escuchar el evento de escritura (input/keyup)
    $globalSearchInput.on('input', function () {
      let term = $(this).val();

      // Si hay menos de 2 caracteres, ocultar y no buscar
      if (term.length < 2) {
        $globalSearchResults.hide();
        return;
      }

      // Llamada AJAX al controlador de búsqueda
      $.ajax({
        url: '<?php echo $URL; ?>/app/controllers/clientes/search_clients.php',
        dataType: 'json',
        global: false, // IMPORTANTE: No activar el spinner global para búsquedas en tiempo real
        data: { q: term },
        success: function (data) {
          let html = '';
          if (data.length > 0) {
            // El controlador retorna [{id:..., text:...}]
            data.forEach(function (item) {
              html += `
                            <a href="#" class="list-group-item list-group-item-action client-suggestion" 
                               data-id="${item.id}" 
                               data-name="${item.text}"
                               style="padding: 8px 15px; font-size: 0.9rem;">
                                ${item.text}
                            </a>`;
            });
          } else {
            html = '<div class="list-group-item text-muted" style="padding: 8px 15px;">No se encontraron resultados</div>';
          }
          $globalSearchResults.html(html).show();
        },
        error: function () {
          $globalSearchResults.html('<div class="list-group-item text-danger">Error en la búsqueda</div>').show();
        }
      });
    });

    // 4. Manejar tecla ENTER para seleccionar el primer resultado
    $globalSearchInput.on('keydown', function (e) {
      if (e.key === 'Enter' || e.keyCode === 13) {
        e.preventDefault();
        // Buscar la primera sugerencia visible
        const firstItem = $globalSearchResults.find('.client-suggestion').first();
        if (firstItem.length > 0) {
          firstItem.click(); // Simular clic
        }
      }
    });

    // 2. Manejar el clic en una sugerencia (Delegado)
    $(document).on('click', '.client-suggestion', function (e) {
      e.preventDefault();

      let id_cliente = $(this).data('id');
      let nombre_cliente = $(this).data('name');

      // Actualizar inputs
      $globalSearchInput.val(nombre_cliente);
      $globalHiddenInput.val(id_cliente);
      $globalSearchResults.hide();

      // --- Lógica Original: Verificar Matrícula ---
      $('#matricular_btn').data('id_cliente', id_cliente);

      $.ajax({
        url: '<?php echo $URL; ?>/app/controllers/dashboard/check_client_membership.php',
        type: 'POST',
        dataType: 'json',
        data: { id_cliente: id_cliente },
        success: function (response) {
          $('#modal_client_name').text(response.nombre_completo_cliente);
          $('#modal_client_code_dni').text(response.codigo_dni);

          if (response.has_membership) {
            $('#modal_no_membership_info').hide();
            $('#modal_membership_status_card').show();
            $('#confirm_attendance_btn').show();
            $('#matricular_btn').show().text('Renovar Matrícula');

            $('#modal_plan_name').text(response.plan_name);
            $('#modal_fecha_inicio').text(response.fecha_inicio);
            $('#modal_fecha_fin').text(response.fecha_fin);

            $('#modal_status_badge').text(response.status_text);
            $('#modal_status_badge').removeClass().addClass('badge ' + response.status_class);
            $('#modal_status_header').removeClass().addClass('card-header ' + response.header_class);
            $('#clientMembershipModalLabel').removeClass().addClass('modal-title text-white');
          } else {
            $('#modal_no_membership_info').show();
            $('#modal_membership_status_card').hide();
            $('#confirm_attendance_btn').hide();
            $('#matricular_btn').show().text('Matricular Cliente');
            $('#modal_status_header').removeClass().addClass('card-header bg-warning');
            $('#clientMembershipModalLabel').removeClass().addClass('modal-title text-white');
          }
          $('#clientMembershipModal').modal('show');
        },
        error: function (xhr, status, error) {
          console.error("Error al verificar matrícula:", status, error);
          alert('Ocurrió un error al verificar la matrícula del cliente.');
        }
      });
    });

    // 3. Ocultar lista si se hace clic fuera
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#global_search_client_input').length &&
        !$(e.target).closest('#global_search_results').length) {
        $globalSearchResults.hide();
      }
    });

    // 5. Limpiar y enfocar el input al cerrar el modal (Workflow tipo Kiosco)
    $('#clientMembershipModal').on('hidden.bs.modal', function () {
      $globalSearchInput.val('');          // Limpiar texto visual
      $globalHiddenInput.val('');          // Limpiar ID oculto
      $globalSearchInput.focus();          // Devolver el foco
    });


    // Evento para el botón "Matricular/Renovar"
    $('#matricular_btn').on('click', function () {
      const id_cliente_para_matricula = $(this).data('id_cliente');
      if (id_cliente_para_matricula) {
        window.location.href = '<?php echo $URL; ?>/matriculas/create.php?id_cliente=' + id_cliente_para_matricula;
      }
    });

    // Evento para registrar asistencia
    $('#confirm_attendance_btn').on('click', function () {
      const id_cliente = $('#global_search_client_select').val();
      const current_date = new Date().toISOString().slice(0, 10);
      const current_time = new Date().toTimeString().slice(0, 5);

      $.ajax({
        url: '<?php echo $URL; ?>/app/controllers/asistencias_clientes/create_asistencia_process.php',
        type: 'POST',
        data: {
          id_cliente: id_cliente,
          fecha_asistencia: current_date,
          hora_entrada: current_time
        },
        success: function (response) {
          $('#clientMembershipModal').modal('hide');
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error("Error al registrar asistencia:", status, error);
          alert('Ocurrió un error al registrar la asistencia.');
        }
      });
    });

    // =========================================
    // LOGICA GLOBAL DEL SPINNER (Cargando...)
    // =========================================

    // 1. Interceptar todas las peticiones AJAX (jQuery)
    $(document).ajaxStart(function () {
      $('#global-loader').css('display', 'flex');
    });

    $(document).ajaxStop(function () {
      // Usamos fadeOut para una transición suave
      $('#global-loader').fadeOut(200);
    });

    // 3. Interceptar clics en el Sidebar para mostrar spinner al cambiar de módulo
    // REVISIÓN: Algunos ítems usan onclick="location.href=..." en el <li> en lugar de href en el <a>
    $('.nav-sidebar .nav-item').on('click', function (e) {
      const $li = $(this);
      const $a = $li.find('a').first();
      const href = $a.attr('href');
      const onclick = $li.attr('onclick');

      // Caso A: Navegación por atributo onclick en el LI (Común en este proyecto)
      if (onclick && onclick.includes('location.href')) {
        $('#global-loader').css('display', 'flex');
        return;
      }

      // Caso B: Navegación estándar por href en el A
      if (href && href !== '#' && href !== 'javascript:void(0);') {
        // Verificar que no sea un treeview que solo abre menú
        if (!$li.hasClass('menu-open') && !$li.has('ul').length) {
          $('#global-loader').css('display', 'flex');
        } else if (href !== '#') {
          // Es un link normal sin submenú
          $('#global-loader').css('display', 'flex');
        }
      }
    });

    // 2. Interceptar envíos de formularios comunes
    // Excluimos formularios que sean targets de búsquedas o filtros que no recargan pagina si se manejan via ajax
    // Pero en general, si el form hace submit tradicional, queremos el spinner.
    $('form').on('submit', function () {
      // Si el formulario valida correctamente (HTML5 validation), mostramos spinner
      if (this.checkValidity()) {
        $('#global-loader').css('display', 'flex');
      }
    });

  });
</script>
</body>

</html>