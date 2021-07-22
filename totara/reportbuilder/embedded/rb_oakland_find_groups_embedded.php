<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/8/15
 * Time: 1:15 PM
 */

class rb_oakland_find_groups_embedded extends rb_base_embedded {

    public $url, $source, $fullname, $filters, $columns;
    public $contentmode, $contentsettings, $embeddedparams;
    public $hidden, $accessmode, $accesssettings, $shortname;

    public function __construct($data) {

        $this->url = '/oakland/groups/findgroups.php';
        $this->source = 'oakland_group';
        $this->shortname = 'oakland_find_groups';
        $this->fullname = get_string('oakland_find_groups_report', 'oakland_groups');

        // define columns to display
        $this->columns = array(
            array(
                'type' => 'base',
                'value' => 'addtogrouplink',
                'heading' => get_string('name', 'oakland_groups')
            ),
            array(
                'type' => 'user',
                'value' => 'groupcreator',
                'heading' => get_string('groupcreator', 'oakland_groups')
            ),
            array(
                'type' => 'base',
                'value' => 'purpose',
                'heading' => get_string('purpose', 'oakland_groups')
            ),
            array(
                'type' => 'courselastupdated',
                'value' => 'event_time',
                'heading' => get_string('courselastupdated', 'oakland_groups')
            ),
            array(
                'type' => 'members',
                'value' => 'member_count',
                'heading' => get_string('member_count', 'oakland_groups')
            )
        );

        // filters
        $this->filters = array(
            array(
                'type' => 'base',
                'value' => 'name',
                'advanced' => 0,
            ),
            array(
                'type' => 'user',
                'value' => 'groupcreator',
                'advanced' => 0,
            ),
            array(
                'type' => 'base',
                'value' => 'purpose',
                'advanced' => 0,
            )
        );

        $this->toolbarsearchcolumns = array(
            array(
                'type' => 'base',
                'value' => 'addtogrouplink'
            ),
            array(
                'type' => 'base',
                'value' => 'purpose'
            ),
            array(
                'type' => 'base',
                'value' => 'topics'
            )
        );

        // No restrictions.
        $this->contentmode = REPORT_BUILDER_CONTENT_MODE_NONE;

        // only show visible groups
        $this->embeddedparams = array();
        $this->embeddedparams['hidden'] = 0;

        parent::__construct();
    }

    /**
     * Check if the user is capable of accessing this report.
     * We use $reportfor instead of $USER->id and $report->get_param_value() instead of getting report params
     * some other way so that the embedded report will be compatible with the scheduler (in the future).
     *
     * @param int $reportfor userid of the user that this report is being generated for
     * @param reportbuilder $report the report object - can use get_param_value to get params
     * @return boolean true if the user can access this report
     */
    public function is_capable($reportfor, $report) {
        global $DB;
        return true;
    }
}
