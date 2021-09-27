<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:43 PM
 */

define('GROUP_AUDIENCE',1);
define('GROUP_COURSE',2);
define('GROUP_DASHBOARD',3);

define('GROUP_VISIBLE',0);
define('GROUP_HIDDEN',1);
define('GROUP_PUBLIC',0);
define('GROUP_PRIVATE',1);


require_once($CFG->dirroot . '/cohort/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/totara/dashboard/lib.php');
require_once($CFG->dirroot . '/course/modlib.php');
#require_once($CFG->dirroot . '/googleapi.php');

function check_group_ownership($group) {
    global $USER, $DB;
	
	if ($USER->id == $group->userid)
		return true;
	
	if($DB->get_record_sql("SELECT * from {oakland_group_alt_admin} where group_id = ? and user_id = ?", array($group->id, $USER->id)))
		  return true;
	  
    return false;
}

function check_group_membership($group) {
    global $DB, $USER;
    $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$group->id));
    $member = $DB->get_record('cohort_members', array('cohortid'=>$cohort->id, 'userid'=>$USER->id));
    return $member != null;
}

/**
 * Creates a new group and all of its components:
 * - An Audience
 * - A Course
 * - A Dashboard
 * @param $form
 */
function create_group($form){
    global $DB,$USER;
    //create audience (cohort)

    $group = new stdClass();
    $group->name = $form->name;
    $group->group_email = $form->group_email;
    $group->userid = $USER->id;
    $group->private = $form->private;
    $group->hidden = 0;
    $group->description = $form->description;
    $group->purpose = $form->purpose;
    $group->topics = $form->topics;
    $group->g_calendar = $form->g_calendar;
    $group->g_drive = $form->g_drive;
    $group->g_hangouts = $form->g_hangouts;
    $group->g_youtube = $form->g_youtube;
    $group->datecreated = time();
	  //create_google_group($group->name,$group->group_email);

    $group->id = $DB->insert_record('oakland_groups',$group);

    $cohortid = create_group_cohort($group);
    add_user_to_cohort($cohortid);
    $category = check_create_group_course_category();
    $course = create_group_course($group,$category,$cohortid);

    $dashboard = create_group_dashboard($group,$cohortid);

    //add forum to course
    $forummodule = new stdClass();
    $forummodule->name = $group->name.'\'s Forum';
    $forummodule->intro = $group->description;
    $forummodule->type = 'general';
    $forummodule->maxattachment = 9;
    $forummodule->displaywordcount = 0;
    $forummodule->forcesubscribe = 0;
    $forummodule->trackingtype = 0;
    $forummodule->blockperiod = 0;
    $forummodule->blockafter = 0;
    $forummodule->warnafter = 0;
    $forummodule->gradecat = 1;
    $forummodule->assessed = 0;
    $forummodule->scale = 100;
    $forummodule->assesstimestart = time();
    $forummodule->assesstimefinish = time();
    $forummodule->visible = 1;
    $forummodule->cmidnumber = '';
    $forummodule->groupmode = 0;
    $forummodule->groupingid = 0;
    $forummodule->completionunlocked = 0;
    $forummodule->completionunlockednoreset = 0;
    $forummodule->completion = 1;
    $forummodule->completionposts = 0;
    $forummodule->completiondiscussion = 0;
    $forummodule->completionreplies = 0;
    $forummodule->completionexpected = 0;
    $forummodule->course = $course->id;
    $forummodule->coursemodule = 0;
    $forummodule->section = 1;
    $forummodule->module = 11;
    $forummodule->modulename = 'forum';
    $forummodule->instance = 0;
    $forummodule->add = 'forum';
    $forummodule->update = 0;
    $forummodule->return = 0;
    $forummodule->sr = 0;

    $forum = add_moduleinfo($forummodule, $course);

    add_blocks_to_dashboard($dashboard->get_id(), $group->id, $USER->id);
    add_user_to_role($course, 'groupcreator', $USER->id);
    add_user_to_role($course, 'editingteacher', $USER->id);

    // There is no proper method to attach our group creator with the right role so we'll manually insert them

    if (!$DB->get_record_sql("select id from {role_assignments} where roleid = (select id from {role} where shortname = 'dashboardeditor') and userid = ?", array($USER->id))){
        $params = array('userid' => $USER->id, 'timemodified' => time());
        $addedRoleId = $DB->execute('INSERT INTO {role_assignments} (roleid, contextid, userid, timemodified, modifierid, component, itemid, sortorder) VALUES((SELECT id FROM {role} WHERE shortname = \'dashboardeditor\'), 1, :userid, :timemodified, 2, \'\', 0, 0 )', $params);
    }

    return $group;
}

