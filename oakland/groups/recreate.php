<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:56 PM
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');

$id = optional_param('id', 0, PARAM_INT);
$type = optional_param('type',0,PARAM_INT);

$group = $DB->get_record('oakland_groups',array('id'=>$id));

$redirect = new moodle_url('/oakland/groups/index.php');
switch($type){
    case GROUP_AUDIENCE:
        //recreate the audience for this group
        create_group_cohort($group);
        redirect($redirect);
        break;
    case GROUP_COURSE:
        //recreate the course for this group
        //check/get group category
        $category = check_create_group_course_category();
        //get related cohort
        if(!$cohortid = $DB->get_field('cohort','id',array('oaklandgroupid'=>$id))){
            //cohort was missing, too. Create it.
            $cohortid =  create_group_cohort($group);
        }
        $course = create_group_course($group,$category,$cohortid);
        redirect($redirect);
        break;
    case GROUP_DASHBOARD:
        //recreate the dashboard
        //get related cohort
        if(!$cohortid = $DB->get_field('cohort','id',array('oaklandgroupid'=>$id))){
            //cohort was missing, too. Create it.
            $cohortid =  create_group_cohort($group);
        }
        $dashboard = create_group_dashboard($group, $cohortid);
        redirect($redirect);
        break;
    default:
        redirect($redirect);
        break;
}