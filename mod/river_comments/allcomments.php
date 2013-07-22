<?php
	/**
	* river_comments
	*
	* @author Pedro Prez
	* @link http://community.elgg.org/pg/profile/pedroprez
	* @copyright (c) Keetup 2010
	* @link http://www.keetup.com/
	* @license GNU General Public License (GPL) version 2
	*/

	// Get the Elgg engine
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	$callback = get_input('callback');
	$entity_guid = get_input('guid');
	
	if ($callback && !isloggedin()) {
		echo 'loginerror';
		exit;
	} else {
		gatekeeper();
	}
	
	
	$entity = get_entity($entity_guid);
	if (!$entity) {
		forward($_SERVER['HTTP_REFERER']);
	}
	
	$title = "";
	if ($entity instanceof ElggUser || $entity instanceof ElggGroup) {
		$title = $entity->name;
	} else {
		if ($entity instanceof ElggObject && $entity->getSubtype()=='thewire') {
			$title = $entity->description;	
		} else {
			$title = $entity->title;
		}
	}
	
	$title = sprintf(elgg_echo('river_comments:allcommentsof'), $title);
	
	$comments_count = (int) count_annotations($entity_guid, "", "", 'generic_comment');
	
	if(!$callback) {
		$content = list_annotations($entity_guid, 'generic_comment', $comments_count);
	} else {
		$comments_offset = 0;
		$comments_limit = $comments_count;
		if ($comments_count > 2) {
			$comments_limit -= 2;  
		}
		$comments = get_annotations($entity_guid, "", "", 'generic_comment', "", "", $comments_limit, $comments_offset);
		foreach($comments as $comment) {
			$owner = get_user($comment->owner_guid);
			$content .= elgg_view('river_comments/river_comment', array(
				'owner' => $owner,
				'annotation' => $comment
			));
		}
		
	}	
	
	if (!$callback) {
		$content = elgg_view_layout('two_column_left_sidebar', '', elgg_view_title($title) . $content);
		page_draw($title, $content);
	} else {
		echo $content; 
	}	