function add_blocks_to_dashboard($dashboardid, $groupid, $userid) {
    global $DB;

    $usercontextid = context_user::instance($userid)->id;
    $systemcontextid = context_system::instance();

    // Commented out to use the default dashboard instead of the per user
    //$dashboarduser = new stdClass();
    //$dashboarduser->userid = $userid;
    //$dashboarduser->dashboardid = $dashboardid;
    //$dashboarduser->id = $DB->insert_record("totara_dashboard_user", $dashboarduser);

    $dashboardblock = new stdClass();
    $dashboardblock->blockname = 'totara_dashboard';
    $dashboardblock->parentcontextid = 1;
    $dashboardblock->showinsubcontexts = 0;
    $dashboardblock->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $dashboardblock->subpagepattern = 'default';
    $dashboardblock->defaultregion='side-pre';
    $dashboardblock->defaultweight = -1;

    $gaccess_block = new stdClass();
    $gaccess_block->blockname = 'gaccess';
    $gaccess_block->parentcontextid = 1;
    $gaccess_block->showinsubcontexts = 0;
    $gaccess_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $gaccess_block->subpagepattern = 'default';
    $gaccess_block->defaultregion='side-post';
    $gaccess_block->defaultweight = 2;

    $activity_block = new stdClass();
    $activity_block->blockname = 'activity_modules';
    $activity_block->parentcontextid = 1;
    $activity_block->showinsubcontexts = 0;
    $activity_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $activity_block->subpagepattern = 'default';
    $activity_block->defaultregion='side-post';
    $activity_block->defaultweight = 3;

    $quicklink_block = new stdClass();
    $quicklink_block->blockname = 'totara_quicklinks';
    $quicklink_block->parentcontextid = 1;
    $quicklink_block->showinsubcontexts = 0;
    $quicklink_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $quicklink_block->subpagepattern = 'default';
    $quicklink_block->defaultregion='side-post';
    $quicklink_block->defaultweight = 4;

    $groupbio_block = new stdClass();
    $groupbio_block->blockname = 'oakland_group_bio';
    $groupbio_block->parentcontextid = 1;
    $groupbio_block->showinsubcontexts = 0;
    $groupbio_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $groupbio_block->subpagepattern = 'default';
    $groupbio_block->defaultregion='content';
    $groupbio_block->defaultweight = 5;

    $forum_block = new stdClass();
    $forum_block->blockname = 'forum';
    $forum_block->parentcontextid = 1;
    $forum_block->showinsubcontexts = 0;
    $forum_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $forum_block->subpagepattern = 'default';
    $forum_block->defaultregion='content';
    $forum_block->defaultweight = 6;

    $admin_block = new stdClass();
    $admin_block->blockname = 'oakland_group_admin';
    $admin_block->parentcontextid = 1;
    $admin_block->showinsubcontexts = 0;
    $admin_block->pagetypepattern = 'my-totara-dashboard-' . $dashboardid;
    $admin_block->subpagepattern = 'default';
    $admin_block->defaultregion='side-pre';
    $admin_block->defaultweight = 7;

    $DB->insert_record('block_instances', $dashboardblock);
    $DB->insert_record('block_instances', $gaccess_block);
    $DB->insert_record('block_instances', $activity_block);
    //$DB->insert_record('block_instances', $quicklink_block);
    $groupbioid = $DB->insert_record('block_instances', $groupbio_block);
    $DB->insert_record('block_instances', $forum_block);
    $DB->insert_record('block_instances', $admin_block);

    $groupbio_instance = new stdClass();
    $groupbio_instance->blockinstanceid = $groupbioid;
    $groupbio_instance->oaklandgroupid = $groupid;
    $DB->insert_record('block_oakland_groups_bio', $groupbio_instance);
}

function add_user_to_cohort($cohortid) {
    global $USER;
    cohort_add_member($cohortid, $USER->id);
}

