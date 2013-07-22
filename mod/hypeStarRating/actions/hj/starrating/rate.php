<?php

sleep(2);

$rating_values = hj_starrating_get_rating_values();
$e = get_input('e', false);
$entity = get_entity($e);

if (!$e || !elgg_instanceof($entity)) {
    register_error(elgg_echo('hj:starrating:rate:error'));
    return true;
}
$owner = elgg_get_logged_in_user_entity();

$starrating = (int) get_input('starrating', 0);
$starrating = hj_starrating_in_range($starrating, $rating_values['min'], $rating_values['max']);

if (!hj_starrating_user_votes($owner, $entity)) {
    create_annotation($e, 'starrating', (int) $starrating, '', $owner->guid, $entity->access_id);
} else {
    register_error(elgg_echo('hj:starrating:rate:alreadyrated'));
    return true;
}

$entity_ratings = hj_starrating_get_entity_ratings($entity);
$output['average_value'] = $entity_ratings['average_value'];
$output['stats'] = elgg_echo('hj:starrating:entity:stats', array($entity_ratings['average'], $rating_values['max'], $entity_ratings['count']));


if (elgg_is_xhr()) {
    system_message(elgg_echo('hj:starrating:rate:success'));
    print(json_encode($output));
    return true;
} else {
    forward(REFERER);
}
