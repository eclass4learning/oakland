<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 4:35 PM
 */

class block_forum_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB;
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $forumarray = array('Group' => 'Group', 'Got It' => 'Got It', 'Want It' => 'Want It');
        $mform->addElement('select', 'config_forumtodisplay', get_string('forumtodisplay', 'block_forum'), $forumarray);
        $mform->setDefault('config_forumtodisplay', 'Group');
        $mform->setType('config_forumtodisplay', PARAM_TEXT);
    }

}