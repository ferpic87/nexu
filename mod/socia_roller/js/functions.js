/**
 * SociaRoller
 *
 * @package SociaRoller
 *
 * Author:  Shane Barron - SocialApparatus
 * Website:  http://socia.us
 * 
 */
function socia_roller_panel_showhide() {
    $(".socia_roller_panel_showhide").live('click', function(e) {
        e.stopPropagation();
        $(this).toggleClass('visible');
        if ($(".socia_roller_panel").offset().left === -235) {
            $(".socia_roller_panel").animate({
                left: "+=235"
            }, 500, function() {
                $(".socia_panel_logo_mid").delay(1000).fadeOut(100);
                $(".socia_panel_logo_right").delay(2100).animate({left: '-=114'}, 100);
                $(".socia_panel_logo_dot").delay(2200).fadeIn('slow');
                $(".socia_panel_logo_blur").delay(2200).fadeIn('fast').fadeOut('fast');
            });

        } else {
            $(".socia_roller_panel").animate({
                left: "-=235"
            }, 500, function() {
                $(".socia_panel_logo_mid").show();
                $(".socia_panel_logo_right").css({left: '+=114'});
                $(".socia_panel_logo_dot").hide();
                $(".socia_panel_logo_blur").hide();
            });

        }
    });
}

function socia_roller_category_handler(categories) {
    $.each(categories, function(div, label) {
        $("." + div).live('click', function(e) {
            e.stopPropagation();
            if ($(this).siblings('.socia_roller_category_showhide').eq(0).is(':hidden')) {
                $(".socia_roller_category_showhide").hide();
                $(".socia_roller_category").removeClass('active');
                $(this).addClass('active');
                $(this).siblings('.socia_roller_category_showhide').eq(0).slideDown('slow');
            } else {
                $(this).siblings('.socia_roller_category_showhide').eq(0).slideUp('slow');
                $(this).removeClass('active');
            }
        });
    });
}

function socia_roller_default_display(variables) {
    $.each(variables, function(key, val) {
        $.each(val.divs, function(key2, val2) {
            var div = key2;
            var type = val.type;
            var attribute = val2;
            var value = val.value;
            if (attribute == 'div')
                attribute = 'socia_roller_div_no_show';
            if (attribute == 'image') {
                attribute = 'socia_roller_image_no_show';
            }
            if (attribute == 'text-shadow') {
                attribute = 'socia_roller_no_text_shadow';
            }
            socia_roller_display(div, type, attribute, value);
        });
    });
}

function socia_roller_update_handler(variables) {
    $.each(variables, function(div, value) {
        switch (value.type) {
            case "yesno":
                $("." + div).live('click', function(e) {
                    e.stopPropagation();
                    socia_roller_yesno_handler(div, value);
                });
                break;
            case "color":
                $("." + div).change(function(e) {
                    e.stopPropagation();
                    socia_roller_color_handler(div, value);
                });
                break;
            case "size":
                $(".socia_roller_value." + div).live('input', function(e) {
                    e.stopPropagation();
                    socia_roller_value_handler(div, value);
                });
                $(".socia_roller_subtract." + div).live('click', function(e) {
                    e.stopPropagation();
                    var fieldvalue = $("input." + div).val();
                    fieldvalue = Number(fieldvalue) - Number(1);
                    $(".socia_roller_value." + div).val(fieldvalue);
                    socia_roller_value_handler(div, value);
                });
                $(".socia_roller_add." + div).live('click', function(e) {
                    e.stopPropagation();
                    var fieldvalue = $("input." + div).val();
                    fieldvalue = Number(fieldvalue) + Number(1);
                    $(".socia_roller_value." + div).val(fieldvalue);
                    socia_roller_value_handler(div, value);
                });
                $(".socia_roller_subtract_five." + div).live('click', function(e) {
                    e.stopPropagation();
                    var fieldvalue = $("input." + div).val();
                    fieldvalue = Number(fieldvalue) - Number(5);
                    $(".socia_roller_value." + div).val(fieldvalue);
                    socia_roller_value_handler(div, value);
                });
                $(".socia_roller_add_five." + div).live('click', function(e) {
                    e.stopPropagation();
                    var fieldvalue = $("input." + div).val();
                    fieldvalue = Number(fieldvalue) + Number(5);
                    $(".socia_roller_value." + div).val(fieldvalue);
                    socia_roller_value_handler(div, value);
                });
                break;
            case "pattern":
                var patterns = [];
                for (var i = 0; i <= 20; i++) {
                    patterns.push(i);
                }
                $.each(patterns, function(key, pattern) {
                    $(".socia_pattern-" + pattern + "." + div).live('click', function(e) {
                        e.stopPropagation();
                        $.each(patterns, function(key2, pattern2) {
                            $(".socia_pattern-" + pattern2 + "." + div).css({borderWidth: '1px', margin: '2px'});
                        });
                        $(this).css({borderWidth: '2px', margin: '1px'});
                        socia_roller_save_data(div, pattern);
                        $.each(value.divs, function(div, attribute) {
                            socia_roller_display(div, 'pattern', attribute, pattern);
                        });
                    });
                });
                break;
            case "font":
                $("." + div).change(function(e) {
                    e.stopPropagation();
                    socia_roller_font_handler(div, value);
                });
                break;
        }
    });
}

