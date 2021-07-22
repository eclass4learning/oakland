<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2015 onwards Totara Learning Solutions LTD
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
 * @author Petr Skoda <petr.skoda@totaralms.com>
 * @package totara_reportbuilder
 */

namespace totara_reportbuilder\rb\aggregate;

/**
 * Class describing column aggregation options.
 */
class percent extends base {
    protected static function get_field_aggregate($field) {
        global $DB;

        $dbfamily = $DB->get_dbfamily();
        if ($dbfamily === 'mssql') {
            return "AVG(1.0*$field)*100.0";
        } else {
            return "AVG($field)*100.0";
        }
    }

    public static function get_displayfunc(\rb_column $column) {
        return 'percent';
    }

    public static function is_column_option_compatible(\rb_column_option $option) {
        return ($option->dbdatatype === 'boolean');
    }

    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report) {
        return true;
    }
}
