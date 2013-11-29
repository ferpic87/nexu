<?php
    // include a library
    require_once("Stomp.php");
	require_once("Stomp/Message/Map.php");
    // make a connection
    $con = new Stomp("tcp://10.24.5.215:61613");
	 echo "New Stomp\n";
    // connect
    $con->connect("user", "admin");
	 echo "Connected\n";
	 
    // send a message to the queue
	$message = "<?xml version='1.0' encoding='UTF-8'?><elgg><action>DELETE</action><object><array_item><id>5544</id></array_item></object></elgg>";
    $con->send("jms.queue.documentqueue", $message);
    echo "Sent message with body 'test'\n";
    // subscribe to the queue
    //$con->subscribe("/jms/queue/documentqueue");
    // receive a message from the queue
    //$msg = $con->readFrame();

    // do what you want with the message
    //if ( $msg != null) {
       // echo "Received message with body '$msg->body'\n";
        //mark the message as received in the queue
      //  $con->ack($msg);
    //} else {
      //  echo "Failed to receive a message\n";
    //}

    // disconnect
    $con->disconnect();
?>