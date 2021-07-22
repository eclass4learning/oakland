<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 4:35 PM
 */

class block_oakland_group_bio_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB;
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        //TODO: I need this setting, but users should not be able to change it.
        $groups = $DB->get_records('oakland_groups');
        $grouparray = array();
        foreach($groups as $group){
            $grouparray[$group->id] = $group->name;
        }

        $mform->addElement('select', 'config_grouptodisplay', get_string('grouptodisplay', 'block_oakland_group_bio'), $grouparray);
    }
}