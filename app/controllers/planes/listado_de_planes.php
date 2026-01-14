<?php



  $sql_planes = "SELECT * FROM tb_planes WHERE id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";
  $query_planes = $pdo->prepare($sql_planes);
  $query_planes-> execute();
  $planes_datos = $query_planes->fetchAll(PDO::FETCH_ASSOC);

  //foreach($roles_datos as $roles_dato){
    //echo $roles_dato['rol'];
  //}