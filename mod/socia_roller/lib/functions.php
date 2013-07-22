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

if (!$sociaRoller) {
    $sociaRoller = new ElggObject();
    $sociaRoller->access_id = ACCESS_PUBLIC;
    $sociaRoller->subtype = 'sociaRollerSettings';
    $sociaRoller->save();
    foreach (socia_roller_variables() as $key => $value) {
        $sociaRoller->$key = $value['default'];
    }
    $sociaRoller->save();
}

function socia_roller_categories() {
    $array = array(
        "general" => "General",
        "topbar" => "Topbar",
        "logo" => "Logo",
        "login" => "Login",
        "header" => "Header",
        "menu" => "Menu",
        "search" => "Search Box",
        "index" => "Custom Index",
        "body" => "Body",
        "page" => "Page",
        "profilecat" => "Profile",
        "footer" => "Footer"
    );
    return $array;
}

function socia_roller_variables() {
    $array = array(
        'h1Font' => array(
            'category' => 'general',
            'label' => 'H1 Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                'h1' => 'font-family'
            )
        ),
        'h1Size' => array(
            'category' => 'general',
            'label' => 'H1 Font Size',
            'type' => 'size',
            'default' => 38,
            'divs' => array(
                'h1' => 'font-size',
                'h1 ' => 'line-height'
            )
        ),
        'h2Font' => array(
            'category' => 'general',
            'label' => 'H2 Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                'h2' => 'font-family'
            )
        ),
        'h2Size' => array(
            'category' => 'general',
            'label' => 'H2 Font Size',
            'type' => 'size',
            'default' => 32,
            'divs' => array(
                'h2' => 'font-size',
                'h2 ' => 'line-height'
            )
        ),
        'h3Font' => array(
            'category' => 'general',
            'label' => 'H3 Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                'h3' => 'font-family'
            )
        ),
        'h3Size' => array(
            'category' => 'general',
            'label' => 'H3 Font Size',
            'type' => 'size',
            'default' => 20,
            'divs' => array(
                'h3' => 'font-size',
                'h3 ' => 'line-height'
            )
        ),
        'h4Font' => array(
            'category' => 'general',
            'label' => 'H4 Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                'h4' => 'font-family'
            )
        ),
        'h4Size' => array(
            'category' => 'general',
            'label' => 'H4 Font Size',
            'type' => 'size',
            'default' => 18,
            'divs' => array(
                'h4' => 'font-size',
                'h4 ' => 'line-height'
            )
        ),
        'topbarShowHide' => array(
            'category' => 'topbar',
            'label' => 'Show the topbar?',
            'type' => 'yesno',
            'default' => 'yes',
            'divs' => array(
                '.elgg-page-default .elgg-page-topbar' => 'div'
            )
        ),
        'topbarLogoShowHide' => array(
            'category' => 'topbar',
            'label' => 'Show the topbar logo?',
            'type' => 'yesno',
            'default' => 'yes',
            'divs' => array(
                'a.elgg-topbar-logo img' => 'div'
            )
        ),
        'headerBackgroundColor' => array(
            'category' => 'header',
            'label' => 'Header Background',
            'type' => 'color',
            'default' => '00AEEC',
            'divs' => array(
                '.elgg-page-header' => 'background-color'
            )
        ),
        'headerBackgroundPattern' => array(
            'category' => 'header',
            'label' => 'Header Background Pattern',
            'type' => 'pattern',
            'default' => 0,
            'divs' => array(
                '.elgg-page-header' => 'socia_pattern'
            )
        ),
        'headerHeight' => array(
            'category' => 'header',
            'label' => 'Header Height',
            'type' => 'size',
            'default' => 110,
            'divs' => array(
                '.elgg-page-default .elgg-page-header > .elgg-inner' => 'height',
                '.elgg-page-default .elgg-page-header' => 'height'
            )
        ),
        'logoFont' => array(
            'category' => 'logo',
            'label' => 'Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                '.elgg-heading-site' => 'font-family'
            ),
        ),
        'logoColor' => array(
            'category' => 'logo',
            'label' => 'Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-heading-site' => 'color'
            )
        ),
        'logoFontSize' => array(
            'category' => 'logo',
            'label' => 'Size',
            'type' => 'size',
            'default' => 46,
            'divs' => array(
                '.elgg-heading-site' => 'font-size'
            )
        ),
        'logoShadow' => array(
            'category' => 'logo',
            'label' => 'Show Shadow?',
            'type' => 'yesno',
            'default' => 'no',
            'divs' => array(
                '.elgg-heading-site' => 'text-shadow'
            )
        ),
        'logoMarginTop' => array(
            'category' => 'logo',
            'label' => 'Top Margin',
            'type' => 'size',
            'default' => 0,
            'divs' => array(
                '.elgg-heading-site' => 'margin-top'
            )
        ),
        'logoMarginLeft' => array(
            'category' => 'logo',
            'label' => 'Left Margin',
            'type' => 'size',
            'default' => 0,
            'divs' => array(
                '.elgg-heading-site' => 'margin-left'
            )
        ),
        'loginButtonBackgroundColor' => array(
            'category' => 'login',
            'label' => 'Button Background Color',
            'type' => 'color',
            'default' => '33BEF0',
            'divs' => array(
                '.elgg-button-dropdown' => 'background-color'
            )
        ),
        'loginButtonHoverBackgroundColor' => array(
            'category' => 'login',
            'label' => 'Button Hover Background Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-button-dropdown' => 'hoverbackground',
                'a.elgg-button.elgg-button-dropdown.elgg-state-active' => 'background-color'
            )
        ),
        'loginBoxBorderColor' => array(
            'category' => 'login',
            'label' => 'Login Box Border Color',
            'type' => 'color',
            'default' => 'CCCCCC',
            'divs' => array(
                '.elgg-module-dropdown' => 'border-color'
            )
        ),
        'mainMenuFont' => array(
            'category' => 'menu',
            'label' => 'Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                '.elgg-menu-site > li > a' => 'font-family'
            ),
        ),
        'mainMenuTextSize' => array(
            'category' => 'menu',
            'label' => 'Font Size',
            'type' => 'size',
            'default' => 16,
            'divs' => array(
                '.elgg-menu-site > li > a' => 'font-size'
            )
        ),
        'mainMenuElementColor' => array(
            'category' => 'menu',
            'label' => 'Menu Text Color',
            'type' => 'color',
            'default' => 'ffffff',
            'divs' => array(
                '.elgg-menu-site > li >a' => 'color'
            )
        ),
        'mainMenuElementBackground' => array(
            'category' => 'menu',
            'label' => 'Menu Background Color',
            'type' => 'color',
            'default' => '00AEEC',
            'divs' => array(
                '.elgg-menu-site > li > a' => 'background-color'
            )
        ),
        'mainMenuHoverBackground' => array(
            'category' => 'menu',
            'label' => 'Hover Background Color',
            'type' => 'color',
            'default' => '33BEF0',
            'divs' => array(
                ".elgg-menu-site-default > li > a" => "hoverbackground",
                ".elgg-menu-site-more > li > a" => 'hoverbackground',
                ".elgg-menu-site-default > .elgg-state-selected > a" => 'background-color'
            )
        ),
        'mainMenuHoverTextColor' => array(
            'category' => 'menu',
            'label' => 'Hover Text Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                ".elgg-menu-site-default > li > a" => 'hovercolor',
                ".elgg-menu-site-more > li > a" => 'hovercolor',
                ".elgg-menu-site-default > .elgg-state-selected > a" => 'color'
            )
        ),
        'mainMenuBottom' => array(
            'category' => 'menu',
            'label' => 'Bottom Position',
            'type' => 'size',
            'default' => 12,
            'divs' => array(
                '.elgg-menu-site-default' => 'bottom'
            )
        ),
        'mainMenuHeight' => array(
            'category' => 'menu',
            'label' => 'Height',
            'type' => 'size',
            'default' => 24,
            'divs' => array(
                '.elgg-menu-site > li > a' => 'height',
                '.elgg-menu-site > li' => 'line-height',
                '.elgg-menu-site-default' => 'height'
            )
        ),
        'mainMenuElementVerticalPadding' => array(
            'category' => 'menu',
            'label' => 'Element Vertical Padding',
            'type' => 'size',
            'default' => 6,
            'divs' => array(
                '.elgg-menu-site > li > a' => 'padding-top',
                '.elgg-menu-site > li > a ' => 'padding-bottom'
            )
        ),
        'mainMenuElementHorizontalPadding' => array(
            'category' => 'menu',
            'label' => 'Element Horizontal Padding',
            'type' => 'size',
            'default' => 14,
            'divs' => array(
                '.elgg-menu-site > li > a' => 'padding-left',
                '.elgg-menu-site > li > a ' => 'padding-right'
            )
        ),
        'mainMenuBorderRadius' => array(
            'category' => 'menu',
            'label' => 'Border Radius',
            'type' => 'size',
            'default' => 4,
            'divs' => array(
                ".elgg-menu-site-default > .elgg-state-selected > a" => "border-top-right-radius",
                ".elgg-menu-site-default > .elgg-state-selected > a " => "border-top-left-radius",
                ".elgg-menu-site-default > li > a" => "border-top-right-radius",
                ".elgg-menu-site-default > li > a " => "border-top-left-radius"
            )
        ),
        'searchBoxFont' => array(
            'category' => 'search',
            'label' => 'Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                ".elgg-search input[type='text']" => 'font-family'
            ),
        ),
        'searchBoxTextColor' => array(
            'category' => 'search',
            'label' => 'Text Color',
            'type' => 'color',
            'default' => '333333',
            'divs' => array(
                '.elgg-search input[type="text"]' => 'color'
            )
        ),
        'searchBoxBackgroundColor' => array(
            'category' => 'search',
            'label' => 'Background Color',
            'type' => 'color',
            'default' => 'ffffff',
            'divs' => array(
                '.elgg-search input[type="text"]' => 'background-color'
            )
        ),
        'searchBoxTextSize' => array(
            'category' => 'search',
            'label' => 'Text Size',
            'type' => 'size',
            'default' => 16,
            'divs' => array(
                '.elgg-search input[type="text"]' => 'font-size'
            )
        ),
        'searchBoxHorizontalPosition' => array(
            'category' => 'search',
            'label' => 'Horizontal Position',
            'type' => 'size',
            'default' => 0,
            'divs' => array(
                '.elgg-search-header' => 'right'
            ),
        ),
        'searchBoxVerticalPosition' => array(
            'category' => 'search',
            'label' => 'Vertical Position',
            'type' => 'size',
            'default' => 20,
            'divs' => array(
                '.elgg-search-header' => 'bottom'
            )
        ),
        'searchBoxPadding' => array(
            'category' => 'search',
            'label' => 'Padding',
            'type' => 'size',
            'default' => 8,
            'divs' => array(
                '.elgg-search input[type="text"]' => 'padding'
            )
        ),
        'searchBoxBorderRadius' => array(
            'category' => 'search',
            'label' => 'Border Radius',
            'type' => 'size',
            'default' => '4',
            'divs' => array(
                '.elgg-search input[type="text"]' => 'border-radius'
            )
        ),
        'searchBoxBorderColor' => array(
            'category' => 'search',
            'label' => 'Border Color',
            'type' => 'color',
            'default' => 'D2D2CD',
            'divs' => array(
                '.elgg-search input[type="text"]' => 'border-color'
            )
        ),
        'searchBoxBorderWidth' => array(
            'category' => 'search',
            'label' => 'Border Width',
            'type' => 'size',
            'default' => 2,
            'divs' => array(
                '.elgg-search input[type="text"]' => 'border-width'
            )
        ),
        'indexModuleBorderColor' => array(
            'category' => 'index',
            'label' => 'Module Border Color',
            'type' => 'color',
            'default' => '00AEEC',
            'divs' => array(
                '.elgg-module-featured' => 'border-color'
            )
        ),
        'indexModuleHeaderTextColor' => array(
            'category' => 'index',
            'label' => 'Module Header Text Color',
            'type' => 'color',
            'default' => '333333',
            'divs' => array(
                '.elgg-module-featured > .elgg-head h3' => 'color'
            )
        ),
        'indexModuleBorderRadius' => array(
            'category' => 'index',
            'label' => 'Module Border Radius',
            'type' => 'size',
            'default' => 8,
            'divs' => array(
                '.elgg-module-featured' => 'border-radius'
            )
        ),
        'indexModuleBorderWidth' => array(
            'category' => 'index',
            'label' => 'Module Border Width',
            'type' => 'size',
            'default' => 0,
            'divs' => array(
                '.elgg-module-featured' => 'border-width'
            )
        ),
        'indexModulePadding' => array(
            'category' => 'index',
            'label' => 'Module Padding',
            'type' => 'size',
            'default' => 4,
            'divs' => array(
                '.elgg-module-featured' => 'padding'
            )
        ),
        'bodyBackgroundColor' => array(
            'category' => 'body',
            'label' => 'Body Background Color',
            'type' => 'color',
            'default' => 'ffffff',
            'divs' => array(
                'body' => 'background-color'
            )
        ),
        'bodyBackgroundPattern' => array(
            'category' => 'body',
            'label' => 'Body Background Pattern',
            'type' => 'pattern',
            'default' => 0,
            'divs' => array(
                'body' => 'socia_pattern'
            )
        ),
        'bodyFont' => array(
            'category' => 'body',
            'label' => 'Body Font',
            'type' => 'font',
            'default' => "'Helvetica Neue', Helvetica, Arial, sans-serif",
            'divs' => array(
                'body' => 'font-family'
            )
        ),
        'bodyFontSize' => array(
            'category' => 'body',
            'label' => 'Body Font Size',
            'type' => 'size',
            'default' => '14',
            'divs' => array(
                'body' => 'font-size'
            )
        ),
        'bodySideMenuTextColor' => array(
            'category' => 'menu',
            'label' => 'Side Menu Text Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-menu-owner-block li a' => 'color'
            )
        ),
        'bodySideMenuTextHoverColor' => array(
            'category' => 'menu',
            'label' => 'Site Menu Text Hover Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-menu-page a' => 'hovercolor'
            )
        ),
        'bodySideMenuHoverBackground' => array(
            'category' => 'menu',
            'label' => 'Side Menu Hover Background',
            'type' => 'color',
            'default' => '33BEF0',
            'divs' => array(
                '.elgg-menu-page a' => 'hoverbackground'
            )
        ),
        'pageBackgroundColor' => array(
            'category' => 'page',
            'label' => 'Page Background Color',
            'type' => 'color',
            'default' => 'ffffff',
            'divs' => array(
                '.elgg-layout-one-sidebar' => 'background-color',
                '.elgg-layout-two-sidebar' => 'background-color',
                '.elgg-menu-filter > .elgg-state-selected > a' => 'background-color',
                '.elgg-menu-filter > .elgg-state-selected' => 'background-color',
                '.elgg-tabs .elgg-state-selected a' => 'background-color',
                '.elgg-tabs .elgg-state-selected' => 'background-color',
                '.custom-index' => 'background-color'
            )
        ),
        'pageBackgroundPattern' => array(
            'category' => 'page',
            'label' => 'Page Background Pattern',
            'type' => 'pattern',
            'default' => '0',
            'divs' => array(
                '.elgg-layout-one-sidebar' => 'socia_pattern',
                '.elgg-layout-two-sidebar' => 'socia_pattern',
                '.elgg-menu-filter > .elgg-state-selected > a' => 'socia_pattern',
                '.elgg-menu-filter > .elgg-state-selected' => 'socia_pattern',
                '.elgg-tabs .elgg-state-selected a' => 'socia_pattern',
                '.elgg-tabs .elgg-state-selected' => 'socia_pattern'
            )
        ),
        'profileInfoBackgroundColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Info Background Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.profile .elgg-inner' => 'background-color'
            )
        ),
        'profileOwnerBlockBackgroundColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Block Background Color',
            'type' => 'color',
            'default' => 'DDDDDD',
            'divs' => array(
                '#profile-owner-block' => 'background-color'
            )
        ),
        'profileInfoBorderRadius' => array(
            'category' => 'profilecat',
            'label' => 'Profile Block Border Radius',
            'type' => 'size',
            'default' => 8,
            'divs' => array(
                '.profile .elgg-inner' => 'border-radius',
                '#profile-owner-block' => 'border-top-left-radius',
                '#profile-owner-block ' => 'border-bottom-left-radius'
            )
        ),
        'profileInfoBorderColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Block Border Color',
            'type' => 'color',
            'default' => 'DDDDDD',
            'divs' => array(
                '.profile .elgg-inner' => 'border-color'
            )
        ),
        'profileMenuBackgroundColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Menu Background Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-menu-owner-block li a' => 'background-color'
            )
        ),
        'profileMenuBackgroundHoverColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Menu Hover Background',
            'type' => 'color',
            'default' => '00AEEC',
            'divs' => array(
                '.elgg-menu-owner-block li a' => 'hoverbackground'
            )
        ),
        'profileMenuTextColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Menu Text Color',
            'type' => 'color',
            'default' => '333333',
            'divs' => array(
                '.elgg-menu-owner-block li a' => 'color'
            )
        ),
        'profileMenuTextHoverColor' => array(
            'category' => 'profilecat',
            'label' => 'Profile Menu Text Hover Color',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-menu-owner-block li a' => 'hovercolor'
            )
        ),
        'profileDetailsOddBackground' => array(
            'category' => 'profilecat',
            'label' => 'Profile Details Odd Background Color',
            'type' => 'color',
            'default' => 'F4F4F4',
            'divs' => array(
                '#profile-details .odd' => 'background-color'
            )
        ),
        'profileDetailsEvenBackground' => array(
            'category' => 'profilecat',
            'label' => 'Profile Details Even Background Color',
            'type' => 'color',
            'default' => 'EEEEEE',
            'divs' => array(
                '#profile-details .even' => 'background-color'
            )
        ),
        'profileWidgetBorderColor' => array(
            'category' => 'profilecat',
            'label' => 'Widget Border Color',
            'type' => 'color',
            'default' => 'EEEEEE',
            'divs' => array(
                '.elgg-module-widget' => 'background-color'
            )
        ),
        'profileWidgetBackgroundColor' => array(
            'category' => 'profilecat',
            'label' => 'Widget Header Background Color',
            'type' => 'color',
            'default' => 'EEEEEE',
            'divs' => array(
                '.elgg-module-widget > .elgg-head' => 'background-color'
            )
        ),
        'profileWidgetHeaderColor' => array(
            'category' => 'profilecat',
            'label' => 'Widget Header Color',
            'type' => 'color',
            'default' => '333333',
            'divs' => array(
                '.elgg-module-widget > .elgg-head h3' => 'color'
            )
        ),
        'profileWidgetBodyBackgroundColor' => array(
            'category' => 'profilecat',
            'label' => 'Widget Body Background',
            'type' => 'color',
            'default' => 'FFFFFF',
            'divs' => array(
                '.elgg-widget-content' => 'background-color'
            )
        ),
        'footerShowFooter' => array(
            'category' => 'footer',
            'label' => 'Show the footer?',
            'type' => 'yesno',
            'default' => 'yes',
            'divs' => array(
                '.elgg-page-footer' => 'div'
            )
        ),
        'footerShowLogo' => array(
            'category' => 'footer',
            'label' => 'Show the Elgg logo?',
            'type' => 'yesno',
            'default' => 'no',
            'divs' => array(
                '.elgg-page-default .elgg-page-footer > .elgg-inner img' => 'div'
            )
        ),
    );
    return $array;
}

