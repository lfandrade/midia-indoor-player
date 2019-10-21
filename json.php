<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include("env.php");

$conexao = mysql_connect($servidor,$usuario,$senha);  
mysql_select_db($banco); 


//$id_dispositivoURL = $_GET['dispositivo'];
$id_canalURL=0;
$id_canalURL = $_GET['canal'];

if($id_canalURL > 0){
 
//Aqui usa a tabela canal_dispositivo para recuperar o Canal, no entanto o sql descomentado usa o campo Canal padrao da propria tabela de canal
  //$sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d, dispositivo_canal dc, cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and dc.id_dispositivo = d.id and dc.id_canal = c.id_canal and z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";

/*
  $sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d,  cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and  z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";*/

//troca do parametro canal em canal_dispositivo para a tabela Dispositivo apenas, onde tme o campo Canal padrao
/*$sql = "select v.id as videoID, v.url, ca.id as canalID, dc.id_dispositivo as dispositivoID, cd.id_cliente as clienteID from canal ca, video v, canal_video cv, dispositivo_canal dc, cliente_dispositivo cd where cv.id_canal = ca.id and cv.id_video = v.id and dc.id_canal = ca.id and cd.id_dispositivo = dc.id_dispositivo and ca.id =".$id_canalURL." order by cv.ordem asc ";*/

//incluido o Distinct no ID video, para evitar duplicidade de videos quando a chave está furada. Como ocorreu na arco iris que na tabela cliente_dispositivo, existia duas linhas...
$sql = "select distinct v.id as videoID, v.url, ca.id as canalID, d.id as dispositivoID, cd.id_cliente as clienteID from canal ca, video v, canal_video cv, dispositivo d, cliente_dispositivo cd where cv.id_canal = ca.id and cv.id_video = v.id and d.id_canalPadrao = ca.id and d.id_canalPadrao =".$id_canalURL." and d.id = cd.id_dispositivo order by cv.ordem asc ";

  $result = mysql_query($sql);            
//echo $sql;

  while($row = mysql_fetch_assoc($result)){
       $json[] = $row;
       //echo $row;
  }

echo json_encode($json);

}




/*
function updateListaVideos(){
   $sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d, dispositivo_canal dc, cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and dc.id_dispositivo = d.id and dc.id_canal = c.id_canal and z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";

  $result = mysql_query($sql);            
//echo $sql;

  while($row = mysql_fetch_assoc($result)){
       $json[] = $row;
       //echo $row;
  }
}*/










?>
