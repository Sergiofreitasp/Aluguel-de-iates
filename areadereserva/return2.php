<?php
include('./db.php');

$sql = "select * from cadastrousuario";

$qr = mysql_query($sql);

$ar=array();

while ($ln = mysql_fetch_assoc($qr)){
    $ar[] = $ln;
}

echo json_encode($ar);

?>