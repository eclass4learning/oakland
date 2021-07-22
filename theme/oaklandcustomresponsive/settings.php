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

/**
 * Settings for the crownequipmentresponsive theme
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Logo file setting.
    $name = 'theme_oaklandcustomresponsive/logo';
    $title = new lang_string('logo', 'theme_oaklandcustomresponsive');
    $description = new lang_string('logodesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Header background file setting.
    $name = 'theme_oaklandcustomresponsive/headerbgimg';
    $title = new lang_string('headerbgimg', 'theme_oaklandcustomresponsive');
    $description = new lang_string('headerbgimgdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerbgimg');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Page header background image opacity setting.
    $name = 'theme_oaklandcustomresponsive/headerbgimgopacity';
    $title = new lang_string('headerbgimgopacity','theme_oaklandcustomresponsive');
    $description = new lang_string('headerbgimgopacitydesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Body background file setting.
    $name = 'theme_oaklandcustomresponsive/bodybgimg';
    $title = new lang_string('bodybgimg', 'theme_oaklandcustomresponsive');
    $description = new lang_string('bodybgimgdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'bodybgimg');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Body background image opacity setting.
    $name = 'theme_oaklandcustomresponsive/bodybgimgopacity';
    $title = new lang_string('bodybgimgopacity','theme_oaklandcustomresponsive');
    $description = new lang_string('bodybgimgopacitydesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Favicon file setting.
    $name = 'theme_oaklandcustomresponsive/favicon';
    $title = new lang_string('favicon', 'theme_oaklandcustomresponsive');
    $description = new lang_string('favicondesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, array('accepted_types' => '.ico'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Link colour setting.
    $name = 'theme_oaklandcustomresponsive/linkcolor';
    $title = new lang_string('linkcolor', 'theme_oaklandcustomresponsive');
    $description = new lang_string('linkcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#087BB1';
    $previewconfig = array('selector' => 'a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    //Link visited colour setting.
    $name = 'theme_oaklandcustomresponsive/linkvisitedcolor';
    $title = new lang_string('linkvisitedcolor', 'theme_oaklandcustomresponsive');
    $description = new lang_string('linkvisitedcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#087BB1';
    $previewconfig = array('selector' => 'a:visited', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Page header background colour setting.
    $name = 'theme_oaklandcustomresponsive/headerbgc';
    $title = new lang_string('headerbgc', 'theme_oaklandcustomresponsive');
    $description = new lang_string('headerbgcdesc', 'theme_oaklandcustomresponsive');
    $default = '';
    $previewconfig = array('selector' => '#page-header', 'style' => 'background');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Page header background colour 2 setting for Gradient.
    $name = 'theme_oaklandcustomresponsive/headergradient';
    $title = new lang_string('headergradient', 'theme_oaklandcustomresponsive');
    $description = new lang_string('headerdescgradient', 'theme_oaklandcustomresponsive');
    $default = '';
    $previewconfig = array('selector' => '#page-header', 'style' => 'background');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Block header background colour setting.
    $name = 'theme_oaklandcustomresponsive/blockheadercolor';
    $title = new lang_string('blockheadercolor', 'theme_oaklandcustomresponsive');
    $description = new lang_string('blockheadercolordesc', 'theme_oaklandcustomresponsive');
    $default = '#fc9920';
    $previewconfig = array('selector' => '.block .header h2', 'style' => 'background');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Block header background top gradient colour setting.
    $name = 'theme_oaklandcustomresponsive/blockheadergradientstart';
    $title = new lang_string('blockheadergradientstart', 'theme_oaklandcustomresponsive');
    $description = new lang_string('blockheadergradientstartdesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => '.block .header h2', 'style' => 'background');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Block header text colour setting.
    $name = 'theme_oaklandcustomresponsive/blockheadertextcolor';
    $title = new lang_string('blockheadertextcolor', 'theme_oaklandcustomresponsive');
    $description = new lang_string('blockheadertextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#4e4546';
    $previewconfig = array('selector' => '.block .header h2', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Block body background colour setting.
    $name = 'theme_oaklandcustomresponsive/blockcontentcolor';
    $title = new lang_string('blockcontentcolor', 'theme_oaklandcustomresponsive');
    $description = new lang_string('blockcontentcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => '.block .content', 'style' => 'background');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Button colour setting.
    $name = 'theme_oaklandcustomresponsive/buttoncolor';
    $title = new lang_string('buttoncolor','theme_oaklandcustomresponsive');
    $description = new lang_string('buttoncolordesc', 'theme_oaklandcustomresponsive');
    $default = '#E6E6E6';
    $previewconfig = array('selector'=>'input[\'type=submit\']]', 'style'=>'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

//--------------------NEW----------------------

    // Button text color setting.
    $name = 'theme_oaklandcustomresponsive/buttontextcolor';
    $title = new lang_string('buttontextcolor','theme_oaklandcustomresponsive');
    $description = new lang_string('buttontextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#FFFFFF';
    $previewconfig = array('selector'=>'input[\'type=submit\']]', 'style'=>'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Menu Color
    $name = 'theme_oaklandcustomresponsive/menucolor';
    $title = get_string('menucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('menucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => 'div#custommenu ul li a', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Menu Text Color
    $name = 'theme_oaklandcustomresponsive/menutextcolor';
    $title = get_string('menutextcolor', 'theme_oaklandcustomresponsive');
    $description = get_string('menutextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#000000';
    $previewconfig = array('selector' => 'div#custommenu ul li a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Selected Menu Color
    $name = 'theme_oaklandcustomresponsive/selectedmenucolor';
    $title = get_string('selectedmenucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('selectedmenucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => 'div#custommenu ul li a', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Selected Menu Text Color
    $name = 'theme_oaklandcustomresponsive/selectedmenutextcolor';
    $title = get_string('selectedmenutextcolor', 'theme_oaklandcustomresponsive');
    $description = get_string('selectedmenutextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#000000';
    $previewconfig = array('selector' => 'div#custommenu ul li a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Sub Menu Item Color
    $name = 'theme_oaklandcustomresponsive/submenucolor';
    $title = get_string('submenucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('submenucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#000000';
    $previewconfig = array('selector' => 'div#totaramenu ul li li a', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Sub Menu Text Color
    $name = 'theme_oaklandcustomresponsive/submenutextcolor';
    $title = get_string('submenutextcolor', 'theme_oaklandcustomresponsive');
    $description = get_string('submenutextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => 'div#totaramenu ul li li a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Selected Sub-Menu Color
    $name = 'theme_oaklandcustomresponsive/selectedsubmenucolor';
    $title = get_string('selectedsubmenucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('selectedsubmenucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#000000';
    $previewconfig = array('selector' => 'div#custommenu ul li.selected li.selected a', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Selected Sub-Menu Text Color
    $name = 'theme_oaklandcustomresponsive/selectedsubmenutextcolor';
    $title = get_string('selectedsubmenutextcolor', 'theme_oaklandcustomresponsive');
    $description = get_string('selectedsubmenutextcolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => 'div#custommenu ul li.selected li.selected a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);


    // Hover Menu Item Color
    $name = 'theme_oaklandcustomresponsive/hovermenucolor';
    $title = get_string('hovermenucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('hovermenucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#808080';
    $previewconfig = array('selector' => 'div#totaramenu ul li a:hover', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Hover Sub Menu Item Color
    $name = 'theme_oaklandcustomresponsive/hoversubmenucolor';
    $title = get_string('hoversubmenucolor', 'theme_oaklandcustomresponsive');
    $description = get_string('hoversubmenucolordesc', 'theme_oaklandcustomresponsive');
    $default = '#808080';
    $previewconfig = array('selector' => 'div#custommenu ul li li a:hover', 'style' => 'background-color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h1>
    $name = 'theme_oaklandcustomresponsive/h1size';
    $title = get_string('h1size', 'theme_oaklandcustomresponsive');
    $description = get_string('h1sizedesc', 'theme_oaklandcustomresponsive');
    $default = '28';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h2>
    $name = 'theme_oaklandcustomresponsive/h2size';
    $title = get_string('h2size', 'theme_oaklandcustomresponsive');
    $description = get_string('h2sizedesc', 'theme_oaklandcustomresponsive');
    $default = '24';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h3>
    $name = 'theme_oaklandcustomresponsive/h3size';
    $title = get_string('h3size', 'theme_oaklandcustomresponsive');
    $description = get_string('h3sizedesc', 'theme_oaklandcustomresponsive');
    $default = '18';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h4>
    $name = 'theme_oaklandcustomresponsive/h4size';
    $title = get_string('h4size', 'theme_oaklandcustomresponsive');
    $description = get_string('h4sizedesc', 'theme_oaklandcustomresponsive');
    $default = '16';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h5>
    $name = 'theme_oaklandcustomresponsive/h5size';
    $title = get_string('h5size', 'theme_oaklandcustomresponsive');
    $description = get_string('h5sizedesc', 'theme_oaklandcustomresponsive');
    $default = '12';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <h6>
    $name = 'theme_oaklandcustomresponsive/h6size';
    $title = get_string('h6size', 'theme_oaklandcustomresponsive');
    $description = get_string('h6sizedesc', 'theme_oaklandcustomresponsive');
    $default = '10';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // <p>
    $name = 'theme_oaklandcustomresponsive/ptagsize';
    $title = get_string('ptagsize', 'theme_oaklandcustomresponsive');
    $description = get_string('ptagsizedesc', 'theme_oaklandcustomresponsive');
    $default = '14';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);  //logged_in_username

    // Logged in Username Color
    $name = 'theme_oaklandcustomresponsive/logged_in_usernamecolor';
    $title = get_string('logged_in_usernamecolor', 'theme_oaklandcustomresponsive');
    $description = get_string('logged_in_usernamecolordesc', 'theme_oaklandcustomresponsive');
    $default = '#ffffff';
    $previewconfig = array('selector' => 'header-user a', 'style' => 'color');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    //-------------BEGIN OAKLAND CUSTOM-------------------

    global $DB;
    $dashboards = $DB->get_records('totara_dashboard');
    $choices = array();
    foreach($dashboards as $dashboard){
        $choices[$dashboard->id] = $dashboard->name;
    }

    // Collabortorium URL/Dashboard setting.
    $name = 'theme_oaklandcustomresponsive/collabdashboard';
    $title = new lang_string('collabdashboard', 'theme_oaklandcustomresponsive');
    $description = new lang_string('collabdashboarddesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configselect($name, $title, $description,1,$choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // HUB URL/Dashboard Setting
    $name = 'theme_oaklandcustomresponsive/hubdashboard';
    $title = new lang_string('hubdashboard', 'theme_oaklandcustomresponsive');
    $description = new lang_string('hubdashboarddesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configselect($name, $title, $description,2, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Contact Text Setting
    $name = 'theme_oaklandcustomresponsive/contacttext';
    $title = new lang_string('contacttext', 'theme_oaklandcustomresponsive');
    $description = new lang_string('contacttextdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description,'',PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // About Text Setting
    $name = 'theme_oaklandcustomresponsive/abouttext';
    $title = new lang_string('abouttext', 'theme_oaklandcustomresponsive');
    $description = new lang_string('abouttextdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description,'',PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // 'First Time Here' Text Setting
    $name = 'theme_oaklandcustomresponsive/firsttime';
    $title = new lang_string('firsttime', 'theme_oaklandcustomresponsive');
    $description = new lang_string('firsttimedesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description,'',PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // 'Copyright' Text Setting
    $name = 'theme_oaklandcustomresponsive/copyright';
    $title = new lang_string('copyright', 'theme_oaklandcustomresponsive');
    $description = new lang_string('copyrightdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtext($name, $title, $description,'',PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    //-------------END OAKLAND CUSTOM-------------------


//-------------END_NEW------------------

    // Custom CSS file.
    $name = 'theme_oaklandcustomresponsive/customcss';
    $title = new lang_string('customcss','theme_oaklandcustomresponsive');
    $description = new lang_string('customcssdesc', 'theme_oaklandcustomresponsive');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

}
