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
 * @author Michael Dunstan <michael.dunstan@androgogic.com>
 * @package totara_contentmarketplace
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'totara_contentmarketplace';
$plugin->release   = '2.0';
$plugin->version   = 2018112200;
$plugin->requires  = 2017051509; // Totara 9+ is required.
$plugin->maturity  = MATURITY_STABLE;
$plugin->dependencies = [
    'mod_scorm' => 2016120501.01,
];
