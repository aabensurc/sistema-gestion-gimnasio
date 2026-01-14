<?php



  $sql_roles = "SELECT * FROM tb_roles WHERE id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ORDER BY rol ASC";
  $query_roles = $pdo->prepare($sql_roles);
  $query_roles-> execute();
  $roles_datos = $query_roles->fetchAll(PDO::FETCH_ASSOC);

  //foreach($roles_datos as $roles_dato){
    //echo $roles_dato['rol'];
  //}