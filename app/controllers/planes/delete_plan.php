<?php
session_start();
include('../../config.php');

$id_plan = $_GET['id_plan'];



    $sentencia = $pdo->prepare("DELETE FROM tb_planes WHERE id_plan = :id_plan AND id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ");

    $sentencia->bindParam('id_plan', $id_plan);


    if($sentencia->execute()){

        $sentencia->execute();
        
        $_SESSION['mensaje'] = "Se eliminó el plan de manera correcta";
        $_SESSION['icono'] = "success";
       // header('Location: '.$URL.'/bases/');

       ?>
       <script>
           location.href = "<?php echo $URL;?>/planes";
       </script>
   <?php

     } else{

    
        $_SESSION['mensaje'] = "Error no se pudo guardar en la base de datos";
        $_SESSION['icono'] = "error";
        //header('Location: '.$URL.'/bases/update.php?id='.$id_rol);
        ?>
            <script>
                location.href = "<?php echo $URL;?>/planes";
            </script>
        <?php

     }


    