<?php
	if (get_context('widget')) {
		$js_action = "onclick=\"rcPrepareItems(); jumpToComment(this); return false;\"";
	}
	if (get_input('comment_action')) {
		
?>
	· <a <?php echo $js_action; ?>class='jump_to_comment' href="#"><?php echo elgg_echo('river_comments:comment')?></a>
<?php 
	}
	set_input('comment_action', false);
?>	