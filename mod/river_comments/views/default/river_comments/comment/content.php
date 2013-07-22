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
	//Content comment view
?>
	<div class="comment_content">
			<div class="comment_text">
				<a href="<?php echo $vars['owner']->getURL() ?>" class="comment_author"><?php echo $vars['owner']->name ?></a>
				<div class="comment_actual_text">
<?php 
					echo $vars['annotation']->value;
?>
				</div><!-- comment_actual_text -->
			</div><!-- comment_text -->
			<div class="comment_actions">
<?php 
				echo friendly_time($vars['annotation']->time_created);

				// if the user looking at the comment can edit, show the delete link
				if ($vars['annotation']->canEdit()) {
?>
				 · 
<?php
					echo elgg_view("output/confirmlink",array(
						'href' => $vars['url'] . "action/uncomment?annotation_id=" . $vars['annotation']->id,
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm'),
						'class' => 'delete_comment',
					));
				} //end of can edit if statement
				
?>
			</div><!-- comment_actions -->
		</div><!-- comment_content -->
