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
