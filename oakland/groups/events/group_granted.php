<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User created event.
 *
 * @package    core
 * @copyright  2013 Rajesh Taneja <rajesh@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace oakland\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event when new user profile is created.
 *
 * @package    core
 * @since      Moodle 2.6
 * @copyright  2013 Rajesh Taneja <rajesh@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class group_granted extends base {
     protected $in_groupName;
	 
    /**
     * Initialise required event data properties.
     */
    protected function init() {
        $this->data['objecttable'] = 'user';
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return 'group granted';
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        return "user with id '$this->objectid' granted access to a group.";
    }

    public static function get_legacy_eventname() {
        return 'group_granted';
    }

    /**
     * Return user_created legacy event data.
     *
     * @return \stdClass user data.
     */
    protected function get_legacy_eventdata() {
        return $this->get_record_snapshot('user', $this->objectid);
    }

    /**
     * Returns array of parameters to be passed to legacy add_to_log() function.
     *
     * @return array
     */
    protected function get_legacy_logdata() {
        return array(SITEID, 'user', 'group_granted', '/view.php?id='.$this->objectid, fullname($this->get_legacy_eventdata()));
    }

    public setGroupName($groupName) {
		$in_groupName = $groupName;
	}
	
	 public getGroupName() {
		return $in_groupName;
	}
 
    /**
     * Create instance of event.
     *
     * @since Moodle 2.6.4, 2.7.1
     *
     * @param int $userid id of user
     * @return user_created
     */
    public static function create_from_userid($userid, $groupName) {
        $data = array(
            'objectid' => $userid,
            'relateduserid' => $userid,
            'context' => \context_user::instance($userid)
        );

		
        // Create user_created event.
        $event = self::create($data);
		$event::setGroupName($groupName);
        return $event;
    }

    public static function get_objectid_mapping() {
        return array('db' => 'user', 'restore' => 'user');
    }
}
