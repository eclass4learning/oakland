<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/12/15
 * Time: 4:36 PM
 * To change this template use File | Settings | File Templates.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

class apply_form extends moodleform {

    public function definition() {
        global $DB, $USER;

        $mform = $this->_form;
        $groupid = optional_param('id',0,PARAM_INT);

        $group = $DB->get_record('oakland_groups', array('id'=>$groupid));
        $submittext = 'Submit';

        if ($group != null) {

            $mform->addElement('hidden', 'groupid', $group->id);
            $mform->setType('groupid', PARAM_RAW);

            $cohort = $DB->get_record('cohort', array('oaklandgroupid'=>$groupid));
            $cohortmember = $DB->get_record('cohort_members', array('cohortid'=>$cohort->id, 'userid'=>$USER->id));

            $pending_join_requests = $DB->get_records('oakland_group_applications', array('status'=>'pending', 'oaklandgroupid'=>$groupid, 'applicantid'=>$USER->id));
            if ($pending_join_requests) {
                $mform->addElement('hidden', 'pending_join_requests', true);
                $mform->setType('pending_join_requests', PARAM_BOOL);

                $mform->addElement('html', '<h1>Pending join request for ' . $group->name . '</h1>');

                $mform->addElement('static','privatestatictext', get_string('pendingjoingrequeststatictext', 'oakland_groups'));

                $submittext = 'Continue';
            } else {
                $mform->addElement('hidden', 'pending_join_requests', false);
                $mform->setType('pending_join_requests', PARAM_BOOL);

                if ($cohortmember == null) {
                    if ($group->private == 1) {
                        $mform->addElement('html', '<h1>' . $group->name . ' is Private</h1>');

                        $mform->addElement('static','privatestatictext', get_string('privatestatictext', 'oakland_groups'));

                        $submittext = get_string('privateapply', 'oakland_groups');
                    }
                    else {
                        $mform->addElement('html', '<h1>Welcome to ' . $group->name . ', ' . $USER->firstname . '</h1>');

                        $mform->addElement('static','publicstatictext', get_string('publicstatictext', 'oakland_groups'));

                        $submittext = get_string('publicapply', 'oakland_groups');
                    }
                } else {
                    $mform->addElement('html', '<h1>Welcome to ' . $group->name . ', ' . $USER->firstname . '</h1>');

                    $mform->addElement('static','existingmembertext', get_string('existingmembertext', 'oakland_groups'));

                    $submittext = 'Continue';
                }
            }
        }

        $this->add_action_buttons(true, $submittext);
    }

}
