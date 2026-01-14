<?php 
// Incluir configuración global para tener acceso a $URL, $pdo, etc.
include('../app/config.php'); 

// 1. INCLUIR LA SESIÓN: Esto cargará TODAS las variables de sesión 
//    ($nombres_sesion, $foto_usuario_global, etc.) que el archivo parte1.php
//    necesita para mostrar el header completo.
include('sesion.php'); 
// NOTA: sesion.php internamente llama a session_start()

// El requirePermiso no es necesario aquí, ya que a esta página se llega por
// una REDIRECCIÓN cuando el permiso falla.

include('parte1.php'); 
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
        
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card card-outline card-danger">
              <div class="card-header">
                <h3 class="card-title">Acceso Restringido</h3>
              </div>
              <div class="card-body">
                <div class="alert alert-danger text-center" role="alert">
                  <h4 class="alert-heading"><i class="fas fa-lock"></i> Acceso Denegado</h4>
                  <p>Su rol de usuario no tiene los permisos necesarios para acceder a esta página.</p>
                  <hr>
                  <a href="<?php echo $URL;?>/index.php" class="btn btn-dark">
                      <i class="fa fa-arrow-alt-circle-left"></i> Volver a la página principal
                  </a>
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

<?php include('parte2.php'); ?>