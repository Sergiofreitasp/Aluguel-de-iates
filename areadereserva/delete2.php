<?php
include('./db.php');
$p=$_REQUEST;

$sql = "delete from cadastrousuario WHERE id in (".$p['id'].") and cpf='".$p['cpf']."'";

$qr= mysql_query($sql);
echo json_encode(1);


?>