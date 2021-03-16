<?php
include('./db.php');
$p=$_REQUEST;

$sql = "delete from cadastrousuario WHERE id in (".$p['id'].")";

$qr= mysql_query($sql);

echo json_encode(array("msg"=>"Deletado"));


?>