<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:44 PM
 */

function xmldb_oakland_groups_install(){
    global $DB;
    $dbman = $DB->get_manager();


    $field = new xmldb_field('oaklandgroupid',XMLDB_TYPE_INTEGER,10);

    $table = new xmldb_table('course');
    if(!$dbman->field_exists($table,$field)){
        $dbman->add_field($table,$field);
    }

    $table = new xmldb_table('cohort');
    if(!$dbman->field_exists($table,$field)){
        $dbman->add_field($table,$field);
    }

    $table = new xmldb_table('totara_dashboard');
    if(!$dbman->field_exists($table,$field)){
        $dbman->add_field($table,$field);
    }

}