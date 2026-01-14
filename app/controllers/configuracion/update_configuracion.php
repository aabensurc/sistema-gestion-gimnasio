<?php
include('../../config.php');

session_start();

// Verificar permiso (15) de nuevo en el backend
if (!isset($_SESSION['permisos_sesion']) || !in_array(15, $_SESSION['permisos_sesion'])) {
    header('Location: ' . $URL . '/no_autorizado.php');
    exit;
}

$id_gimnasio = $_POST['id_gimnasio'];
$nombre = $_POST['nombre'];
$clave_descuento = $_POST['clave_descuento'];
$image_text = ""; // Para mantener el nombre de la imagen si no se cambia

// 1. Manejo de la imagen
if ($_FILES['imagen']['name'] != null) {
    // Si subieron una nueva imagen
    $nombre_del_archivo = date("Y-m-d-h-i-s");
    $image_text = $nombre_del_archivo . "__" . $_FILES['imagen']['name'];
    $location = "../../../public/images/gimnasios/" . $image_text;

    move_uploaded_file($_FILES['imagen']['tmp_name'], $location);
} else {
    // Si no subieron imagen, recuperar la actual de la BD
    $sql_consulta = "SELECT imagen FROM tb_gimnasios WHERE id_gimnasio = '$id_gimnasio'";
    $query_consulta = $pdo->prepare($sql_consulta);
    $query_consulta->execute();
    $dato_gimnasio = $query_consulta->fetch(PDO::FETCH_ASSOC);
    $image_text = $dato_gimnasio['imagen'];
}

// 2. Actualizar datos en la BD
$sentencia = $pdo->prepare("UPDATE tb_gimnasios SET 
    nombre = :nombre,
    imagen = :imagen,
    clave_descuento = :clave_descuento
    WHERE id_gimnasio = :id_gimnasio");

$sentencia->bindParam(':nombre', $nombre);
$sentencia->bindParam(':imagen', $image_text);
$sentencia->bindParam(':clave_descuento', $clave_descuento);
$sentencia->bindParam(':id_gimnasio', $id_gimnasio);

if ($sentencia->execute()) {
    // 3. Actualizar variables de sesión relacionadas si es necesario
    // Si el usuario actual está en este gimnasio, actualizamos el nombre en su sesión para que se refleje inmediatamente
    if ($_SESSION['id_gimnasio_sesion'] == $id_gimnasio) {
        $_SESSION['nombre_gimnasio_sesion'] = $nombre;
    }

    session_start();
    $_SESSION['mensaje'] = "Se actualizaron los datos de la empresa correctamente.";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/configuracion/');
} else {
    session_start();
    $_SESSION['mensaje'] = "Error al actualizar los datos.";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/configuracion/');
}
