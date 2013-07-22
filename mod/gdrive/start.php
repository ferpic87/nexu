<?php

require_once(dirname(__FILE__) . '/lib/functions.php');
$gdrive_lib = dirname(__FILE__) . '/vendor/google/';
require_once $gdrive_lib.'apiClient.php';
require_once $gdrive_lib.'contrib/apiOauth2Service.php';
require_once $gdrive_lib.'contrib/apiDriveService.php';

function gdrive_init() {        
    elgg_register_widget_type('list', 'Google Drive', 'Google Drive widget',$context='index,profile,dashboard,groups',$multiple=true);

    elgg_extend_view('css','gdrive/css');

    expose_function("gdrive.list", "gdrive_list_cached", 
                    array("path" => array('type' => 'string')), 
                    'Google GDrive List documents', 'GET', false, false);

    //Register OAuth2 Callback page handler
    elgg_register_page_handler('oauth2callback','oauth2callback');

    //Enable search on Google Docs
    register_plugin_hook('search_types', 'get_types', 'search_custom_types_gdrive_hook');
    register_plugin_hook('search', 'gdrive', 'search_gdrive_hook');

}

elgg_register_event_handler('init', 'system', 'gdrive_init');       

?>