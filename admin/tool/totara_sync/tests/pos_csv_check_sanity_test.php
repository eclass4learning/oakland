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
 * @author Simon Player <simon.player@totaralearning.com>
 * @package tool_totara_sync
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/admin/tool/totara_sync/lib.php');
require_once($CFG->dirroot . '/admin/tool/totara_sync/tests/source_csv_testcase.php');
require_once($CFG->dirroot . '/admin/tool/totara_sync/sources/source_pos_csv.php');

/**
 * @group tool_totara_sync
 */
class tool_totara_sync_pos_csv_check_sanity_testcase extends totara_sync_csv_testcase {

    protected $filedir      = null;
    protected $configcsv    = [];
    protected $config       = [];
    protected $elementname  = 'pos';
    protected $sourcename   = 'totara_sync_source_pos_csv';
    protected $source       = null;

    protected $pos_framework_data1 = [
        'id' => 1,
        'fullname'          => 'Position Framework 1',
        'shortname'         => 'PFW1',
        'idnumber'          => 'PF1',
        'description'       => 'Description 1',
        'sortorder'         => 1,
        'visible'           => 1,
        'hidecustomfields'  => 0,
        'timecreated'       => 1265963591,
        'timemodified'      => 1265963591,
        'usermodified'      => 2
    ];

    protected function tearDown() {
        $this->filedir                      = null;
        $this->configcsv                    = null;
        $this->config                       = null;
        $this->pos_framework_data1          = null;
        $this->type_data1                   = null;
        $this->source                       = null;
        parent::tearDown();
    }

    public function setUp() {
        global $CFG;

        parent::setUp();

        $this->resetAfterTest(true);
        $this->setAdminUser();

        $this->source = new $this->sourcename();

        $this->filedir = $CFG->dataroot . '/totara_sync';
        mkdir($this->filedir . '/csv/ready', 0777, true);

        set_config('element_pos_enabled', 1, 'totara_sync');
        set_config('source_pos', 'totara_sync_source_pos_csv', 'totara_sync');
        set_config('fileaccess', FILE_ACCESS_DIRECTORY, 'totara_sync');
        set_config('filesdir', $this->filedir, 'totara_sync');

        // Create a Position framework.
        $this->loadDataSet($this->createArrayDataset([
            'pos_framework' => [$this->pos_framework_data1]
        ]));

        $this->configcsv = [
            'csvuserencoding'                   => 'UTF-8',
            'delimiter'                         => ',',
            'csvsaveemptyfields'                => true,

            'fieldmapping_idnumber'             => '',
            'fieldmapping_fullname'             => '',
            'fieldmapping_frameworkidnumber'    => '',
            'fieldmapping_timemodified'         => '',

            'fieldmapping_shortname'            => '',
            'fieldmapping_description'          => '',
            'fieldmapping_parentidnumber'       => '',
            'fieldmapping_typeidnumber'         => '',

            'import_shortname'                  => '0',
            'import_description'                => '0',
            'import_parentidnumber'             => '0',
            'import_typeidnumber'               => '0',
            'import_frameworkidnumber'          => '1'
        ];
        $this->config = [
            'sourceallrecords'  => '1',
            'allow_create'      => '1',
            'allow_delete'      => '0',
            'allow_update'      => '1',
        ];
    }

    public function importfields() {
        $importfields = [];
        $importfields['import_idnumber']            = 1;
        $importfields['import_fullname']            = 1;
        $importfields['import_shortname']           = 0;
        $importfields['import_frameworkidnumber']   = 1;
        $importfields['import_timemodified']        = 1;
        $importfields['import_description']         = 0;
        $importfields['import_deleted']             = 0;

        return $importfields;
    }

    public function test_pos_csv_field_mappings_incorrect() {
        global $DB;

        $this->assertCount(0, $DB->get_records('pos'));

        // Set the element config.
        $this->set_element_config($this->config);

        // Using a mapping of fullname to name.
        $additional_fields = ['fieldmapping_fullname' => 'posname'];
        $config = array_merge($this->configcsv, $this->importfields(), $additional_fields);
        $this->set_source_config($config);
        $this->add_csv('positions_field_mapping_1.csv', 'pos');
        $error = '';
        try {
            $this->sync();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertEquals("CSV file not valid, missing field \"fullname\" (mapping for \"posname\")", $error);
        $this->assertCount(0, $DB->get_records('pos'));
    }

    public function test_pos_csv_field_mappings_correct() {
        global $DB;

        $this->assertCount(0, $DB->get_records('pos'));

        // Set the element config.
        $this->set_element_config($this->config);

        // Using a mapping of fullname to name.
        $additional_fields = ['fieldmapping_fullname' => 'name'];
        $config = array_merge($this->configcsv, $this->importfields(), $additional_fields);
        $this->set_source_config($config);
        $this->add_csv('positions_field_mapping_1.csv', 'pos');
        $this->sync();

        $records = $DB->get_records('pos');
        $this->assertCount(1, $records);
        $this->assertEquals('Position 1', current($records)->fullname);
    }

    public function test_pos_csv_field_mappings_for_types() {
        global $DB;

        // Setup type and customfields
        $hierarchygenerator = $this->getDataGenerator()->get_plugin_generator('totara_hierarchy');
        $typeid = $hierarchygenerator->create_pos_type(['idnumber' => 'T1', 'fullname' => 'Type 1']);

        $text_customfield_data = [
            'hierarchy'    => 'position',
            'typeidnumber' => 'T1',
            'shortname'    => 'cf1',
            'value'        => '',
        ];
        $hierarchygenerator->create_hierarchy_type_text($text_customfield_data);

        $this->assertCount(0, $DB->get_records('pos'));

        // Set the element config.
        $this->set_element_config($this->config);

        $additional_fields = [
            'import_shortname'                       => '1',
            'import_typeidnumber'                    => '1',
            'import_customfield_' . $typeid . '_cf1' => '1',

            'fieldmapping_shortname'    => 'sname',
            'fieldmapping_idnumber'     => 'id',
            'fieldmapping_typeidnumber' => 'typeid',
        ];
        $config = array_merge($this->configcsv, $this->importfields(), $additional_fields);
        $this->set_source_config($config);
        $this->add_csv('positions_field_mapping_2.csv', 'pos');
        $this->sync();

        $records = $DB->get_records('pos');
        $this->assertCount(1, $records);
        $this->assertEquals('Position 1', current($records)->fullname);
    }
}
