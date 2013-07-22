<?php
/**
 * Elgg live_notifications plugin
 * 
 */
require_once(dirname(__FILE__) . '/lib/functions.php');

elgg_register_event_handler('init', 'system', 'live_notifications');

function live_notifications() {

	expose_function("notifications", "get_notifications", array(), 'A method that returns all the notifications for the user', 'GET', false, true);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('live_notifications', 'live_notifications_page_handler');

	elgg_register_event_handler('pagesetup', 'system', 'live_notifications_notifier');

	elgg_register_event_handler('pagesetup', 'system', 'live_notifications_plugin_pagesetup');

	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg', 'live_notifications/css');

	if (elgg_is_logged_in()){
		elgg_extend_view('page/elements/topbar', 'live_notifications/floatlist');
    	elgg_extend_view('js/elgg', 'live_notifications/js/live_notifications.init.js');
	}


    elgg_register_entity_type('object', 'notification');

    //disable message notifications site, 
    //Comment this line if you want to receive also default site notifications
    unregister_notification_handler("site");

    //Cron to remove old notifications two week ago
    elgg_register_plugin_hook_handler('cron', 'daily', 'live_notifications_cron');

    //Extend add_to_river function for catch a event and create notification
    elgg_register_plugin_hook_handler('creating', 'river', 'catch_add_to_river_event');
    elgg_register_plugin_hook_handler("action", "likes/add", "likes_notification_action");
    //Actions
    $actions_base = elgg_get_plugins_path() . 'live_notifications/actions';
    elgg_register_action('live_notifications/refresh_count', "$actions_base/refresh_count.php");
    elgg_register_action('live_notifications/read_all', "$actions_base/read_all.php");
    elgg_register_action('live_notifications/delete', "$actions_base/delete.php");
    elgg_register_action('live_notifications/delete_all', "$actions_base/delete_all.php");
}

function live_notifications_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$live_notifications_dir = elgg_get_plugins_path() . 'live_notifications/pages';

	$page_type = $page[0];
	switch ($page_type) {
		case 'all':			
			include "$live_notifications_dir/all.php";
			break;
		case 'ajax':			
			include "$live_notifications_dir/ajax.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Live Notifications settings sidebar menu
 *
 */
function live_notifications_plugin_pagesetup() {
	if (elgg_in_context("settings") && elgg_get_logged_in_user_guid()) {

		$user = elgg_get_page_owner_entity();
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}

		$params = array(
			'name' => '2_a_user_live_notification',
			'text' => elgg_echo('live_notifications:all'),
			'href' => "live_notifications/all",
			'section' => "notifications",
		);
		elgg_register_menu_item('page', $params);		
	}
}

//Set notifier icon with auto update
function live_notifications_notifier() {
	global $CONFIG;
	if (elgg_is_logged_in()) {
		$user_guid = elgg_get_logged_in_user_guid();
		$tooltip = elgg_echo("live_notifications");
		//get unread messages
		$num_notifications = count_unread_notifications(25);

		$text = elgg_view('live_notifications/topbar_icon',array('num_notifications'=>$num_notifications));

		elgg_register_menu_item('topbar', array(
			'name' => 'notification',
			'href' => '#live_notifications',
			'text' => $text,
			'priority' => 600,
			'title' => $tooltip,
			'id' => 'live_notifications_link'
		));
	}
}

//Save Notification, call this function to add new notification from a action or event plugin
function add_new_notification($to_guid, $from_guid, $type, $entity_guid, $description) {
    $ia = elgg_set_ignore_access(true);
    $notify = new ElggObject();
    $notify->subtype = "notification";
    $notify->access_id = ACCESS_PRIVATE;
    //User notification
    $notify->owner_guid = $to_guid;
    $notify->read = 0;
    $notify->action_type = $entity_type;
    $notify->entity_guid = $entity_guid;
    //who took the action: user
    $notify->from_guid = $from_guid;
    //Message
    $notify->description = $description;

    if($notify->save()){
    	$res = $notify->guid; 
    }
    else{
    	$res = NULL;
    }
	elgg_set_ignore_access($ia);

	return $res;
}

