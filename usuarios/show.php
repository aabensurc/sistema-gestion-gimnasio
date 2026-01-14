<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

include("../app/controllers/usuarios/show_usuario_data.php");

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
        <div class="col-md-5">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Datos del usuario</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Foto</label>
                    <center><img src="../public/images/usuarios/<?php echo $foto; ?>" name="foto"
                        style="max-height: 200px;"></center>
                  </div>
                  <div class="form-group">
                    <label for="">Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="<?php echo $nombres ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Rol del usuario</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $rol ?>" disabled>
                  </div>
                  <hr>
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