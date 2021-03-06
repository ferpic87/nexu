<?php
/**
 * Bootstraps the Elgg engine.
 *
 * This file loads the full Elgg engine, checks the installation
 * state, and triggers a series of events to finish booting Elgg:
 * 	- {@elgg_event boot system}
 * 	- {@elgg_event init system}
 * 	- {@elgg_event ready system}
 *
 * If Elgg is fully uninstalled, the browser will be redirected to an
 * installation page.
 *
 * @see install.php
 * @package Elgg.Core
 * @subpackage Core
 */

/*
 * No settings means a fresh install
 */
if (!file_exists(dirname(__FILE__) . '/settings.php')) {
	header("Location: install.php");
	exit;
}

/**
 * The time with microseconds when the Elgg engine was started.
 *
 * @global float
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);

/**
 * Configuration values.
 *
 * The $CONFIG global contains configuration values required
 * for running Elgg as defined in the settings.php file.
 *
 * Plugin authors are encouraged to use elgg_get_config() instead of accessing
 * the global directly.
 *
 * @see elgg_get_config()
 * @see engine/settings.php
 * @global stdClass $CONFIG
 */
global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}
$CONFIG->boot_complete = false;

$lib_dir = dirname(__FILE__) . '/lib/';

// Load the bootstrapping library
$path = $lib_dir . 'elgglib.php';
if (!include_once($path)) {
	echo "Could not load file '$path'. Please check your Elgg installation for all required files.";
	exit;
}



// Load the system settings
if (!include_once(dirname(__FILE__) . "/settings.php")) {
	$msg = 'Elgg could not load the settings file. It does not exist or there is a file permissions issue.';
	throw new InstallationException($msg);
}


// load the rest of the library files from engine/lib/
$lib_files = array(
	'access.php', 'actions.php', 'admin.php', 'annotations.php', 'cache.php',
	'calendar.php', 'configuration.php', 'cron.php', 'database.php',
	'entities.php', 'export.php', 'extender.php', 'filestore.php', 'group.php',
	'input.php', 'languages.php', 'location.php', 'mb_wrapper.php',
	'memcache.php', 'metadata.php', 'metastrings.php', 'navigation.php',
	'notification.php', 'objects.php', 'opendd.php', 'output.php',
	'pagehandler.php', 'pageowner.php', 'pam.php', 'plugins.php',
	'private_settings.php', 'relationships.php', 'river.php', 'sessions.php',
	'sites.php', 'statistics.php', 'system_log.php', 'tags.php',
	'user_settings.php', 'users.php', 'upgrade.php', 'views.php',
	'web_services.php', 'widgets.php', 'xml.php', 'xml-rpc.php',
	// backward compatibility
	'deprecated-1.7.php', 'deprecated-1.8.php',
);

foreach ($lib_files as $file) {
	$file = $lib_dir . $file;
	elgg_log("Loading $file...");
	if (!include_once($file)) {
		$msg = "Could not load $file";
		throw new InstallationException($msg);
	}
}

// Load the get_members function                                                                     
include_once($lib_dir."members.php");

// Load the retrieve_data function                                                                     
include_once($lib_dir."data_expose.php");

// Load the get_window function                                                                     
//include_once($lib_dir."activities_window.php");

// Load the get_stats function                                                                     
include_once($lib_dir."stats.php");

// Load the get_stats function                                                                     
include_once($lib_dir."queue.php");

// Load the Solr PHP Client                                                                     
include_once($lib_dir.'SolrPhpClient/Apache/Solr/Service.php' );

// Connect to database, load language files, load configuration, init session
// Plugins can't use this event because they haven't been loaded yet.
elgg_trigger_event('boot', 'system');

// Load the plugins that are active
elgg_load_plugins();

// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
// and then move this code in there.
// This validates the view type - first opportunity to do it is after plugins load.
$view_type = elgg_get_viewtype();
if (!elgg_is_valid_view_type($view_type)) {
	elgg_set_viewtype('default');
}

// @todo deprecate as plugins can use 'init', 'system' event
elgg_trigger_event('plugins_boot', 'system');

// Complete the boot process for both engine and plugins
elgg_trigger_event('init', 'system');

$CONFIG->boot_complete = true;

// System loaded and ready
elgg_trigger_event('ready', 'system');

expose_function("members", "get_members", array('getAll' => array ('type' => 'string','required' => false, 'default'=> false),'source' => array ('type' => 'string','required' => false, 'default'=> null)), 'A method that returns all the info about the members', 'GET', false, false);

expose_function("retrieve_data", "retrieve_data", array( 'guid' => array ('type' => 'string'),
                       'what_to_retrieve' => array ('type' => 'string'),
                     ), 'A method that returns the data of interest for the user guid', 'GET', false, false);

/*expose_function("get_window", "get_window", array( 'guid' => array ('type' => 'string')), 

						'A method that returns the activities window of the user', 'GET', false, false);
*/

expose_function("get_authorship", "get_authorship", array( 'guid' => array ('type' => 'string', 'required' => true), 
						'timestamp' => array('type' => 'string', 'required' => false, 'default' => '0'),
						'source' => array('type' => 'string', 'required' => false, 'default' => null),
						'get_comment' => array('type' => 'string', 'required' => false, 'default' => false)
					 ), 'A method that returns the authorship of the user', 'GET', false, false);

						
expose_function("stats", "get_stats", array( 'type' => array ('type' => 'string'),
											 'timeStart' => array ('type' => 'string','required' => false, 'default'=> 0),
											 'timeEnd' => array ('type' => 'string','required' => false, 'default'=> 0),
											 'perTool' => array ('type' => 'string','required' => false, 'default'=> 0),
											 'interactionType' => array ('type' => 'string','required' => true),
											 'tool' => array ('type' => 'string','required' => false, 'default'=> "")), 
						'A method that the stats of the community', 'GET', false, false);
expose_function("experiment", "save_permutations_data", array( 	'id' => array ('type' => 'string'),
																'permutations' => array ('type' => 'string'),
											), 
						'A method that saves the permutations for the experiment', 'POST', false, false);
						
expose_function("permutations", "get_permutations_data", array(), 
						'A method that returns the permutations for the experiment', 'GET', false, false);						
						
expose_function("access_list", "get_alist", array( 'guid' => array ('type' => 'string')), 
						'A method that returns the access list for the given user', 'GET', false, false);	
