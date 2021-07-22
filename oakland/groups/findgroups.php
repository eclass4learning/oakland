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

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('noblocks');
$PAGE->set_url(new moodle_url('/oakland/groups/findgroups.php'));
$PAGE->set_title(get_string('oaklandfindgroups','oakland_groups'));

$report = reportbuilder_get_embedded_report('oakland_find_groups', null, false, $sid);

echo $OUTPUT->header();

if ($debug) {
    $report->debug($debug);
}

$fullcount = $report->get_full_count();
$filteredcount = $report->get_filtered_count();
$count = ($fullcount != $filteredcount) ? " ($filteredcount/$fullcount)" : " ($filteredcount)";

echo $OUTPUT->heading(get_string('oaklandfindgroups', 'oakland_groups').$count);

$report->display_search();
$report->display_table();

echo $OUTPUT->footer();