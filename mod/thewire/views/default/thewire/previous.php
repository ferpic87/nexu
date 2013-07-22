<?php
/**
 * Serve up html for a post
 */

$guid = (int) get_input('guid');

$parent = thewire_get_parent($guid);
//error_log("guid_from_wire->".$guid);
//error_log("response_from_wire:".var_export($parent,true));

if ($parent) {
	echo elgg_view_entity($parent);
}
