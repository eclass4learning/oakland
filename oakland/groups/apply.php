<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/12/15
 * Time: 4:34 PM
 * To change this template use File | Settings | File Templates.
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');
require_once($CFG->dirroot. '/oakland/groups/apply_form.php');

$id = optional_param('id',0,PARAM_INT);

if($id){
    $group = $DB->get_record('oakland_groups',array('id'=>$id));
}else{
    $group = new stdClass();
    $group->id = 0;
}

/*
 * OAKLAND Custom
 * Deny users if they are in the Site Visitor's Role
 */

$sql = "SELECT u.id
    FROM mdl_role as r,
    mdl_role_assignments as ra,
    mdl_user as u
    WHERE r.shortname = 'visitor'
    AND r.id = ra.roleid
    AND ra.userid = u.id
    AND u.id = ?";

$visitorRoleUserId = $DB->get_record_sql($sql, array($USER->id), IGNORE_MULTIPLE);
$miplaceMemberRole = $DB->get_record('role', array('shortname'=>'member'));
$miplaceRoleUserAssignment = $DB->get_record('role_assignments', array('roleid'=>$miplaceMemberRole->id, 'userid'=>$USER->id));


$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/apply.php',array('id'=>$id)));
$PAGE->set_title(get_string('applytogrouptitle','oakland_groups'));

$form = new apply_form();
$form->set_data($group);

if($form->is_cancelled()){
    redirect(new moodle_url('/oakland/groups/findgroups.php'));
}else if($fromform = $form->get_data()){
    if ($_POST['pending_join_requests']) {
        $collaboratorium = $DB->get_record('totara_dashboard', array('name'=>'Collaboratorium'));
        if ($collaboratorium) {
            redirect(new moodle_url('/totara/dashboard/index.php',array('id'=>$collaboratorium->id)));
        } else {
            redirect(new moodle_url('/oakland/groups/findgroups.php'));
        }
    } else {
        $redirecturl = submit_group_application();
    }
    redirect(new moodle_url($redirecturl));
}else{
    echo $OUTPUT->header();

    if ($visitorRoleUserId->id == $USER->id && $miplaceRoleUserAssignment == null){
        echo $OUTPUT->notification(get_string('notauthorized', 'oakland_groups'));
    }
    else{
        $form->display();
    }
    echo $OUTPUT->footer();
}
