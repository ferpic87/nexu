<?php
/**
 * Body of river item
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 * @uses $vars['responses']   Alternate respones (comments, replies, etc.)
 */

$item = $vars['item'];

$responses = "";

//error_log(var_export($item,true));

// questo if � proprio scandaloso -> ma funziona
if ($item->getObjectEntity()->canComment() || elgg_instanceof($item->getObjectEntity(),'object', 'thewire')) {
	$menu = elgg_view_menu('river', array(
		'item' => $item,
		'sort_by' => 'priority',
	));
	

	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$responses = "<div class=\"elgg-river-responses\">$responses</div>";
	}

}
// river item header
$timestamp = elgg_get_friendly_time($item->getPostedTime());

$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary', array('item' => $vars['item'])));
if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
	));
}

$message = elgg_extract('message', $vars, false);
if ($message !== false) {
	$message = "<div class=\"elgg-river-message\">$message</div>";
}


$attachments = elgg_extract('attachments', $vars, false);
if ($attachments !== false) {
	$attachments = "<div class=\"elgg-river-attachments\">$attachments</div>";
}

$group_string = '';
$object = $item->getObjectEntity();
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
} else if(elgg_instanceof($object,'object', 'thewire')) {
	//$thread_id = $object->wire_thread;
	//if (!$thread_id) 
	//$group_string = var_export($object->guid." ".$object->wire_thread,true);
	if($object->wire_thread != $object->guid) {
		$thread_link = elgg_view('output/url', array(
			'href' => "thewire/thread/".$object->wire_thread,
			'text' => elgg_echo('thewire:thread'),
			'is_trusted' => true,
			'style' => 'text-transform:lowercase',
		));
		$group_string = elgg_echo('thewire:response', array($thread_link));
	}
}
$rank = $item->rank;
$debug = false;
if($debug) {
	$stampaRank = "[rank=".$rank."]";
}

echo <<<RIVER
<div class="elgg-river-summary">$summary $group_string</div>
$message
$attachments
<span class="elgg-river-timestamp">$stampaRank$timestamp</span>
$menu
$responses
RIVER;