function add_user_to_role($course, $rolename, $userid) {
    global $DB;
    $role = $DB->get_record('role', array('shortname'=>$rolename));
    if(!$role){
        debugging("Role named $rolename not found.");
    }
    $coursecontext = context_course::instance($course->id);

    $assignment = new stdClass();
    $assignment->roleid = $role->id;
    $assignment->contextid = $coursecontext->id;
    $assignment->userid = $userid;
    $assignment->timemodified = date('U');
    $assignment->modifierid = $userid;
    $assignment->component = '';
    $assignment->itemid = 0;
    $assignment->sortorder = 0;

    $DB->insert_record('role_assignments', $assignment);
}

function create_group_cohort($group){
    $cohort = new stdClass();
    $cohort->name = $group->name;
    $cohort->contextid = context_system::instance()->id;
    $cohort->oaklandgroupid = $group->id;
    // Oakland Modifications
    $cohort->alertmembers = 1;
    $cohortid = cohort_add_cohort($cohort);
    return $cohortid;
}

function check_create_group_course_category(){
    global $DB;
    if(!$category = $DB->get_record('course_categories',array('idnumber'=>'GROUP'))){
        $data = new stdClass();
        $data->parent = 0;
        $data->name = 'Oakland Group Courses';
        $data->idnumber = 'GROUP';
        $data->type = 'course';
        $category = coursecat::create($data);
    }
    return $category;
}

function create_group_course($group, $category,$cohortid){
    $course = new stdClass();
    $course->fullname = $group->name;
    $course->shortname = $group->name;
    $course->visible = 1;
    $course->coursetype = 0;
    $course->startdate = time();
    $course->format = 'topics';
    $course->numsections = 1;
    $course->hiddensections = 0;
    $course->addcourseformatoptionsehere = 0;
    $course->lang = "";
    $course->newsitems = 0;
    $course->showgrades = 1;
    $course->showreport = 0;
    $course->maybytes = 0;
    $course->enablecompletion = 1;
    $course->completionstartedonenrol = 1;
    $course->completionprogressview = 0;
    $course->icon = 'principles-of-managerial-finance';
    $course->enrol_guest_status_0 = 1;
    $course->cohortsenrolled = $cohortid;
    $course->groupmodeforce = 0;
    $course->defaultgroupid = 0;
    $course->category = $category->id;
    $course->cohortsenrolled = $cohortid;
    $course->oaklandgroupid = $group->id;
    $course = create_course($course);

    totara_cohort_add_association($cohortid, $course->id, COHORT_ASSN_ITEMTYPE_COURSE);

    return $course;

}

function create_group_dashboard($group,$cohortid){
    $dashboard = new totara_dashboard(0);
    $data = new stdClass();
    $data->name = $group->name.'\'s Dashboard';
    $data->oaklandgroupid = $group->id;
    $data->cohorts = $cohortid;
    $data->published = 1;
    $data->allowguest = 1;
    $data->oaklandgroupid = $group->id;
    $dashboard->set_from_form($data)->save();
    
    return $dashboard;
}

/**
 * Updates the group. We only need to worry about the actual oakland_groups record for this.
 * @param $form
 * @return stdClass
 */
function update_group($form){
    global $DB,$USER;

    $group = new stdClass();
    $group->name = $form->name;
    $group->group_email = $form->group_email;
    $group->userid = $USER->id;
    $group->private = $form->private;
    $group->hidden = 0;
    $group->description = $form->description;
    $group->purpose = $form->purpose;
    $group->topics = $form->topics;
    $group->g_calendar = $form->g_calendar;
    $group->g_drive = $form->g_drive;
    $group->g_hangouts = $form->g_hangouts;
    $group->g_youtube = $form->g_youtube;
    $group->datecreated = time();
    $group->id = $form->id;

    $DB->update_record('oakland_groups',$group);

    //We can just edit the raw table records for these
    //update name of course
    if($course = $DB->get_record('course',array('oaklandgroupid'=>$group->id))){
        $course->fullname = $form->name;
        $course->shortname = $form->name;
        $DB->update_record('course',$course);
    }

    //update name on dashboard
    if($dashboard = $DB->get_record('totara_dashboard',array('oaklandgroupid'=>$group->id))){
        $dashboard->name = $form->name;
        $DB->update_record('totara_dashboard',$dashboard);
    }
    //update name on audience
    if($cohort = $DB->get_record('cohort',array('oaklandgroupid'=>$group->id))){
        $cohort->name = $form->name;
        $DB->update_record('cohort',$cohort);
    }
	
	//update_google_group($group->name,$group->group_email);
    return $group;
}

