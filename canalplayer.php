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

$sql = "select distinct v.id as videoID, cv.ordem, v.url, cv.id_canal as canalID, d.id  as dispositivoID, v.id_cliente as clienteID from video v , canal_video cv, dispositivo d
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

<form action="./canalplayer.php" method="GET" name="form1">
  <label for="canal">Código do Canal:</label>
  <input class="" id="canal" name="canal" type="number">
  <button type="submit" value="OK">OK</button>
</form>


<?php }else{ 

echo "<HR><b>Canal Selecionado:</b>".$_GET['canal'];



  ?>
  <form action="./canalplayer.php" method="GET" name="form1">
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

<script type='text/javascript'>



  var count = 0;

    
    //EVENTO ACIONADO AO TÉRMINO DO VIDEO
    document.getElementById('video').addEventListener('ended', function(e) {

    var video = document.getElementById('video');
    var source = document.createElement('source');
    
    //cria objeto JS com os dados JSON retornados da base
    var JSONObject = <?php echo json_encode($json); ?>;
    //alert(<?php echo json_encode($json); ?>);
    console.log("json json_encode");
    console.log("size: "+JSONObject.length);

    /*for (var key in JSONObject) {
    if (JSONObject.hasOwnProperty(key)) {*/

      

      
     
      
      //urlExecucao = JSONObject[key]["url"];

      if(count < JSONObject.length){

      idClienteExecucao = JSONObject[count]["clienteID"];
      idCanalExecucao = JSONObject[count]["canalID"];
      idPontoExecucao = 0;
      idDispositivo = JSONObject[count]["dispositivoID"];
      idVideoExecucao = JSONObject[count]["videoID"];
      
      registrarExibicao(idClienteExecucao,idCanalExecucao,idPontoExecucao,idDispositivo,idVideoExecucao);
      console.log(idClienteExecucao+"-"+idCanalExecucao+"-"+idPontoExecucao+"-"+idDispositivo+"-"+idVideoExecucao);
      console.log(JSONObject[count]["url"]);
          changeSource(JSONObject[count]["url"]);
          
          //console.log("Teste:"+JSONObject[count]["videoID"] + ", " + JSONObject[count]["url"]+ ", " + JSONObject[count]["canalID"]);


        }else{
           count=0;



      idClienteExecucao = JSONObject[count]["clienteID"];
      idCanalExecucao = JSONObject[count]["canalID"];
      idPontoExecucao = 0;
      idDispositivo = JSONObject[count]["dispositivoID"];
      idVideoExecucao = JSONObject[count]["videoID"];
      
      registrarExibicao(idClienteExecucao,idCanalExecucao,idPontoExecucao,idDispositivo,idVideoExecucao);

           //valida se tem video novo, atualiza objeto Json e inicia

           //verificaConteudoNovo(idCanalExecucao);

          changeSource(JSONObject[count]["url"]);
        }

    /*}
  }*/


    //se a tag <video loop (elemento loop) estiver presente, o evento ENDED não é disparado
    })

    function verificaConteudoNovo(canal){
        $.ajax({
              method: "get",
              url: "<?php echo $urlFunction;?>?canal="+canal,
              data: $("#form").serialize(),
          success: function(data){

            //cria objeto JS com os dados JSON retornados da base
            //<?php updateListaVideos();?>
            var JSONObject = <?php echo json_encode($json); ?>;

            //alert(123);
            //#("#badge").html("teste");    
              //var content = pontuacao ;
              //document.getElementById('badge-'+foto).innerHTML = content;
          }

      });
    }

  function registrarExibicao(cliente, canal, ponto, dispositivo, video){


    $.ajax({
              method: "get",
              url: "<?php echo $urlLog;?>?id_cliente="+cliente+"&id_canal="+canal+"&id_ponto="+ponto+"&id_dispositivo="+dispositivo+"&id_video="+video,
              data: $("#form").serialize(),
          success: function(data){
            //alet("registrou");
          }

      });

}

function alteraVideo(url) {
   var video = document.getElementById('video');
   video.src = url;
   video.crossOrigin = 'anonymous';
   video.play();
}

function functiontofindIndexByKeyValue(arraytosearch, key, valuetosearch) {

    for (var i = 0; i < arraytosearch.length; i++) {

    if (arraytosearch[i][key] == valuetosearch) {
    return i;
    }
    }
    return null;
    }

function changeSource(url) {
   var video = document.getElementById('video');
   video.src = url;
   video.crossOrigin = 'anonymous';
   video.play();

   count++;
}

function fullScreen(){

  
  document.getElementById("video").style.visibility = "visible";



  var video = document.getElementById('video');

  video.play();

    var elem = document.getElementById("video");

  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.msRequestFullscreen) {
    elem.msRequestFullscreen();
  } else if (elem.mozRequestFullScreen) {
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen();
  }
}



</script>


</body>
</html>
