<?php 

$id_rol_get = $_GET['id'];

// Se agrega el campo 'permisos' a la consulta SQL
$sql_roles = "SELECT rol, permisos FROM tb_roles 
              WHERE id_rol = '$id_rol_get' 
              AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]' 
              ORDER BY rol ASC";

  $query_roles = $pdo->prepare($sql_roles);
  $query_roles-> execute();
  $roles_datos = $query_roles->fetchAll(PDO::FETCH_ASSOC);

  // Inicializar variables
  $rol = '';
  $permisos_actuales = [];

  foreach($roles_datos as $roles_dato) {
    $rol = $roles_dato['rol'];
    
    // **PASO CLAVE: Decodificar el JSON de permisos a un array de PHP**
    // El 'true' asegura que se decodifique como un array asociativo/numérico.
    // Si el campo está vacío (null o cadena vacía), json_decode podría retornar null, por eso usamos el operador ??
    $permisos_json = $roles_dato['permisos'];
    $permisos_actuales = json_decode($permisos_json, true) ?? []; 
  }

// Nota: $permisos_actuales es ahora un array (ej: [1, 3, 7]) y se usará en la pantalla update.php