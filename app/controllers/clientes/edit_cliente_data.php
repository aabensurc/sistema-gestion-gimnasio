<?php 

$id_cliente_get = $_GET['id'];

// MODIFICACIÓN: La consulta SQL ahora selecciona cli.id_cliente como 'codigo'.
$sql_clientes = "SELECT cli.id_cliente as id_cliente, cli.id_cliente as codigo, cli.dni as dni, cli.nombres as nombres, cli.ape_pat as ape_pat, cli.ape_mat as ape_mat, cli.telefono as telefono, cli.email as email, cli.foto as foto 
                    FROM tb_clientes cli WHERE id_cliente = '$id_cliente_get' AND cli.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";
  $query_clientes = $pdo->prepare($sql_clientes);
  $query_clientes-> execute();
  $clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);

  foreach($clientes_datos as $clientes_dato) {
    // La variable $codigo ahora obtiene el valor de id_cliente gracias al alias 'codigo' en el SELECT.
    $codigo = $clientes_dato['codigo'];
    $dni = $clientes_dato['dni'];
    $nombres = $clientes_dato['nombres'];
    $ape_pat = $clientes_dato['ape_pat'];
    $ape_mat = $clientes_dato['ape_mat'];
    $telefono = $clientes_dato['telefono'];
    $email = $clientes_dato['email'];
    $foto = $clientes_dato['foto'];
  }
?>