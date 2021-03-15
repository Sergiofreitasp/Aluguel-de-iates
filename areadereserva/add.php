<?php
  include('../db.php');  
  $p = $_REQUEST;
  $sql_1 = "";
  $sql_2 = "";
 
  if ($p['id']!=''){
	   $sql_1 = "update ";
     $sql_2 = " where id = '".$p['id']."' ";
  }else{
	   $sql_1 = "insert into ";
     $sql_2 = "";  
  }  
  
    $sql=$sql_1."usuarios set nome='".$p['nome']."', cpf='".$p['cpf']."', idade='".$p['idade']."', endereco='".$p['endereco']."', numero='".$p['numero']."'";
  $sql.=" ".$sql_2;  
  $qr=mysql_query($sql);
  echo json_encode(array("sucesso"=>true,"msg"=>"ok"));
?>