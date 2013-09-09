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
	
	$list = elgg_get_metadata(array(
		'guid' => $id,
		'limit' => 0,
		'metadata_names' => array($what),
	));
		
	if(count($list)>0) {
		if(count($list) != 1) {
			foreach ($list as $field) {
				array_push($toReturn, $field['value']);
			}
		} else
			array_push($toReturn, $list['value']);
	}
	return $toReturn;
}
