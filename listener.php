<?php
require("phpMQTT.php");

$mqtt = new phpMQTT("192.168.1.214", 1883, "garageDoor");

if(!$mqtt->connect()){
	exit(1);
}

$topics['/garage'] = array(
	"qos" => 0,
	"function" => "procmsg"
);
$mqtt->subscribe($topics, 0);

while($mqtt->proc()){
	//Loop here so we continue running
}

$mqtt->close();

function procmsg($topic, $msg){
	echo "$msg\n";
	shell_exec("sudo sh -c 'echo 0 > /sys/class/gpio/gpio1021/value'");
	shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio1021/value'");
}
?>
