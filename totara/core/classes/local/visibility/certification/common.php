<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
 * @package totara_core
 */

namespace totara_core\local\visibility\certification;

defined('MOODLE_INTERNAL') || die();

/**
 * Methods common to all forms of Certification visibility.
 *
 * @internal
 */
trait common {

    /**
     * Overridden constructor used to ensure requirements are loaded.
     */
    public function __construct() {
        global $CFG;
        require_once($CFG->dirroot . '/totara/program/lib.php');
    }

    /**
     * Returns an instance of the certification map.
     *
     * @return map
     */
    public function map(): \totara_core\local\visibility\map {
        return new map();
    }

    /**
     * Returns an array containing category id's and a count of the certifications the given user can see within it.
     *
     * @param int $userid
     * @return int[] The key is the category id, the value is the count of visible items.
     */
    public function get_visible_counts_for_all_categories(int $userid): array {
        return $this->program_common_get_visible_counts_for_all_categories($userid, true);
    }

    /**
     * Returns an array of certifications visible to the given user within the given category.
     *
     * @param int $categoryid
     * @param int $userid
     * @param array $fields The fields to fetch for the item.
     * @return array The resulting items.
     */
    public function get_visible_in_category(int $categoryid, int $userid, array $fields = ['*']): array {
        return $this->program_common_get_visible_in_category($categoryid, $userid, $fields, true);
    }

    /**
     * Returns an SQL snippet the resolves whether the user has an assignment on certification given its ID field.
     *
     * @param int $userid
     * @param string $field_id
     * @return array An array with two items, string:SQL, array:params
     */
    protected function sql_user_assignment(int $userid, string $field_id): array {
        return $this->program_common_sql_user_assignment($userid, $field_id);
    }

    /**
     * Returns an SQL snippet that resolves whether the certification is currently available or not.
     *
     * @param int $userid
     * @param string $prog_tablealias
     * @return array|null An array with two items, string:SQL, array:params OR null if there is no availability to calculate.
     */
    protected function get_availability_sql(int $userid, string $prog_tablealias): ?array {
        return get_programs_availability_sql($prog_tablealias, $this->sql_separator(), $userid);
    }

}