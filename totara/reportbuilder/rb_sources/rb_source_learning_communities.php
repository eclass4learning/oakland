<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/7/15
 * Time: 3:09 PM
 */

defined('MOODLE_INTERNAL') || die();

class rb_source_learning_communities extends rb_base_source {
    public $base, $joinlist, $columnoptions, $filteroptions;
    public $contentoptions, $paramoptions, $defaultcolumns;
    public $defaultfilters, $requiredcolumns, $sourcetitle;

    function __construct() {
        $this->base = self::get_base_sql();
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_learning_communities');

        parent::__construct();
    }

    /**
     * Get base sql for learning communities view
     */
    public static function get_base_sql() {
        return "(".
        "SELECT
            mog.id,
            mu.firstname || ' ' || mu.lastname AS group_audience_member,
            mog.name                           AS group_name,
            mc.shortname                       AS course_shortname,
            ccomp.timeenrolled                 AS course_enrolled_date,
            ccomp.timestarted                  AS course_start_date,
            ccomp.status                       AS course_status,
            mc.fullname                        AS course_fullname,
            ccomp.timecompleted                AS course_completion_date,
            mog.topics                         AS group_topic
        FROM
            mdl_cohort cohort,
            mdl_cohort_members mcm,
            mdl_user mu,
            mdl_oakland_groups mog,
            mdl_course mc,
            mdl_course_completions ccomp
        WHERE
            mog.id = cohort.oaklandgroupid
            AND cohort.id = mcm.cohortid
            AND mcm.userid = mu.id
            AND mcm.userid = ccomp.userid
            AND ccomp.course = mc.id
            ORDER BY group_audience_member, group_name, course_shortname".
        ")";
    }

    //
    //
    // Methods for defining contents of source
    //
    //

    protected function define_joinlist() {
        $joinlist = array(

        );
        return $joinlist;
    }

    protected function define_columnoptions() {
        $columnoptions = array(
            new rb_column_option(
                'base',
                'group_audience_member',
                get_string('group_audience_member', 'rb_source_learning_communities'),
                "base.group_audience_member"
            ),
            new rb_column_option(
                'base',
                'group_name',
                get_string('group_name', 'rb_source_learning_communities'),
                "base.group_name"
            ),
            new rb_column_option(
                'base',
                'course_shortname',
                get_string('course_shortname', 'rb_source_learning_communities'),
                "base.course_shortname"
            ),
            new rb_column_option(
                'base',
                'course_enrolled_date',
                get_string('course_enrolled_date', 'rb_source_learning_communities'),
                "base.course_enrolled_date",
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'base',
                'course_start_date',
                get_string('course_start_date', 'rb_source_learning_communities'),
                "base.course_start_date",
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'base',
                'course_status',
                get_string('course_status', 'rb_source_learning_communities'),
                "base.course_status",
                array('displayfunc' => 'completion_status')
            ),
            new rb_column_option(
                'base',
                'course_fullname',
                get_string('course_fullname', 'rb_source_learning_communities'),
                "base.course_fullname"
            ),
            new rb_column_option(
                'base',
                'course_completion_date',
                get_string('course_completion_date', 'rb_source_learning_communities'),
                "base.course_completion_date",
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'base',
                'group_topic',
                get_string('group_topic', 'rb_source_learning_communities'),
                "base.group_topic"
            )
        );
        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'base',
                'group_name',
                get_string('group_name', 'rb_source_learning_communities'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'group_audience_member',
                get_string('group_audience_member', 'rb_source_learning_communities'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'course_shortname',
                get_string('course_shortname', 'rb_source_learning_communities'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'course_status',
                get_string('course_status', 'rb_source_learning_communities'),
                'multicheck',
                array(
                    'selectfunc' => 'completion_status_list'
                )
            ),
            new rb_filter_option(
                'base',
                'course_completion_date',
                get_string('course_completion_date', 'rb_source_learning_communities'),
                'date'
            )
        );
        return $filteroptions;
    }

    protected function define_paramoptions() {
        $paramoptions = array();
        return $paramoptions;
    }

    protected function define_defaultcolumns() {
        $defaultcolumns = array(
            array(
                'type' => 'base',
                'value' => 'group_audience_member',
            ),
            array(
                'type' => 'base',
                'value' => 'group_name',
            ),
            array(
                'type' => 'base',
                'value' => 'course_shortname',
            ),
            array(
                'type' => 'base',
                'value' => 'course_enrolled_date',
            ),
            array(
                'type' => 'base',
                'value' => 'course_start_date',
            ),
            array(
                'type' => 'base',
                'value' => 'course_status',
            ),
            array(
                'type' => 'base',
                'value' => 'course_fullname',
            ),
            array(
                'type' => 'base',
                'value' => 'course_completion_date',
            ),
            array(
                'type' => 'base',
                'value' => 'group_topic',
            )
        );
        return $defaultcolumns;
    }

    protected function define_defaultfilters() {
        $defaultfilters = array(

        );
        return $defaultfilters;
    }

    //
    //
    // Source specific column display methods
    //
    //

    function rb_display_completion_status($status, $row, $isexport) {
        global $CFG;
        require_once($CFG->dirroot.'/completion/completion_completion.php');
        global $COMPLETION_STATUS;

        if (!array_key_exists((int)$status, $COMPLETION_STATUS)) {
            return '';
        }
        $string = $COMPLETION_STATUS[(int)$status];
        if (empty($string)) {
            return '';
        } else {
            return get_string($string, 'completion');
        }
    }

    //
    //
    // Source specific filter display methods
    //
    //

    function rb_filter_completion_status_list() {
        global $CFG;
        require_once($CFG->dirroot.'/completion/completion_completion.php');
        global $COMPLETION_STATUS;

        $statuslist = array();
        foreach ($COMPLETION_STATUS as $key => $value) {
            $statuslist[(string)$key] = get_string($value, 'completion');
        }
        return $statuslist;
    }

} // End of rb_source_learning_communities class.
