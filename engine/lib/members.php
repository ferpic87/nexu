<?php

include_once("entities.php");

function get_members($getAll = false, $source = null)  {
	$response = array();
	if($getAll == false && $source == null) {
		//$options = array('types' => 'user', 'limit' => '200', 'subtypes' => ELGG_ENTITIES_ANY_VALUE);
			//$content = elgg_get_entities($options);
		$loggedIn = get_loggedin_user();
		if(!isset($loggedIn))
			return array();
		$content = $loggedIn->getFriends("",100,0);
		
		foreach($content as $user) {
		   $var["id"] = $user->username;
		   $var["name"] = $user->name;
		   $var["avatar"] = "";
		   $var["type"] = "contact";
		   array_push($response, $var);	
		}
		$response = member_sort($response);
	} else {
		$usersInfo = elgg_get_entities(array('types'=>'user', 'limit' => false));
		if($source == null) {
			foreach($usersInfo as $user) {
				$var["id"] = $user->guid;
				$var["name"] = $user->name;
				if($var["name"]!="admin")
					array_push($response, $var);
			}
		} else {   // members for Solr indexing
			foreach($usersInfo as $user) {
				$var["id"] = $user->guid;
				$var["name"] = $user->name;
				$var["timestamp"] = $user->time_created;
				$url = $user->getURL();
				$parsedUrl = parse_url($url);
				$var["link"] = $parsedUrl["path"];
				$skills = extractMetadata($user->guid,"skills");
				$interests = extractMetadata($user->guid,"interests");
				$var["tags"] = array_merge($skills,$interests);
				$var["access_id"] = 2;
				$var["content_type"] = "user";
				array_push($response, $var);
			}
		}	
	}
	return $response;
}

function get_members_knoboos()  {
	$response = array();
	$usersInfo = elgg_get_entities(array('types'=>'user', 'limit' => false));
	
	return $response;
}

function member_sort($arr) {
	$num = count($arr);
	for($i=0; $i<$num-1; $i++)
	   for($j=$i+1; $j<$num; $j++)
		if(strcmp($arr[$i]["name"],$arr[$j]["name"]) > 0) {
		   $tempname = $arr[$i]['name'];
		   $tempusername = $arr[$i]['id'];
		   $tempavatar = $arr[$i]['avatar'];
		   $arr[$i]['name'] = $arr[$j]['name'];
		   $arr[$i]['id'] = $arr[$j]['id'];
		   $arr[$i]['avatar'] = $arr[$j]['avatar'];
		   $arr[$j]['name'] = $tempname;
		   $arr[$j]['id'] = $tempusername;     
		   $arr[$j]['avatar'] = $tempavatar;     
		}
	return $arr;
}
