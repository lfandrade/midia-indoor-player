<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
include("./conecta.php");
include("./constants.php");
// Conecta-se ao banco.
$db = new DB_CONNECT();
$conn = $db->getConnection();



$id_canalURL = $_GET['canal'];

//function recuperaListaVideosCanalJson(){
   //04.12.2018 - alterado para o SQL seguinte após "apagão"
   /*$sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d, dispositivo_canal dc, cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and dc.id_dispositivo = d.id and dc.id_canal = c.id_canal and z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";*/

   $sql = "select distinct v.id as videoID,  cv.ordem, v.url, cv.id_canal as canalID, d.id  as dispositivoID, v.id_cliente as clienteID from video v , canal_video cv, dispositivo d
 where v.id = cv.id_video and d.id_canalPadrao = cv.id_canal and cv.id_canal=$id_canalURL
 order by cv.ordem asc, v.id asc ";

 $result = $conn->query($sql);          


  while($row = $result->fetch_assoc()){
       $json[] = $row;
  }

  echo json_encode($json);

//}

?>