function catch_add_to_river_event($hook, $type, $returnvalue, $params){
	$type = $returnvalue["subtype"]; //Subtype object: blog, thewire, bookmark,etc..
	$action_type = $returnvalue["action_type"]; //Type of action: create, update, comment
	$entity_guid = $returnvalue["object_guid"];	//Guid of object entity
	$entity = get_entity($entity_guid);
	$to_guid = $entity->owner_guid;//Entity creator to notify
	$to_entity = get_entity($to_guid);
	$from_entity = elgg_get_logged_in_user_entity();

	//In case of $action_type is "comment" get the annotation 
	$annotation = elgg_get_annotation_from_id($returnvalue["annotation_id"]);

	create_message_for_entity($to_entity, $from_entity, $type, $action_type, $entity, $annotation);

	return true;
}

function likes_notification_action($hook, $entity_type, $returnvalue, $params){
	if (!elgg_annotation_exists($entity_guid, 'likes')) {
		$entity_guid = get_input('guid');
		$entity = get_entity($entity_guid);
		
		$to_guid = $entity->owner_guid;	
		$from_entity = elgg_get_logged_in_user_entity();
		$from_guid = $from_entity->guid;

		if($to_guid!=$from_guid){
			$url_user = elgg_view('output/url', array(
						'href' => $from_entity->getURL(),
						'text' => $from_entity->name,
						'class' => 'elgg-river-subject',
					));		
			$description =  elgg_echo('live_notifications:like', array($url_user));
			
			if($entity->getSubtype()=='thewire')
				$description .= '<a href="'.$entity->getUrl().'" title="">'.elgg_get_excerpt($entity->description,60).'</a>';
			else
				$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a>';

			add_new_notification($to_guid, $from_guid, 'like', $entity_guid, $description);		
			
		}	
	}
	return true;
}


function create_message_for_entity($to_entity, $from_entity, $type, $action_type, $entity, $annotation=NULL){
	global $CONFIG;
	if($action_type=='comment'){
		analize_thread_comments($entity, $annotation, $from_entity);
		if($from_entity->guid!=$entity->owner_guid){
			$url_user = elgg_view('output/url', array(
					'href' => $from_entity->getURL(),
					'text' => $from_entity->name,
					'class' => 'elgg-river-subject',
				));
			$description =  elgg_echo('live_notifications:comments:create', array($url_user, $to_entity->name));
			$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a> <br/>';
			$description .= '<i>'.elgg_get_excerpt($annotation->value,50).'</i>';
			add_new_notification($to_entity->guid, $from_entity->guid, 'comment', $entity->guid, $description);			
		}
	}	

	//Notify user when someone reply thewire post
	if($entity->getSubtype()=='thewire' && $entity->wire_thread != $entity->guid){
		$url_user = elgg_view('output/url', array(
					'href' => $from_entity->getURL(),
					'text' => $from_entity->name,
					'class' => 'elgg-river-subject',
				));
		$thread = get_entity($entity->wire_thread);
		if($thread->owner_guid!=$from_entity->guid){
			$description =  elgg_echo('live_notifications:thewire:reply', array($url_user));		
			$description .= '<a href="'.$CONFIG->wwwroot.'thewire/thread/'.$thread->guid.'" title="">'.elgg_get_excerpt($entity->description,60).'</a>';
//			add_new_notification($thread->owner_guid, $from_entity->guid, 'thewire', $entity->guid, $description);	
		}
	}

	$container = get_entity($entity->container_guid);

	if(elgg_instanceof($container, 'group')){

		if($action_type=='create'){
			$url_user = elgg_view('output/url', array(
					'href' => $from_entity->getURL(),
					'text' => $from_entity->name,
					'class' => 'elgg-river-subject',
				)); 
			$url_group = elgg_view('output/url', array(
					'href' => $container->getURL(),
					'text' => $container->name,
					'class' => 'elgg-river-subject',
				));
			$description =  elgg_echo('live_notifications:group:create:'.$type, array($url_user, $url_group));
			$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a>';

			$members = $container->getMembers();
			foreach ($members as $member) {
				# Notify to all members
				if($from_entity->guid!=$member->guid){
					add_new_notification($member->guid, $from_entity->guid, $type, $entity->guid, $description);					
				}
			}
		}

		if($action_type=='reply'){
			$url_user = elgg_view('output/url', array(
					'href' => $from_entity->getURL(),
					'text' => $from_entity->name,
					'class' => 'elgg-river-subject',
				)); 
			$url_group = elgg_view('output/url', array(
					'href' => $container->getURL(),
					'text' => $container->name,
					'class' => 'elgg-river-subject',
				));
			
			if($from_entity->guid!=$entity->owner_guid){
				$description =  elgg_echo('live_notifications:group:discussion:replyowner', array($url_user, $url_group));
				$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a>';
				add_new_notification($entity->owner_guid, $from_entity->guid, $type, $entity->guid, $description);
			}

			$members = $container->getMembers();
			foreach ($members as $member) {
				$description =  elgg_echo('live_notifications:group:discussion:reply', array($url_user, $url_group));
				$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a>';
				# Notify to all members
				if($from_entity->guid!=$member->guid && $member->guid!=$entity->owner_guid){
					add_new_notification($member->guid, $from_entity->guid, $type, $entity->guid, $description);					
				}
			}
			
		}
	}
}

