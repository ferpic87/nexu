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
$variables = socia_roller_variables();
$sociaRoller = elgg_get_entities(array('type' => 'object', 'subtype' => 'sociaRollerSettings', 'limit' => '1'));
$sociaRollerValues = array_shift(array_values($sociaRoller));
$temp = get_entity($sociaRollerValues->guid);
foreach ($variables as $key => $value) {
    $temp->$key = $value['default'];
}
$temp->save();
?>
