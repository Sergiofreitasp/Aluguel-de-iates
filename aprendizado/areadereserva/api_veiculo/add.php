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

$sql = $sql1." veiculo_estagio set modelo='".$p['modelo']."', placa='".$p['placa']."'";
$sql.=" ".$sql2;

$qr=mysql_query($sql);

echo $sql;
echo json_encode(array("Sucesso"=>true, "msg"=>"Foi"));

?>