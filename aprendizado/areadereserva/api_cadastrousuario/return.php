<?php
include('./db.php');

$sql = "select cadu.*, dbc.nome as condominio from cadastrousuario cadu inner join db_condominio dbc on cadu.condominio_id = dbc.id";

$qr = mysql_query($sql);

$ar=array();

while ($ln = mysql_fetch_assoc($qr)){
    $ar[] = $ln;
}

echo json_encode($ar);

?>