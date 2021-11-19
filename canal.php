
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include("./conecta.php");
include("./constants.php");

// Conecta-se ao banco.
$db = new DB_CONNECT();
$conn = $db->getConnection();


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
/*$sql = "select distinct v.id as videoID, v.url, ca.id as canalID, d.id as dispositivoID, cd.id_cliente as clienteID from canal ca, video v, canal_video cv, dispositivo d, cliente_dispositivo cd where cv.id_canal = ca.id and cv.id_video = v.id and d.id_canalPadrao = ca.id and d.id_canalPadrao =".$id_canalURL." and d.id = cd.id_dispositivo order by cv.ordem asc, v.id asc ";*/

$sql = "select distinct v.id as videoID,  cv.ordem, v.url, cv.id_canal as canalID, d.id  as dispositivoID, v.id_cliente as clienteID from video v , canal_video cv, dispositivo d
 where v.id = cv.id_video and d.id_canalPadrao = cv.id_canal and cv.id_canal=$id_canalURL
 order by cv.ordem asc, v.id asc  ";

 $result = $conn->query($sql);          
//echo $sql;

  while($row = $result->fetch_assoc()){
       $json[] = $row;
       //echo $row;
  }

}

function updateListaVideos(){
   $sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d, dispositivo_canal dc, cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and dc.id_dispositivo = d.id and dc.id_canal = c.id_canal and z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";
// Conecta-se ao banco.
$db = new DB_CONNECT();
$conn = $db->getConnection();
   $result = $conn->query($sql);            
//echo $sql;

  while($row = $result->fetch_assoc()){
       $json[] = $row;
       //echo $row;
  }
}






//echo json_encode($json);



?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta http-equiv="Cache-control" content="public">
  <meta charset="UTF-8">
  <title>Player</title>
  <style type="text/css">
    video 
{
    width: 100%;
    height: auto;
    max-height: 100%;
    display: none;
    visibility: hidden;
}

.videoContainer 
{
    position:absolute;
    height:100%;
    width:100%;
    overflow: hidden;
}

.videoContainer video 
{
    min-width: 100%;
    min-height: 100%;
}
.btnIniciar{
 height:500px;
  width:100%;
}
#btnIniciar{
 height:500px;
  width:100%;
}
</style>
</head>
<body>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<?php if(!$id_canalURL > 0){ ?>

<form action="./canal.php" method="GET" name="form1">
  <label for="canal">Código do Canal:</label>
  <input class="" id="canal" name="canal" type="number">
  <button type="submit" value="OK">OK</button>
</form>


<?php }else{ 

echo "<HR><b>Canal Selecionado:</b>".$_GET['canal'];



  ?>
  <form action="./canal.php" method="GET" name="form1">
  <label for="canal">Código do Canal:</label>
  <input class="" id="canal" name="canal" type="number">
  <button type="submit" value="OK">OK</button>
</form>

<input class="btnIniciar" id="btnIniciar" type="button" value="INICIAR" onclick="javascript:fullScreen()">  


<?php } ?>

<div id="videoContainer" class="videoContainer">
  <!--<video id="video" onplay="fullScreen()" controls style="width:640px;height:360px;" poster="pre.jpg" preload="auto" >-->
<video id="video" onplay="fullScreen()" controls="false" style="width:640px;height:360px;" preload="auto" >
  <source src="<?php echo $urlIntro; ?>" type='video/mp4;' />
  
</video>
</div>



</body>
</html>
