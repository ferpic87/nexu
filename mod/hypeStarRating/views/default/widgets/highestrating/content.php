<?php

$widget = elgg_extract('entity', $vars);

if (!$limit = $widget->num_display) {
	$limit = $widget->num_display = 5;
}
$types = $widget->type_display;

if (!$types || $types == 'all') {
	$types = array('user', 'object', 'group');
}

if ($types == 'object') {
$subtypes = $widget->subtype_display;

	if (!$subtypes || $subtypes == 'all') {
		$subtypes = null;
	}
}

if (elgg_in_context('profile') || elgg_in_context('groups')) {
	$page_owner = elgg_get_page_owner_entity();
}

if (elgg_instanceof($page_owner, 'user')) {
	$owner_guid = $page_owner->guid;
	$container_guid = null;
} else if (elgg_instanceof($page_owner, 'group')) {
	$owner_guid = null;
	$container_guid = $page_owner->guid;
} else {
	$owner_guid = null;
	$container_guid = null;
}

$options = array(
	'types' => $types,
	'subtypes' => $subtypes,
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
	'limit' => $limit,
	'annotation_name' => 'starrating',
	'calculation' => 'avg',
	'order_by' => 'annotation_calculation desc',
);

$target = "hj-starrating-$widget->guid";
$view_params = array(
	'full_view' => false,
	'list_id' => $target,
	'list_class' => 'hj-view-list',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'limit' => $limit,
);

$entities = elgg_get_entities_from_annotation_calculation($options);

elgg_push_context('starrating');
$content = elgg_view_entity_list($entities, $view_params);
elgg_pop_context();

echo $content;
