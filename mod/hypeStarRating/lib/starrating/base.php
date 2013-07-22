<?php

function hj_starrating_get_rating_values() {
    for ($i = 1; $i <= 10; $i++) {
        $value = elgg_get_plugin_setting($i, 'hypeStarRating');
        if ($value && !empty($value)) {
            if (!$min) {
                $min = $i;
            }

            $values["$i"] = $value;
            $max = $i;
        }
    }
    $size = sizeof($values);
    $mid = round($size / 2);
    $keys = array_keys($values);
    $mid = $keys[$mid - 1];


    $return = array('min' => $min, 'max' => $max, 'mid' => $mid, 'values' => $values);
    return $return;
}

function hj_starrating_in_range($val, $from = 0, $to = 100) {
    return min($to, max($from, (int) $val));
}

function hj_starrating_get_entity_ratings($entity) {
    if (!elgg_instanceof($entity)) {
        return false;
    }
    $options = hj_starrating_get_rating_values();

    $count = $entity->countAnnotations('starrating');

    if ($count > 0) {
        $sum = $entity->getAnnotationsSum('starrating');

        $average = $sum / $count;
        $average_rounded = round($average, 2);

        $keys = array_keys($options['values']);
        $average_value = $keys[$average_rounded - 1];
    } else {
        $sum = 0;
        $average_rounded = 0;
        $average_value = 0;
    }
    $return = array(
        'count' => $count,
        'sum' => $sum,
        'average' => $average_rounded,
        'average_value' => $average_value,
        'options' => $options
    );

    return $return;
}

function hj_starrating_user_votes($user, $entity) {
    return elgg_get_annotations(array(
                'annotation_names' => 'starrating',
                'guids' => $entity->guid,
                'annotation_owner_guids' => $user->guid
            ));
}