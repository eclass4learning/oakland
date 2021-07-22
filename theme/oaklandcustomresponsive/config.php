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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @author Brian Barnes <brian.barnes@totaralms.com>
 * @package totara
 * @subpackage theme
 */

$THEME->name = 'oaklandcustomresponsive';
$THEME->parents = array('standardtotararesponsive', 'bootstrapbase', 'base');
$THEME->sheets = array(
    'core',     /** Must come first**/
    'admin',
    'blocks',
    'calendar',
    'course',
    'user',
    'dock',
    'grade',
    'message',
    'modules',
    'question',
    'pagelayout',
    'myfontswebfontskit',
    'settings',
    'custom_navigation',
    'custom_login'
);

$THEME->layouts = array(
    // The site home page.
    'frontpage' => array(
        'file' => 'columns3.php',
        'regions' => array('side-pre', 'side-post', 'center-content'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true),
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),                //TODO: It is possible to more directly link a custom login page to the theme, but I will need to completely copy the current login page's setup before being able to use it.
        'options' => array('nonavbar'=>true),
    ),
);

//if (!(core_useragent::is_ie() && !core_useragent::check_ie_version('10.0'))) {
    $THEME->enable_dock = true;
//}
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_oaklandcustomresponsive_process_css';
