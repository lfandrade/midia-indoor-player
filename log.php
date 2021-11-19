<?php
include("./conecta.php");
include("./constants.php");

// Conecta-se ao banco.
$db = new DB_CONNECT();
$conn = $db->getConnection();

/*mysqli_set_charset('utf8',$link);
if (!$link) {
    die('Não foi possível conectar: ' . mysql_error());
}*/



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');




// Function to get the client ip address
function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}


//$ipaddress = $_SERVER['REMOTE_ADDR']
$ipaddress = 0;
$ipaddress = get_client_ip_env();
$browser = $_SERVER['HTTP_USER_AGENT'];
$referrer = $_SERVER['HTTP_REFERER'];
 if ($referred == "") {
  $referrer = "página acessada diretatemente";
  }

$id_dispositivo = $_GET['id_dispositivo'];
$id_cliente = $_GET['id_cliente'];
$id_ponto = $_GET['id_ponto'];
$id_video = $_GET['id_video'];
$id_canal = $_GET['id_canal'];

$data = date("Ymd");
$hora = date("His");   

//function gravar(){

$sql="insert into log (id_cliente,id_dispositivo,id_canal, id_video, data, hora, ipaddress,browser,referrer ) values($id_cliente,$id_dispositivo,$id_canal,$id_video, $data, $hora, '$ipaddress','$browser','$referrer');";
echo "<br>".$sql;

$result = $conn->query($sql);  

//} 

?>