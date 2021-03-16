<?php include_once('/var/www/html/vn3/vn3/api/url.php'); ?>
<?php 

function gatilhoatualizacao($condominio_id,$nometabela){
	$sql="select servidoracesso_id from condominio where id ='".$condominio_id."'";
	$qr=mysql_query($sql);
	$ln=mysql_fetch_assoc($qr);
	$ids = 1;
	if (($ln['servidoracesso_id']!=null)&&($ln['servidoracesso_id']!='')){
		$ids=$ln['servidoracesso_id'];
	}
	$sqlx="select url from servidoracesso where id = '".$ids."'"; 
	$qrx=mysql_query($sqlx);
	$ln=mysql_fetch_assoc($qrx);
	$url = $ln['url']."/acessoapi/req.php?tipo=".$nometabela;
	$x = file_get_contents($url);
 // https://apac01.sigowl.com.br/acessoapi/req.php?tipo=usuario
}

function catalogarequests(){
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$pasta = '/var/www/html/vn3/scripts/request/'.date('Ymd');
	$arq=array();
	criapasta($pasta);
	$arqn=$pasta."/".$ip.".txt";
	if (file_exists($arqn)){
		$arq=json_decode(file_get_contents($pasta."/".$ip.".txt"));
	}
	$ob = array();
	$ob['datahora']=date('Y-m-d H:i:s');
	$ob['ip']=$ip;
	$ob['url']=$_SERVER['REQUEST_URI']."?".urlencode($_SERVER['QUERY_STRING']);
	$arq[]=$ob;
	file_put_contents($arqn, json_encode($arq));
	return $ob;
}

function criapasta($pasta){
	$r = mkdir($pasta, 0777, true);
	//print_r($r);
}

catalogarequests();

 $link = mysql_connect(P_DB_HOST,"root",P_DB_SENHA);
 header('Access-Control-Allow-Origin: *');  

 if (!$link) {
	die('Could not connect database: ' . mysql_error());
 }
 mysql_select_db(P_DB_NOME);
 mysql_query("SET NAMES 'utf8'");
 mysql_query('SET character_set_connection=utf8');
 mysql_query('SET character_set_client=utf8');
 mysql_query('SET character_set_results=utf8');
 

include_once('/var/www/html/vn3/scripts/encoding.php');

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}


 function json_encode_acx($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
 
    return json_encode($array);
}
 function translate($s){
	 if ($s =='New'){
		 $s='Novo';
	 }
	 if ($s =='Edit'){
		 $s='Editar';
	 }
	 if ($s =='Delete'){
		 $s='Deletar';
	 }
	 if ($s =='Administrator'){
		 $s='Administrador';
	 }
	// $sql="select * from language where english = '".($s)."'";
	// $qr=mysql_query($sql);
	// if (mysql_num_rows($qr)==0){
	// 	$sql="insert into language set english = '".($s)."'";
	//     $qr=mysql_query($sql); 
	// 	$sql="select * from language where english = '".($s)."'";
	//     $qr=mysql_query($sql);   
 //    }   
 //    $ln=mysqli_fetch_assoc($qr); 
	return $s;
}

if ($_REQUEST['enviasms']=='1'){
	//echo mandamsgsmsalerta(10);
}

function enviasms($numero,$msg,$tipo='',$condominio_id=''){
	return enviasmsreal($numero,$msg,$tipo,$condominio_id);
}
 
