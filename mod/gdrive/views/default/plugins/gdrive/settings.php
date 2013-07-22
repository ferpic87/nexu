<?php
/**
 * Google Drive plugin settings.
 *
 * @package Google Drive
 */


?>
<div>
	<?php echo elgg_echo('gdrive:serverkey'); ?>	
	<?php
		echo elgg_view('input/text', array( 'name'  => 'params[serverkey]',   'value' => $vars['entity']->serverkey));
	?>
	<?php echo elgg_echo('gdrive:browserkey'); ?>	
	<?php
		echo elgg_view('input/text', array( 'name'  => 'params[browserkey]',   'value' => $vars['entity']->browserkey));
	?>
	<?php echo elgg_echo('gdrive:clientid'); ?>	
	<?php
		echo elgg_view('input/text', array( 'name'  => 'params[clientid]',   'value' => $vars['entity']->clientid));
	?>
	<?php echo elgg_echo('gdrive:clientsecret'); ?>	
	<?php
		echo elgg_view('input/text', array( 'name'  => 'params[clientsecret]',   'value' => $vars['entity']->clientsecret));
	?>
</div>