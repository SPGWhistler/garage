<?php
require("phpMQTT.php");

#Setup gpio
shell_exec("sudo sh -c 'echo 1017 > /sys/class/gpio/export'");
shell_exec("sudo sh -c 'echo 1022 > /sys/class/gpio/export'");
shell_exec("sudo sh -c 'echo out > /sys/class/gpio/gpio1017/direction'");
shell_exec("sudo sh -c 'echo out > /sys/class/gpio/gpio1022/direction'");
shell_exec("sudo sh -c 'echo 0 > /sys/class/gpio/gpio1022/value'");

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
	global $mqtt;
	echo $msg . "\n";
	if ($msg === "get") {
		$value = shell_exec("cat /sys/class/gpio/gpio1017/value");
		$mqtt->publish("/garage", $value, 0);
	} else {
		shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio1022/value'");
		shell_exec("sudo sh -c 'echo 0 > /sys/class/gpio/gpio1022/value'");
	}
}
?>
