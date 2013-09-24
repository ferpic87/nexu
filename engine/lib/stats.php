<?php

include_once("entities.php");

function get_stats($type, $month = 0, $perTool = 0, $interactionType = 0)  {
	$response = array();
	if($type == "usage_frequency") {
		$subtypes = array('blog','thewire','bookmarks','file','groupforumtopic');
		
		if($perTool == true) {
			$aliases = array('blog','status_update','bookmark','file','discussion');
		
			$count = count($subtypes);	
			for($i=0; $i<$count; $i++) {
				$arrResponse = doQueryGetGuidsAndNames($type, $month, $interactionType, array($subtypes[$i]), $aliases[$i]);
				//error_log("responseTemp:".var_export($arrResponse,true));	
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
			$response = doQueryGetNames($type, $month, $interactionType, $subtypes, "value");
			//error_log("response:".var_export($response,true));
		}
	}
	return $response;
}

function doQuery($toGet, $type, $month, $interactionType, $subtypes, $alias) {
	$eventTypeClause = "log.event = 'create' or log.event = 'annotate'";
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
	
	$query = "select $toGet,count(*) as $alias from (elgg_system_log log join elgg_users_entity user on (log.performed_by_guid = user.guid)) where log.object_type = 'object' and ($subtypeClause) and user.name <> 'admin' and ($monthClause) and ($eventTypeClause) group by name order by $alias desc";
	
	$response = get_data($query);
	return $response;
}

function doQueryGetGuids($type, $month, $interactionType, $subtypes, $alias) {
	return doQuery("user.guid as guid", $type, $month, $interactionType, $subtypes, $alias);
}

function doQueryGetNames($type, $month, $interactionType, $subtypes, $alias) {
	$toReturn = array();
	$response = doQueryGetGuidsAndNames($type, $month, $interactionType, $subtypes, $alias);
	$temp = array("name" => 0, "value" => 0);
	foreach($response as $item) {
		$toReturn[$item->guid] = $temp;
		$toReturn[$item->guid]["name"] = $item->name;
		$toReturn[$item->guid]["value"] = $item->value;
	}
	return $toReturn;
}

function doQueryGetGuidsAndNames($type, $month, $interactionType, $subtypes, $alias) {
	return doQuery("user.guid as guid, user.name as name", $type, $month, $interactionType, $subtypes, $alias);
}