function socia_roller_display(div, type, attribute, value) {
    switch (type) {
        case "size":
            $(div).css(attribute, value + 'px');
            break;
        case "yesno":
            switch (value) {
                case "no":
                    $(div).addClass(attribute);
                    $(div).hover(function() {
                        $(this).addClass(attribute);
                    });
                    break;
                case "yes":
                    $(div).removeClass(attribute);
                    $(div).hover(function() {
                        $(this).removeClass(attribute);
                    });
                    break;
            }
            break;
        case "color":
            if (attribute == 'hovercolor') {
                var color = $(div).css('color');
                $(div).hover(function() {
                    $(this).css('color', "#" + value);
                }, function() {
                    $(this).css('color', color);
                });
            } else if (attribute == 'hoverbackground') {
                var backgroundcolor = $(div).css('background-color');
                $(div).hover(function() {
                    $(this).css('background-color', "#" + value);
                }, function() {
                    $(this).css('background-color', backgroundcolor);
                });
            } else {
                $(div).css(attribute, "#" + value);
            }
            break;
        case "font":
            $(div).css(attribute, value);
            break;
        case "pattern":
            for (var i = 0; i <= 20; i++) {
                $(div).removeClass(attribute + "-" + i);
            }
            $(div).addClass(attribute + "-" + value);
    }
}

function socia_roller_value_handler(div, value) {
    var size = $(".socia_roller_value." + div).val();
    socia_roller_save_data(div, size);
    $.each(value.divs, function(div2, action) {
        socia_roller_display(div2, 'size', action, size);
    });
}

function socia_roller_yesno_handler(div, value) {

    if ($(".socia_roller_yesno." + div).hasClass('no')) {
        $("." + div).toggleClass('no');
        var yesno = 'yes';
    } else {
        $(".socia_roller_yesno." + div).toggleClass('no');
        var yesno = 'no';
    }
    socia_roller_save_data(div, yesno);
    $.each(value.divs, function(key, value2) {
        switch (value2) {
            case "box-shadow":
                socia_roller_display(key, 'yesno', 'socia_roller_no_box_shadow', yesno);
                break;
            case "text-shadow":
                socia_roller_display(key, 'yesno', 'socia_roller_no_text_shadow', yesno);
                break;
            case "div":
                socia_roller_display(key, 'yesno', 'socia_roller_div_no_show', yesno);
                break;
            case "image":
                socia_roller_display(key, 'yesno', 'socia_roller_image_no_show', yesno);
                break;
        }
    });
}

function socia_roller_color_handler(div, value) {
    var color = $("." + div).val();
    socia_roller_save_data(div, color);
    $.each(value.divs, function(div2, attribute) {
        socia_roller_display(div2, 'color', attribute, color);
    });
}

function socia_roller_font_handler(div, value) {
    var font = $("." + div).val();
    socia_roller_save_data(div, font);
    $.each(value.divs, function(div2, attribute) {
        socia_roller_display(div2, 'font', attribute, font);
    });
}

function socia_roller_save_data(variable, value) {
    elgg.action('socia_roller/save', {
        data: {
            variable: variable,
            value: value
        }
    });
}


