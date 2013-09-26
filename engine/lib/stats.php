<?php

include_once("entities.php");

function get_stats($type, $month = 0, $perTool = 0, $interactionType)  {
	$response = array();
	if($type == "usage_frequency") {
		$subtypes = array('blog','thewire','bookmarks','file','groupforumtopic');
		
		if($perTool == true) {
			$aliases = array('blog','status_update','bookmark','file','discussion');
		
			$count = count($subtypes);	
			for($i=0; $i<$count; $i++) {
				$arrResponse = doQuery($type, $month, $interactionType, array($subtypes[$i]), $aliases[$i]);
				$temp = array("blog" => 0, "status_update" => 0, "bookmark" => 0, "file" => 0, "discussion" => 0);
				foreach($arrResponse as $value) {
					if(!isset($response[$value->guid])) {
						$response[$value->guid] = $temp;
						$response[$value->guid]["name"] = $value->name;
					}
					$response[$value->guid][$aliases[$i]] = $value->$aliases[$i];	
				}
			}
		} else {
			$respQuery = doQuery($type, $month, $interactionType, $subtypes, "value");
			$temp = array("name" => 0, "value" => 0);
			foreach($respQuery as $item) {
				$response[$item->guid] = $temp;
				$response[$item->guid]["name"] = $item->name;
				$response[$item->guid]["value"] = $item->value;
			}
		}
	}
	return $response;
}

function doQuery($type, $month, $interactionType, $subtypes, $alias) {
	if($interactionType == "creator")
		return getContentCreated($type, $month, $interactionType, $subtypes, $alias);
	else if($interactionType == "engaged")
		return getNumberOfInteractions($type, $month, $subtypes, $alias, "all");
	
}


function getContentCreated($type, $month, $interactionType, $subtypes, $alias) {
	$monthClause = "true";

	$subtypeClause = "(log.object_subtype = '".$subtypes[0]."')";
	$subtypeCount = count($subtypes);
	for($i=1; $i < $subtypeCount; $i++)
		$subtypeClause .= " or (log.object_subtype = '".$subtypes[$i]."')";
			
	if($interactionType == "creator")
		$eventTypeClause = "log.event = 'create'";
	else if($interactionType == "engaged")
		$eventTypeClause = "log.event = 'annotate'";

	if($month != 0) {
		$monthClause = "month(from_unixtime(log.time_created)) = '".$month."'"; 
	}
	
	$query = "select user.guid as guid, user.name as name,count(*) as $alias from elgg_system_log log join elgg_users_entity user on (log.performed_by_guid = user.guid) join elgg_objects_entity obj on (log.object_id = obj.guid) where log.object_type = 'object' and ($subtypeClause) and user.name <> 'admin' and ($monthClause) and log.event = 'create' group by name order by $alias desc";
	
	$response = get_data($query);
	error_log("q=".$query);
	return $response;
}

function getNumberOfInteractions($type, $month, $subtypes, $alias, $what) {
	$monthClause = "true";
	
	$subtypeClause = "(sub.subtype = '".$subtypes[0]."')";
	$subtypeCount = count($subtypes);
	for($i=1; $i < $subtypeCount; $i++)
		$subtypeClause .= " or (sub.subtype = '".$subtypes[$i]."')";
	
	$interactionClause = "name_id = 443 or name_id = 91";
	if($what == "likes") {
		$interactionClause = "name_id = 91";
	} else if ($what == "comments") {
		$interactionClause = "name_id = 443"; 
	}
	
	if($month != 0) {
		$monthClause = "month(from_unixtime(ann.time_created)) = '".$month."'"; 
	}
	
	$query = "SELECT user.guid as guid, user.name, count(*) as $alias FROM elgg_annotations ann JOIN elgg_users_entity user ON ( ann.owner_guid = user.guid ) JOIN elgg_entities ent ON ( ann.entity_guid = ent.guid ) JOIN elgg_entity_subtypes sub ON ( ent.subtype = sub.id) WHERE ($interactionClause) and ($subtypeClause) and $monthClause group by name order by $alias desc";
	$response = get_data($query);
	error_log("q=".$query);
	return $response;
}