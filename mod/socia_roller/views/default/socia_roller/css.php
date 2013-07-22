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
$sociaRoller = elgg_get_entities(array('type' => 'object', 'subtype' => 'sociaRollerSettings', 'limit' => '1'));
$sociaRollerValues = array_shift(array_values($sociaRoller));
$variables = socia_roller_variables();
$patterns = range(1, 20);
foreach ($patterns as $pattern) {
    echo ".socia_pattern-$pattern { background:url({$CONFIG->url}mod/socia_roller/graphics/patterns/{$pattern}.png); }\r\n";
}
echo ".socia_pattern-0 { background-image:none; }\r\n";
$pixels = array(
    'font-size',
    'line-height',
    'height',
    'margin-top',
    'margin-left',
    'margin-bottom',
    'margin-right',
    'top',
    'left',
    'bottom',
    'right',
    'padding',
    'padding-top',
    'padding-left',
    'padding-right',
    'padding-bottom',
    'border-top-right-radius',
    'border-top-left-radius',
    'border-bottom-right-radius',
    'border-bottom-left-radius',
    'border-radius',
    'border-width'
);
$colors = array(
    'color',
    'background-color',
    'border-color'
);
foreach ($variables as $key => $value) {
    $property = $sociaRollerValues->$key;
    echo elgg_get_calling_plugin_id();
    foreach ($value['divs'] as $div => $attribute) {
        if (in_array($attribute, $pixels)) {
            echo $div . "{" . $attribute . ":" . $property . "px;}\r\n";
            echo $div . ":hover {" . $attribute . ":" . $property . "px;}\r\n";
        } elseif (in_array($attribute, $colors)) {
            echo $div . "{" . $attribute . ": #" . $property . ";}\r\n";
            echo $div . ":hover {" . $attribute . ": #" . $property . ";}\r\n";
        } elseif ($attribute == 'div') {
            switch ($property) {
                case "yes":
                    echo $div . " { display:block; }\r\n";
                    break;
                case "no":
                    echo $div . " { display:none; }\r\n";
                    break;
            }
        } elseif ($attribute == 'socia_pattern') {
            if ($property != 0) {
                echo $div . " { background-image:url({$CONFIG->url}mod/socia_roller/graphics/patterns/{$property}.png); }\r\n";
            }
        } elseif ($attribute == 'text-shadow') {
            switch ($property) {
                case "no":
                    echo $div . " { text-shadow:none;}\r\n";
                    echo $div . ":hover { text-shadow:none}\r\n";
                    break;
            }
        } elseif ($attribute == 'hoverbackground') {
            echo $div . ":hover { background-color: #" . $property . ";}\r\n";
        } elseif ($attribute == 'hovercolor') {
            echo $div . ":hover { color: #" . $property . ";}\r\n";
        } elseif ($attribute == 'font-family') {
            echo $div . " { " . $attribute . ":" . $property . "; }\r\n";
            echo $div . ":hover { " . $attribute . ":" . $property . "; }\r\n";
        } else {
            echo $div . " { " . $attribute . ":" . $property . "; }\r\n";
        }
    }
}
?>