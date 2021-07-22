<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/11/15
 * Time: 1:34 PM
 * To change this template use File | Settings | File Templates.
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');
require_once($CFG->dirroot. '/oakland/groups/processrequests_form.php');

$id = optional_param('id',0,PARAM_INT);
$formdata = array('id'=>$id);

if($id){
    $group = $DB->get_record('oakland_groups',array('id'=>$id));
    $course = $DB->get_record('course', array('oaklandgroupid'=>$group->id));
    $context = context_course::instance($course->id);
    $role = $DB->get_record('role', array('shortname'=>'dashboardeditor'));

    // OS-208 modified to allow members with dashboardeditor role as well
    //require_capability('block/oakland_group_admin:approvemembers', $context, $USER->id, false, 'Permission Denied: You do not have access to approve group requests.');
    if ((is_siteadmin()) || (check_group_ownership($group)) || (check_group_membership($group) && user_has_role_assignment($USER->id, $role->id, 1) && $group->alt_admin == $USER->id)   ) {
    }
    else{
        throw new required_capability_exception($context, 'block/oakland_group_admin:configuregroup', 'Permission Denied: You are not the owner of this group.', null);
    }

}else{
    $group = new stdClass();
    $group->id = 0;
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/processrequests.php',array('id'=>$id)));
$PAGE->set_title(get_string('grouprequeststitle','oakland_groups'));

$form = new process_requests_form(null, $formdata);
$dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$group->id));

if($form->is_cancelled()){
    redirect(new moodle_url('/totara/dashboard/index.php?id=' . $dashboard->id));
}else if($fromform = $form->get_data()){
    submit_process_requests($fromform);
    redirect(new moodle_url('/totara/dashboard/index.php?id=' . $dashboard->id));
}else{
    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
}
