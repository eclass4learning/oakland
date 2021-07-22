<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 4:30 PM
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/oakland/groups/lib.php');


///
/// Setup / loading data
///

$sitecontext = context_system::instance();

// Get params
$id = optional_param('id',0,PARAM_INT);
$confirm = optional_param('confirm',0,PARAM_INT);
$ret_url = optional_param('cancelurl',null,PARAM_RAW);

$group = $DB->get_record('oakland_groups',array('id'=>$id));
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/oakland/groups/index.php'));
$PAGE->set_title(get_string('deletegroup','oakland_groups'));

if (!$confirm) {
    ///
    /// Display page
    ///
//    $PAGE->navbar->add(get_string("{$prefix}frameworks", 'totara_hierarchy'), new moodle_url('/totara/hierarchy/framework/index.php', array('prefix' => $prefix)));
//    $PAGE->navbar->add(format_string($framework->fullname), new moodle_url('/totara/hierarchy/index.php', array('prefix' => $prefix, 'frameworkid' => $framework->id)));
//    $PAGE->navbar->add(format_string($item->fullname), new moodle_url('/totara/hierarchy/item/view.php', array('prefix' => $prefix, 'id' => $item->id)));
//    $PAGE->navbar->add(get_string('delete'.$prefix, 'totara_hierarchy'));

    echo $OUTPUT->header();

    $strdelete = get_string('groupdeletemessage','oakland_groups',$group);

    echo $OUTPUT->confirm($strdelete, new moodle_url("/oakland/groups/delete.php", array('id' => $group->id, 'confirm' => 1)),
        new moodle_url("/oakland/group/index.php"));

    echo $OUTPUT->footer();
    exit;
}else{
    delete_group($id);
    if(isset($ret_url)){
        redirect($ret_url);
    }else{
        redirect(new moodle_url('/oakland/groups/index.php'));
    }
}


///
/// Delete
///
//TODO: Delete