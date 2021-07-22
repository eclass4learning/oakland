<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/11/15
 * Time: 1:24 PM
 * To change this template use File | Settings | File Templates.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

class process_requests_form extends moodleform {

    public function definition() {
        global $DB;

        $mform = $this->_form;

        $groupid = $this->_customdata['id'];
        $mform->setType('id', PARAM_RAW);

        $mform->addElement('hidden', 'id', $groupid);

        $mform->addElement('html', '<h1>' . get_string('grouprequeststitle', 'oakland_groups') . '</h1>');

        $mform->addElement('header','grouprequestsheader', get_string('grouprequestsheader', 'oakland_groups'));
        $mform->addHelpButton('grouprequestsheader','grouprequestsheader','oakland_groups');

        $applications = $DB->get_records('oakland_group_applications', array('status'=>'pending', 'oaklandgroupid'=>$groupid));

        foreach($applications as $application) {
            $values = array(0=>'Choose an action', 'accept:' . $application->applicantid=>'Accept', 'deny:' . $application->applicantid=>"Deny");

            $applicant = $DB->get_record('user', array('id'=>$application->applicantid));

            $label = 'Name: ' . $applicant->firstname . ' ' . $applicant->lastname . '<br>Date of Request: ' . date('M j, Y', $application->requestdate);

            $mform->addElement('select', 'applicant' . $applicant->id, $label, $values);
            $mform->setType('applicant' . $applicant->id, PARAM_RAW);

            $mform->addElement('html', '<hr>');
        }

        if (count($applications) == 0) {
            $mform->addElement('static', 'noapplications', 'There are no applications for the group at this time.');
        }

        $this->add_action_buttons();
    }

}
