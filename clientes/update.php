<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

include("../app/controllers/clientes/edit_cliente_data.php");

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
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Llene los datos con cuidado</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">

                  <form action="../app/controllers/clientes/update_cliente_process.php" method="post"
                    enctype="multipart/form-data">


                    <div class="form-group">
                      <center><output id="list"><img class="thumb thumbnail"
                            src="../public/images/clientes/<?php echo $foto ?>" width="200px"></output></center>

                      <label for="">Foto</label>
                      <input type="file" name="image" class="form-control" id="file">

                      <script>
                        function archivo(evt) {
                          var files = evt.target.files; // FileList object
                          // Obtenemos la imagen del campo "file".
                          for (var i = 0, f; f = files[i]; i++) {
                            //Solo admitimos imágenes.
                            if (!f.type.match('image.*')) {
                              continue;
                            }
                            var reader = new FileReader();
                            reader.onload = (function (theFile) {
                              return function (e) {
                                // Insertamos la imagen
                                document.getElementById("list").innerHTML = ['<img class="thumb thumbnail" src="', e.target.result, '" style= "max-height: 200px;" title="', escape(theFile.name), '"/>'].join('');
                              };
                            })(f);
                            reader.readAsDataURL(f);
                          }
                        }
                        document.getElementById('file').addEventListener('change', archivo, false);
                      </script>
                    </div>

                    <input type="text" name="id_cliente" value="<?php echo $id_cliente_get ?>" hidden>
                    <div class="form-group">
                      <label for="">Código (No Modificable)</label>
                      <input type="text" name="codigo" class="form-control" value="<?php echo $codigo ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">DNI</label>
                      <input type="text" name="dni" class="form-control" value="<?php echo $dni ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Nombres</label>
                      <input type="text" name="nombres" class="form-control" value="<?php echo $nombres ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Apellido Paterno</label>
                      <input type="text" name="ape_pat" class="form-control" value="<?php echo $ape_pat ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Apellido Materno</label>
                      <input type="text" name="ape_mat" class="form-control" value="<?php echo $ape_mat ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Telefono</label>
                      <input type="text" name="telefono" class="form-control" value="<?php echo $telefono ?>">
                    </div>
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="text" name="email" class="form-control" value="<?php echo $email ?>">
                    </div>


                    <hr>
                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>

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