function delete_group($id){
    global $DB, $USER;
    if(!$group = $DB->get_record('oakland_groups',array('id'=>$id))){
        return false; //group id was invalid
    }

    $course = $DB->get_record('course',array('oaklandgroupid'=>$group->id));

    //delete audience
    if($cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$group->id))){
        $members = $DB->get_records('cohort_members', array('cohortid'=>$cohort->id));
        foreach ($members as $member) {
            delete_user_role($course, 'groupmember', $member->userid);
        }
        cohort_delete_cohort($cohort);
    }
    //delete course
    if($course = $DB->get_record('course',array('oaklandgroupid'=>$group->id))){
        delete_user_role($course, 'groupcreator', $group->userid);
        delete_course($course,false);
    }
    //delete dashboard
    if($dashboardid = $DB->get_field('totara_dashboard','id',array('oaklandgroupid'=>$group->id))){
        $dashboard = new totara_dashboard($dashboardid);
        $dashboard->delete();
    }
    //delete group record
    $DB->delete_records('oakland_groups',array('id'=>$group->id));

    //delete google group
    delete_google_group($group->group_email);

    return true;
}

function set_alt_admin($altadminuserid, $groupid){
    global $DB;

    //Update the alt_user in our group record
    if($group = $DB->get_record_sql("SELECT * from {oakland_groups} where id = ?", array($groupid))){
        $group->alt_admin = $altadminuserid;
        $DB->update_record('oakland_groups',$group);
        return true;
    }
    else{
        return false;
    }
}

function delete_alt_admin_alt($altadminuserid, $groupid){
    global $DB;

        $DB->delete_records('oakland_group_alt_admin',array('group_id'=>$groupid, 'user_id'=>$altadminuserid));
   //delete_user_role($course, 'groupcreator', $group->userid);
}

function set_alt_admin_alt($altadminuserid, $groupid, $course){
    global $DB;

	
	
    //Update the alt_user in our group record
    if($group = $DB->get_record_sql("SELECT * from {oakland_group_alt_admin} where group_id = ? and user_id = ?", array($groupid, $altadminuserid))){
      
        return true;
    } else{
		$group = new stdClass();
		$group->group_id = $groupid;
		$group->user_id = $altadminuserid;
		$DB->insert_record('oakland_group_alt_admin', $group);
	
                $dashboard = $DB->get_record_sql("select id from {totara_dashboard} where oaklandgroupid = ?",array($groupid)); 
                var_dump($dashboard);	
        add_user_to_role($course, 'editingteacher', $altadminuserid);
        add_user_to_role($course, 'groupcreator', $altadminuserid);
  //       add_blocks_to_dashboard($dashboard->id, $groupid, $altadminuserid);
 
		// There is no proper method to attach our group creator with the right role so we'll manually insert them

		if (!$DB->get_record_sql("select id from {role_assignments} where roleid = (select id from {role} where shortname = 'dashboardeditor') and userid = ?", array($altadminuserid))){
			$params = array('userid' => $altadminuserid, 'timemodified' => time());
			$addedRoleId = $DB->execute('INSERT INTO {role_assignments} (roleid, contextid, userid, timemodified, modifierid, component, itemid, sortorder) VALUES((SELECT id FROM {role} WHERE shortname = \'dashboardeditor\'), 1, :userid, :timemodified, 2, \'\', 0, 0 )', $params);
		}
        return true;
    }
}

function get_alt_admin_alt($groupid){
    global $DB;

	
	
    $corhortmembers = $DB->get_records('oakland_group_alt_admin',  array('group_id'=>$groupid));
	
      $optionshtml = "";
       foreach ($corhortmembers as $member) {
			$userdata = $DB->get_record('user',array('id'=>$member->user_id));
            if (!empty($userdata)) {
				$optionshtml = $optionshtml . "" . $userdata->firstname . " " .  $userdata->lastname . "<br>";
			}
		}
		return $optionshtml;
}

