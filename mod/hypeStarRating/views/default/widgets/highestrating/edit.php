<?php
/**
 * User blog widget edit view
 */
// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 5;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
);
$limit = elgg_view('input/dropdown', $params);

$registered = get_registered_entity_types();
$types_options[] = 'all';
$subtypes_options[] = 'all';

foreach ($registered as $key => $value) {
	$types_options[] = $key;
	if ($key == 'object') {
		foreach ($value as $subtype) {
			$subtypes_options[$subtype] = elgg_echo('item:object:' . $subtype);
		}
	}
}
$params = array(
	'name' => 'params[type_display]',
	'value' => $vars['entity']->type_display,
	'options' => $types_options,
	'js' => "onchange=\"if(this.value == 'object') { $('div#hj-starrating-subtypes').removeClass('hidden'); } else { $('div#hj-starrating-subtypes').addClass('hidden'); }\""
);
$types = elgg_view('input/dropdown', $params);

if ($vars['entity']->type_display !== 'object') {
	$hidden = 'hidden';
}

$params = array(
	'name' => 'params[subtype_display]',
	'value' => $vars['entity']->subtype_display,
	'options' => $subtypes_options,
);
$subtypes = elgg_view('input/dropdown', $params);
?>
<div>
	<?php
	echo elgg_echo('hj:starrating:widget:numbertodisplay') . ':';
	echo $limit . '<br />';
	echo elgg_echo('hj:starrating:widget:types') . ':';
	echo $types . '<br />';
	echo '<div id="hj-starrating-subtypes" class="' . $hidden . '">';
	echo elgg_echo('hj:starrating:widget:subtypes') . ':';
	echo $subtypes;
	echo '</div>'
	?>
</div>
