<?php
include('../api_cadastrousuario/db.php');


$p = $_REQUEST;

$sql1="";
$sql2="";

if ($p['id']!=''){
    $sql1 = "update ";
    $sql2 = " where id ='".$p['id']."'";
}else{
    $sql1="insert into ";
    $sql2=" ";
}

$sql = $sql1." db_condominio set nome='".$p['nome']."'";
$sql.=" ".$sql2;

$qr = mysql_query($sql);

echo json_encode(array("sucesso"=> true, "msg"=>"foi"));


?>