<?php

/**
* @copyright  Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
* Copyright (C) 2011 Catalyst IT Ltd (http://www.catalyst.net.nz)
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
*
* @author     Chris Stones
* @author     Piers Harding
* @license    http://opensource.org/licenses/gpl-3.0.html     GNU Public License
*/

/**
 * Google Services Access
 *
 * Development plans:
 * All services we support will have links and icons
 * Optional Google Icon Set
 *
 * @author Chris Stones
 * @version $Id$
 * @package block_gaccess
 **/
class block_gaccess extends block_list {

    function init() {
        $this->title   = get_string('pluginname', 'block_gaccess');
    }

    /**
     * Default case: the block can be used in all course types
     * @return array
     * @todo finish documenting this function
     */
    function applicable_formats() {
        // Default case: the block can be used in courses and site index, but not in activities
        return array('all' => true, 'site' => true);
    }

    function has_config() {
        return true;
    }

    function get_content() {
        global $DB, $CFG, $USER, $COURSE, $OUTPUT;


        // quick and simple way to prevent block from showing up on front page
        if (!isloggedin()) {
            $this->content = NULL;
            return $this->content;
        }

        // Quick and simple way to prevent block from showing up on users My Moodle if their email does not match the Google registered domain.
        $domain = (get_config('blocks/gaccess','domainname') ? get_config('blocks/gaccess','domainname') : false);

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
	$this->content->footer = '';


        $instance = $this->instance;
	if ($instance->subpagepattern > 0) {
	    $dashboard_user = $DB->get_record('totara_dashboard_user', array('id'=>$instance->subpagepattern));
	    $dashboard = $DB->get_record('totara_dashboard', array('id'=>$dashboard_user->dashboardid));
	    $oakland_group = $DB->get_record('oakland_groups', array('id'=>$dashboard->oaklandgroupid));
	}
        // Test for domain settings
        if( empty($domain)) {
            $this->content->items = array(get_string('mustusegoogleauthentication', 'block_gaccess'));
            $this->content->icons = array();
            return $this->content;
        }

        // USE the icons from this page
        // https://www.google.com/a/cpanel/mroomsdev.com/Dashboard
        // Google won't mind ;) (I hope)
	$google_services = array();
        if (get_config('blocks/gaccess','gmail')) {
            $google_services []=
                array(
                        'service'   => 'Gmail',
                        'relayurl'  => 'http://mail.google.com/a/'.$domain,
                        'icon_name' => 'gmail'
                );
        }

	if (isset($oakland_group) && $oakland_group->g_calendar) {
	    $google_services []=
                array(
                        'service'   => 'Calendar',
                        'relayurl'  => $oakland_group->g_calendar,
                        'icon_name' => 'calendar'
                );
	}

        if (isset($oakland_group) && $oakland_group->g_drive) {
            $google_services []=
                array(
                        'service'   => 'Drive',
                        'relayurl'  => $oakland_group->g_drive,
                        'icon_name' => 'gdocs'
                );
        }

        $newwinlnk = get_config('blocks/gaccess','newwinlink');
        if ($newwinlnk) {
            $target = 'target=\"_new\"';
        }
        else {
            $target = '';
        }

        foreach ($google_services as $gs) {

            if (!empty($gs['icon_name'])) {
                $icon = $OUTPUT->pix_icon($gs['icon_name'], $gs['service'], 'block_gaccess');
            } else {
                // Default to a check graphic
                $icon = $OUTPUT->pix_icon('i/valid', $gs['service']);
            }
            $this->content->items[] = "<a ".$target.". title=\"".$gs['service']."\"  href=\"".$gs['relayurl']."\">".$icon . '&nbsp;' . $gs['service']."</a>";
        }

        return $this->content;
    }
}
