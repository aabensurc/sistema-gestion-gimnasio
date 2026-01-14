<?php

include('../app/config.php');
include('../layout/sesion.php');


include('../app/controllers/verificar_permisos.php');
requirePermiso(8);



include('../layout/parte1.php');

// Incluir el controlador para listar los productos
include('../app/controllers/productos/listado_de_productos.php');

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
        <div class="col-md-12">

          <div class="card card-outline card-primary">
            <div class="card-header">
              <h1 class="m-0">Productos registrados
                <button type="button" class="btn btn-primary"
                  onclick="location.href='<?php echo $URL; ?>/productos/create.php'">
                  <i class="fa fa-plus"></i> Nuevo Producto
                </button>
              </h1>


            </div>

            <div class="card-body" style="display: block;">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <!-- <th><center>Foto</center></th> --><!-- Columna de foto eliminada -->
                    <th>
                      <center>Nombre</center>
                    </th>
                    <th>
                      <center>Descripción</center>
                    </th>
                    <th>
                      <center>Precio Venta (S/.)</center>
                    </th>
                    <th>
                      <center>Stock</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $contador = 0;
                  if (isset($productos_datos) && is_array($productos_datos)) {
                    foreach ($productos_datos as $producto_dato) {
                      $contador++;
                      $id_producto = $producto_dato['id_producto'];
                      // $foto_producto = $producto_dato['foto'] ?? 'default_product.png'; // No se usa aquí
                      ?>
                      <tr>
                        <td>
                          <center><?php echo $contador; ?></center>
                        </td>
                        <!-- <td><center>
                            <img src="<?php echo $URL . '/public/images/productos/' . $foto_producto; ?>" 
                                 alt="Foto Producto" width="50px" height="50px">
                        </center></td> -->
                        <td>
                          <center><?php echo $producto_dato['nombre']; ?></center>
                        </td>
                        <td>
                          <center>
                            <?php echo isset($producto_dato['descripcion']) ? $producto_dato['descripcion'] : 'N/A'; ?>
                          </center>
                        </td>
                        <td>
                          <center><?php echo number_format($producto_dato['precio_venta'], 2); ?></center>
                        </td>
                        <td>
                          <center><?php echo $producto_dato['stock']; ?></center>
                        </td>
                        <td>
                          <center>
                            <div class="btn-group">
                              <a href="show.php?id=<?php echo $id_producto; ?>" type="button" class="btn btn-info btn-sm"><i
                                  class="fa fa-eye"></i> Ver</a>
                              <a href="edit.php?id=<?php echo $id_producto; ?>" type="button"
                                class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>
                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#modal-delete-producto<?php echo $id_producto; ?>">
                                <i class="fa fa-trash-alt"></i> Eliminar
                              </button>
                            </div>
                          </center>
                        </td>
                      </tr>
                      <!-- Modal para Eliminar Producto -->
                      <div class="modal fade" id="modal-delete-producto<?php echo $id_producto; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color: #CC3E3E; color: white">
                              <h4 class="modal-title">Eliminación de Producto</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="../app/controllers/productos/delete_producto.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
                                <p>¿Está seguro de que desea eliminar el producto
                                  **<?php echo $producto_dato['nombre']; ?>**?</p>
                                <small class="text-danger">Advertencia: Esto podría afectar ventas vinculadas.</small>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <?php
                    }
                  }
                  ?>
                </tbody>

                <tfoot>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <!-- <th><center>Foto</center></th> --><!-- Columna de foto eliminada -->
                    <th>
                      <center>Nombre</center>
                    </th>
                    <th>
                      <center>Descripción</center>
                    </th>
                    <th>
                      <center>Precio Venta (S/.)</center>
                    </th>
                    <th>
                      <center>Stock</center>
                    </th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>

          </div>

        </div>
      </div>

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include('../layout/parte2.php'); ?>
<?php include('../layout/mensajes.php'); ?>

<script>
  $(function () {
    $("#example1").DataTable({
      "pageLength": 10,
      language: {
        "emptyTable": "No hay información",
        "decimal": "",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Productos",
        "infoEmpty": "Mostrando 0 to 0 of 0 Productos",
        "infoFiltered": "(Filtrado de _MAX_ total Productos)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Productos",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscador:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      },
      "responsive": true, "lengthChange": true, "autoWidth": false,
      buttons: [{
        extend: 'collection',
        text: 'Reportes',
        orientation: 'landscape',
        buttons: [{
          text: 'Copiar',
          extend: 'copy'
        }, {
          extend: 'pdf',
        }, {
          extend: 'csv',
        }, {
          extend: 'excel',
        }, {
          text: 'Imprimir',
          extend: 'print'
        }
        ]
      },
      {
        extend: 'colvis',
        text: 'Visor de columnas'
      }
      ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });
</script>