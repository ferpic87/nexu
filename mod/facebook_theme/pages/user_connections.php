<?php
gatekeeper();

$user = elgg_get_logged_in_user_entity();

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('user_connections');

$composer = elgg_view('page/elements/composer', array('entity' => $user));


$db_prefix = elgg_get_config('dbprefix');
$activity = elgg_list_river(array(
	'joins' => array("JOIN {$db_prefix}entities object ON object.guid = rv.object_guid"),
	'wheres' => array("
		(rv.subject_guid IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend'))
		AND rv.action_type = 'friend'
		AND rv.object_guid NOT IN (SELECT guid_two FROM {$db_prefix}entity_relationships WHERE guid_one=$user->guid AND relationship='friend')
		AND rv.object_guid <> $user->guid"
	),
));

elgg_set_page_owner_guid(1);
$content = elgg_view_layout('two_sidebar', array(
	'title' => $title,
	'content' => $composer . $activity,
));

echo elgg_view_page($title, $content);
