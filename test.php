<?php
require("/Users/tpetty/repos/phpMQTT/phpMQTT.php");

$mqtt = new phpMQTT("192.168.1.214", 1883, "phpMQTT Sub Example"); //Change client name to something unique
if(!$mqtt->connect()){
	exit(1);
}
$topics['/garage'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){

}

$mqtt->close();
function procmsg($topic,$msg){
	echo "Msg Recieved: ".date("r")."\nTopic:{$topic}\n$msg\n";
}

?>
