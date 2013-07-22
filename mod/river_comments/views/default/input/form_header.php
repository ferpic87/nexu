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

	if (isset($vars['internalid'])) {
		$id = $vars['internalid'];
	} else {
		$id = '';
	}
	
	if (isset($vars['internalname'])) {
		$name = $vars['internalname'];
	} else {
		$name = '';
	}
	$body = $vars['body'];
	$action = $vars['action'];
	if (isset($vars['enctype'])) {
		$enctype = $vars['enctype'];
	} else {
		$enctype = '';
	}
	if (isset($vars['method'])) {
		$method = $vars['method'];
	} else {
		$method = 'POST';
	}
	
	$method = strtolower($method);
	
	// Generate a security header
	$security_header = "";
	if (!isset($vars['disable_security']) || $vars['disable_security'] != true) {
		$security_header = elgg_view('input/securitytoken');
	}
	?>
	<form <?php if ($id) { ?>id="<?php echo $id; ?>" <?php } ?> <?php if ($name) { ?>name="<?php echo $name; ?>" <?php } ?> <?php echo $vars['js']; ?> action="<?php echo $action; ?>" method="<?php echo $method; ?>" <?php if ($enctype!="") echo "enctype=\"$enctype\""; ?>>
	<?php echo $security_header; ?>