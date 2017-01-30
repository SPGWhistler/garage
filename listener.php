<?php
require("phpMQTT.php");

#Setup gpio
$door = 1016;
$closedSensor = 1023;
shell_exec("sudo sh -c 'echo $door > /sys/class/gpio/export'");
shell_exec("sudo sh -c 'echo out > /sys/class/gpio/gpio$door/direction'");
shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio$door/value'");
shell_exec("sudo sh -c 'echo $closedSensor > /sys/class/gpio/export'");
shell_exec("sudo sh -c 'echo out > /sys/class/gpio/gpio$closedSensor/direction'");
shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio$closedSensor/value'");

$mqtt = new phpMQTT("192.168.1.214", 1883, "garageDoor");

if(!$mqtt->connect()){
	exit(1);
}

$topics['/garage/door/command'] = array(
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
		$value = shell_exec("cat /sys/class/gpio/gpio$closedSensor/value");
		$mqtt->publish("/garage/door/status", $value, 0);
	} else {
		shell_exec("sudo sh -c 'echo 0 > /sys/class/gpio/gpio$door/value'");
		shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio$door/value'");
	}
}
?>
