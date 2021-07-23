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
 * @author Nathan Lewis <nathan.lewis@totaralearning.com>
 * @package core_course
 * @category totara_catalog
 */
namespace core_course\totara_catalog\course\filter_factory;

defined('MOODLE_INTERNAL') || die();

use totara_catalog\filter_factory;
use totara_catalog\provider_handler;
use totara_customfield\totara_catalog\filter_factory as customfield_filter_factory;

class custom_fields extends filter_factory {

    public static function get_filters(): array {
        return customfield_filter_factory::get_filters(
            'course',
            'course',
            provider_handler::instance()->get_provider('course')
        );
    }
}