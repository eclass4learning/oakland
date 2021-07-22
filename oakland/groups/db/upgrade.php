<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:44 PM
 * To change this template use File | Settings | File Templates.
 */
defined('MOODLE_INTERNAL') || die;
//require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

function  xmldb_oakland_groups_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2015050601) {
        $grouptable = new xmldb_table('oakland_groups');

        $groupemailcolumn = new xmldb_field('group_email',XMLDB_TYPE_CHAR,255, null, true);

        if (!$dbman->field_exists($grouptable, $groupemailcolumn)) {
            $dbman->add_field($grouptable, $groupemailcolumn);
        }

        upgrade_plugin_savepoint(true, 2015050601, 'oakland', 'groups');
    }

    if ($oldversion < 2015050602) {
        $grouptable = new xmldb_table('oakland_groups');
        $drivecolumn = new xmldb_field('g_drive',XMLDB_TYPE_CHAR,255, null, true);
        $calendarcolumn = new xmldb_field('g_calendar',XMLDB_TYPE_CHAR,255, null, true);
        $youtubecolumn = new xmldb_field('g_youtube',XMLDB_TYPE_CHAR,255, null, true);
        $hangoutscolumn = new xmldb_field('g_hangouts',XMLDB_TYPE_CHAR,255, null, true);

        if (!$dbman->field_exists($grouptable, $drivecolumn)) {
            $dbman->add_field($grouptable, $drivecolumn);
        }
        if (!$dbman->field_exists($grouptable, $calendarcolumn)) {
            $dbman->add_field($grouptable, $calendarcolumn);
        }
        if (!$dbman->field_exists($grouptable, $youtubecolumn)) {
            $dbman->add_field($grouptable, $youtubecolumn);
        }
        if (!$dbman->field_exists($grouptable, $hangoutscolumn)) {
            $dbman->add_field($grouptable, $hangoutscolumn);
        }
        upgrade_plugin_savepoint(true, 2015050602, 'oakland', 'groups');
    }

    if ($oldversion < 2015050603) {
        $grouptable = new xmldb_table('oakland_groups');
        $imagecolumn = new xmldb_field('group_image', XMLDB_TYPE_BINARY, 'medium', null, false);

        if (!$dbman->field_exists($grouptable, $imagecolumn)) {
            $dbman->add_field($grouptable, $imagecolumn);
        }

        upgrade_plugin_savepoint(true, 2015050603, 'oakland', 'groups');
    }

    if ($oldversion < 2015050605) {
        $applicationtable = new xmldb_table('oakland_group_applications');

        if (!$dbman->table_exists($applicationtable)) {

            $applicationtable->add_field('id', XMLDB_TYPE_INTEGER, 10, true, true, XMLDB_SEQUENCE);
            $applicationtable->add_field('oaklandgroupid', XMLDB_TYPE_INTEGER, 10, true, true);
            $applicationtable->add_field('applicantid', XMLDB_TYPE_INTEGER, 10, true, true);
            $applicationtable->add_field('status',XMLDB_TYPE_CHAR,255, null, true);
            $applicationtable->add_field('requestsource',XMLDB_TYPE_CHAR,255, null, true);
            $applicationtable->add_field('requestdate',XMLDB_TYPE_INTEGER, 10, true, true);
            $applicationtable->add_field('statusdate',XMLDB_TYPE_INTEGER, 10, true, false);
            $applicationtable->add_field('adminuserid', XMLDB_TYPE_INTEGER, 10, true, false);

            $applicationtable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $applicationtable->add_key('oaklandgroupid', XMLDB_KEY_FOREIGN, array('oaklandgroupid'), 'oakland_groups', array('id'));
            $applicationtable->add_key('applicantid', XMLDB_KEY_FOREIGN, array('applicantid'), 'user', array('id'));
            $applicationtable->add_key('adminuserid', XMLDB_KEY_FOREIGN, array('adminuserid'), 'user', array('id'));

            $dbman->create_table($applicationtable);

            upgrade_plugin_savepoint(true, 2015050605, 'oakland', 'groups');
        }

    }

    if ($oldversion < 2015050606) {
        $grouptable = new xmldb_table('oakland_groups');
        $topicscolumn = new xmldb_field('topics', XMLDB_TYPE_CHAR, 255, null, false);

        if (!$dbman->field_exists($grouptable, $topicscolumn)) {
            $dbman->add_field($grouptable, $topicscolumn);
        }

        upgrade_plugin_savepoint(true, 2015050606, 'oakland', 'groups');
    }

    if ($oldversion < 2015072100) {
        $grouptable = new xmldb_table('oakland_groups');
        $topicscolumn = new xmldb_field('logo', XMLDB_TYPE_INTEGER, 10);

        if (!$dbman->field_exists($grouptable, $topicscolumn)) {
            $dbman->add_field($grouptable, $topicscolumn);
        }

        upgrade_plugin_savepoint(true, 2015072100, 'oakland', 'groups');
    }

    if ($oldversion < 2015100600) {
        $grouptable = new xmldb_table('oakland_groups');
        $alt_admin = new xmldb_field('alt_admin', XMLDB_TYPE_INTEGER, 10);

        if (!$dbman->field_exists($grouptable, $alt_admin)) {
            $dbman->add_field($grouptable, $alt_admin);
        }

        upgrade_plugin_savepoint(true, 2015100600, 'oakland', 'groups');
    }

}