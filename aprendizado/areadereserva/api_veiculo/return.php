<?php
include('../api_cadastrousuario/db.php');

$sql = "select * from veiculo_estagio";

$qr = mysql_query($sql);

$ar=array();

while ($ln = mysql_fetch_assoc($qr)){
    $ar[] = $ln;
}

echo json_encode($ar);

?>