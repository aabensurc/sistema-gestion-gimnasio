<?php
session_start();
include('../../config.php');

// ELIMINADA la línea: $codigo = $_POST['codigo']; 
// Ya no se necesita, pues la base de datos genera el ID automáticamente.

$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$ape_pat = $_POST['ape_pat'];
$ape_mat = $_POST['ape_mat'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$foto=$_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'],"../../../public/images/clientes/".$_FILES['image']['name']);

if($foto==null){
  $foto="default_image.jpg";
}

       // La columna 'codigo' se ELIMINA de la lista de inserción.
       // La columna 'id_cliente' se auto-rellenará con el siguiente número (1000, 1001, etc.).
        $sentencia = $pdo->prepare("INSERT INTO tb_clientes 
               ( dni, nombres, ape_pat, ape_mat, telefono, email, foto, fyh_creacion, id_gimnasio)
        VALUES (:dni, :nombres, :ape_pat, :ape_mat, :telefono, :email, :foto, :fyh_creacion, :id_gimnasio)");

        // ELIMINADA la vinculación: $sentencia->bindParam('codigo', $codigo);
        
        $sentencia->bindParam('dni', $dni);
        $sentencia->bindParam('nombres', $nombres);
        $sentencia->bindParam('ape_pat', $ape_pat);
        $sentencia->bindParam('ape_mat', $ape_mat);
        $sentencia->bindParam('telefono', $telefono);
        $sentencia->bindParam('email', $email);
        $sentencia->bindParam('foto', $foto);
        $sentencia->bindParam('fyh_creacion', $fechaHora);
        $sentencia->bindParam('id_gimnasio', $_SESSION['id_gimnasio_sesion']);
        
        if($sentencia->execute()){
            
            $_SESSION['mensaje'] = "Se registró el cliente";
            $_SESSION['icono'] = "success";
            header('Location: '.$URL.'/clientes/');
        }else{
            
            $_SESSION['mensaje'] = "Error no se pudo registrar en la base de datos";
            $_SESSION['icono'] = "error";
            header('Location: '.$URL.'/clientes/create.php');
        }


?>