<?php

function hj_starrating_setup() {
    if (elgg_is_logged_in()) {
        hj_starrating_setup_rating_values();
        elgg_set_plugin_setting('hj:starrating:setup', true);
        return true;
    }
    return false;
}

function hj_starrating_setup_rating_values() {
    $options = array(
            array('score' => '1', 'desc' => 'Not so great'),
            array('score' => '2', 'desc' => 'Quite good'),
            array('score' => '3', 'desc' => 'Good'),
            array('score' => '4', 'desc' => 'Great!'),
            array('score' => '5', 'desc' => 'Excellent!'),
	);
    foreach($options as $option) {
        elgg_set_plugin_setting($option['score'], $option['desc'], 'hypeStarRating');
    }
    return true;
}
