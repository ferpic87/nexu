<?php

	/**
	 * Elgg Poll plugin
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @Original author John Mellberg
	 * website http://www.syslogicinc.com
	 * @Modified By Team Webgalli to work with ElggV1.5
	 * www.webgalli.com or www.m4medicine.com
	 */
	 

	if (isset($vars['entity']))
	{
		//set up our variables
		$action = "polls/vote";
		$question = $vars['entity']->question;
		$tags = $vars['entity']->tags;
		$access_id = $vars['entity']->access_id;
	}
	else 
	{
		register_error(elgg_echo("polls:blank"));
		forward($vars['url']."pg/polls/all");
	}

	//convert $responses to radio inputs for form display
	$responses = polls_get_choice_array($vars['entity']);
	  		
	$response_inputs .= elgg_view('input/radio', array('internalname' => 'response','options' => $responses));
   	
   	$submit_input = '<br />'.elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('Vote')));

	if (isset($vars['entity'])) {
    	$entity_hidden = elgg_view('input/hidden', array('internalname' => 'pollpost', 'value' => $vars['entity']->getGUID()));
    	$entity_hidden .= elgg_view('input/hidden', array('internalname' => 'callback', 'value' => $vars['callback']));
    } else {
    	$entity_hidden = '';
    }
    
    $form_body =  "<p>" . $response_inputs . "</p>";
    $form_body .= "<p>" . $submit_input . $entity_hidden . "</p>";
    if ($vars['form_display']) {
    	echo '<div id="poll_vote_form_container" style="display:'.$vars['form_display'].'">';
    } else {
    	echo  '<div id="poll_vote_form_container">';
    } 
    echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body,'internalid'=>'poll_vote_form'));
    echo '</div>';

    if (!$vars['full']) {
?>
<script>
// need this here to rebind form
$(document).ready(function() { 
    	// bind form and provide a callback function 
		$('#poll_vote_form').ajaxForm(function(response) {
        	$('#poll_widget_container').html(response);
        });
});
</script>
<?
    }
?>
