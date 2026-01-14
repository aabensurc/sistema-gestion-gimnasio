<?php

session_start();

include('../../config.php');

$nombres = $_POST['nombres'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];




$foto=$_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'],"../../../public/images/usuarios/".$_FILES['image']['name']);

if($foto==null){
  $foto="default_image.jpg";
}


    if($password_user==$password_repeat){
        $password_user = password_hash($password_user,PASSWORD_DEFAULT);
        echo $nombres.'--'.$email.'--'.$rol.'--'.$password_user.'--'.$password_repeat;
        $sentencia = $pdo->prepare("INSERT INTO tb_usuarios 
        ( nombres,  email,  id_rol,  password_user,  fyh_creacion,  foto, id_gimnasio) 
 VALUES (:nombres, :email, :id_rol, :password_user, :fyh_creacion, :foto, :id_gimnasio)");

        $sentencia->bindParam('nombres', $nombres);
        $sentencia->bindParam('email', $email);
        $sentencia->bindParam('id_rol', $rol);
        $sentencia->bindParam('password_user', $password_user);
        $sentencia->bindParam('fyh_creacion', $fechaHora);
        $sentencia->bindParam('foto', $foto);
        $sentencia->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion'] );
        echo $_SESSION['id_gimnasio_sesion'];
        $sentencia->execute();
       
        $_SESSION['mensaje'] = "Se registró al usuario de la manera correcta";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/usuarios/');

    }else{
      // echo "Error las contraseñas no son iguales"; 
      
      $_SESSION['mensaje'] = "Error las contraseñas no son iguales";
      $_SESSION['icono'] = "error";
      header('Location: '.$URL.'/usuarios/create.php');
    }







