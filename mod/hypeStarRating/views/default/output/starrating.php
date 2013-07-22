<?php
$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
    return true;
}

elgg_load_css('hj.starrating.base');
elgg_load_js('hj.starrating.lib');
elgg_load_js('hj.starrating.base');

$options = hj_starrating_get_rating_values();
$options_values = $options['values'];

$entity_ratings = hj_starrating_get_entity_ratings($entity);
$selected_value = $entity_ratings['average_value'];

$disabled = "disabled";

$input = elgg_view('input/dropdown', array(
    'name' => 'starrating',
    'value' => $selected_value,
    'options_values' => $options_values,
    'disabled' => $disabled,
    'class' => 'hj-starrating-default-select'
));

$submit = elgg_view('input/submit', array('value' => elgg_echo('rate')));

$form = elgg_view('input/form', array(
    'body' => $input . $submit,
    'action' => "action/stars/rate?e={$entity->guid}",
    'id' => "hj-starrating-entity-{$entity->guid}",
    'class' => 'hj-ajaxed-starrating'
));

$stats = elgg_echo('hj:starrating:entity:stats', array($entity_ratings['average'], $options['max'], $entity_ratings['count']));

$html = <<<HTML
    <div class="hj-starrating-container hidden">
        $form
        <div class="hj-starrating-caption">
            $stats
        </div>
    </div>
HTML;


echo $html;
?>
<script type="text/javascript">
	elgg.register_hook_handler('success', 'hj:framework:ajax', hj.starrating.base.init, 500);
	elgg.trigger_hook('success', 'hj:framework:ajax');
</script>