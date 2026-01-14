<?php
 session_start();
include('../../config.php');

$id_rol = $_POST['id_rol'];
$rol = $_POST['rol'];

// 1. OBTENER EL ARRAY DE PERMISOS
// Si no se seleccionó ningún checkbox, $_POST['permisos'] no existirá, se inicializa a un array vacío.
$permisos_array = $_POST['permisos'] ?? []; 

// 2. CODIFICAR EL ARRAY A FORMATO JSON
// Esto convierte el array de PHP (ej: [1, 5, 8]) en la cadena de texto JSON (ej: "[1,5,8]")
$permisos_json = json_encode($permisos_array);

  
try {
    
    $sentencia = $pdo->prepare("UPDATE tb_roles 
    SET rol = :rol,
        permisos = :permisos,            -- Nuevo campo de permisos
        fyh_actualizacion = :fyh_actualizacion
    WHERE id_rol = :id_rol
    AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]'");

    $sentencia->bindParam('rol', $rol);
    $sentencia->bindParam('permisos', $permisos_json); // Se inserta la cadena JSON
    $sentencia->bindParam('fyh_actualizacion', $fechaHora);
    $sentencia->bindParam('id_rol', $id_rol);


    if($sentencia->execute()){
    
        $_SESSION['mensaje'] = "Se actualizó al rol y sus permisos de la manera correcta";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/roles/');

    }else{
       
        $_SESSION['mensaje'] = "Error, no se pudo actualizar el rol y sus permisos en la base de datos";
        $_SESSION['icono'] = "error";
        header('Location: '.$URL.'/roles/update.php?id='.$id_rol);

    }

} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    $_SESSION['mensaje'] = "Error de Base de Datos al actualizar: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    error_log("DB Update Error: " . $e->getMessage()); // Registrar error
    header('Location: '.$URL.'/roles/update.php?id='.$id_rol);
}