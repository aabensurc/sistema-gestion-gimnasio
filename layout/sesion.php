<?php

session_start();

if (isset($_SESSION['sesion_email'])) {
  //echo "si existe sesion de: ".$_SESSION['sesion_email'];
  $email_sesion = $_SESSION['sesion_email'];

  // MODIFICACIÓN DE LA CONSULTA SQL: 
  // Se incluye el campo 'tg.imagen' de la tabla tb_gimnasios en la selección.
  $sql = "SELECT tu.*, 
                 tg.nombre, 
                 tg.imagen AS imagen_gimnasio,  /* <<-- CAMBIO CLAVE: Alias para la imagen */
                 tg.clave_descuento, /* <<-- NUEVO CAMPO */
                 tr.permisos, 
                 tr.rol 
          FROM tb_usuarios tu 
          INNER JOIN tb_gimnasios tg ON tu.id_gimnasio = tg.id_gimnasio
          INNER JOIN tb_roles tr ON tu.id_rol = tr.id_rol
          WHERE tu.email = :email_sesion";

  $query = $pdo->prepare($sql);
  $query->bindParam(':email_sesion', $email_sesion); // Uso de bindParam para seguridad
  $query->execute();
  $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

  // Inicializamos el array de permisos
  $permisos_usuario = [];

  foreach ($usuarios as $usuario) {
    $nombres_sesion = $usuario['nombres'];
    $id_usuario_global = $usuario['id_usuario'];
    $_SESSION['id_usuario_global'] = $id_usuario_global;
    $foto_usuario_global = $usuario['foto'];
    $_SESSION['foto_usuario_global'] = $foto_usuario_global;

    $id_gimnasio_sesion = $usuario['id_gimnasio'];
    $_SESSION['id_gimnasio_sesion'] = $id_gimnasio_sesion;

    $nombre_gimnasio_sesion = $usuario['nombre'];
    $_SESSION['nombre_gimnasio_sesion'] = $nombre_gimnasio_sesion;

    // <<-- CAMBIO CLAVE: Cargar el nombre del archivo de imagen del gimnasio en la sesión
    $imagen_gimnasio_sesion = $usuario['imagen_gimnasio'];
    $_SESSION['imagen_gimnasio_sesion'] = $imagen_gimnasio_sesion;

    // Guardar clave de descuento en sesión
    $_SESSION['clave_descuento_sesion'] = $usuario['clave_descuento'];


    // Cargar y decodificar los permisos en la sesión
    $permisos_json = $usuario['permisos'];
    // Decodifica el JSON (ej: "[1, 2, 5]") a un array de PHP (ej: [1, 2, 5])
    $permisos_usuario = json_decode($permisos_json, true) ?? [];
    $_SESSION['permisos_sesion'] = $permisos_usuario;

    // Conservamos el rol base que viene de la tabla tb_roles a través del join
    $_SESSION['rol_base'] = $usuario['rol'];
  }

  // Si no se encuentra un usuario, redirigir al login
  if (empty($usuarios)) {
    header('Location: ' . $URL . '/login');
    exit;
  }


  // NOTA: Se inicializa a 0 por si es necesario en otras partes del layout.
  $contador2 = 0;


} else {
  // Si no hay sesión, redirigir al login
  //echo "no existe sesion";
  header('Location: ' . $URL . '/login');
}