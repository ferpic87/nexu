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

	$version = river_comments_get_version();
	$release = river_comments_get_version(true);
?>	

	<meta name="river_comment_release" content="<?php echo $release; ?>" />
	<meta name="river_comment_version" content="<?php echo $version; ?>" />