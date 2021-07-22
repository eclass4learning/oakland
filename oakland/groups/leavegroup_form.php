<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/22/15
 * Time: 12:42 PM
 * To change this template use File | Settings | File Templates.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

class leave_group_form extends moodleform {

    public function definition() {
        global $DB;

        $mform = $this->_form;

        $groupid = $this->_customdata['id'];
        $mform->setType('id', PARAM_RAW);

        $group = $DB->get_record('oakland_groups', array('id'=>$groupid));

        $mform->addElement('hidden', 'id', $groupid);

        $mform->addElement('html', '<h1>' . 'Leaving&nbsp' . $group->name . '</h1>');

        $mform->addElement('static', 'staticleavetext', get_string('leavegroupstatic', 'oakland_groups'));

        $this->add_action_buttons(true, 'Confirm');
    }

}
