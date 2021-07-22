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
require_once($CFG->dirroot.'/totara/reportbuilder/lib.php');

$sid = optional_param('sid',0,PARAM_INT);
$debug = optional_param('debug', 0, PARAM_INT);

// OS-208 modified to allow members with dashboardeditor role as well
//require_capability('block/oakland_group_admin:configuregroup', $context, $USER->id, false, 'Permission Denied: You do not have access to edit groups.');
if ((is_siteadmin())) {
}
else{
    throw new exception('Permission Denied: You must be a site administrator to view this page.');
}


$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/index.php'));
$PAGE->set_title(get_string('oaklandgroupadmin','oakland_groups'));
$report = reportbuilder_get_embedded_report('oakland_group_admin', null, false, $sid);

echo $OUTPUT->header();

if ($debug) {
    $report->debug($debug);
}

$fullcount = $report->get_full_count();
$filteredcount = $report->get_filtered_count();
$count = ($fullcount != $filteredcount) ? " ($filteredcount/$fullcount)" : " ($filteredcount)";

echo $OUTPUT->heading(get_string('oaklandgroupadmin', 'oakland_groups').$count);
echo '<hr>';
echo get_string('group_explain','oakland_groups');
echo $OUTPUT->single_button(new moodle_url('/oakland/groups/editgroup.php'), get_string('creategroup', 'oakland_groups'));

$report->display_search(false);
$report->display_table();

echo $OUTPUT->footer();