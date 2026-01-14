<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Incluir el controlador para cargar los datos del producto específico
include("../app/controllers/productos/show_producto_data.php");

// Redirigir si no se encontraron datos del producto
if (!isset($producto_data)) {
  header('Location: ' . $URL . '/productos/');
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
        <div class="col-md-5">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Información del Producto</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Foto</label>
                    <center><img src="<?php echo $URL . '/public/images/productos/' . $producto_data['foto']; ?>"
                        name="foto" style="max-height: 200px;"></center>
                  </div>
                  <div class="form-group">
                    <label for="">ID Producto</label>
                    <input type="text" name="id_producto" class="form-control"
                      value="<?php echo $producto_data['id_producto']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                      value="<?php echo $producto_data['nombre']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"
                      disabled><?php echo $producto_data['descripcion']; ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Precio Venta (S/.)</label>
                    <input type="text" name="precio_venta" class="form-control"
                      value="<?php echo number_format($producto_data['precio_venta'], 2); ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="">Stock</label>
                    <input type="text" name="stock" class="form-control" value="<?php echo $producto_data['stock']; ?>"
                      disabled>
                  </div>
                  <!-- Fechas de creación y actualización eliminadas de la interfaz -->

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