function remacentos($texto){
	/* função que gera uma texto limpo pra virar URL:
	- limpa acentos e transforma em letra normal
	- limpa cedilha e transforma em c normal, o mesmo com o ñ
	- transforma espaços em hifen (-)
	- tira caracteres invalidos
	by Micox - elmicox.blogspot.com - www.ievolutionweb.com
	*/
	//desconvertendo do padrão entitie (tipo á para á)
	$texto = trim(html_entity_decode($texto));
	//tirando os acentos
	$texto= preg_replace('![áàãâä]+!u','a',$texto);
	$texto= preg_replace('![éèêë]+!u','e',$texto);
	$texto= preg_replace('![íìîï]+!u','i',$texto);
	$texto= preg_replace('![óòõôö]+!u','o',$texto);
	$texto= preg_replace('![úùûü]+!u','u',$texto);
	//parte que tira o cedilha e o ñ
	$texto= preg_replace('![ç]+!u','c',$texto);
	$texto= preg_replace('![ñ]+!u','n',$texto);
	//tirando outros caracteres invalidos
	$texto= preg_replace('[^a-z0-9\-]','-',$texto);
	//tirando espaços 
	//trocando duplo espaço (hifen) por 1 hifen só
	$texto = str_replace('--','-',$texto);

	return strtolower($texto);
}


function enviasmsreal($numero,$msg,$tipo='',$condominio_id=''){
	$msg=ucwords(remacentos($msg));
	$server_output=1;
	$numero=soNumero($numero);
	if (($numero != null)&&($numero != '')&&(strlen($numero)>=8)){
		if (strlen($numero)<11){
			return false;
		}else if (strlen($numero)<9){
			return false;
		}else{
			if (strlen($numero)==11){
			    	$numero=$numero;
			}
		  	if (false){
				$ch = curl_init(); 
				
				curl_setopt($ch, CURLOPT_URL,"https://www.experttexting.com/ExptRestApi/sms/json/Message/Send");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,"username=vn3app&password=vn3seguranca&from=DEFAULT&api_key=hb0q2hob47jx4sk&to=".$numero."&text=".urlencode($msg));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
				
				
				// receive server response ...
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				$server_output = curl_exec ($ch);
				
				curl_close ($ch);
			}else{
	   			$ch = curl_init();
	   			$sql="insert into sms set condominio_id='".$condominio_id."',menssagem='".$msg."',numero='".$numero."',tipo='".$tipo."'"; 
	   			$qr=mysql_query($sql);
                $server_output = mandasmszenvia($numero,$msg);
                if (false){
				curl_setopt($ch, CURLOPT_URL,"http://bemfacil.smswave.com.br/api/v1/send");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,"cpf=073.982.684-08&password=carlos@8408&type=short&numbers=".$numero."&messages=".urlencode($msg));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


				// receive server response ...
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$server_output = curl_exec ($ch);

				curl_close ($ch);
				 }
				return $server_output;
	   		}
		}
	}
	return $server_output;
}
if ($_REQUEST['xyz']=='1'){
	//enviasmsreal('83991284699','panico medico','test','3');
	$r = mandasmszenvia('83991284699','Ola Carlos Temos Uma Emergencia Medica Para Diego Paiva! Para Mais Detalhes Clique Aqui: Https://www.vn3anjo.com.br/alerta/?id=79890');
	print_r($r);
}
function mandasmszenvia($numero,$msg){ 
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.smsfire.com.br/v1/sms/send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"to\": [
    \"55".$numero."\"
  ],
  \"from\": \"SMSFIRE\",
  \"text\": \"".$msg."\",
  \"timezone\": \"America/Sao_Paulo\",
  \"2ways\": {
    \"active\": true,
    \"url\": \"https://www.vn3anjo.com.br/vn3/api/firesms.php\",
    \"email\": \"vn3app@gmail.com\"
  },
  \"notificationUrl\": \"http://www.vn3anjo.com.br/vn3/api/firesms.php\",
  \"routing\": 1
}");
$bscode=base64_encode('07398268408:carlos@8408');
//echo $bscode;
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: Basic ".$bscode
));

$response = curl_exec($ch);
curl_close($ch);
return $response;
//var_dump($response);
}



function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

