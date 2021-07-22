<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 1:48 PM
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');
require_once($CFG->dirroot.'/cohort/locallib.php');

$id = optional_param('id',0,PARAM_INT);

if(!$id || !$group = $DB->get_record('oakland_groups',array('id'=>$id))){
    //not a valid group.
    redirect(new moodle_url('/oakland/group/index.php')); //TODO: Should redirect to the front-end user course index.
}

$course = $DB->get_record('course', array('oaklandgroupid'=>$group->id));
$coursecontext = context_course::instance($course->id);
$role = $DB->get_record('role', array('shortname'=>'dashboardeditor'));
$altadminuserid = optional_param('userid', 0, PARAM_INT);
$alt_admin_flag = optional_param('alt_admin', 0, PARAM_INT);


$cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$id), '*', MUST_EXIST);
//$context = context::instance_by_id($cohort->contextid, MUST_EXIST);
$context = context_system::instance();


// OS-208 modified to allow members with dashboardeditor role as well
//require_capability('block/oakland_group_admin:configuregroup', $context, $USER->id, false, 'Permission Denied: You do not have access to edit groups.');
if ((is_siteadmin()) || (check_group_ownership($group)) || (check_group_membership($group) && user_has_role_assignment($USER->id, $role->id, 1) && $group->alt_admin == $USER->id)  ) {
}
else{
    throw new required_capability_exception($context, 'block/oakland_group_admin:configuregroup', 'Permission Denied: You are not the owner of this group.', null);
}
require_login();


// OS-215 Save an alt_admin for the group
if(!is_null($id) && !is_null($alt_admin_flag)){
    $setaltadmin = set_alt_admin_alt($altadminuserid, $group->id, $course);
} else if(!is_null($id) && !is_null($altadminuserid) && $altadminuserid != 0){
    $setaltadmin = set_alt_admin($altadminuserid, $group->id, $course);
}

//Remove alternate administrators
if (!is_null($id) && !is_null($alt_admin_flag) &&  optional_param('remove_setalt_alt', false, PARAM_BOOL) && confirm_sesskey()) {
	delete_alt_admin_alt($altadminuserid, $group->id);
}
$PAGE->set_context($context);
$PAGE->set_url('/oakland/groups/editmembers.php?', array('id'=>$id));
$PAGE->set_pagelayout('admin');

$returnurl = new moodle_url('/oakland/group/index.php');

if (optional_param('cancel', false, PARAM_BOOL)) {
    redirect($returnurl);
}

$PAGE->navbar->add(get_string('managegroups', 'oakland_groups'), new moodle_url('/oakland/groups/index.php'));
$dashboard = $DB->get_field('totara_dashboard','id',array('oaklandgroupid'=>$id));
$PAGE->navbar->add(format_string($group->name), new moodle_url('/totara/dashboard/index.php',array('id'=>$dashboard)));
$PAGE->navbar->add(get_string('editmembers', 'oakland_groups'));

$PAGE->set_title(get_string('editmembers', 'oakland_groups'));
$PAGE->set_heading($COURSE->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editmembers', 'oakland_groups', format_string($group->name)));

//echo $OUTPUT->notification(get_string('removeuserwarning', 'core_cohort'));

// Get the user_selector we will need.

$existinguserselector = new cohort_existing_selector('removeselect', array('cohortid'=>$cohort->id, 'accesscontext'=>$context));

// Get current roles assigned to this cohort.
$currentcohortroles = totara_get_cohort_roles($cohort->id);

// Process removing user assignments to the cohort
if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoremove = $existinguserselector->get_selected_users();
    if (!empty($userstoremove)) {
        $delids = array();
        foreach ($userstoremove as $removeuser) {
            delete_user_role($course, 'groupmember', $removeuser->id);
            cohort_remove_member($cohort->id, $removeuser->id);
            $delids[$removeuser->id] = $removeuser->id;
        }
        // Unassign roles to users deleted.
        totara_unset_role_assignments_cohort($currentcohortroles, $cohort->id, array_keys($delids));
        // Notify users.
        totara_cohort_notify_del_users($cohort->id, $delids);
        $existinguserselector->invalidate_selected_users();
    }
}

$selectionlistaltadmins = "<option value = '2'>Default is Admin</option>";
$selectionlistaltadmins = $selectionlistaltadmins . get_cohort_members($cohort->id, $group->id);
// Print the form.
?>
    <form id="assignform" method="post" action="<?php echo $PAGE->url ?>"><div>
            <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>" />

            <table summary="" class="generaltable generalbox boxaligncenter" cellspacing="0">
                <tr>
                    <td id="existingcell">
                        <p><label for="removeselect"><?php print_string('currentusers', 'cohort'); ?></label></p>
                        <?php $existinguserselector->display() ?>
                    </td>
                    <td id="buttonscell">
                        <div id="removecontrols">
                            <input name="remove" id="remove" type="submit" value="<?php echo s(get_string('remove')).'&nbsp;'.$OUTPUT->rarrow(); ?>" title="<?php p(get_string('remove')); ?>" />
                        </div>
                    </td>
                </tr>
            </table>
        </div></form>


    <?php


$selectionlistaltadmins_alt = "<option value = '2'>Default is Admin</option>";
$selectionlistaltadmins_alt = $selectionlistaltadmins_alt . get_cohort_members($cohort->id, $group->id);
// Print the form.
?>
 

    <form id="assignform_alt" method="post" action="<?php echo $PAGE->url .'&alt_admin=true' ?>"><div>
            <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>" />

            <table summary="" class="generaltable generalbox boxaligncenter" cellspacing="0">
                <tr>
                    <td id="existingcell">
                        <p><label for="setalt">Additional Administrators (not owners)</label></p>
                        <select id="userid" name="userid" >
                            <?php echo $selectionlistaltadmins_alt ?>
                        </select>
                    </td>
					<td id="existingcell_users">
                        <p><label for="setalt">Current Additional Administrators</label></p>
                        <?php echo  get_alt_admin_alt($group->id) ?>
                    </td>
                    <td id="buttonscell">
                        <div id="removecontrols">
                            <input name="setalt_alt" id="setalt_alt" type="submit" value="<?php echo s(get_string('setalt', 'oakland_groups')).'&nbsp;'.$OUTPUT->rarrow(); ?>" title="<?php p(get_string('setalt', 'oakland_groups')); ?>" />
                        </div>
						   <div id="removecontrols">
                            <input name="remove_setalt_alt" id="remove_setalt_alt" type="submit" value="Remove Admin " title="<?php p(get_string('setalt', 'oakland_groups')); ?>" />
                        </div>
                    </td>
                </tr>
            </table>
        </div></form>
<?php
echo $OUTPUT->footer();
