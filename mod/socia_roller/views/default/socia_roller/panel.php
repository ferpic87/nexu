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
elgg_load_js('socia_roller_functions');
elgg_load_css('elgg_roller');
elgg_load_js('jscolor');
elgg_load_css('fonts');

$categories = socia_roller_categories();
$variables = socia_roller_variables();
$sociaRoller = elgg_get_entities(array('type' => 'object', 'subtype' => 'sociaRollerSettings', 'limit' => '1'));
$sociaRollerValues = array_shift(array_values($sociaRoller));
foreach ($variables as $key => $value) {
    $variables[$key]['value'] = $sociaRollerValues->$key;
    $$key = $sociaRollerValues->$key;
}
$fonts = socia_roller_fonts();
if (elgg_is_admin_logged_in()) {
?>
<div class="socia_roller_panel">
    <div class="socia_roller_header_logo"></div>
    <div class="socia_roller_panel_showhide_holder scroll-pane">
        <div class="socia_roller_panel_showhide"></div>
    </div>
    <div class='socia_roller_category_holder'>
        <?php
        foreach ($categories as $key => $value) {
            echo "<div class='socia_roller_category_parent'>";
            echo "<div class='socia_roller_category {$key}'>";
            echo $value;
            echo "</div>";
            echo "<div class='socia_roller_category_showhide'>";
            foreach ($variables as $key2 => $value2) {
                if ($value2['category'] == $key) {
                    echo "<div class='socia_roller_value_master_holder'>";
                    echo "<label>" . $value2['label'] . "</label>";
                    echo "<div class='clear'></div>";
                    switch ($value2['type']) {
                        case 'yesno':
                            echo "<div class='socia_roller_yesno ";
                            if ($$key2 == 'no')
                                echo "no";
                            echo " {$key2}'></div>";
                            echo "<div class='clear'></div>";
                            break;
                        case 'color':
                            echo elgg_view('input/text', array("value" => $$key2, "class" => "color {$key2}"));
                            break;
                        case 'size':
                            echo "<div class='socia_roller_value_holder'>";
                            echo "<div class='socia_roller_subtract_five $key2'></div>";
                            echo "<div class='socia_roller_subtract $key2'></div>";
                            echo elgg_view('input/text', array("value" => $$key2, "class" => "socia_roller_value " . $key2));
                            echo "<div class='socia_roller_add $key2'></div>";
                            echo "<div class='socia_roller_add_five $key2'></div>";
                            echo "</div>";
                            echo "<div class='clear'></div>";
                            break;
                        case 'font';
                            echo elgg_view('input/dropdown', array('value' => $$key2, 'class' => "socia_roller_font " . $key2, 'options_values' => $fonts));
                            break;
                        case 'pattern':
                            $patterns = range(0, 20);
                            foreach ($patterns as $pattern) {
                                echo "<div class='socia_pattern_display ";
                                if ($$key2 == $pattern)
                                    echo "style='margin:1px;border-width:2px;'";
                                echo "socia_pattern-$pattern $key2'></div>";
                            }
                            echo "<div style='clear:both;'></div>";
                            break;
                    }
                    echo "</div>";
                    echo "<hr/>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
    <div class='socia_panel_logo'>
    </div>
    <div style="clear:both;"></div>
    <span class="socia_reset">Reset!</span>
    <span class="socia_saving">Saving...</span>
</div>
<?php } ?>
<script type="text/javascript">
    (function() {
        $(document).ready(function() {
            var categories = <?php echo json_encode($categories); ?>;
            var variables = <?php echo json_encode($variables); ?>;
            socia_roller_default_display(variables);
            socia_roller_panel_showhide();
            socia_roller_category_handler(categories);
            socia_roller_update_handler(variables);
            $(".socia_panel_logo").live('click', function(e) {
                e.stopPropagation();
                window.location = 'http://socia.us';
            });
            $(".socia_reset").live('click', function(e) {
                e.stopPropagation();
                var answer = confirm("Are you sure you want to reset all settings to their default values?")
                if (answer) {
                    elgg.action('socia_roller/reset', {
                        data: {
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }

            });
            $(".socia_roller_header_logo").live('click', function(e) {
                e.stopPropagation();
                window.location = 'http://sociaroller.com';
            })
        });
    })();
</script>