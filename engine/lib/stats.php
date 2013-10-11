<?php

include_once("entities.php");

function get_stats($type, $timeStart,$timeEnd, $perTool = 0, $interactionType, $tool = 0)  {
	$response = array();
	
	error_log("type ".$type." timeStart ".$timeStart." timeEnd ".$timeEnd."  perTool ".$perTool." interactionType ".$interactionType." tool ".$tool);
	
//	$month = $temp[0];
//	$year = $temp[1];
	
	if($tool == "") {
		$subtypes = array('blog','thewire','bookmarks','file','groupforumtopic');
	} else {
		$subtypes = array($tool);
	}
	
	if($perTool == true) {
		$aliases = array('blog','status_update','bookmark','file','discussion');
	
		$count = count($subtypes);	
		for($i=0; $i<$count; $i++) {
			$arrResponse = doQuery($type, $timeStart,$timeEnd, $interactionType, array($subtypes[$i]), $aliases[$i]);
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
		$respQuery = doQuery($type, $timeStart,$timeEnd, $interactionType, $subtypes, "value");
		$temp = array("name" => 0, "value" => 0);
		foreach($respQuery as $item) {
			$response[$item->guid] = $temp;
			$response[$item->guid]["name"] = $item->name;
			$response[$item->guid]["value"] = $item->value;
		}
	}
	return $response;
}

function doQuery($type, $timeStart,$timeEnd, $interactionType, $subtypes, $alias) {	
	if($interactionType == "creator") {
		if($type == "usage_frequency") {
			$toSelect = "user.guid as guid, user.name as name";
			$groupby = "name";
		} else if($type == "usage_frequency_app") {
			$toSelect = "log.id as guid, object_subtype as name";
			$groupby = "object_subtype";	
		}
		return getContentCreated($toSelect, $groupby, $type, $timeStart,$timeEnd, $interactionType, $subtypes, $alias);
	} else if($interactionType == "engaged") {
		
		if($type == "usage_frequency") {
			$toSelect = "user.guid as guid, user.name as name";
			$groupby = "name";
		} else if($type == "usage_frequency_app") {
			$toSelect = "sub.id as guid, sub.subtype as name";
			$groupby = "sub.subtype";	
		}
		return getNumberOfInteractions($toSelect, $groupby, $type, $timeStart,$timeEnd, $subtypes, $alias, "all");
	}
}


function getContentCreated($fieldToSelect, $groupby, $type, $timeStart,$timeEnd, $interactionType, $subtypes, $alias) {
	$timeClause = "true";

	$subtypeClause = "(log.object_subtype = '".$subtypes[0]."')";
	$subtypeCount = count($subtypes);
	for($i=1; $i < $subtypeCount; $i++)
		$subtypeClause .= " or (log.object_subtype = '".$subtypes[$i]."')";
			
	if($interactionType == "creator")
		$eventTypeClause = "log.event = 'create'";
	else if($interactionType == "engaged")
		$eventTypeClause = "log.event = 'annotate'";

	if($timeStart!= 0 && $timeEnd !=0) {
		//$timeClause = "log.time_created >= '".$timeStart."' AND log.time_created <='".$timeEnd."'"; 
		$timeClause = "log.time_created BETWEEN '".$timeStart."' AND '".$timeEnd."'"; 
	}
	if($timeStart!= 0 && $timeEnd ==0) {
		//$timeClause = "log.time_created >= '".$timeStart."' AND log.time_created <='".$timeEnd."'"; 
		$timeClause = "log.time_created >='".$timeStart."'"; 
	}

	
	$query = "select $fieldToSelect,count(*) as $alias from elgg_system_log log join elgg_users_entity user on (log.performed_by_guid = user.guid) join elgg_objects_entity obj on (log.object_id = obj.guid) where log.time_created >= 1372636800 and log.object_type = 'object' and ($subtypeClause) and user.name <> 'admin' and ($timeClause) and log.event = 'create' group by $groupby order by $alias desc";
	
	$response = get_data($query);
	error_log("q=".$query);
	return $response; 
}

function getNumberOfInteractions($fieldToSelect, $groupby, $type, $timeStart,$timeEnd, $subtypes, $alias, $what) {
	$timeClause = "true";
	
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
	
	if($timeStart!= 0 && $timeEnd !=0) {
		//$timeClause = "month(from_unixtime(ann.time_created)) = '".$month."'";
		//$timeClause = "log.time_created >= '".$timeStart."' AND log.time_created <='".$timeEnd."'"; 
		$timeClause = "ann.time_created BETWEEN '".$timeStart."' AND '".$timeEnd."'"; 
	}
		if($timeStart!= 0 && $timeEnd ==0) {
		//$timeClause = "log.time_created >= '".$timeStart."' AND log.time_created <='".$timeEnd."'"; 
		$timeClause = "ann.time_created >='".$timeStart."'"; 
	}

	
	$query = "SELECT $fieldToSelect, count(*) as $alias FROM elgg_annotations ann JOIN elgg_users_entity user ON ( ann.owner_guid = user.guid ) JOIN elgg_entities ent ON ( ann.entity_guid = ent.guid ) JOIN elgg_entity_subtypes sub ON ( ent.subtype = sub.id) WHERE  ann.time_created >= 1372636800 and ($interactionClause) and ($subtypeClause) and $timeClause group by $groupby order by $alias desc";
	$response = get_data($query);
	error_log("q=".$query);
	return $response;
}