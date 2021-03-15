<?php
include('./db.php');
$p = $_REQUEST;
$sql1="";
$sql2="";

if ($p['id']!=''){
    $sql1 = "update ";
    $sql2 = " where id '".$p['id']."'";
}else{
    $sql1="insert into ";
    $sql2=" ";
}

$sql = $sql1." cadastrousuario set nome='".$p['nome']."', cpf='".$p['cpf']."', idade='".$p['idade']."', endereco='".$p['endereco']."', numero='".$p['numero']."'";
$sql.=" ".$sql2;

$qr=mysql_query($sql);
echo json_encode(array("Sucesso"=>true, "msg"=>"ok"));

?>