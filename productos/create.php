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
              <h3 class="card-title">Llene los datos del producto</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/productos/create_producto_process.php" method="post"
                    enctype="multipart/form-data">

                    <div class="form-group">
                      <center><output id="list"><img class="thumb thumbnail"
                            src="<?php echo $URL; ?>/public/images/productos/default_product.png" width="200px"></output>
                      </center>
                      <label for="">Foto (Opcional)</label>
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
                      <label for="">Nombre</label>
                      <input type="text" name="nombre" class="form-control"
                        placeholder="Escriba aquí el nombre del producto..." required>
                    </div>
                    <div class="form-group">
                      <label for="">Descripción</label>
                      <textarea name="descripcion" class="form-control" rows="3"
                        placeholder="Escriba aquí la descripción del producto..."></textarea>
                    </div>
                    <div class="form-group">
                      <label for="">Precio Venta (S/.)</label>
                      <input type="number" name="precio_venta" class="form-control"
                        placeholder="Ingrese el precio de venta..." step="0.01" required>
                    </div>
                    <div class="form-group">
                      <label for="">Stock</label>
                      <input type="number" name="stock" class="form-control"
                        placeholder="Ingrese la cantidad en stock..." required>
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