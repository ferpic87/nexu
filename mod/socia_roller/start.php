<?php

/**
 * SociaRoller
 *
 * @package SociaRoller
 *
 * Author:  Shane Barron - SocialApparatus
 * Website:  http://socia.us
 * 
 */
elgg_register_event_handler('init', 'system', 'socia_roller_init');

function socia_roller_init() {
    elgg_register_js('socia_roller_functions', $CONFIG->url . 'mod/socia_roller/js/functions.js', 'head');
    elgg_register_library('elgg_roller_functions', elgg_get_plugins_path() . 'socia_roller/lib/functions.php');
    elgg_load_library('elgg_roller_functions');
    elgg_register_css('elgg_roller', $CONFIG->url . 'mod/socia_roller/css/socia_roller.css');
    elgg_register_js('jscolor', $CONFIG->url . 'mod/socia_roller/js/jscolor/jscolor.js');
    elgg_extend_view('page/elements/header', 'socia_roller/panel');
    elgg_register_css('fonts', "http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700italic,700|Kavoon|Fruktur|Iceland|Nova+Square|Text+Me+One|Elsie:400,900|Englebert|Arbutus|Wendy+One|Libre+Baskerville:400,400italic,700|Quintessential|Fjalla+One|Racing+Sans+One|Galindo|Bonbon|Mr+Dafoe|Diplomata+SC|Snowburst+One|Domine:400,700");

    elgg_extend_view('css/elgg', 'socia_roller/css');
}



elgg_register_action("socia_roller/save", elgg_get_plugins_path() . "socia_roller/actions/socia_roller/save.php","public");
elgg_register_action("socia_roller/reset", elgg_get_plugins_path() . "socia_roller/actions/socia_roller/reset.php","public");
?>
