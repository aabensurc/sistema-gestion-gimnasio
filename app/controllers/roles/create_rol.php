<?php
session_start();
include('../../config.php');

$rol = $_POST['rol'];

// 1. OBTENER EL ARRAY DE PERMISOS
// Si no se seleccionó ningún checkbox, $_POST['permisos'] no existirá, 
// por eso usamos el operador de fusión de nulos (??) para asegurar que siempre sea un array.
// Si el array existe, contiene los IDs de enteros seleccionados.
$permisos_array = $_POST['permisos'] ?? []; 

// 2. CODIFICAR EL ARRAY A FORMATO JSON (LA MEJOR PRÁCTICA)
// Esto convierte el array de PHP (ej: [1, 5, 8]) en la cadena de texto JSON (ej: "[1,5,8]")
$permisos_json = json_encode($permisos_array);

// 3. INSERCIÓN EN LA BASE DE DATOS
// Se agrega 'permisos' a la lista de columnas y al placeholder de valores.
try {
    $sentencia = $pdo->prepare("INSERT INTO tb_roles 
           ( rol, permisos, fyh_creacion, id_gimnasio ) 
    VALUES (:rol, :permisos, :fyh_creacion, :id_gimnasio )");

    $sentencia->bindParam('rol', $rol);
    $sentencia->bindParam('permisos', $permisos_json); // Se inserta la cadena JSON
    $sentencia->bindParam('fyh_creacion', $fechaHora);
    $sentencia->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);
    
    if($sentencia->execute()){
        $_SESSION['mensaje'] = "Se registró el rol y sus permisos correctamente";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/roles/');
    } else {
        $_SESSION['mensaje'] = "Error, no se pudo registrar el rol en la base de datos";
        $_SESSION['icono'] = "error";
        header('Location: '.$URL.'/roles/create.php');
    }
} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    $_SESSION['mensaje'] = "Error de Base de Datos: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    error_log("DB Error: " . $e->getMessage()); // Registrar error
    header('Location: '.$URL.'/roles/create.php');
}

// Nota sobre la recuperación:
// Cuando recuperes el campo 'permisos' de la BD, deberás usar json_decode() en PHP:
// $permisos_recuperados = json_decode($fila['permisos'], true); // Devuelve el array original de enteros