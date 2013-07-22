<?php 

function rest_plugin_setup_pams() {
	// user token can also be used for user authentication
	register_pam_handler('pam_auth_usertoken');
	
	// simple API key check
	register_pam_handler('api_auth_key', "sufficient", "api");
	
	// override the default pams
	return true;
}

function get_notifications() {			
	$notifications = unread_notifications(25);

	$response = array();
	if(count($notifications)>0) {
		foreach ($notifications as $notify) 
		    array_push($response, $notify->description);
	}
	return $response;

}
				 
function unread_notifications($top=25){
    $user_guid = elgg_get_logged_in_user_guid();
    $result = NULL;
    $params = array(
        'types' => 'object',
        'subtype' => 'notification',
        'owner_guid' => $user_guid,
        'metadata_names' => 'read',
        'metadata_values' => 0,
        'limit' => $top
    );

    $data = elgg_get_entities_from_metadata($params);    	
	
    if($data)
	$result = $data;

    return $result;
}


