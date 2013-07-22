<?php
/**
 * Simnple Skype widget
 * This plugin allows users to chat or call using Skype
 *
 * @package Skype
 */

	elgg_register_event_handler('init', 'system', 'skype_init');
	elgg_register_js('skype', 'mod/skype/vendor/skype/jscripts/skype-uri.js');


function skype_init() {
        elgg_register_widget_type('skype', elgg_echo('skype:title'), elgg_echo('skype:info'));
}

