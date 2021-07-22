<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/18/15
 * Time: 9:43 AM
 * To change this template use File | Settings | File Templates.
 */
require_once($CFG->dirroot.'/oakland/groups/lib.php');
class block_oakland_group_admin_renderer extends plugin_renderer_base {
    public function display_admin_block ($group) {
        global $DB, $USER;

        $groupid = $group->id;
        $course = $DB->get_record('course', array('oaklandgroupid'=>$groupid));
        $role = $DB->get_record('role', array('shortname'=>'dashboardeditor'));
        $context = context_course::instance($course->id);
	    $systemcontext = context_system::instance();
        $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));

        // Links are relative to the site root (e.g. if location is /totara/totara/* use /totara/*
        $editgrouplink = new moodle_url('/oakland/groups/editgroup.php?id=' . $groupid);
        $managememberslink = new moodle_url('/oakland/groups/editmembers.php?id=' . $groupid);
        $processrequestslink = new moodle_url('/oakland/groups/processrequests.php?id=' . $groupid);
        $messagelink = new moodle_url('/user/index.php?id=' . $course->id);
        $courselink = new moodle_url('/course/view.php?id=' . $course->id);
        $leavegrouplink = new moodle_url('/oakland/groups/leavegroup.php?id=' . $groupid);
        $layoutmodify = new moodle_url('/totara/dashboard/layout.php?id=' . $dashboard->id);


        $html = '';

        if ((has_capability('block/oakland_group_admin:configuregroup', $context, $USER->id) && check_group_ownership($group)) || (user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id)) {
            $html .= html_writer::tag('a', 'Edit Configuration', array('href'=>$editgrouplink));
            $html .= '<br>';
        }

        if ((has_capability('block/oakland_group_admin:removemembers', $context, $USER->id) && check_group_ownership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id) {
            $html .= html_writer::tag('a', 'Manage Members', array('href'=>$managememberslink));
            $html .= '<br>';
        }

        if ((has_capability('block/oakland_group_admin:approvemembers', $context, $USER->id) && check_group_ownership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id) {
            $html .= html_writer::tag('a', 'Process Requests', array('href'=>$processrequestslink));
            $html .= '<br>';
        }

        if ((has_capability('block/oakland_group_admin:messagegroup', $context, $USER->id) && check_group_ownership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id) {
            $html .= html_writer::tag('a', 'Send Message to Group', array('href'=>$messagelink));
            $html .= '<br>';
        }

        if ((has_capability('block/oakland_group_admin:viewcourse', $context, $USER->id) && check_group_ownership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id) {
            $html .= html_writer::tag('a', 'View Associated Course', array('href'=>$courselink));
            $html .= '<br>';
        }
        if ((has_capability('block/oakland_group_admin:removeself', $context, $USER->id) && check_group_membership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group)) {
            $html .= html_writer::tag('a', 'Leave Group', array('href'=>$leavegrouplink));
            $html .= '<br />';
        }
        /*
         *  There is no way to naviage to this link without the admin roles, this link is a direct link to the dashboard layout page
         */

        if ((has_capability('block/oakland_group_admin:configuregroup', $context, $USER->id) && check_group_ownership($group)) || user_has_role_assignment($USER->id, $role->id, 1) && check_group_membership($group) && $group->alt_admin == $USER->id) {
            $html .= html_writer::tag('a', 'Modify Default Layout', array('href'=>$layoutmodify));
        }

        return $html;
    }

}