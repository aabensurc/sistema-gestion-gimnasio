<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Registro de un nuevo Entrenador</h1>
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
              <h3 class="card-title">Llene los datos con cuidado</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/entrenadores/create_entrenador_process.php" method="post"
                    enctype="multipart/form-data">

                    <div class="form-group">
                      <center><output id="list"><img class="thumb thumbnail"
                            src="<?php echo $URL; ?>/public/images/entrenadores/default_image.jpg"
                            width="200px"></output></center>
                      <label for="">Foto</label>
                      <input type="file" name="image" class="form-control" id="file" accept="image/*">

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

                    <div class="form-group">
                      <label for="">Nombres</label>
                      <input type="text" name="nombre" class="form-control"
                        placeholder="Escriba aquí los nombres del entrenador..." required>
                    </div>
                    <div class="form-group">
                      <label for="">Apellido Paterno</label>
                      <input type="text" name="ape_pat" class="form-control"
                        placeholder="Escriba aquí el apellido paterno del entrenador..." required>
                    </div>
                    <div class="form-group">
                      <label for="">Apellido Materno</label>
                      <input type="text" name="ape_mat" class="form-control"
                        placeholder="Escriba aquí el apellido materno del entrenador..." required>
                    </div>
                    <div class="form-group">
                      <label for="">DNI</label>
                      <input type="text" name="dni" class="form-control"
                        placeholder="Escriba aquí el DNI del entrenador..." required>
                    </div>
                    <div class="form-group">
                      <label for="">Teléfono</label>
                      <input type="text" name="telefono" class="form-control"
                        placeholder="Escriba aquí el teléfono del entrenador..." required>
                    </div>
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="text" name="email" class="form-control"
                        placeholder="Escriba aquí el email del entrenador..." required>
                    </div>

                    <hr>
                    <div class="form-group">
                      <a href="index.php" class="btn btn-secondary">Cancelar</a>
                      <button type="submit" class="btn btn-primary">Guardar</button>
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