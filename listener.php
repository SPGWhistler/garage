<?php
require("phpMQTT.php");

#Setup gpio
for ($i = 16; $i <= 23; $i++) {
	shell_exec("sudo sh -c 'echo 10$i > /sys/class/gpio/export'");
	shell_exec("sudo sh -c 'echo out > /sys/class/gpio/gpio10$i/direction'");
	shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio10$i/value'");
}

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
		for ($i = 16; $i <= 23; $i++) {
			$value = shell_exec("cat /sys/class/gpio/gpio10$i/value");
			$mqtt->publish("/garage/door/status", $value, 0);
			echo $i ."\n";
			sleep(3);
		}
	} else {
		for ($i = 16; $i <= 23; $i++) {
			shell_exec("sudo sh -c 'echo 1 > /sys/class/gpio/gpio10$i/value'");
			shell_exec("sudo sh -c 'echo 0 > /sys/class/gpio/gpio10$i/value'");
			echo $i ."\n";
			sleep(3);
		}
	}
}
?>
