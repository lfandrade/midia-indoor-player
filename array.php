<?php
include("env.php");
$conexao = mysql_connect($servidor,$usuario,$senha);  
mysql_select_db($banco); 


$result = mysql_query("SELECT * FROM video");            


while($row = mysql_fetch_assoc($result)){
     $json[] = $row;
}

echo json_encode($json);

echo "<hr>teste<hr>";

?>
<div id="videoContainer" class="videoContainer">
  <!--<video id="video" onplay="fullScreen()" controls style="width:640px;height:360px;" poster="pre.jpg" preload="auto" >-->
<video id="video" controls="false" style="width:640px;height:360px;" preload="auto" >
  <source src="<?php echo $urlIntro;?>" type='video/mp4;' />
</video>
</div>

<script type='text/javascript'>



  var count = 1;

    
    //EVENTO ACIONADO AO TÉRMINO DO VIDEO
    document.getElementById('video').addEventListener('ended', function(e) {

    var video = document.getElementById('video');
    var source = document.createElement('source');
    //se a tag <video loop (elemento loop) estiver presente, o evento ENDED não é disparado

	var myArray = <?php echo json_encode($json); ?>;

    alert("fim");
	
	 var JSONObject = <?php echo json_encode($json); ?>;

  //console.log(JSONObject);      // Dump all data of the Object in the console
  //alert(JSONObject[0]["url"]);

  alert(JSONObject.length);


  for (var key in JSONObject) {
    if (JSONObject.hasOwnProperty(key)) {
      console.log(JSONObject[key]["id"] + ", " + JSONObject[key]["url"]);
    }
  }

    })
</script>