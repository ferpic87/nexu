<?php
/**
 * Walled garden login
 */

$title = elgg_get_site_entity()->name;
$welcome = elgg_echo('walled_garden:welcome');
$welcome .= '&nbsp;' . $title;

$menu = elgg_view_menu('walled_garden', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-general elgg-menu-hz',
));

$login_box = elgg_view('core/account/login_box', array('module' => 'walledgarden-login'));

echo <<<HTML
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		<h1 class="elgg-heading-walledgarden">
			$welcome
		</h1>
		<div class="inner-content-walledgarden">NEXU è la community del CRS dedicata ai ricercatori del centro. La community si rivolge a dipendenti e collaboratori del CRS che vogliono condividere idee ed esperienze e restare aggiornati su tutte le novit&agrave; provenienti dal nostro piccolo angolo di ricerca.	</div>
	</div>
</div>
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		$login_box
	</div>
</div>
HTML;
