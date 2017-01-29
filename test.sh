bishbosh_server='192.168.1.214'
bishbosh_clientId=12
bishbosh_connection_handler_CONNACK()
{
    # Set up some subscriptions... another implementation could read from a standard file
    bishbosh_subscribe '/test' 0

    bishbosh_unsubscribe \
        '/topic/not/wanted' \
        '/and/also/topic/not/wanted'

    # Publish a QoS 0 message
    # On topic a/b
    # Unretained
    # With value 'X'
    #bishbosh_publishText 0 'a/b' no 'X'
}

bishbosh_connection_handler_PUBLISH()
{
    #echo "Message received: retain=$retain, QoS=$QoS, dup=$dup, topicLength=$topicLength, topicName=$topicName, messageLength=$messageLength, messageFilePath=$messageFilePath" > ~/test.txt
for i in 16 17 18 19 20 21 22 23;
do
       sudo sh -c "echo 0 > /sys/class/gpio/gpio10$i/value"
       echo $i;
done;
for i in 16 17 18 19 20 21 22 23;
do
	sudo sh -c "echo 1 > /sys/class/gpio/gpio10$i/value"
	echo $i;
done;
}
#bishbosh_connection_handler_noControlPacketsRead()
#{
    # This event happens every few milliseconds - use this to publish some messages, change subscriptions or reload our configuration. Perhaps we could monitor a folder path?
    #bishbosh_publishText 0 '/test' no 'hello world'
    #echo 'No Control Packages Read' 1>&2
#}
