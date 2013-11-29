<?php

include_once("stomp-php/Stomp.php");

function add_to_queue($id, $subject_guid, $action, $action_type = null, $annotation_id = null) {
	$con = new Stomp("tcp://10.24.5.215:61613");
	echo "New Stomp\n";
    // connect
	$messages = getLocalQueue();
	
	$info = array();
	$tagsString = "";
	if($action == "ADD" || $action == "UPDATE") {
		$info = extract_info_for_indexing($id, $action_type, $subject_guid, $annotation_id);
		foreach($info["tags"] as $tag) 
			$tagsString .= "<array_item>".$tag."</array_item>";
	} else {
		$info["id"] = $id;
	}
	//error_log("info:".var_export($info,true));
	$array_item = "<array_item><id>".$info["id"]."</id><author>".$info["subject_guid"]."</author><author_name>".$info["subject_name"]."</author_name><access_id>".$info["access_id"]."</access_id><timestamp>".$info["timestamp"]."</timestamp><name>".$info["title"]."</name><content>".$info["content"]."</content><tags>".$tagsString."</tags><link>".$info["link"]."</link><content_type>".$info["content_type"]."</content_type></array_item>";
	$message = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><elgg><action>".$action."</action> <object>".$array_item."</object></elgg>";
	
	$messages[] = $message; 
	
	
    try {
		$con->connect("user", "admin");
		foreach($messages as $mess) {
			// echo "Connected\n"; 
			 
			// send a message to the queue
			$con->send("jms.queue.documentqueue", $mess);
			//echo "Sent message with body 'test'\n";
			
			// disconnect
		}
		$con->disconnect();
	} catch (Exception $e) {
		saveLocally($messages);
	}
}

function saveLocally($messages) {
	$query = "insert into local_queue (message) values ";
	foreach($messages as $mess) {
		$query .= "('".addslashes($mess)."'),";
	}
	$query = substr($query, 0, -1);
	get_data($query);
}

function getLocalQueue() {
	// get content from queue ...
	$query = "select message from local_queue;";
	$messages = get_data($query);
	// ... and delete it!
	$query = "TRUNCATE TABLE local_queue;";
	get_data($query);
	$toReturn = array();
	foreach($messages as $message) {
		$toReturn[] = stripslashes($message->message);
	}
	return $toReturn;
}

function extract_info_for_indexing($id, $action_type, $subject_guid, $annotation_id) {
	$toReturn = array();
	$entity_object = get_entity($id);
	$parsedUrl = parse_url($entity_object->getURL());
	$type = $entity_object->getType();
	$subtype = $entity_object->getSubtype();
	$toReturn["content_type"] = getContentTypeMapping($type, $subtype, $action_type);
	$toReturn["subject_guid"] = $subject_guid;
	$toReturn["subject_name"] = get_entity($subject_guid)->name;
	$toReturn["timestamp"] = $entity_object->last_action;
	$toReturn["access_id"] = $entity_object->access_id;
	$toReturn["link"] = $parsedUrl["path"];
	if($toReturn["content_type"]!="comment") {
		$toReturn["id"] = $id;
		$toReturn["title"] = $entity_object->title;
		$toReturn["content"] = strip_tags($entity_object->description);
		$toReturn["tags"] = extractMetadata($id, "tags");
	} else {
		$toReturn["id"] = $id."-".$annotation_id;
		$toReturn["title"] = "Comment on '".$entity_object->title."'";
		$obj = elgg_get_annotation_from_id($annotation_id);
		$toReturn["content"] = strip_tags($obj->value);
		$toReturn["tags"] = array();
	}
	
	return $toReturn;
}