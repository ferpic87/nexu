<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-text mention {$vars['class']}";
} else {
	if($vars['name']=="generic_comment")
		$vars['class'] = "elgg-input-text mention";
	else
		$vars['class'] = "elgg-input-text";
	
}

$defaults = array(
	'value' => '',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

?>
<input type="text" <?php echo elgg_format_attributes($vars); ?> />
