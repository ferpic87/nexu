<?php

include_once("entities.php");

function retrieve_data($id,$what)  {

	if($id == -1 && ($what == "interests" || $what == "skills")) {
		$params = array('types' => array('user'),'limit' => 0,'count' => FALSE);

		$users = elgg_get_entities($params);
		
		$response = array();
		foreach ($users as $user) {				
			$attrs = extractMetadata($user->guid,$what);
			$elem['id'] = $user->guid;
			$elem['attrs'] = $attrs;
			array_push($response, $elem);
		}
	} else {
		$response = extractMetadata($id, $what);
	}	
	return $response;
}

function extractMetadata($id, $what) {
	$toReturn = array();
	$options = array(
		'guid' => $id,
		'limit' => 0,
		'metadata_names' => array($what),
	);
	
	$list = elgg_get_metadata($options);
		
	if(count($list)>0) {
		foreach ($list as $field) {
			array_push($toReturn, $field['value']);
		}
	}
	return $toReturn;
}

function get_authorship($guid, $timestamp = 0) {
	$response = "";
	if(is_numeric($guid) && is_numeric($timestamp)) {
		$query = "select distinct object_id,  log.object_type, log.object_subtype, log.event, obj.time_updated as timestamp from elgg_system_log log 
	join elgg_users_entity user on (log.performed_by_guid = user.guid) 
	join elgg_entities obj on (log.object_id = obj.guid) 
	where obj.time_updated >= $timestamp and log.object_type = 'object' and ((log.object_subtype = 'blog') or (log.object_subtype = 'thewire') or (log.object_subtype = 'bookmarks') or (log.object_subtype = 'file') or (log.object_subtype = 'groupforumtopic')) and user.name <> 'admin' and user.guid = $guid and (log.event = 'create')";
		$response = get_data($query);
		foreach($response as $object) {
			$attrs = extractMetadata($object->object_id, "tags");
			$object->tags = $attrs;
			$object->content_type = getContentTypeMapping($object->object_type, $object->object_subtype, $object->event);			
			unset($object->object_type);
			unset($object->object_subtype);
			unset($object->event);
		}
	}
	
	return $response;
}

function save_permutations_data($guid, $permutations) {
	$query = "insert into permutations values (\"$guid\", \"$permutations\") on duplicate key update permutations=VALUES(permutations)";
//	error_log("experiment_query:$query");
	get_data($query);
	return "Data successfully saved!";
}

function get_permutations_data() {
	$query = "select * from permutations";
//	error_log("experiment_query:$query");
	$data = get_data($query);
	return $data;
}