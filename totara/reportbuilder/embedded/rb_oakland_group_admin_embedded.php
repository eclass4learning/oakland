<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/8/15
 * Time: 1:15 PM
 */

class rb_oakland_group_admin_embedded extends rb_base_embedded {

    public $url, $source, $fullname, $filters, $columns;
    public $contentmode, $contentsettings, $embeddedparams;
    public $hidden, $accessmode, $accesssettings, $shortname;

    public function __construct($data) {

        $this->url = '/oakland/groups/index.php';
        $this->source = 'oakland_group';
        $this->shortname = 'oakland_group_admin';
        $this->fullname = get_string('oakland_group_admin_report', 'oakland_groups');
        $this->columns = array(
            array(
                'type' => 'base',
                'value' => 'name',
                'heading' => get_string('name', 'oakland_groups')
            ),
            array(
                'type' => 'audience',
                'value' => 'name',
                'heading' => get_string('audiencename', 'oakland_groups')
            ),
            array(
                'type' => 'dashboard',
                'value' => 'name',
                'heading' => get_string('dashboardname', 'oakland_groups')
            ),
            array(
                'type' => 'course',
                'value' => 'name',
                'heading' => get_string('coursename', 'oakland_groups')
            ),
            array(
                'type' => 'base',
                'value' => 'datecreated',
                'heading' => get_string('datecreated', 'oakland_groups')
            ),
            array(
                'type' => 'user',
                'value' => 'owner',
                'heading' => get_string('owner', 'oakland_groups')
            ),
//            array( //TODO: For Group Status (Active v. Inactive)
//                'type' => 'base',
//                'value' => 'status',
//                'heading' => get_string('actions', 'oakland_groups')
//            ),
            array(
                'type' => 'base',
                'value' => 'actions',
                'heading' => get_string('actions', 'oakland_groups')
            )
        );

        // no filters
        $this->filters = array(
            array(
                'type' => 'base',
                'value' => 'name',
                'advanced' => 0,
            ),
            array(
                'type' => 'base',
                'value' => 'datecreated',
                'advanced' => 0,
            ),
            array(
                'type' => 'user',
                'value' => 'owner',
                'advanced' => 0,
            ),
        );

        // No restrictions.
        $this->contentmode = REPORT_BUILDER_CONTENT_MODE_NONE;

//        // Set the context.
//        if (isset($contextid)) {
//            $this->embeddedparams['contextid'] = $contextid;
//        }

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

        $contextid = $report->get_param_value('contextid');
        if ($contextid) {
            $context = context::instance_by_id($contextid, MUST_EXIST);
        } else {
            $context = context_system::instance();
        }

        if ($context->contextlevel != CONTEXT_COURSECAT && $context->contextlevel != CONTEXT_SYSTEM) {
            return false;
        }

        if ($context->contextlevel == CONTEXT_COURSECAT) {
            $category = $DB->get_record('course_categories', array('id'=>$context->instanceid));
            if (empty($category)) {
                return false;
            }
        }

        if (!has_capability('moodle/cohort:manage', $context, $reportfor) &&
            !has_capability('moodle/cohort:view', $context, $reportfor)) {
            return false;
        }

        return true;
    }
}
