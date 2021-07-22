<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
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
 * @package totara_core
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Test function from totara/core/totara.php file.
 */
class totara_core_totara_testcase extends advanced_testcase {
    public function test_totara_major_version() {
        global $CFG;

        $majorversion = totara_major_version();
        $this->assertInternalType('string', $majorversion);
        $this->assertRegExp('/^[0-9]+$/', $majorversion);

        $TOTARA = null;
        require("$CFG->dirroot/version.php");
        $this->assertSame(0, strpos($TOTARA->version, $majorversion));

        // Make sure the totara_major_version() is actually used in lang pack downloads.
        require_once("$CFG->dirroot/lib/componentlib.class.php");
        $installer = new lang_installer();
        $this->assertSame('https://download.totaralms.com/lang/T' . $majorversion . '/', $installer->lang_pack_url());
    }
}

