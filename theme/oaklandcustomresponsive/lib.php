<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Brian Barnes <brian.barnes@totaralms.com>
 * @package theme
 * @subpackage crownequipmentresponsive
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Makes our changes to the CSS
 *
 * @param string $css
 * @param theme_config $theme
 * @return string
 */
function theme_oaklandcustomresponsive_process_css($css, $theme) {

    $substitutions = array(
        'linkcolor' => '#087BB1',
        'linkvisitedcolor' => '#087BB1',
        'headerbgc' => '',
        'blockheadercolor' => '#ffa80b',
        'blockheadergradientstart' => '#ffffff',
        'blockheadertextcolor' => '#4e4546',
        'headergradient' => '#777777',
        'buttoncolor' => '#E6E6E6',
        'buttontextcolor' => '#ffffff',
        'blockcontentcolor' => '#ffffff',
        'headerbgimgopacity' => '0.4',
        'bodybgimgopacity' => '0.4',
        'headericonscolor' => '#ffffff',
        'menucolor' => '#ffffff',
        'menutextcolor' => '#000000',
        'submenucolor' => '#000000',
        'submenutextcolor' => '#ffffff',
        'selectedsubmenucolor' => '#000000',
        'selectedsubmenutextcolor' => '#ffffff',
        'hovermenucolor' => '#808080',
        'hoversubmenucolor' => '#808080',
        'logged_in_usernamecolor' => '#ffffff'
    );
    $css = totara_theme_generate_autocolors($css, $theme, $substitutions);

    $fonts = array(
        'h1size' => '24',
        'h2size' => '22',
        'h3size' => '18',
        'h4size' => '16',
        'h5size' => '12',
        'h6size' => '10',
        'ptagsize' => '14'
    );

    $css = custom_theme_generate_fontsizes($css, $theme, $fonts);
	// Set Images.
    $setting = 'headerbgimg';
    // Creates the url for image file which is then served up by 'theme_oaklandcustomresponsive_pluginfile' below.
    $headerbgimage = $theme->setting_file_url($setting, $setting);
    $css = theme_oaklandcustomresponsive_set_image($css, $headerbgimage, $setting);

    $setting = 'bodybgimg';
    // Creates the url for image file which is then served up by 'theme_oaklandcustomresponsive_pluginfile' below.
    $bodybgimage = $theme->setting_file_url($setting, $setting);
    $css = theme_oaklandcustomresponsive_set_image($css, $bodybgimage, $setting);

    // Set the custom CSS
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_oaklandcustomresponsive_set_customcss($css, $customcss);

    return $css;
}


function theme_oaklandcustomresponsive_set_image($css, $image, $setting) {
    global $OUTPUT;
    $tag = 'setting:'.$setting.'';
    $replacement = $image;
    if (is_null($replacement)) {
        // Get default image from themes 'images' folder of the name in $setting.
        $replacement = $OUTPUT->pix_url('images/'.$setting, 'theme');
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

/**
 * Sets the custom css variable in CSS
 *
 * @param string $css
 * @param mixed $customcss
 * @return string
 */
function theme_oaklandcustomresponsive_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_oaklandcustomresponsive_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'favicon')) {
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'headerbgimg') {
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve('headerbgimg', $args, $forcedownload, $options);
    } else if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'bodybgimg') {
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve('bodybgimg', $args, $forcedownload, $options);
    } else if($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'collablogo'){
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve('collablogo', $args, $forcedownload, $options);
    } else if($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'hublogo'){
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve('hublogo', $args, $forcedownload, $options);
    }else {
        send_file_not_found();
    }
}

function cec_login_info() {
    global $USER, $CFG;

    $fullname = fullname($USER, true);

    return "<a href=\"$CFG->wwwroot/user/profile.php?id=$USER->id\" style='margin-top: 15px;' title=\"$fullname\" class='text-center white'>$fullname<br/></a>";
}

function cec_logout() {
    global $USER, $CFG;

    return "<a class=\"loginstatus\" href=\"$CFG->wwwroot/login/logout.php?loginpage=1&sesskey=".sesskey()."\"><button class=\"btn\">".get_string('logout').'</button></a>';
}

function cec_login() {
        global $CFG;

    return "<a class=\"loginstatus\" href=\"$CFG->wwwroot/login/index.php\"><button class=\"btn\">".get_string('login').'</button></a>';
}

/**
 * Interprets Font Size settings and inserts their values into the stylesheet.
 * @param $css
 * @param $theme
 * @param $fonts
 * @return mixed
 */

function custom_theme_generate_fontsizes($css, $theme, $fonts){
    $find = array();
    $replace = array();
    foreach ($fonts as $setting => $defaultfontsize) {
        if(!isset($theme->settings->$setting) || $theme->settings->$setting == 0){
            $value = $defaultfontsize;
        }else{
            $value = $theme->settings->$setting;
        }

        $find[] = "[[setting:{$setting}]]";
        $replace[] = $value;

    }
    return str_replace($find, $replace, $css);
}

