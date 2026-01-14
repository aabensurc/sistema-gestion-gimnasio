<?php

include('../app/config.php');
include('../layout/sesion.php');

include('../layout/parte1.php');

// Incluye el controlador que CARGA los datos (incluidos los permisos decodificados)
include('../app/controllers/roles/edit_rol_data.php');

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
          <h1>Editar Rol: <?php echo $rol; ?></h1>
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
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Llene los datos con cuidado para actualizar</h3>

            </div>

            <div class="card-body" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <form action="../app/controllers/roles/update_rol_process.php" method="post">
                    <input type="text" name="id_rol" value="<?php echo $id_rol_get; ?>" hidden>

                    <!-- Campo Rol -->
                    <div class="form-group">
                      <label for="rol">Nombre del rol</label>
                      <input type="text" name="rol" class="form-control" placeholder="Escriba aquí el rol..."
                        value="<?php echo $rol; ?>" required>
                    </div>

                    <!-- Sección de Permisos (Checkboxes) -->
                    <div class="form-group border rounded p-3 bg-light">
                      <label class="d-block mb-3">Permisos del Rol (Acceso a Sidebar)</label>
                      <div class="row">
                        <?php
                        $count = 0;
                        $half = ceil(count($permisos_list) / 2);
                        foreach ($permisos_list as $id => $description):
                          // Comprueba si el ID del permiso está en el array de permisos actuales del rol
                          $checked = in_array($id, $permisos_actuales) ? 'checked' : '';

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
                            <!-- Si el ID está en $permisos_actuales, se añade el atributo 'checked' -->
                            <input class="form-check-input" type="checkbox" name="permisos[]" value="<?php echo $id; ?>"
                              id="permiso-<?php echo $id; ?>" <?php echo $checked; ?>>
                            <label class="form-check-label" for="permiso-<?php echo $id; ?>">
                              <?php echo $id . ' - ' . $description; ?>
                            </label>
                          </div>
                          <?php
                          $count++;
                        endforeach;
                        if ($count > 0) {
                          echo '</div>';
                        }
                        ?>
                      </div>
                    </div>

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