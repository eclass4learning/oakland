<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
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
 * @author Brian Barnes <brian.barnes@totaralearning.com>
 * @author Joby Harding <joby.harding@totaralearning.com>
 * @package theme_oaklandcustomresponsive
 */

defined('MOODLE_INTERNAL') || die();

use theme_oaklandcustomresponsive\css_processor;

/**
 * Makes our changes to the CSS
 *
 * This is only called when compiling CSS after cache clearing.
 *
 * @param string $css
 * @param theme_config $theme
 * @return string
 */
function theme_oaklandcustomresponsive_process_css($css, $theme) {

    $processor   = new css_processor($theme);
    $settingscss = $processor->get_settings_css($css);

    if (empty($theme->settings->enablestyleoverrides)) {
        // Replace all instances ($settingscss is an array).
        $css = str_replace($settingscss, '', $css);
        // Always insert settings-based custom CSS.
        return $processor->replace_tokens(array('customcss' => css_processor::$DEFAULT_CUSTOMCSS), $css);
    }

    $replacements = $settingscss;

    // Based on oaklandcustomresponsive Bootswatch.
    // These defaults will also be used to generate and replace
    // variant colours (e.g. linkcolor-dark, linkcolor-darker).
    $variantdefaults = array(
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

    // These default values do not have programmatic variants.
    $nonvariantdefaults = array(
        'contentbackground' => css_processor::$DEFAULT_CONTENTBACKGROUND,
        'bodybackground'    => css_processor::$DEFAULT_BODYBACKGROUND,
        'textcolor'         => css_processor::$DEFAULT_TEXTCOLOR,
        'navtextcolor'      => css_processor::$DEFAULT_NAVTEXTCOLOR,
    );

    foreach (array_values($replacements) as $i => $replacement) {
        $replacements[$i] = $processor->replace_colours($variantdefaults, $replacement);
        $replacements[$i] = $processor->replace_tokens($nonvariantdefaults, $replacements[$i]);
        $replacements[$i] = $processor->remove_delimiters($replacements[$i]);
    }

    if (!empty($settingscss)) {
        $css = str_replace($settingscss, $replacements, $css);
    }

    // Settings based CSS is not applied conditionally.
    $css = $processor->replace_tokens(array('customcss' => css_processor::$DEFAULT_CUSTOMCSS), $css);

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
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'favicon' || $filearea === 'backgroundimage' || $filearea === 'headerbgimg')) {
        $theme = theme_config::load('oaklandcustomresponsive');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    send_file_not_found();
}