function mandamsgsmsalerta($id){
	if ($GURL == ''){
	   $GURL=GURL;	
	}
	$ss='';
	$sql="select * from alertas where id = '".$id."'";
	$qr=mysql_query($sql);
	$ln_alerta=mysql_fetch_assoc($qr);
	
		$ss.='1<br>';
	if (in_array($ln_alerta['tipo'],array('medico','coacao','portaria em risco'))){
		$sql="select * from usuario where id = '".$ln_alerta['usuario_id']."'";
		$qr=mysql_query($sql);
		$ln_usuario=mysql_fetch_assoc($qr);
		
		$ss.='2<br>';
		$sql="select * from condominio where id = '".$ln_usuario['condominio_id']."'";
		$qr=mysql_query($sql);
		$ln_condominio=mysql_fetch_assoc($qr);
		$ss.='3<br>';
		$sql="select * from contatos_condominio where condominio_id = '".$ln_usuario['condominio_id']."'";
		$qr=mysql_query($sql);
		if (mysql_num_rows($qr)>0){
			while($ln_contatos=mysql_fetch_assoc($qr)){
				$msg = 'Ola '.$ln_contatos['nome']." Temos uma emergencia medica!  para mais detalhes clique aqui: ".$GURL."alerta/?id=".$ln_alerta['id'];
				$ss.=enviasms($ln_contatos['celular'],$msg);
			}
		}
		if ( ($ln_usuario['nomeparente'] != null)&&($ln_usuario['telefoneparente'] != null)&&($ln_usuario['nomeparente'] != '')&&($ln_usuario['telefoneparente'] != '')){
			$msg = 'Ola '.$ln_usuario['nomeparente']."  Temos uma emergencia medica para ".$ln_usuario['nome']."!  para mais detalhes clique aqui: ".$GURL."alerta/?id=".$ln_alerta['id'];
			$ss.=enviasms($ln_usuario['telefoneparente'],$msg);
		}
		if ( ($ln_usuario['nomeparente2'] != null)&&($ln_usuario['telefoneparente2'] != null)&&($ln_usuario['nomeparente2'] != '')&&($ln_usuario['telefoneparente2'] != '')){
			$msg = 'Ola '.$ln_usuario['nomeparente2']." Temos uma emergencia medica para ".$ln_usuario['nome']."! para mais detalhes clique aqui: ".$GURL."alerta/?id=".$ln_alerta['id'];
			
			$ss.=enviasms($ln_usuario['telefoneparente2'],$msg);
		}
	}
	return $ss;
}






function getobjant($id,$tbl){
    if ($id==''){
      return '';
    }else{
      $sqlxw="select * from ".$tbl." where id ='".$id."'";
      $qrx=mysql_query($sqlxw);
      $lnx=mysql_fetch_assoc($qrx);
      return $lnx;
    }
 }
 
function comparaobjx($objantigo,$objnovo){
   $resp='';
   foreach ($objnovo as $key => $value){
       $xde=$objantigo[$key];
       if ($xde == null){
        $xde='';
       }
       if ($xde != $value){
        $xvl=$key.' ';
         if ($xde!=''){
            $xvl.='de '.$xde;
         }
         $xvl.=' para '.$value."<br>";
         $resp.=$xvl;
         //$resp[]=array('campo'=>$key,'de'=>$xde,'para'=>$value,);
       }
   }
   return $resp;
}

function addauditoria($condominio_id,$usuario_id,$usuario_nome,$identificacao,$plataforma,$tipo,$id,$tabela,$objantigo){ 
  $objnovo=$objantigo;
  if ($tipo!='Excluir'){  
    $objnovo=getobjant($id,$tabela);
  }else{
    $objantigo='';
  }
  $detalhes=comparaobjx($objantigo,$objnovo);
  $sqlxws="insert into historico_uso set condominio_id='".$condominio_id."',usuario_id='".$usuario_id."',usuario_nome='".$usuario_nome."',identificacao='".$identificacao."',plataforma='".$plataforma."',tipo='".$tipo." $tabela',detalhes='".$detalhes."',datahora=now()";
  
  $qrxws=mysql_query($sqlxws);
}

