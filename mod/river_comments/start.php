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

	global $ASKQUESTION;

	function river_comments_init() {
		global $CONFIG;
		global $ASKQUESTION;
		
		$ASKQUESTION = false;
		
		//Page Handler
		register_page_handler('river_comments','river_comments_page_handler');
		
		//Extend css view
		extend_view('css', 'river_comments/css');
		
		//Elastic Plugin
		extend_view('page_elements/footer', 'river_comments/footer', 400);
		
		//Extend js view on riverdashboard
		extend_view('page_elements/footer', 'river_comments/js', 450);
		extend_view('riverdashboard/js', 'river_comments/riverdashboardjs');
		
		//View for river actions
		extend_view('river/item/actions', 'river_comments/item_action');
		
		//Print the plugin version
		extend_view('metatags', 'river_comments/version');
		
		if (isadminloggedin()) {
			extend_view('page_elements/header_contents', 'river_comments/question/content');
		}
		
		//Actions
		register_action("comment",false, $CONFIG->pluginspath . "/river_comments/actions/comment.php");
		register_action("uncomment",false, $CONFIG->pluginspath . "/river_comments/actions/uncomment.php");
	}
	
	function river_comments_page_handler($page) {
		global $CONFIG;
		if (isset($page[0])) {
			switch($page[0]) {
				case "admin":
					!@include_once(dirname(__FILE__) . "/admin.php");
					return false;
          			break;
          		case "allcomments":
					!@include_once(dirname(__FILE__) . "/allcomments.php");
					return false;
          			break;	
			}
		}
	}
	
	function river_comments_setup() {
		global $CONFIG;
		global $activity_widget;
		if (get_context()=='admin') {
    		add_submenu_item(elgg_echo("river_comments:admin"), $CONFIG->wwwroot . "pg/river_comments/admin" );
		}
		
//		For milestone v2		
//		extend_view('river/user/default/profileiconupdate', 'river_comments/comments');
//		extend_view('friends/river/create', 'river_comments/comments');
//		extend_view('river/sitemessage/create', 'river_comments/comments');

		$priority = 600;	
		
	
		/*
		 * Out of the box mods
		*/
		if (get_plugin_setting('show_thewire', 'river_comments') != 'no') {
			extend_view('river/object/thewire/create', 'river_comments/comments', $priority);
		}
		if (get_plugin_setting('show_blog', 'river_comments') != 'no') {
			extend_view('river/object/blog/create', 'river_comments/comments', $priority);
		}
		if (get_plugin_setting('show_page', 'river_comments') != 'no') {
			extend_view('river/object/page/create', 'river_comments/comments', $priority);
		}
		if (get_plugin_setting('show_topic', 'river_comments') != 'no') {
			extend_view('river/forum/topic/create', 'river_comments/comments', $priority);
		}
		
		/*
		 * Third party mods
		*/
		//Tidypics
		if (is_plugin_enabled('tidypics') && get_plugin_setting('show_tidypics_image', 'river_comments') != 'no') {
			extend_view('river/object/image/create', 'river_comments/comments', $priority);
		}
		if (is_plugin_enabled('tidypics') && get_plugin_setting('show_tidypics_album', 'river_comments') != 'no') {
			extend_view('river/object/album/create', 'river_comments/comments', $priority);
		}
		//iZap Videos
		if (is_plugin_enabled('izap_videos') && get_plugin_setting('show_izap_videos', 'river_comments') != 'no') {
			extend_view('river/object/izap_videos/create', 'river_comments/comments', $priority);
		}
		//Event Calendar
		if (is_plugin_enabled('event_calendar') && get_plugin_setting('show_event_calendar', 'river_comments') != 'no') {
			extend_view('river/object/event_calendar/create', 'river_comments/comments', $priority);
		}
		
	}
	
	//Generate url for accept action on elgg 1.7
	if(!is_callable('url_compatible_mode')) {
	    function url_compatible_mode($hook = '?') {
	    	$now = time();
			$query[] = "__elgg_ts=" . $now;
			$query[] = "__elgg_token=" . generate_action_token($now);
			$query_string = implode("&", $query);
			return $hook . $query_string;
	    }
	}
	
	function river_comments_question_for_ping() {
		global $ASKQUESTION;
		$ASKQUESTION = true;
	}
	
	function river_comments_get_version($humanreadable = false){
	    if (include(dirname(__FILE__) . "/version.php")) {
			return (!$humanreadable) ? $version : $release;
		}
		return FALSE;
    }
	
	register_elgg_event_handler('init','system','river_comments_init');
	register_elgg_event_handler('pagesetup','system','river_comments_setup');