<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Gestión de Gimnnasio</title>

  <link rel="icon" type="image/x-icon" href="<?php echo $URL; ?>/public/images/icon.ico">

  <!-- Google Font: Poppins (Modern & Clean) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/css/styles.css?v=<?php echo time(); ?>">

  <!-- Libreria Sweetalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- DataTables -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- jQuery -->
  <script src="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>


  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/select2/css/select2.min.css">
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">





  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/jqvmap/jqvmap.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet"
    href="<?php echo $URL; ?>/public/templates/AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.css">









  <!-- ============================================= -->
  <!-- INICIO: CÓDIGO AGREGADO PARA PWA (BBVA STYLE) -->
  <!-- ============================================= -->
  <!-- 1. Meta tag para el color de la barra de título en el móvil (BBVA Blue: #0050A1) -->
  <meta name="theme-color" content="#3755eb">

  <!-- 2. Enlace al manifiesto de la aplicación -->
  <link rel="manifest" href="<?php echo $URL; ?>/manifest.json">

  <!-- 3. Script para registrar el Service Worker -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        // Asegúrate de que la ruta sea correcta (service-worker.js debe estar en la raíz)
        navigator.serviceWorker.register('<?php echo $URL; ?>/service-worker.js')
          .then(registration => {
            console.log('Service Worker registrado con éxito:', registration);
          })
          .catch(error => {
            console.error('Fallo en el registro del Service Worker:', error);
          });
      });
    }
  </script>
  <!-- ============================================= -->
  <!-- FIN: CÓDIGO AGREGADO PARA PWA -->
  <!-- ============================================= -->







  <style>
    /* Estilo para los ítems activos del sidebar (Blanco con texto Azul) */
    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
      background-color: #fff !important;
      color: #3755eb !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">

  <!-- GLOBAL SPINNER -->
  <div id="global-loader">
    <div class="spinner"></div>
    <div class="loader-text">Procesando...</div>
  </div>

  <?php
  if ($_SESSION['nro_refresh'] == 0) {
    $_SESSION['nro_refresh'] += 1;
    ?>

    <script>
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Bienvenido al sistema <?php echo $_SESSION['sesion_nombres']; ?>",
        showConfirmButton: false,
        timer: 2000
      });
    </script>
    <?php
  }
  ?>

  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav" style="flex: 1;">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>



        <!-- GLOBAL SEARCH BAR -->
        <li class="nav-item ml-2 global-search-container" style="flex-grow: 1; max-width: 800px; min-width: 500px;">
          <div class="input-group input-group-sm" style="margin-top: 6px; width: 100%;">
            <input class="form-control form-control-navbar" type="search" id="global_search_client_input"
              placeholder="Buscar Cliente (Nombre, DNI o Còdigo)" aria-label="Search"
              style="background-color: #f2f4f6; border: none; color: #333; height: 38px; font-size: 1rem;"
              autocomplete="off">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="button"
                style="background-color: #f2f4f6; border: none; border-left: 1px solid #e1e1e1;">
                <i class="fas fa-search text-muted"></i>
              </button>
            </div>
            <!-- Results Container -->
            <div id="global_search_results" class="list-group"
              style="position: absolute; top: 100%; left: 0; width: 100%; z-index: 9999; max-height: 400px; overflow-y: auto; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            </div>
          </div>
          <input type="hidden" id="global_search_client_select">
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Navbar Search -->




        <li class="nav-item dropdown user-menu d-none d-md-inline-block">

          <a href="#" class="nav-link d-flex align-items-center p-1 dropdown-toggle" data-toggle="dropdown"
            aria-expanded="false" style="line-height: 1;">

            <div class="text-right mr-3 d-flex flex-column align-items-end justify-content-center">
              <span class="d-block text-dark"
                style="font-size: 0.9em; font-weight: 600; white-space: nowrap; line-height: 1.2;"><?php echo $nombres_sesion; ?></span>
              <span class="badge mt-1"
                style="background-color: var(--primary-color); color: #fff; padding: 3px 8px; border-radius: 10px; font-weight: 500; font-size: 0.65em; letter-spacing: 0.5px;">
                <?php echo $_SESSION['rol_base'] ?>
              </span>
            </div>

            <img src="<?php echo $URL; ?>/public/images/usuarios/<?php echo $_SESSION['foto_usuario_global']; ?>"
              class="user-image img-circle elevation-1 align-self-center" alt="User Image"
              style="width: 42px; height: 42px; object-fit: cover; margin-top: 0 !important; margin-bottom: 0 !important;">

          </a>

          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="
              border-radius: 12px;
              border: none;
              box-shadow: 0 10px 25px rgba(0,0,0,0.15);
              padding: 10px;
              min-width: 200px;
          ">
            <span class="dropdown-item dropdown-header text-center" style="font-weight: 600; color: #666;">Mi
              Cuenta</span>
            <div class="dropdown-divider"></div>

            <a href="<?php echo $URL; ?>/usuarios/show.php?id=<?php echo $_SESSION['id_usuario_global']; ?>"
              class="dropdown-item" style="border-radius: 8px; transition: all 0.2s;">
              <i class="fas fa-user-circle mr-2 text-primary"></i> Mi Perfil
            </a>

            <div class="dropdown-divider" style="margin: 8px 0;"></div>

            <a href="<?php echo $URL; ?>/app/controllers/login/cerrar_sesion.php" class="dropdown-item text-danger"
              style="border-radius: 8px; transition: all 0.2s;">
              <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
            </a>
          </div>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Mobile User Info (Only visible on mobile) -->
    <div class="d-block d-md-none bg-white border-bottom d-flex justify-content-between align-items-center px-3 py-2"
      style="background-color: #f4f6f9;">
      <div>
        <span class="font-weight-500 text-dark mr-1" style="font-size: 0.9rem;">
          Hola, <?php echo $nombres_sesion; ?>
        </span>
        <span class="badge badge-info"><?php echo $_SESSION['rol_base'] ?></span>
      </div>

      <div class="dropdown">
        <a href="#" class="text-dark pl-2" role="button" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">
          <i class="fas fa-ellipsis-v text-muted" style="font-size: 1.1rem;"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" style="
                border-radius: 12px;
                border: none;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                padding: 10px;
                min-width: 200px;
            ">
          <a href="<?php echo $URL; ?>/usuarios/show.php?id=<?php echo $_SESSION['id_usuario_global']; ?>"
            class="dropdown-item" style="border-radius: 8px;">
            <i class="fas fa-user-circle mr-2 text-primary"></i> Mi Perfil
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?php echo $URL; ?>/app/controllers/login/cerrar_sesion.php" class="dropdown-item text-danger"
            style="border-radius: 8px;">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
          </a>
        </div>
      </div>
    </div>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?php $URL; ?>" class="brand-link">
        <img src="<?php echo $URL; ?>/public/images/iconjpg.jpg" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $_SESSION['nombre_gimnasio_sesion']; ?></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">



        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <?php
            $current_uri = $_SERVER['REQUEST_URI'];

            // Función simple para verificar si la URL contiene el módulo
            // Ajustamos para que coincida con la estructura de carpetas (/usuarios, /roles, etc.)
            function isActiveModule($module, $uri)
            {
              // Agregar las barras para asegurar que coincide con el directorio del módulo
              return strpos($uri, "/$module/") !== false ? 'active' : '';
            }

            // Lógica especial para Dashboard: Si es index.php en la raíz o la raíz misma
            // y NO estamos en ningún subdirectorio de módulo conocido.
            $is_dashboard = false;
            $modules = ['usuarios', 'roles', 'clientes', 'planes', 'matriculas', 'ventas', 'productos', 'asesorias', 'entrenadores', 'pagos', 'asistencias_clientes', 'caja', 'configuracion'];

            $is_in_module = false;
            foreach ($modules as $mod) {
              if (isActiveModule($mod, $current_uri)) {
                $is_in_module = true;
                break;
              }
            }

            if (!$is_in_module) {
              $is_dashboard = true;
            }
            ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(1, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item">
                <a href="<?php echo $URL; ?>" class="nav-link <?php echo $is_dashboard ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>
                    Dashboard
                    <i class="right fas fa-angle"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(2, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/usuarios'">
                <a href="#" class="nav-link <?php echo isActiveModule('usuarios', $current_uri); ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Usuarios
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(3, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/roles'">
                <a href="#" class="nav-link <?php echo isActiveModule('roles', $current_uri); ?>">
                  <i class="nav-icon fas fa-address-card"></i>
                  <p>
                    Roles
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(4, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/clientes'">
                <a href="#" class="nav-link <?php echo isActiveModule('clientes', $current_uri); ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Clientes
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(5, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/planes'">
                <a href="#" class="nav-link <?php echo isActiveModule('planes', $current_uri); ?>">
                  <i class="nav-icon fas fa-tags"></i>
                  <p>
                    Planes
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(6, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/matriculas'">
                <a href="#" class="nav-link <?php echo isActiveModule('matriculas', $current_uri); ?>">
                  <i class="nav-icon fas fa-pen"></i>
                  <p>
                    Matriculas
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(7, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/ventas'">
                <a href="#" class="nav-link <?php echo isActiveModule('ventas', $current_uri); ?>">
                  <i class="nav-icon fas fa-shopping-cart"></i>
                  <p>
                    Ventas
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(8, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/productos'">
                <a href="#" class="nav-link <?php echo isActiveModule('productos', $current_uri); ?>">
                  <i class="nav-icon fas fa-cubes"></i>
                  <p>
                    Productos
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(9, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/asesorias'">
                <a href="#" class="nav-link <?php echo isActiveModule('asesorias', $current_uri); ?>">
                  <i class="nav-icon fas fa-dumbbell"></i>
                  <p>
                    Asesorias
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(10, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/entrenadores'">
                <a href="#" class="nav-link <?php echo isActiveModule('entrenadores', $current_uri); ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Entrenadores
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(11, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/pagos'">
                <a href="#" class="nav-link <?php echo isActiveModule('pagos', $current_uri); ?>">
                  <i class="nav-icon fas fa-credit-card"></i>
                  <p>
                    Pagos
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(12, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/asistencias_clientes'">
                <a href="#" class="nav-link <?php echo isActiveModule('asistencias_clientes', $current_uri); ?>">
                  <i class="nav-icon fas fa-check"></i>
                  <p>
                    Asistencias
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <!-- MODULO CAJA -->
            <?php
            if (isset($_SESSION['permisos_sesion']) && in_array(13, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/caja'">
                <a href="#"
                  class="nav-link <?php echo isActiveModule('caja', $current_uri) && !strpos($current_uri, 'reporte.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-cash-register"></i>
                  <p>
                    Caja / Turno
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            // Reporte de Cajas (Permiso 14)
            if (isset($_SESSION['permisos_sesion']) && in_array(14, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/caja/reporte.php'">
                <a href="#"
                  class="nav-link <?php echo strpos($current_uri, '/caja/reporte.php') !== false ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-file-invoice-dollar"></i>
                  <p>
                    Reporte Cajas
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>

            <?php
            // CONFIGURACIÓN (Permiso 15)
            if (isset($_SESSION['permisos_sesion']) && in_array(15, $_SESSION['permisos_sesion'])) {
              ?>
              <li class="nav-item" onclick="location.href='<?php echo $URL; ?>/configuracion'">
                <a href="#" class="nav-link <?php echo isActiveModule('configuracion', $current_uri); ?>">
                  <i class="nav-icon fas fa-cogs"></i>
                  <p>
                    Configuración
                    <i class="right fas fa-angle-right"></i>
                  </p>
                </a>
              </li>
            <?php } ?>






          </ul>

        </nav>

      </div>

    </aside>