function socia_roller_fonts() {
    $array = array(
        "'Helvetica Neue', Helvetica, Arial, sans-serif" => "Helvetica",
        "'Roboto Condensed', sans-serif" => "Roboto",
        "'Kavoon', cursive" => "Kavoon",
        "'Fruktur', cursive" => "Fruktur",
        "'Iceland', cursive" => "Iceland",
        "'Nova Square', cursive" => "Nova Square",
        "'Text Me One', sans-serif" => "Text Me One",
        "'Elsie', cursive" => "Elsie",
        "'Englebert', sans-serif" => "Englebert",
        "'Arbutus', cursive" => "Arbutus",
        "'Wendy One', sans-serif" => "Wendy One",
        "'Libre Baskerville', serif" => "Libre Baskerville",
        "'Quintessential', cursive" => "Quintessential",
        "'Fjalla One', sans-serif" => "Fjalla One",
        "'Racing Sans One', cursive" => "Racing Sans One",
        "'Galindo', cursive" => "Galindo",
        "'Bonbon', cursive" => "Bonbon",
        "'Mr Dafoe', cursive" => "Mr Dafoe",
        "'Diplomata SC', cursive" => "Diplomata SC",
        "'Snowburst One', cursive" => "Snowburst One",
        "'Domine', serif" => "Domine"
    );
    return $array;
}

?>
