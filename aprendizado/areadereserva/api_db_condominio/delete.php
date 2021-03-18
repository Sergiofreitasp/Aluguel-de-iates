<?php
include('../api_cadastrousuario/db.php');
$p=$_REQUEST;

$sql = "delete from db_condominio WHERE id in (".$p['id'].")";

$qr= mysql_query($sql);

echo json_encode(array("msg"=>"Deletado"));


?>