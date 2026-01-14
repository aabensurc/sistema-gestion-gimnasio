<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Lista de permisos actualizada para coincidir con el SIDEBAR
// NOTA: El ID (clave) es el valor entero que se guardará en la BD.
$permisos_list = [
  1 => 'Dashboard',
  2 => 'Usuarios',
  3 => 'Roles',
  4 => 'Clientes',
  5 => 'Planes',
  6 => 'Matrículas',
  7 => 'Ventas',
  8 => 'Productos',
  9 => 'Asesorías',
  10 => 'Entrenadores',
  11 => 'Pagos',
  12 => 'Asistencias',
  13 => 'Caja / Turno',
  14 => 'Reporte Cajas',
  15 => 'Configuración',
];

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1>Crear Nuevo Rol y Asignar Permisos</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-7">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Llene los datos con cuidado</h3>


            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/roles/create_rol.php" method="post">
                    <!-- Campo Rol -->
                    <div class="form-group">
                      <label for="rol">Nombre del rol</label>
                      <input type="text" name="rol" class="form-control" placeholder="Escriba aquí el rol..." required>
                    </div>

                    <!-- Sección de Permisos (Checkboxes) -->
                    <div class="form-group border rounded p-3 bg-light">
                      <label class="d-block mb-3">Permisos del Rol (Acceso a Sidebar)</label>
                      <div class="row">
                        <?php
                        $count = 0;
                        $half = ceil(count($permisos_list) / 2);
                        foreach ($permisos_list as $id => $description):
                          // Separador para dividir la lista en dos columnas
                          if ($count == 0 || $count == $half) {
                            if ($count == $half) {
                              echo '</div><div class="col-md-6">';
                            } else {
                              echo '<div class="col-md-6">';
                            }
                          }
                          ?>
                          <div class="form-check">
                            <!-- La clave es 'name="permisos[]"' para que PHP cree un array -->
                            <input class="form-check-input" type="checkbox" name="permisos[]" value="<?php echo $id; ?>"
                              id="permiso-<?php echo $id; ?>">
                            <label class="form-check-label" for="permiso-<?php echo $id; ?>">
                              <?php echo $id . ' - ' . $description; ?>
                            </label>
                          </div>
                          <?php
                          $count++;
                        endforeach;
                        // Cierra la última columna y la fila si es necesario
                        if ($count > 0) {
                          echo '</div>';
                        }
                        ?>
                      </div>
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