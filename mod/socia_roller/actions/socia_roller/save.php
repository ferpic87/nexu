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
$variable = $_POST['variable'];
$value = $_POST['value'];
$sociaRoller = elgg_get_entities(array('type' => 'object', 'subtype' => 'sociaRollerSettings', 'limit' => '1'));
$sociaRollerValues = array_shift(array_values($sociaRoller));
$guid = $sociaRollerValues->guid;
$temp = get_entity($guid);
$temp->$variable = $value;
$temp->save();
?>
