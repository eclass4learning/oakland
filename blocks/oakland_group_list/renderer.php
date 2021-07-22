<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 5/22/15
 * Time: 1:59 PM
 * To change this template use File | Settings | File Templates.
 */
class block_oakland_group_list_renderer extends plugin_renderer_base {

    public function oakland_group_list() {
        global $DB, $USER;
        $html = '';
        $cohort_members = $DB->get_records('cohort_members', array('userid'=>$USER->id));
        $users_groups = array();
        foreach ($cohort_members as &$member) {
            $cohort = $DB->get_record('cohort', array('id'=>$member->cohortid));
            array_push($users_groups, $cohort);
        }
        usort($users_groups, function($a, $b){
            return strcmp($a->name, $b->name);
        });
        foreach ($users_groups as &$cohort) {
            if ($cohort->oaklandgroupid != null) {
                $group = $DB->get_record('oakland_groups', array('id'=>$cohort->oaklandgroupid), 'name');
                if (strtolower($group->name) != 'classifieds') {
                    $dashboard = $DB->get_record('totara_dashboard', array('oaklandgroupid'=>$cohort->oaklandgroupid));
                    $url = new moodle_url('/totara/dashboard/index.php',array('id'=>$dashboard->id));
                    $html .= html_writer::tag('p', '<a href="'.$url.'">'.$group->name.'</a>');
                }
            }
        }

        return $html;
    }

}
