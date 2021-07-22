<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/7/15
 * Time: 3:09 PM
 */

defined('MOODLE_INTERNAL') || die();

class rb_source_oakland_group extends rb_base_source {
    public $base, $joinlist, $columnoptions, $filteroptions;
    public $contentoptions, $paramoptions, $defaultcolumns;
    public $defaultfilters, $requiredcolumns, $sourcetitle;

    function __construct() {
        $this->base = '{oakland_groups}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->defaulttoolbarsearchcolumns = $this->define_defaultsearchcolumns();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_oakland_group');

        parent::__construct();
    }

    //
    //
    // Methods for defining contents of source
    //
    //

    protected function define_joinlist() {

        $joinlist = array(
            new rb_join(
                'cohort',
                'LEFT',
                '{cohort}',
                'base.id = cohort.oaklandgroupid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
            new rb_join(
                'course',
                'LEFT',
                '{course}',
                'base.id = course.oaklandgroupid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
            new rb_join(
                'totara_dashboard',
                'LEFT',
                '{totara_dashboard}',
                'base.id = totara_dashboard.oaklandgroupid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
            new rb_join(
                'group_member_count',
                'LEFT',
                '(SELECT oaklandgroupid, COUNT(*) as member_count FROM {cohort} AS mc, {cohort_members} AS mcm WHERE mc.oaklandgroupid IS NOT NULL AND mc.id = mcm.cohortid GROUP BY mc.oaklandgroupid)',
                'base.id = group_member_count.oaklandgroupid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
            new rb_join(
                'course_last_updated',
                'LEFT',
                '(SELECT mc.oaklandgroupid, TO_CHAR(TO_TIMESTAMP(MAX(mlsl.timecreated)),\'DD Mon YYYY\') AS event_time FROM {course} AS mc, {logstore_standard_log} AS mlsl WHERE mc.oaklandgroupid IS NOT NULL AND mc.id = mlsl.courseid GROUP BY mc.oaklandgroupid)',
                'base.id = course_last_updated.oaklandgroupid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            )
        );
        $this->add_user_table_to_joinlist($joinlist,'base','userid');
        return $joinlist;
    }

    protected function define_columnoptions() {
        $columnoptions = array(
            new rb_column_option(
                'base',
                'name',
                get_string('name', 'rb_source_oakland_group'),
                "base.name"
            ),
	    new rb_column_option(
                'base',
                'private',
                get_string('access', 'rb_source_oakland_group'),
		"base.private",
		array(
                    'displayfunc' => 'isprivate',
                )
            ),
            new rb_column_option(
                'base',
                'addtogrouplink',
                get_string('namewithaddtogrouplink', 'rb_source_oakland_group'),
                "base.name",
                array(
                    'displayfunc' => 'addtogrouplink',
                    'extrafields' => array('groupid'=>'base.id')
                )
            ),
            new rb_column_option(
                'audience',
                'name',
                get_string('audience', 'rb_source_oakland_group'),
                "cohort.id",
                array(
                    'joins' => 'cohort',
                    'displayfunc' => 'confirm_exist_aud',
                    'extrafields' => array('groupid'=>'base.id')
                )
            ),
            new rb_column_option(
                'dashboard',
                'name',
                get_string('dashboard', 'rb_source_oakland_group'),
                "totara_dashboard.id",
                array(
                    'joins' => 'totara_dashboard',
                    'displayfunc' => 'confirm_exist_dashboard',
                    'extrafields' => array('groupid'=>'base.id')
                )
            ),
            new rb_column_option(
                'course',
                'name',
                get_string('course', 'rb_source_oakland_group'),
                "course.id",
                array(
                    'joins' => 'course',
                    'displayfunc' => 'confirm_exist_course',
                    'extrafields' => array('groupid'=>'base.id')
                )
            ),
            new rb_column_option(
                'base',
                'datecreated',
                get_string('datecreated', 'rb_source_oakland_group'),
                "base.datecreated" ,
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'user',
                'owner',
                get_string('owner', 'rb_source_oakland_group'),
                "auser.firstname",
                array(
                    'joins' => 'auser',
                    'displayfunc' => 'username',
                    'extrafields' => array('lastname'=>'auser.lastname')
                )
            ),
            new rb_column_option(
                'user',
                'groupcreator',
                get_string('groupcreator', 'rb_source_oakland_group'),
                "concat(auser.firstname, ' ', auser.lastname)",
                array(
                    'joins' => 'auser',
                    'displayfunc' => 'groupcreator',
                    'extrafields' => array('lastname'=>'auser.lastname','userid'=>'auser.id')
                )
            ),
            new rb_column_option(
                'base',
                'actions',
                get_string('actions', 'rb_source_oakland_group'),
                "base.id",
                array(
                    'displayfunc' => 'oakland_group_actions',
                    'extrafields' => array('groupid'=>'base.id')
                )
            ),
            new rb_column_option(
                'base',
                'purpose',
                get_string('purpose', 'rb_source_oakland_group'),
                "base.purpose"
            ),
            new rb_column_option(
                'members',
                'member_count',
                get_string('member_count', 'rb_source_oakland_group'),
                'group_member_count.member_count',
                array(
                    'joins' => 'group_member_count'
                )
            ),
            new rb_column_option(
                'courselastupdated',
                'event_time',
                get_string('courselastupdated', 'rb_source_oakland_group'),
                'course_last_updated.event_time',
                array(
                    'joins' => 'course_last_updated'
                )
            ),
            new rb_column_option(
                'base',
                'topics',
                get_string('topics', 'rb_source_oakland_group'),
                "base.topics"
            )
        );
        $this->add_user_fields_to_columns($columnoptions);
        $this->add_course_fields_to_columns($columnoptions);
        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'base',
                'name',
                get_string('name', 'rb_source_oakland_group'),
                'text'
            ),
	    new rb_filter_option(
                'base',
                'private',
                get_string('access', 'rb_source_oakland_group'),
		'select',
		array('selectchoices' => array( 1 => 'Private', 0 => 'Public'))
            ),
            new rb_filter_option(
                'base',
                'datecreated',
                get_string('datecreated', 'rb_source_oakland_group'),
                'date'
            ),
            new rb_filter_option(
                'user',
                'owner',
                get_string('owner', 'rb_source_oakland_group'),
                'text'
            ),
            new rb_filter_option(
                'user',
                'groupcreator',
                get_string('groupcreator', 'rb_source_oakland_group'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'purpose',
                get_string('purpose', 'rb_source_oakland_group'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'topics',
                get_string('topics', 'rb_source_oakland_group'),
                'text'
            )
        );

        return $filteroptions;
    }

    protected function define_paramoptions() {
        $paramoptions = array();
        $paramoptions[] = new rb_param_option(
            'hidden',
            'base.hidden',
            'base'
        );
        return $paramoptions;
    }

    protected function define_defaultcolumns() {
        $defaultcolumns = array(

        );
        return $defaultcolumns;
    }

    protected function define_defaultfilters() {
        $defaultfilters = array(

        );

        return $defaultfilters;
    }

    protected function define_defaultsearchcolumns() {
        $defaultsearchcolumns = array(
            array(
                'type' => 'base',
                'value' => 'name'
            ),
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

        return $defaultsearchcolumns;
    }

    //
    //
    // Source specific column display methods
    //
    //

    function rb_display_username($item, $row, $isexport = false){
        if(!empty($row)){
            return $item.' '.$row->lastname;
        }
        return $item;
    }

    function rb_display_groupcreator($item, $row, $isexport = false){
        if(!empty($row)){
            $url = new moodle_url('/user/profile.php',array('id'=>$row->userid));
            return '<a href="'.$url.'">'.$item.'</a>';
        }
        return $item;
    }

    function rb_display_isprivate($item, $row, $isexport = false){
        return $item ? 'Private' : 'Public';
    }
    function rb_display_addtogrouplink($item, $row, $isexport = false){
        if(!empty($row)){
            $url = new moodle_url('/oakland/groups/apply.php',array('id'=>$row->groupid));
            return '<a href="'.$url.'">'.$item.'</a>';
        }
        return $item;
    }

    function rb_display_confirm_exist_aud($item, $row, $isexport = false){
        if(!isset($item) || empty($item)){
            $string = get_string('recreate','rb_source_oakland_group');
            $a = new stdClass();
            $url = new moodle_url('/oakland/groups/recreate.php',array('type'=>1,'id'=>$row->groupid));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('recreate_aud','rb_source_oakland_group', $a);
        }else{
            $string = get_string('gotoaud','rb_source_oakland_group');
            $a = new stdClass();
            $a->id = $item;
            $url = new moodle_url('/cohort/view.php',array('id'=>$item));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('statusgood_aud','rb_source_oakland_group', $a);
        }
    }

    function rb_display_confirm_exist_dashboard($item, $row, $isexport = false){
        if(!isset($item) || empty($item)){
            $string = get_string('recreate','rb_source_oakland_group');
            $a = new stdClass();
            $url = new moodle_url('/oakland/groups/recreate.php',array('type'=>3,'id'=>$row->groupid));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('recreate_dash','rb_source_oakland_group', $a);
        }else{
            $string = get_string('gotodash','rb_source_oakland_group');
            $a = new stdClass();
            $url = new moodle_url('/totara/dashboard/index.php',array('id'=>$item));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('statusgood_dash','rb_source_oakland_group', $a);
        }
    }

    function rb_display_confirm_exist_course($item, $row, $isexport = false){
        if(!isset($item) || empty($item)){
            $string = get_string('recreate','rb_source_oakland_group');
            $a = new stdClass();
            $url = new moodle_url('/oakland/groups/recreate.php',array('type'=>2,'id'=>$row->groupid));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('recreate_course','rb_source_oakland_group', $a);
        }else{
            $string = get_string('gotocourse','rb_source_oakland_group');
            $a = new stdClass();
            $url = new moodle_url('/course/view.php',array('id'=>$item));
            $a->url = '<a href="'.$url.'">'.$string.'</a>';
            return get_string('statusgood_course','rb_source_oakland_group', $a);
        }
    }

    public function rb_display_oakland_group_actions($groupid, $row) {
        global $OUTPUT;

        $editurl = new moodle_url('/oakland/groups/editgroup.php', array('id' => $groupid));
        $str = html_writer::link(
            $editurl,
            $OUTPUT->pix_icon('t/edit', get_string('edit'), null, array('class' => 'iconsmall')),
            null,
            array('class' => 'action-icon')
        );
        $memberurl = new moodle_url('/oakland/groups/editmembers.php', array('id' => $groupid));
        $str .= html_writer::link(
            $memberurl,
            $OUTPUT->pix_icon('t/user', get_string('editmembers','oakland_groups'), null, array('class' => 'iconsmall')),
            null,
            array('class' => 'action-icon')
        );
        $delurl = new moodle_url('/oakland/groups/delete.php', array('id'=>$groupid, 'delete' => 0, 'cancelurl' => qualified_me()));
        $str .= html_writer::link(
            $delurl,
            $OUTPUT->pix_icon('t/delete', get_string('delete'), null, array('class' => 'iconsmall')),
            null,
            array('class' => 'action-icon')
        );
        return $str;
    }
} // End of rb_source_oakland_groups class.
