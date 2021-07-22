<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2014 onwards Totara Learning Solutions LTD
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
 * @author David Curry <david.curry@totaralms.com>
 * @package totara_hierarchy
 */

namespace totara_hierarchy\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Triggered when a hierarchies framework is exported.
 *
 * @property-read array $other {
 *      Extra information about the event.
 *
 *      - prefix        The type of hierarchy frameworks exported
 *      - frameworkid   The id of the hierarchy framework exported
 * }
 *
 * @author David Curry <david.curry@totaralms.com>
 * @package totara_hierarchy
 */
class framework_exported extends \core\event\base {

    /**
     * Flag for prevention of direct create() call.
     * @var bool
     */
    protected static $preventcreatecall = true;

    /**
     * Create instance of event.
     *
     * @param string $prefix The type of framework exported (pos/org/comp/goal)
     * @param \stdClass $framework
     * @return framework_exported
     */
    public static function create_from_instance($prefix, $framework) {
        $data = array(
            'context' => \context_system::instance(),
            'other' => array(
                'prefix' => $prefix,
                'frameworkid' => $framework->id,
            ),
        );

        self::$preventcreatecall = false;
        $event = self::create($data);
        self::$preventcreatecall = true;

        return $event;
    }

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string("eventexportedframework", "totara_hierarchy");
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The {$this->data['other']['prefix']} framework: {$this->data['other']['frameworkid']} was exported";
    }

    public function get_url() {
        $urlparams = array('id' => $this->data['other']['frameworkid'], 'prefix' => $this->data['other']['prefix']);
        return new \moodle_url('/totara/hierarchy/framework/index.php', $urlparams);
    }

    public function get_legacy_logdata() {
        $logdata = array();
        $logdata[] = SITEID;
        $logdata[] = $this->data['other']['prefix'];
        $logdata[] = 'export framework';
        $logdata[] = $this->get_url()->out_as_local_url(false);
        $logdata[] = "{$this->data['other']['prefix']} framework: {$this->data['other']['frameworkid']}";

        return $logdata;
    }

    /**
     * Custom validation
     *
     * @throws \coding_exception
     * @return void
     */
    public function validate_data() {
        if (self::$preventcreatecall) {
            throw new \coding_exception('cannot call create() directly, use create_from_instance() instead.');
        }

        parent::validate_data();

        if (empty($this->other['prefix'])) {
            throw new \coding_exception('prefix must be set in $other');
        }

        if (empty($this->other['frameworkid'])) {
            throw new \coding_exception('frameworkid must be set in $other');
        }
    }
}