if ($_REQUEST['atualizaacesso']=='1'){
	echo atualizaacesso('3');
}
if ($_REQUEST['atualizamrd']=='1'){
	echo atualizamrd('414');
}



function atualizaacesso($condominio_id){
	  $sql="select bombaagua,nivel,date_format(datahora_nivel, '%d/%m/%Y %H:%i:%s') datahora_nivel,bomba1,bomba2,bomba3 from setores where bombaagua='Sim' and condominio_id ='".$condominio_id."' "; 
	  $qr=mysql_query($sql);
	  $arb=array();
	  while ($ln=mysql_fetch_assoc($qr)){
		  $arb[]=$ln;  
	  }
	  
	  $sql="delete from notificacoes where datahora <= DATE_ADD(now(),interval -2 hour)";
	  $qr=mysql_query($sql);

	  $sql="select * from notificacoes where condominio_id = '".$condominio_id."'";
	  $qr=mysql_query($sql);
	  $arnot=array();
	  while($ln=mysql_fetch_assoc($qr)){
	    $arnot[]=$ln; 
	  }
	  $conteudo = base64_encode(json_encode(array("bomba"=>$arb,"notificacoes"=>$arnot)));
	  $url = "https://img.vn3anjo.com.br/api/create.php?condominio_id=".$condominio_id."&tipo=acesso&conteudo=".$conteudo;
	  $x = file_get_contents($url);
	  return $url;  

}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function atualizamrd($morador_id,$condominio_id){
  $sql="delete from alertaspen where date(datahora) < date(now())";
  $qr=mysql_query($sql);

  $sql="select count(id) qt from encomendas where datahora_entregue is null  and confirmado is not null and morador_id = '".$morador_id."'";
  $qr=mysql_query($sql);
  $r = mysql_fetch_assoc($qr);
  $encomendasqt=intval($r['qt']);
  
  $sql="select count(alertas.id) qt from alertas inner join usuario usu on usu.id = alertas.usuario_id where 1 and (alertas.tipo <> 'Falta de Agua' and alertas.tipo <> 'Elevador em Manutenção'  and alertas.tipo <> 'Encomenda' and alertas.tipo <> 'emergencia medica' and alertas.tipo <> 'Encomenda') ";
 
  if ($condominio_id>0){
    $sql.=" and alertas.condominio_id = '".$condominio_id."' ";
  } 
  $sql.=" and (alertas.tipo <> 'medico' and alertas.tipo <> 'coacao') and alertas.status = 'Em Aberto' order by alertas.id desc";
  if ($_REQUEST['debug']=='1'){
    echo $sql."<br>";
  }
  $qr=mysql_query($sql);
  $r = mysql_fetch_assoc($qr);
  $alertasqt=$r['qt'];
  
  
  $sql="select count(av.id) qt from avisos av inner join avisos_moradores avm on av.id = avm.aviso_id where avm.usuario_id = '".$morador_id."'";
  if ($_REQUEST['debug']=='1'){
    echo $sql."<br>";
  }
  $qr=mysql_query($sql);
  $r = mysql_fetch_assoc($qr);
  $alertasqt=intval($alertasqt)+intval($r['qt']);

  $sql="select id from alertaspen where date(datahora) = date(now()) and usuario_id = '".$morador_id."'";
  $qrx=mysql_query($sql);
  $qtot=mysql_num_rows($qrx);

	$conteudo = base64_encode(json_encode(array("sucesso"=>false,"encomendas"=>$encomendasqt,"alertas"=>$alertasqt,"pendentes"=>$qtot)));
	$url = "https://img.vn3anjo.com.br/api/create.php?condominio_id=".$morador_id."&tipo=morador&conteudo=".$conteudo;
	$x = file_get_contents($url);
	return $url;  
}

?> 