function get_cohort_members($cohortid, $groupid){
    global $DB;

    $optionshtml = "";

    if(!$corhortmembers = $DB->get_records('cohort_members',array('cohortid'=>$cohortid))){
        return false; //group id was invalid
    }
    $group2 = $DB->get_record('oakland_groups', array('id'=>$groupid));

    foreach ($corhortmembers as $member) {
        $userdata = $DB->get_record('user',array('id'=>$member->userid));
        if($group2->alt_admin == $member->userid){
            $optionshtml = $optionshtml . "<option value=\"" . $member->userid . "\" selected=\"selected\">" . $userdata->firstname . " " .  $userdata->lastname . "</option>\n";
        }
        else{
            $optionshtml = $optionshtml . "<option value=\"" . $member->userid .  "\">" . $userdata->firstname . " " .  $userdata->lastname . "</option>\n";
        }

    }

    return $optionshtml;
}


function delete_user_role($course, $rolename, $userid) {
    global $DB;
    $role = $DB->get_record('role', array('shortname'=>$rolename));
    if(!$role){
        debugging("Role named $rolename not found.");
    }
    $coursecontext = context_course::instance($course->id);

    $DB->delete_records('role_assignments', array('roleid'=>$role->id, 'contextid'=>$coursecontext->id, 'userid'=>$userid));
}

function submit_group_application() {
    global $DB, $USER;
    $groupid = $_POST['groupid'];
    $group = $DB->get_record('oakland_groups', array('id'=>$groupid));
    $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$groupid));
    $cohortmember = $DB->get_record('cohort_members', array('cohortid'=>$cohort->id, 'userid'=>$USER->id));
    $redirecturl = '';
    if ($cohortmember == null) {
        if ($group->private == 1) {
            $application = new stdClass();
            $application->oaklandgroupid = $groupid;
            $application->applicantid = $USER->id;
            $application->status = 'pending';
            $application->requestsource = 'collaboratorium';
            $application->requestdate = date('U');
            $DB->insert_record('oakland_group_applications', $application);
            $dashboard = $DB->get_record('totara_dashboard', array('name'=>'Collaboratorium'));
            $redirecturl = '/totara/dashboard/index.php?id='.$dashboard->id;

            // Notify users.
            totara_cohort_notify_group_creators($cohort->id, array($group->userid), false, $USER->id);
        } else {
            $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$groupid));
            $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));
            $redirecturl = '/totara/dashboard/index.php?id='.$dashboard->id;
            $course = $DB->get_record('course',array('oaklandgroupid'=>$group->id));

            //add_blocks_to_dashboard($dashboard->id, $groupid, $USER->id);
            add_user_to_cohort($cohort->id);
            add_user_to_role($course, 'groupmember', $USER->id);

            // Notify users.
            totara_cohort_notify_add_users($cohort->id, array($USER->id));
        }
    } else {
        $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));
        $redirecturl = '/totara/dashboard/index.php?id='.$dashboard->id;
    }

    return $redirecturl;
}

function submit_leave_group($form, $cancel) {
    global $DB, $USER;
    $groupid = $form->id;

    if ($cancel) {
        $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));
    } else {
        $dashboard = $DB->get_record('totara_dashboard', array('name'=>'Collaboratorium'));
        $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$groupid));
        $course = $DB->get_record('course',array('oaklandgroupid'=>$groupid));

        cohort_remove_member($cohort->id, $USER->id);
        delete_user_role($course, 'groupmember', $USER->id);

        // remove as a dashboard user, remove block instances
        $usercontextid = context_user::instance($USER->id)->id;
        $remove_from_dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));
        if ($remove_from_dashboard) {
            $DB->delete_records('totara_dashboard_user',array('dashboardid'=>$remove_from_dashboard->id,'userid'=>$USER->id));
            $DB->delete_records('block_instances', array('parentcontextid'=>$usercontextid,'pagetypepattern'=>'my-totara-dashboard-'.$remove_from_dashboard->id));
        }

        // Notify users.
        totara_cohort_notify_del_users($cohort->id, array($USER->id));
    }

    $redirecturl = '/totara/dashboard/index.php?id='.$dashboard->id;
    return $redirecturl;
}

