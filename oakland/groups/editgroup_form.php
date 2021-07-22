<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:43 PM
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
$PAGE->requires->js('/oakland/groups/yui/submit_group_form.js');

class group_edit_form extends moodleform {

    /**
     * Define the cohort edit form
     */
    public function definition() {
        global $USER,$TEXTAREA_OPTIONS,$CFG;

        $options = $this->_customdata['options'];
        $data = $this->_customdata['data'];
        $mform = $this->_form;

        $mform->addElement('header','settings_header',get_string('settings_header','oakland_groups'));

        $mform->addElement('text', 'name', get_string('name', 'oakland_groups'), 'maxlength="254" size="50"');
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);
        $mform->addHelpButton('name','name','oakland_groups');

        $mform->addElement('hidden', 'group_email');
        $mform->setType('group_email', PARAM_TEXT);

//        $visibleoptions = array(0=>get_string('visible','oakland_groups'),1=>get_string('hidden','oakland_groups'));
//        $mform->addElement('select','hidden',get_string('visible','oakland_groups'),$visibleoptions);
//        $mform->addHelpButton('hidden','hidden','oakland_groups');

        $accessoptions = array(0=>get_string('public','oakland_groups'),1=>get_string('private','oakland_groups'));
        $mform->addElement('select','private',get_string('access','oakland_groups'),$accessoptions);
        $mform->addHelpButton('private','private','oakland_groups');

        $mform->addElement('header','description_header',get_string('description_header','oakland_groups'));

        $mform->addElement('textarea', 'description', get_string('description', 'oakland_groups'));
        $mform->setType('description', PARAM_TEXT);
        $mform->addHelpButton('description','description','oakland_groups');

        $mform->addElement('textarea', 'purpose', get_string('purpose', 'oakland_groups'));
        $mform->setType('purpose', PARAM_TEXT);
        $mform->addHelpButton('purpose','purpose','oakland_groups');

        $mform->addElement('text','topics',get_string('topics','oakland_groups'));
        $mform->setType('topics',PARAM_TEXT);
        $mform->addHelpButton('topics','topics','oakland_groups');

        /**
         * BEGIN FILE UPLOAD
         */
        $mform->addElement('filemanager', 'grouplogo', get_string('group_image', 'oakland_groups'), null, $options);
        $mform->addHelpButton('grouplogo','group_image','oakland_groups');

        $mform->addElement('static','currentlogo',get_string('currentlogo','oakland_groups'));
        /**
         * END FILE UPLOAD
         */
        $mform->addElement('header','google_header',get_string('google_header','oakland_groups'));
        $mform->addElement('text', 'g_calendar', get_string('g_calendar', 'oakland_groups'), 'maxlength="254" size="50"');
        $mform->setType('g_calendar', PARAM_TEXT);
        $mform->addElement('static', 'g_calendar_static', null, get_string('g_calendar_static', 'oakland_groups'));
        $mform->addHelpButton('g_calendar','g_calendar','oakland_groups');

        $mform->addElement('text', 'g_drive', get_string('g_drive', 'oakland_groups'), 'maxlength="254" size="50"');
        $mform->setType('g_drive', PARAM_TEXT);
        $mform->addElement('static', 'g_drive_static', null, get_string('g_drive_static', 'oakland_groups'));
        $mform->addHelpButton('g_drive','g_drive','oakland_groups');

        $mform->addElement('text', 'g_hangouts', get_string('g_hangouts', 'oakland_groups'), 'maxlength="254" size="50"');
        $mform->setType('g_hangouts', PARAM_TEXT);
        $mform->addElement('static', 'g_hangouts_static', null, get_string('g_hangouts_static', 'oakland_groups'));
        $mform->addHelpButton('g_hangouts','g_hangouts','oakland_groups');

        $mform->addElement('text', 'g_youtube', get_string('g_youtube', 'oakland_groups'), 'maxlength="254" size="50"');
        $mform->setType('g_youtube', PARAM_TEXT);
        $mform->addElement('static', 'g_youtube_static', null, get_string('g_youtube_static', 'oakland_groups'));
        $mform->addHelpButton('g_youtube','g_youtube','oakland_groups');

        if(isset($data->id) && $data->id > 0){
            $mform->addElement('static', 'group_email_static', get_string('groupemail','oakland_groups'), $data->email);
        }

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'user_email', $USER->email);
        $mform->setType('user_email', PARAM_TEXT);

        $mform->setExpanded('description_header');
        $mform->setExpanded('google_header');

        $mform->addElement('hidden', 'edit_validation_url', new moodle_url('/oakland/groups/edit_validation.php'));
        $mform->setType('edit_validation_url', PARAM_INT);

        $this->add_action_buttons();

    }

    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);
        $id = $data['id'];

        // check for unique group name
        $name = $data['name'];
        if ($id == 0) {
            $courses = $DB->get_records_sql('SELECT * FROM {course} WHERE shortname = ?',array($name));
            $existing_groups = $DB->get_records_sql('SELECT * FROM {oakland_groups} WHERE name = ?',array($name));
        } else {
            $courses = $DB->get_records_sql('SELECT * FROM {course} WHERE shortname = ? and oaklandgroupid != ?',array($name, $id));
            $existing_groups = $DB->get_records_sql('SELECT * FROM {oakland_groups} WHERE name = ? and id != ?',array($name, $id));
        }
        if(!empty($courses) || !empty($existing_groups)){
            $errors['name'] = get_string('error:shortnameinuse','oakland_groups');
        }

        $purpose = strlen($data['purpose']);
        if($purpose>200){
            $a = new stdClass();
            $a->count = $purpose;
            $errors['purpose'] = get_string('error:purposetoolong','oakland_groups',$a);
        }
        $desc = strlen($data['description']);
        if($desc>500){
            $a = new stdClass();
            $a->count = $desc;
            $errors['description'] = get_string('error:descriptiontoolong','oakland_groups',$a);
        }
        return $errors;
    }

    function definition_after_data() {
        global $DB, $OUTPUT;

        // Print picture.
        $mform = $this->_form;
        if ($userid = $mform->getElementValue('id')) {
            $group = $DB->get_record('oakland_groups', array('id' => $userid));
        } else {
            $group = false;
        }

        if ($group) {
            $context = context_system::instance();
            $fs = get_file_storage();
            $filename = 'f1.png';
            $hasuploadedpicture = $fs->file_exists($context->id, 'oakland_groups', 'grouplogo', $group->id, '/', $filename);
            if (!empty($group->logo) && $hasuploadedpicture) {
                global $CFG;
                $component = 'oakland_groups';
                $filearea = 'grouplogo';
                if($file_record = $DB->get_record('files',array('id'=>$group->logo))){
                    $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$context->id/$component/$filearea/$file_record->itemid".'/'.$filename);
                    $imagevalue = html_writer::img($url,'');
                }else{
                    $imagevalue = get_string('none');
                }

            } else {
                $imagevalue = get_string('none');
            }
        } else {
            $imagevalue = get_string('none');
        }
        $imageelement = $mform->getElement('currentlogo');
        $imageelement->setValue($imagevalue);

//        if ($org && $mform->elementExists('deletepicture') && !$hasuploadedpicture) {
//            $mform->removeElement('deletepicture');
//        }


    }
}
