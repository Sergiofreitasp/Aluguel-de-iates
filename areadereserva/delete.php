<?php
  include('../db.php'); 
  
  $sql="delete from usuarios where id in (".$_REQUEST['id'].") and cpf = '".$_REQUEST['cpf']."'";

  $qr=mysql_query($sql); 
  echo json_encode(1);
?>