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
 * Unit tests for datetimeselector form element
 *
 * This file contains unit test related to datetimeselector form element
 *
 * @package    core_form
 * @category   phpunit
 * @copyright  2012 Rajesh Taneja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/form/datetimeselector.php');
require_once($CFG->libdir.'/formslib.php');

/**
 * Unit tests for MoodleQuickForm_date_time_selector
 *
 * Contains test cases for testing MoodleQuickForm_date_time_selector
 *
 * @package    core_form
 * @category   phpunit
 * @copyright  2012 Rajesh Taneja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_form_datetimeselector_testcase extends advanced_testcase {
    /** @var MoodleQuickForm Keeps reference of dummy form object */
    private $mform;
    /** @var array test fixtures */
    private $testvals;

    protected function tearDown() {
        $this->mform = null;
        $this->testvals = null;
        parent::tearDown();
    }

    /**
     * Initalize test wide variable, it is called in start of the testcase
     */
    protected function setUp() {
        global $CFG;
        parent::setUp();

        $this->resetAfterTest();
        $this->setAdminUser();

        $this->setTimezone('Australia/Perth');

        // Get form data.
        $form = new temp_form_datetime();
        $this->mform = $form->getform();

        // Set test values.
        $this->testvals = array(
            array (
                'minute' => 0,
                'hour' => 0,
                'day' => 1,
                'month' => 7,
                'year' => 2011,
                'usertimezone' => 'America/Moncton',
                'timezone' => 'America/Moncton',
                'timestamp' => 1309489200
            ),
            array (
                'minute' => 0,
                'hour' => 0,
                'day' => 1,
                'month' => 7,
                'year' => 2011,
                'usertimezone' => 'America/Moncton',
                'timezone' => 99,
                'timestamp' => 1309489200
            ),
            array (
                'minute' => 0,
                'hour' => 23,
                'day' => 30,
                'month' => 6,
                'year' => 2011,
                'usertimezone' => 'America/Moncton',
                'timezone' => -4,
                'timestamp' => 1309489200
            ),
            array (
                'minute' => 0,
                'hour' => 23,
                'day' => 30,
                'month' => 6,
                'year' => 2011,
                'usertimezone' => -4,
                'timezone' => 99,
                'timestamp' => 1309489200
            ),
            array (
                'minute' => 0,
                'hour' => 0,
                'day' => 1,
                'month' => 7,
                'year' => 2011,
                'usertimezone' => 0.0,
                'timezone' => 0.0,
                'timestamp' => 1309478400 // 6am at UTC+0
            ),
            array (
                'minute' => 0,
                'hour' => 0,
                'day' => 1,
                'month' => 7,
                'year' => 2011,
                'usertimezone' => 0.0,
                'timezone' => 99,
                'timestamp' => 1309478400 // 6am at UTC+0
            )
        );
    }

    /**
     * Testcase to check exportvalue
     */
    public function test_exportvalue() {
        global $USER;
        $testvals = $this->testvals;

        foreach ($testvals as $vals) {
            // Set user timezone to test value.
            $USER->timezone = $vals['usertimezone'];

            // Create dateselector element with different timezones.
            $elparams = array('optional'=>false, 'timezone' => $vals['timezone']);
            $el = new MoodleQuickForm_date_time_selector('dateselector', null, $elparams);
            $el->_createElements();
            $submitvalues = array('dateselector' => $vals);

            // Totara: tweak the vals to look like real submission.
            $submitvalues['dateselector']['timezone'] = core_date::get_user_timezone($vals['timezone']);
            unset($submitvalues['dateselector']['usertimezone']);

            //Totara added the _raw field for the date in ISO format
            $rawvalue = $vals['year'] . '-' . str_pad($vals['month'], 2, '0', STR_PAD_LEFT)  . '-' . str_pad($vals['day'], 2, '0', STR_PAD_LEFT);
            $rawvalue .= ' ' . str_pad($vals['hour'], 2, '0', STR_PAD_LEFT)  . ':' . str_pad($vals['minute'], 2, '0', STR_PAD_LEFT)  . ':00';
            $exportvalues = $el->exportValue($submitvalues, true);
            ksort($exportvalues);
            $expectedvalues = array('dateselector' => $vals['timestamp'], 'dateselector_raw' => $rawvalue,
                'dateselector_timezone' => core_date::get_user_timezone($vals['timezone'])); // Totara extra.
            ksort($expectedvalues);
            $this->assertSame($expectedvalues, $exportvalues,
                    "Please check if timezones are updated (Site adminstration -> location -> update timezone)");
        }
    }

    /**
     * Testcase to check onQuickformEvent
     */
    public function test_onquickformevent() {
        global $USER;
        $testvals = $this->testvals;
        // Get dummy form for data.
        $mform = $this->mform;

        foreach ($testvals as $vals) {
            // Set user timezone to test value.
            $USER->timezone = $vals['usertimezone'];

            // Create dateselector element with different timezones.
            $elparams = array('optional'=>false, 'timezone' => $vals['timezone']);
            $el = new MoodleQuickForm_date_time_selector('dateselector', null, $elparams);
            $el->_createElements();
            $expectedvalues = array(
                'day' => array($vals['day']),
                'month' => array($vals['month']),
                'year' => array($vals['year']),
                'hour' => array($vals['hour']),
                'minute' => array($vals['minute']),
                'timezone' => core_date::get_user_timezone($vals['timezone']),  // Totara extra.
                );
            $mform->_submitValues = array('dateselector' => $vals['timestamp']);
            $el->onQuickFormEvent('updateValue', null, $mform);
            $this->assertSame($expectedvalues, $el->getValue());
        }
    }

    /**
     * Testcase to check if the icon is visible
     */
    public function test_calendaricon() {
        $mform = $this->mform;

        $elparams = array('optional' => false);
        $el = new MoodleQuickform_date_time_selector('datetimeselector', null, $elparams);
        $el->_createElements();
        $output = $el->toHtml();

        // Should be visible if not frozen.
        $this->assertTrue(strpos($output, 'visibleifjs') !== false);

        $el->freeze();
        $output = $el->toHtml();
        // Should not be visible if frozen.
        $this->assertFalse(strpos($output, 'visibleifjs'));
    }
}

/**
 * Form object to be used in test case
 */
class temp_form_datetime extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        // No definition required.
    }
    /**
     * Returns form reference.
     * @return MoodleQuickForm
     */
    public function getform() {
        $mform = $this->_form;
        // set submitted flag, to simulate submission
        $mform->_flagSubmitted = true;
        return $mform;
    }
}
