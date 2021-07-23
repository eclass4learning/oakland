<?php
/*
 * This file is part of Totara Learn
 *
 * Copyright (C) 2018 onwards Totara Learning Solutions LTD
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
 * @author Sam Hemelryk <sam.hemelryk@totaralearning.com>
 * @package scormreport_interactions
 */

namespace scormreport_interactions\userdata;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/user/tests/userdata_plugin_preferences_testcase.php');

/**
 * @group totara_userdata
 */
class scormreport_interactions_userdata_preferences_testcase extends \core_user_userdata_plugin_preferences_testcase {

    protected function get_preferences_class(): string {
        return preferences::class;
    }

    protected function get_preferences(): array {
        return [
            'scorm_report_interactions_qtext' => [true, false],
            'scorm_report_interactions_resp' => [true, false],
            'scorm_report_interactions_right' => [true, false],
            'scorm_report_interactions_result' => [true, false],
        ];
    }

}