function analize_thread_comments($entity, $annotation, $from_entity){
	$comments = elgg_get_annotations(array(
		'guid' => $entity->getGUID(),
		'annotation_name' => 'generic_comment',
		'limit' => 25,
		'order_by' => 'n_table.time_created desc'
	));
	
	$autors_comments = array();	

    if ($comments) {       
        foreach ($comments as $comment) {
        	$owner_guid = $comment->owner_guid;                
            if($owner_guid!=$from_entity->guid && $owner_guid!=$entity->owner_guid && !in_array($owner_guid, $autors_comments)){
            	$url_user = elgg_view('output/url', array(
					'href' => $from_entity->getURL(),
					'text' => $from_entity->name,
					'class' => 'elgg-river-subject',
				));            	
            	$description =  elgg_echo('live_notifications:comments:thread', array($url_user, $entity->name));
				$description .= '<a href="'.$entity->getUrl().'" title="">'.$entity->title.'</a> <br/>';
				$description .= '<i>'.elgg_get_excerpt($annotation->value,50).'</i>';

				add_new_notification($owner_guid, $from_entity->guid, 'comment', $entity->getGUID(), $description);				            		
            	$autors_comments[] = $owner_guid;
            }            
        }
    }
}

function get_last_notifications($top=25){

	$user_guid = elgg_get_logged_in_user_guid();
	if($user_guid){
	    $params = array(
	        'types' => 'object',
	        'subtype' => 'notification',
	        'owner_guid' => $user_guid,
	        'limit' => $top,
	    );

	    $objects = elgg_get_entities_from_metadata($params);
    	return $objects;
	}

}


function count_unread_notifications($top=25){
	$user_guid = elgg_get_logged_in_user_guid();
	
    $params = array(
        'types' => 'object',
        'subtype' => 'notification',
        'owner_guid' => $user_guid,
        'metadata_names' => 'read',
        'metadata_values' => 0,
        'limit' => $top,
        'count' => TRUE
    );

    $result = elgg_get_entities_from_metadata($params);    	
    
	return $result;
}

function get_unread_notifications($top=25){
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


/**
 * Remove old notifications
 */
function live_notifications_cron($hook, $entity_type, $returnvalue, $params) {
	// two week ago
	$time = time() - 60 * 60 * 24 * 14;

	$options = array(
		'types' => 'object',
		'subtype' => 'notification',
		'wheres' => array("e.time_created < $time"),
		'limit' => false
	);

	$ia = elgg_set_ignore_access(true);
	$notifications = elgg_get_entities_from_metadata($options);

	foreach ($notifications as $notification) {
		$notification->delete();
	}

	elgg_set_ignore_access($ia);
}
