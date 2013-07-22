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

	//River comments js
?>
	<script type="text/javascript">

		function rcPrepareItems() {
			//Hide all the buttons submit
			$('.collapsable_box_content .comment_box_submit').hide();
			
			//Add events
			$('.comment_add_box textarea').unbind('click');
			$('.comment_add_box textarea').click(function(){ readyToWrite(this) });
			$('.comment_add_box textarea').unbind('blur');
			$('.comment_add_box textarea').blur(function() { blurTextArea(this) });
			$('.jump_to_comment').unbind('click');
			$('.jump_to_comment').click(function(e){
				e.stopPropagation();
				jumpToComment(this)
				return false;	 
			});
			
<?php
			if (get_plugin_setting('enable_ajaxsupport', 'river_comments') != 'no') {
?>
				$('.comment_reduced a').unbind('click');
				$('.comment_reduced a').click(function(e){
					e.stopPropagation();
					getMoreComments(this);
					return false;
				})
<?php
			}			
?>			
			//The user can post comment via ajax
			$('.comment_box_submit input').unbind('click');
			$('.comment_box_submit input').click(function(e){
				e.stopPropagation();
				postComment(this);
				return false;
			})
		}

		function jumpToComment(oObject) {
			oParent = $(oObject).parents('.river_item');
			oParent.find('.comment_add_box textarea').click().focus();
		}

		function blurTextArea(oObject) {
			oObject = $(oObject);
			if (oObject.val() ==  "") {
				//Close if one textarea is open
				oObjectToClose = $('.comment_add_box textarea.current');
				oObjectToClose.removeClass('current');
				oObjectToClose.parent().find('.comment_icon').hide();
				oObjectToClose.parent().find('.comment_box_submit').hide();
				oObjectToClose.val("<?php echo elgg_echo('river_comments:writeacomment'); ?>");
			}	
		}

		function readyToWrite(oObject) {
			oObject = $(oObject);
			if (oObject.val() ==  "<?php echo elgg_echo('river_comments:writeacomment'); ?>") {
				oObject.val("");
			}
			oObject.addClass('current');
			//Show the user photo
			oObject.parent().find('.comment_icon').show();
			oObject.parent().find('.comment_box_submit').show();
			//Add elastic support
			$(oObject).elastic();
		}

		function getMoreComments(oObject) {
			oObject = $(oObject);
			oParent = oObject.parent();
			oMaster = oObject.parents('.feed_comments');
			oTextArea = oMaster.find('.comment_add_box textarea');
			oParent.addClass('river_comment_loading');
			//Prepare for delete...when the comments are loaded
			oParent.parent().parent().addClass('to_remove');
			//Delete all the warning
			oMaster.find('.river_error').remove();
			//Get the content via ajax.
			$.get(oObject.attr('href') + '&callback=1', function(data){
			   if(data != 'loginerror' && data.length > 0) {
				   oParent.parents('.feed_comments').prepend(data);
				   oParent.parents('.feed_comments').find('.to_remove').remove();
				} else {
					oTextArea.after("<span class='river_error'><?php echo elgg_echo('river_comments:notloginerror')?></span>");
				} 
			 });
		}

		function getMoreCommentsViaWidgets(oObject){
			rcPrepareItems(); 
			getMoreComments(oObject); 
			return false;
		}

		function postComment(oObject) {
			oObject = $(oObject);
			oMaster = oObject.parents('.feed_comments');
			oTextArea = oMaster.find('.comment_add_box textarea');
			oHiddenGuid = oMaster.find('input:hidden[name=guid]');
			endpoint = "<?php echo $vars['url'] ?>mod/river_comments/endpoint/comment.php";
			dataPost = new Object();
			dataPost.river_comment_text = oTextArea.val();
			dataPost.guid = oHiddenGuid.val();
			//Disable the botton
			oTextArea.attr('disabled', 'disabled');
			oObject.attr('disabled', 'disabled');
			//Delete all the warning
			oMaster.find('.river_error').remove();
			$.post(endpoint, dataPost, function(data){
				if (data.length > 0) {
					if(data != 'loginerror') {
						oMaster.find('.comment_add_box').before(data);
					} else {
						oTextArea.after("<span class='river_error'><?php echo elgg_echo('river_comments:notloginerror')?></span>");
					} 
				}
				//Reset the textarea for write comments
				oTextArea.val("<?php echo elgg_echo('river_comments:writeacomment'); ?>");
				oObject.removeAttr('disabled');
				oTextArea.removeAttr('disabled');
			});
		}

		$(document).ready(function(){
			rcPrepareItems();
		})
	</script>