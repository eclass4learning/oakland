<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/22/15
 * Time: 12:42 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');
require_once($CFG->dirroot. '/oakland/groups/leavegroup_form.php');

$id = optional_param('id',0,PARAM_INT);
$formdata = array('id'=>$id);


if($id){
    $group = $DB->get_record('oakland_groups',array('id'=>$id));
}else{
    $group = new stdClass();
    $group->id = 0;
}

$course = $DB->get_record('course', array('oaklandgroupid'=>$group->id));
$context = context_course::instance($course->id);
require_capability('block/oakland_group_admin:removeself', $context, $USER->id, false, 'Permission Denied: You do not have access to this group.');

if (!check_group_membership($group)) {
    throw new required_capability_exception($context, 'block/oakland_group_admin:removeself', 'Permission Denied: You are not a member of this group.', null);
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/leavegroup.php',array('id'=>$id)));
$PAGE->set_title(get_string('leavegrouptitle','oakland_groups'));

$form = new leave_group_form(null, $formdata);

if($form->is_cancelled()){
    $form->id = $group->id;
    $redirecturl = submit_leave_group($form, true);
    redirect(new moodle_url($redirecturl));
}else if($fromform = $form->get_data()){
    $redirecturl = submit_leave_group($fromform, false);
    redirect(new moodle_url($redirecturl));
}else{
    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
}
