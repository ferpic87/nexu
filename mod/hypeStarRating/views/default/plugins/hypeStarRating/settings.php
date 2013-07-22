<?php

echo 'Please enter description corresponding to each score. If you would like to use a scale of 1 to 5, enter values corresponding to those scores, and leave the rest blank.';
for($i=1;$i<=10;$i++) {
    echo '<div class="clearfix">';
    echo elgg_view('input/text', array(
        'value' => $i,
        'disabled' => true,
        'class' => 'hj-left',
        'maxlength' => 2,
        'size' => 2,
        'style' => 'width:auto;'
    ));
    echo elgg_view('input/text', array(
        'value' => $vars['entity']->$i,
        'name' => "params[$i]",
        'class' => 'hj-left',
        'maxlength' => 25,
        'size' => 25,
        'style' => 'width:auto;'
    ));
    echo '</div>';
}
