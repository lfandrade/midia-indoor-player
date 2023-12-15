
<script>
  function toggleFullScreen() {
  if (!document.fullscreenElement &&    // alternative standard method
      !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

  function fullScreen(){

toggleFullScreen();

document.getElementById("video").style.visibility = "visible";




var video = document.getElementById('video');

video.play();
console.log("fullScreen->video.play()");

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
<?php
# primeira atualização
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
include("./includes/conecta.php");
include("./includes/constants.php");

// Conecta-se ao banco.
$db = new DB_CONNECT();
$conn = $db->getConnection();



$id_canalParametro = $_GET['canal'];
$id_canalURL=0;
$id_canalURL = $_POST['canal'];

if($id_canalURL > 0){
 
//Aqui usa a tabela canal_dispositivo para recuperar o Canal, no entanto o sql descomentado usa o campo Canal padrao da propria tabela de canal
  //$sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d, dispositivo_canal dc, cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and dc.id_dispositivo = d.id and dc.id_canal = c.id_canal and z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";

/*
  $sql = "select c.id_video as videoID, v.url, c.id_canal as canalID, d.id as dispositivoID, x.id as clienteID from video v, canal_video c, dispositivo d,  cliente x, cliente_dispositivo z where c.id_video = v.id and c.id_canal=".$id_canalURL." and  z.id_dispositivo = d.id and z.id_cliente = x.id order by c.ordem asc";*/

//troca do parametro canal em canal_dispositivo para a tabela Dispositivo apenas, onde tme o campo Canal padrao
/*$sql = "select v.id as videoID, v.url, ca.id as canalID, dc.id_dispositivo as dispositivoID, cd.id_cliente as clienteID from canal ca, video v, canal_video cv, dispositivo_canal dc, cliente_dispositivo cd where cv.id_canal = ca.id and cv.id_video = v.id and dc.id_canal = ca.id and cd.id_dispositivo = dc.id_dispositivo and ca.id =".$id_canalURL." order by cv.ordem asc ";*/

//incluido o Distinct no ID video, para evitar duplicidade de videos quando a chave está furada. Como ocorreu na arco iris que na tabela cliente_dispositivo, existia duas linhas...

/* Início - 12-10.2018
1. Desativado após implementação do id_revenda nas tabelas.
2. Inclusao do campo id_cliente na tabela de videos
3. Inclusao do id_canalPadrao na tabela Dispositivo
4. Nao usa mais as tabelas -> cliente_dispositivo e dispositivo_canal */ 
//$sql = "select distinct v.id as videoID, v.url, ca.id as canalID, d.id as dispositivoID, cd.id_cliente as clienteID from canal ca, video v, canal_video cv, dispositivo d, cliente_dispositivo cd where cv.id_canal = ca.id and cv.id_video = v.id and d.id_canalPadrao = ca.id and d.id_canalPadrao =".$id_canalURL." and d.id = cd.id_dispositivo order by cv.ordem asc ";

$sql = "select distinct v.id as videoID, v.url, cv.id_canal as canalID, d.id  as dispositivoID, v.id_cliente as clienteID from video v , canal_video cv, dispositivo d
 where v.id = cv.id_video and d.id_canalPadrao = cv.id_canal and cv.id_canal=$id_canalURL";
/* Fim - 12-10.2018 */

  
  $result = $conn->query($sql);           
//echo $sql;

while($row = $result->fetch_assoc()) {
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

while($row = $result->fetch_assoc()) {
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
    /*visibility: hidden;*/
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
 
<body>


<table>
<?php if(!$id_canalURL > 0){ ?>

<tr><td>
<form action="./" method="POST" name="form1">
  <label for="canal">Código do Canal:</label>
  <input class="" id="canal" name="canal" type="number">
  <button type="submit" value="OK">OK</button>
</form>
</td>
</tr>

<?php }else{ 

echo "<HR>Canal:".$_POST['canal'];



  ?>
  <tr><td>
  <form action="./" method="POST" name="form1">
  <label for="canal">Código do Canal:</label>
  <input class="" id="canal" name="canal" type="number">
  <button type="submit" value="OK">OK</button>
</form>
</td></tr>
<tr><td>
<input class="btnIniciar" id="btnIniciar" type="button" value="INICIAR" onclick="javascript:fullScreen()">  
</td></tr>

<?php } ?>
<tr><td>
<div id="videoContainer" class="videoContainer">
  <!--<video id="video" onplay="fullScreen()" controls style="width:640px;height:360px;" poster="pre.jpg" preload="auto" >-->
<video id="video" onplay="fullScreen()" controls="false" style="width:640px;height:360px;" preload="auto" >
  <source src="<?php echo $urlIntro; ?>" type='video/mp4;' />
  
</video>
</div>
</td></tr>
<script type='text/javascript'>



  var count = 0;

    
    //EVENTO ACIONADO AO TÉRMINO DO VIDEO
    document.getElementById('video').addEventListener('ended', function(e) {

    var video = document.getElementById('video');
    var source = document.createElement('source');
    
    //cria objeto JS com os dados JSON retornados da base
    var JSONObject = <?php echo json_encode($json); ?>;

      if(count < JSONObject.length){

      idClienteExecucao = JSONObject[count]["clienteID"];
      idCanalExecucao = JSONObject[count]["canalID"];
      idPontoExecucao = 0;
      idDispositivo = JSONObject[count]["dispositivoID"];
      idVideoExecucao = JSONObject[count]["videoID"];
      
      registrarExibicao(idClienteExecucao,idCanalExecucao,idPontoExecucao,idDispositivo,idVideoExecucao);

        //alert(JSONObject[count]["url"]);
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
console.log("alterando video");
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
        console.log("lista de videos recupeda.");
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
  //alert(album+" - "+foto+" - "+status);

  $.ajax({
            method: "get",
            url: "<?php echo $urlLog;?>?id_cliente="+cliente+"&id_canal="+canal+"&id_ponto="+ponto+"&id_dispositivo="+dispositivo+"&id_video="+video,
            data: $("#form").serialize(),
        success: function(data){
console.log("acesso registrado");
          //#("#badge").html("teste");    
            //var content = pontuacao ;
            //document.getElementById('badge-'+foto).innerHTML = content;
        }

    });

}

function alteraVideo(url) {
   var video = document.getElementById('video');
   video.src = url;
   video.crossOrigin = 'anonymous';
   video.play();
   console.log("video.play()");
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


$(document).ready(function(){
    toggleFullScreen();
    console.log("documento pronto");
});
</script>
</body>
</html>