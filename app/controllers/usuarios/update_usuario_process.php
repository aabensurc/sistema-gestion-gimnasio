<?php

session_start();

include('../../config.php');

$nombres = $_POST['nombres'];
$email = $_POST['email'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];
$id_usuario = $_POST['id_usuario'];
$rol = $_POST['rol'];


$foto=$_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'],"../../../public/images/usuarios/".$_FILES['image']['name']);


if($password_user == ""){
  if($password_user==$password_repeat){
    $password_user = password_hash($password_user,PASSWORD_DEFAULT);

    $sentencia = $pdo->prepare("UPDATE tb_usuarios 
    SET nombres = :nombres,
        email =:email,
        id_rol =:id_rol,
        fyh_actualizacion = :fyh_actualizacion,
        foto = :foto
        WHERE id_usuario = :id_usuario
        AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ");
    echo $nombres.'--'.$email.'--'.$rol.'--'.$fechaHora.'--'.$id_usuario;
    $sentencia->bindParam('nombres', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('fyh_actualizacion', $fechaHora);
    $sentencia->bindParam('foto', $foto);
    $sentencia->bindParam('id_usuario', $id_usuario);
    $sentencia->execute();

    $_SESSION['mensaje'] = "Se actualizó al usuario de la manera correcta";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/usuarios/');

}else{
  // echo "Error las contraseñas no son iguales"; 

  $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
  $_SESSION['icono'] = "error";
  header('Location: '.$URL.'/usuarios/update.php?id='.$id_usuario);
}

}else{
  if($password_user==$password_repeat){
    $password_user = password_hash($password_user,PASSWORD_DEFAULT);

    $sentencia = $pdo->prepare("UPDATE tb_usuarios 
    SET nombres = :nombres,
        email =:email,
        id_rol =:id_rol,
        password_user = :password_user,
        foto = :foto,
        fyh_actualizacion = :fyh_actualizacion
        WHERE id_usuario = :id_usuario ");

    $sentencia->bindParam('nombres', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('password_user', $password_user);
    $sentencia->bindParam('foto', $foto);
    $sentencia->bindParam('fyh_actualizacion', $fechaHora);
    $sentencia->bindParam('id_usuario', $id_usuario);
    $sentencia->execute();
  
    $_SESSION['mensaje'] = "Se actualizó al usuario de la manera correcta";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/usuarios/');

}else{
  // echo "Error las contraseñas no son iguales"; 
  
  $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
  $_SESSION['icono'] = "error";
  header('Location: '.$URL.'/usuarios/update.php?id='.$id_usuario);
}

}

