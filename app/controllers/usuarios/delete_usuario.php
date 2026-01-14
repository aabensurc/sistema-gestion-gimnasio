<?php
session_start();
include('../../config.php');

$id_usuario = $_GET['id_usuario'];



    $sentencia = $pdo->prepare("DELETE FROM tb_usuarios WHERE id_usuario = :id_usuario AND id_gimnasio = :id_gimnasio");

    $sentencia->bindParam('id_usuario', $id_usuario);
    $sentencia->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);


    if($sentencia->execute()){

        $sentencia->execute();

        $_SESSION['mensaje'] = "Se eliminó el usuario de la manera correcta";
        $_SESSION['icono'] = "success";
       // header('Location: '.$URL.'/bases/');

       ?>
       <script>
           location.href = "<?php echo $URL;?>/usuarios";
       </script>
   <?php

     } else{


        $_SESSION['mensaje'] = "Error no se pudo guardar en la base de datos";
        $_SESSION['icono'] = "error";
        //header('Location: '.$URL.'/bases/update.php?id='.$id_rol);
        ?>
            <script>
                location.href = "<?php echo $URL;?>/usuarios";
            </script>
        <?php

     }


    