function submit_process_requests($form) {
    global $DB, $USER;
    $groupid = $form->id;
    $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$groupid));

    foreach($form as $key=>$value) {
        if ($key != 'submitbutton' && $key != 'id' && $value != '0') {
            $values = explode(':', $value);
            $status = $values[0];
            $applicantid = $values[1];

            $application = $DB->get_record('oakland_group_applications', array('applicantid'=>$applicantid, 'oaklandgroupid'=>$groupid));
            $application->status = $status;
            $application->statusdate = date('U');
            $application->adminuserid = $USER->id;
            $DB->update_record('oakland_group_applications', $application);

            if ($status == 'accept') {
                //add_blocks_to_dashboard($dashboard->id, $groupid, $applicantid);
                $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$application->oaklandgroupid));
                $course = $DB->get_record('course',array('oaklandgroupid'=>$groupid));
                
                cohort_add_member($cohort->id, $applicantid);
                add_user_to_role($course, 'groupmember', $applicantid);

                totara_cohort_notify_join_request_approved($cohort->id, array($applicantid));
            } else {
                $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$application->oaklandgroupid));

                $event = \core\event\cohort_member_removed::create(array(
                    'context' => context::instance_by_id($cohort->contextid),
                    'objectid' => $cohort->id,
                    'relateduserid' => $applicantid,
                ));

                $event->add_record_snapshot('cohort', $cohort);
                $event->trigger();

                totara_cohort_notify_join_request_denied($cohort->id, array($applicantid));
            }
        }
    }
}

/**
 * Updates the provided users profile picture based upon the expected fields returned from the edit or edit_advanced forms.
 *
 * @global moodle_database $DB
 * @param stdClass $orgformdata An object that contains some information about the user being updated
 * @param array $filemanageroptions
 * @return bool True if the user was updated, false if it stayed the same.
 */
function group_update_logo($group, stdClass $formdata, $filemanageroptions = array()) {
    global $CFG, $DB, $USER;
    require_once("$CFG->libdir/gdlib.php");

    $context = context_system::instance();
    $group = $DB->get_record('oakland_groups', array('id' => $group->id), 'id, logo', MUST_EXIST);

    $newpicture = $group->logo;
    // Get file_storage to process files.
    $fs = get_file_storage();
    $usercontext = context_user::instance($USER->id);
    $draftfiles = $fs->get_area_files($usercontext->id, 'user', 'draft', $formdata->grouplogo, 'id');
    if (!empty($formdata->deletepicture)) {
        // The user has chosen to delete the selected users picture.
        //$fs->delete_area_files($context->id, 'totata_hierarchy', 'icon'); // Drop all images in area.
        //$newpicture = 0;

    } else if(count($draftfiles)>0){
        file_save_draft_area_files($formdata->grouplogo, $context->id, 'oakland_groups', 'grouplogo', $group->id, $filemanageroptions);
        if (($iconfiles = $fs->get_area_files($context->id, 'oakland_groups', 'grouplogo', $group->id)) && count($iconfiles) == 2) {
            // Get file which was uploaded in draft area.
            foreach ($iconfiles as $file) {
                if (!$file->is_directory()) {
                    break;
                }
            }
            // Copy file to temporary location and the send it for processing icon.
            if ($iconfile = $file->copy_content_to_temp()) {
                // There is a new image that has been uploaded.
                // Process the new image and set the user to make use of it.
                // NOTE: Uploaded images always take over Gravatar.
                $newpicture = (int)process_new_icon($context, 'oakland_groups', 'grouplogo',  $group->id, $iconfile);
                // Delete temporary file.
//                @unlink($iconfile);
//                // Remove uploaded file.
//                $fs->delete_area_files($context->id, 'totara_hierarchy', 'orgseal');
            } else {
                // Something went wrong while creating temp file.
                // Remove uploaded file.
                $fs->delete_area_files($context->id, 'oakland_groups', 'grouplogo');
                return false;
            }
        }
    }

    if ($newpicture != $group->logo) {
        $DB->set_field('oakland_groups', 'logo', $newpicture, array('id' => $group->id));
        return true;
    } else {
        return false;
    }
}

function oakland_groups_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options=array()) {
    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/{$context->id}/oakland_groups/$filearea/$args[0]/$args[1]";
    $hash = sha1($fullpath);
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }
    // finally send the file
    send_stored_file($file, 86400, 0, true, $options); // download MUST be forced - security!
}
