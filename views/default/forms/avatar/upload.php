<?php
/**
 * Avatar upload form
 * 
 * @uses $vars['entity']
 */

?>
<div>
	<label><?php echo elgg_echo("avatar:upload"); ?></label><br />
	<?php echo elgg_view("input/file",array('name' => 'avatar')); ?>
</div>
<div class="elgg-foot">
	<div id="error_file_size" style="display:none"><?php echo elgg_echo('error_file_size'); ?></div>
	<?php echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid)); ?>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('upload'),'onclick' => 'return checkFileSize();')); ?>
</div>
