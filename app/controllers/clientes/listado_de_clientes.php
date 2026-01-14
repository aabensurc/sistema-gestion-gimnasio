<?php


  // MODIFICACIÓN: Se añade un subquery para obtener la fecha_fin_ultima_matricula.
  $sql_clientes = "SELECT 
                    cli.id_cliente as id_cliente, 
                    cli.id_cliente as codigo, 
                    cli.dni as dni, 
                    cli.nombres as nombres, 
                    cli.ape_pat as ape_pat, 
                    cli.ape_mat as ape_mat, 
                    cli.telefono as telefono, 
                    cli.email as email, 
                    cli.foto as foto,
                    (SELECT MAX(mat.fecha_fin) 
                     FROM tb_matriculas mat 
                     WHERE mat.id_cliente = cli.id_cliente) as fecha_fin_ultima_matricula
                    FROM tb_clientes cli WHERE cli.id_gimnasio = '$_SESSION[id_gimnasio_sesion]' ";

  $query_clientes = $pdo->prepare($sql_clientes);
  $query_clientes-> execute();
  $clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);

?>