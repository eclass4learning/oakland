<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:43 PM
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');
require_once($CFG->dirroot. '/oakland/groups/editgroup_form.php');
require_once($CFG->dirroot. '/repository/lib.php');

$id = optional_param('id',0,PARAM_INT);




if($id){

    $group = $DB->get_record('oakland_groups',array('id'=>$id));
    $course = $DB->get_record('course', array('oaklandgroupid'=>$group->id));
    $context = context_course::instance($course->id);
    $role = $DB->get_record('role', array('shortname'=>'dashboardeditor'));

    // OS-208 modified to allow members with dashboardeditor role as well
    //require_capability('block/oakland_group_admin:configuregroup', $context, $USER->id, false, 'Permission Denied: You do not have access to edit groups.');
    if ((is_siteadmin()) || (check_group_ownership($group)) || (check_group_membership($group) && user_has_role_assignment($USER->id, $role->id, 1) && $group->alt_admin == $USER->id)  ) {
    }
    else{
        throw new required_capability_exception($context, 'block/oakland_group_admin:configuregroup', 'Permission Denied: You are not the owner of this group.', null);
    }

}else{
    $group = new stdClass();
    $group->id = 0;
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/editgroup.php',array('id'=>$id)));
$PAGE->set_title(get_string('editgroup','oakland_groups'));

$supportedtypes = array('.jpe', '.jpeIE', '.jpeg', '.jpegIE', '.jpg', '.jpgIE', '.png', '.pngIE');

$ft = 'group_logo';
$context = context_system::instance();
$options = array('subdirs' => 0,
    'maxbytes' => $CFG->maxbytes,
    'maxfiles' => -1,
    'accepted_types' => $supportedtypes,
    'return_types' => FILE_INTERNAL);

$data = new stdClass();
$data->id = $group->id;
if($group->id > 0){
    $data->email = $group->group_email;
}


/*
 * OAKLAND Custom
 * Check to see if the user is approved and allowed to join
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

file_prepare_standard_filemanager($data, $ft, $options, $context, 'oakland_groups', $ft, 3);

$datatosend = array('options'=>$options,'data'=>$data);
$form = new group_edit_form(null, $datatosend);
$form->set_data($group);
if($form->is_cancelled()){
    if (is_siteadmin($USER->id)) {
        redirect(new moodle_url('/oakland/groups/index.php'));
    }
    else {
        $collabdashboard = $DB->get_record('totara_dashboard', array('name'=>'Collaboratorium'));
        redirect(new moodle_url('/totara/dashboard/index.php?id=' . $collabdashboard->id));
    }

}else if($fromform = $form->get_data()){
    if(!$id){
        $group = create_group($fromform);
    }else{
        $group = update_group($fromform);
    }
    group_update_logo($group, $fromform,$options);

    if (is_siteadmin($USER->id)) {
        redirect(new moodle_url('/oakland/groups/index.php'));
    }
    else {
        $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$group->id));
        redirect(new moodle_url('/totara/dashboard/index.php?id=' . $dashboard->id));
    }
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
