<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

// Verificar permiso (15) antes de mostrar contenido
if (!isset($_SESSION['permisos_sesion']) || !in_array(15, $_SESSION['permisos_sesion'])) {
    header('Location: ' . $URL . '/no_autorizado.php');
    exit;
}

// Obtener datos del gimnasio actual
$id_gimnasio = $_SESSION['id_gimnasio_sesion'];
$sql_gimnasio = "SELECT * FROM tb_gimnasios WHERE id_gimnasio = '$id_gimnasio'";
$query_gimnasio = $pdo->prepare($sql_gimnasio);
$query_gimnasio->execute();
$gimnasio_data = $query_gimnasio->fetch(PDO::FETCH_ASSOC);

$nombre_gimnasio = $gimnasio_data['nombre'];
$imagen_gimnasio = $gimnasio_data['imagen'];
$clave_descuento = $gimnasio_data['clave_descuento']; // El campo ya existe según lo confirmado
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Configuración de la Empresa</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Datos de la Empresa</h3>
                        </div>

                        <div class="card-body">
                            <form action="../app/controllers/configuracion/update_configuracion.php" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id_gimnasio" value="<?php echo $id_gimnasio; ?>">

                                <div class="form-group">
                                    <label for="nombre">Nombre de la Empresa</label>
                                    <input type="text" name="nombre" class="form-control"
                                        value="<?php echo $nombre_gimnasio; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="clave_descuento">Contraseña para Descuentos</label>
                                    <input type="text" name="clave_descuento" class="form-control"
                                        value="<?php echo $clave_descuento; ?>" required>
                                    <small class="form-text text-muted">Esta contraseña se solicita al autorizar
                                        descuentos en ventas/matrículas.</small>
                                </div>

                                <div class="form-group">
                                    <label for="imagen">Logo de la Empresa</label>
                                    <div class="row align-items-center">
                                        <div class="col-md-4 text-center">
                                            <img src="<?php echo $URL; ?>/public/images/gimnasios/<?php echo $imagen_gimnasio; ?>"
                                                alt="Logo Actual" class="img-thumbnail" style="max-height: 150px;">
                                            <p class="mt-2 text-muted">Logo Actual</p>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="imagen" name="imagen"
                                                    accept="image/*">
                                                <label class="custom-file-label" for="imagen">Elegir archivo...</label>
                                            </div>
                                            <small class="form-text text-muted">Subir una nueva imagen para reemplazar
                                                la actual (Opcional).</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group text-right">
                                    <a href="<?php echo $URL; ?>" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Datos</button>
                                </div>
                            </form>
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

<script>
    // Script para que el input file muestre el nombre del archivo seleccionado (BS4)
    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>