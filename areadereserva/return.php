<?php
  include('../db.php'); 


  $resto=" where 1";
 

  if($_REQUEST['condominio_id']!= ''){
    $resto.=" and ar.condominio_id = ".$_REQUEST['condominio_id']." ";
  }
  $sql="select ar.*,con.nome condominio from areadereserva ar inner join condominio con on con.id = ar.condominio_id ".$resto." order by ar.nome asc"; 
  $qr=mysql_query($sql);
  $ar=array();

  while ($ln=mysql_fetch_assoc($qr)){
	 $ar[]=$ln;  
  }
  echo json_encode($ar);
?>