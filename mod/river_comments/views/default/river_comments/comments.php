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

	$item = $vars['item'];
	$object = get_entity($item->object_guid);
	if (!$object instanceof ElggEntity) {
		return false;
	}
	
	set_input('comment_action', true);
?>	
	<!-- separator -->
	<!-- itemtime -->
	<!-- river_actions -->
	<!-- separator -->
<?php	
	
	//Get the comments
	//We count the quantity of people that comment about this object.
	$comments_count = (int) count_annotations($object->getGUID(), "", "", 'generic_comment');

	
?>
	<div class="comment_box">
		<div class="feed_comments">
<?php 
			$comments_offset = 0;
			$comments_limit = $comments_count;
			if ($comments_count > 0) {
				if ($comments_count > 3) {
					
					$comments_offset = $comments_limit-2;
					$comment_reduced = sprintf(elgg_echo('river_comments:viewallcomments'), $comments_count);
					$link = "{$vars['url']}pg/river_comments/allcomments/?guid=$object->guid";
					$context = get_context();
					if ($context == 'widget') {
						$js_more = "onclick=\"getMoreCommentsViaWidgets(this); return false\"";
					}
					$comment_reduced = <<<EOT
					<div class="comment_reduced comment_reduced_icon">
						<div class='comment_view_all'>
							<a $js_more href="$link" target="_blank" title="$comment_reduced">
								$comment_reduced
							</a>
						</div>	
					</div>
EOT;
					echo elgg_view('river_comments/comment/wrapper', array(
						'body' => $comment_reduced
					));
				}
				$comments = get_annotations($object->getGUID(), "", "", 'generic_comment', "", "", $comments_limit, $comments_offset);
				foreach($comments as $comment) {
					$owner = get_user($comment->owner_guid);
					echo elgg_view('river_comments/river_comment', array(
						'owner' => $owner,
						'annotation' => $comment
					));
				}
			}
			if (isloggedin()) {
?>		
				<div class="comment_feed comment_add_box">
<?php
					//Form View header 
					echo elgg_view('input/form_header', array(
						'action' => "{$vars['url']}action/comment"
					));
					echo elgg_view('input/hidden', array(
						'internalname' => 'guid',
						'value' => $vars['item']->object_guid,  
					))
?>
						<div class="comment_icon">
<?php	
							echo elgg_view("profile/icon",array(
								'entity' => $user, 
								'size' => 'tiny',
								'override' => true)
							);
?>
						</div>
<?php 
						$context = get_context();
						if ($context == 'widget') {
							$js = "onclick=\"rcPrepareItems(); readyToWrite(this)\"";
						}
?>												
						<textarea <?php echo $js ?>name="river_comment_text"><?php echo elgg_echo('river_comments:writeacomment'); ?></textarea>
						<label class="comment_box_submit">
							<input type="submit" name="comment" value="<?php echo elgg_echo('river_comments:comment'); ?>" />
						</label>
<?php
				//Form View header 
					echo elgg_view('input/form_footer');
?>			
				</div> <!-- comment_add_box -->
<?php 
			}
?>				
		</div><!-- feed_comments -->
	</div><!-- comment_box -->