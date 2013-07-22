<?php
gatekeeper();

$user = elgg_get_logged_in_user_entity();

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('newsfeed');

$composer = elgg_view('page/elements/composer', array('entity' => $user));


$db_prefix = elgg_get_config('dbprefix');
$friendConnections = elgg_get_river(array(
	'joins' => array("JOIN {$db_prefix}entities object ON object.guid = rv.object_guid"),
	'wheres' => array("
		(rv.subject_guid = $user->guid
		OR rv.subject_guid IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend'))
		AND rv.action_type = 'friend'
		AND rv.object_guid NOT IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend')
		AND rv.object_guid <> $user->guid
	"),
));

$friendsSummary = "<a href='".elgg_get_site_url()."user_connections'>I tuoi amici stanno seguendo persone che potrebbero interessarti</a>";

$activity = elgg_list_river(array(
	'joins' => array("JOIN {$db_prefix}entities object ON object.guid = rv.object_guid"),
	/*'wheres' => array("
		rv.subject_guid = $user->guid
		OR rv.subject_guid IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend')
		OR rv.subject_guid IN (SELECT guid_one FROM {$db_prefix}entity_relationships WHERE guid_two=$user->guid AND relationship='friend')
	"),*/
	'wheres' => array("
		(rv.subject_guid = $user->guid
		OR rv.subject_guid IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend'))
		AND rv.action_type <> 'friend'
	"),
));

elgg_set_page_owner_guid(1);
$content = elgg_view_layout('two_sidebar', array(
	'title' => $title,
	'content' => $composer . $friendsSummary.$activity,
));

echo elgg_view_page($title, $content);
