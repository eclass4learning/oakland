<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author  Valerii Kuznetsov <valerii.kuznetsov@totaralearning.com>
 * @package mod_facetoface
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Update 9.0 manager prefix strings to new with added "Below is the message that was sent to learner:" suffix.
 * If affects only unchanged original 9.0 strings in facetoface notifications and their templates
 */
function facetoface_upgradelib_managerprefix_clarification() {
    global $DB;

    $upgradestrings = [
        "setting:defaultconfirmationinstrmngrdefault" => "setting:defaultconfirmationinstrmngrdefault_v92",
        "setting:defaultcancellationinstrmngrdefault" => "setting:defaultcancellationinstrmngrdefault_v92",
        "setting:defaultreminderinstrmngrdefault" => "setting:defaultreminderinstrmngrdefault_v92",
        "setting:defaultrequestinstrmngrdefault" => "setting:defaultrequestinstrmngrdefault_v92",
        "setting:defaultrolerequestinstrmngrdefault" => "setting:defaultrolerequestinstrmngrdefault_v92",
        "setting:defaultadminrequestinstrmngrdefault" => "setting:defaultadminrequestinstrmngrdefault_v92",
        "setting:defaultdeclineinstrmngrdefault" => "setting:defaultdeclineinstrmngrdefault_v92",
        "setting:defaultregistrationexpiredinstrmngr" => "setting:defaultregistrationexpiredinstrmngr_v92",
        "setting:defaultpendingreqclosureinstrmngrcopybelow" => "setting:defaultpendingreqclosureinstrmngrcopybelow_v92"
    ];
    // Get all notifications templates.
    $notificationtables = ['facetoface_notification_tpl', 'facetoface_notification'];
    foreach ($notificationtables as $table) {
        $templates = $DB->get_records($table);
        foreach ($templates as $template) {
            foreach ($upgradestrings as $original => $new) {
                // Conditionaly update strings according content.
                if (strcmp($template->managerprefix, text_to_html(get_string($original, 'facetoface'))) === 0) {
                    $template->managerprefix = text_to_html(get_string($new, 'facetoface'));
                    $DB->update_record($table, $template);
                }
            }
        }
    }
}

/**
 * Ensure all Calendar events exist for seminar sessions with multiple dates
 * Events are only created for missing sessiondates if there are at least one existing event
 * Non-date values are copied from the existing event(s)
 */
function facetoface_upgradelib_calendar_events_for_sessiondates() {
    global $DB;

    // Due to the type inconsistencies between {event}.uuid and {facetoface_sessions_dates}.id,
    // we can't use a join between events and facetoface_sessions_dates. We therefore need to
    // use muliple statements to allow $DB to handle type conversions.

    // Get all the facetoface_sessions that have events with multiple dates
    $sql = 'SELECT sessionid
            FROM {facetoface_sessions_dates}
        GROUP BY sessionid
          HAVING count(id) > 1';

    if ($sessionidrows = $DB->get_records_sql($sql)) {
        // As other settings may have prevented calendar events being created for these sessions,
        // we only create missing events if there is at least one existing event for this each session

        foreach ($sessionidrows as $sessionidrow) {
            $sql = 'SELECT DISTINCT e.name, e.description, e.format, e.courseid, e.groupid, e.userid,
                                    e.uuid, e.instance, e.modulename, e.eventtype, e.visible
                  FROM {event} e
                 WHERE e.uuid = :uuid
                   AND e.modulename = :modulename
                   AND e.eventtype = :eventtype';
            $params =  array('uuid' => $sessionidrow->sessionid,
                             'modulename' => 'facetoface',
                             'eventtype' => 'facetofacesession');

            if ($eventrows = $DB->get_recordset_sql($sql, $params)) {
                foreach ($eventrows as $eventrow) {
                    $sql = 'INSERT INTO {event}
                                        (name, description, format, courseid, groupid, userid, uuid, instance, modulename, eventtype,
                                         timestart, timeduration, visible)
                                 SELECT :name, :description, :format, :courseid, :groupid, :userid, :uuid, :instance, :modulename, :eventtype,
                                        d.timestart, d.timefinish - d.timestart, :visible
                                   FROM {facetoface_sessions_dates} d
                                  WHERE d.sessionid = :sessionid
                                    AND d.timestart not in (
                                        SELECT timestart
                                        FROM {event} e
                                        WHERE e.uuid = :seluuid
                                          AND e.courseid = :selcourseid
                                          AND e.groupid = :selgroupid
                                          AND e.userid = :seluserid)';

                    $params = array('name' => $eventrow->name,
                                    'description' => $eventrow->description,
                                    'format' => $eventrow->format,
                                    'courseid' => $eventrow->courseid,
                                    'groupid' => $eventrow->groupid,
                                    'userid' => $eventrow->userid,
                                    'uuid' => $eventrow->uuid,
                                    'instance' => $eventrow->instance,
                                    'modulename' => $eventrow->modulename,
                                    'eventtype' => $eventrow->eventtype,
                                    'visible' => $eventrow->visible,
                                    'sessionid' => $sessionidrow->sessionid,
                                    'seluuid' => $eventrow->uuid,
                                    'selcourseid' => $eventrow->courseid,
                                    'selgroupid' => $eventrow->groupid,
                                    'seluserid' => $eventrow->userid,
                                );

                    $DB->execute($sql, $params);
                }
            }
        }
    }
}

/**
 *  Rewrite grades if they are 0.0000 and their signup state is either fully/partially attended
 */
function facetoface_upgradelib_fixup_seminar_grades() {
    global $DB;
    /** @var \moodle_database $DB */
    // magic numbers
    // status_fully: 100
    // status_partially: 90
    // status_booked: 70
    // grade_fully: 100
    // grade_partially: 50

    // TODO: need more complicated solution to the system upgraded from Evergreen-20190322
    $DB->execute('UPDATE {facetoface_signups_status} SET grade = NULL WHERE statuscode <= 70');
    $DB->execute('UPDATE {facetoface_signups_status} SET grade = 50 WHERE statuscode = 90 AND grade = 0');
    $DB->execute('UPDATE {facetoface_signups_status} SET grade = 100 WHERE statuscode = 100 AND grade = 0');
}

/**
 * Fixed the orphaned records with statuscode 50 as we deprecated "Approved" status.
 */
function facetoface_upgradelib_approval_to_declined_status() {
    global $DB;

    $superceded = 0;
    $statuscode = 50;
    $statuses = $DB->get_records_sql(
        'SELECT fss.*, u.id AS userid, f.id AS facetofaceid
           FROM {facetoface_signups_status} fss
           JOIN {facetoface_signups} fs ON fs.id = fss.signupid
           JOIN {facetoface_sessions} s ON s.id = fs.sessionid
           JOIN {facetoface} f ON f.id = s.facetoface
           JOIN {user} u ON u.id = fs.userid
          WHERE superceded = :superceded AND statuscode = :statuscode',
        ['superceded' => $superceded, 'statuscode' => $statuscode]
    );
    /** @see \mod_facetoface\signup\state\declined::get_code() */
    $declined_status = 30;
    $upgrade_log_notice = defined('UPGRADE_LOG_NOTICE') ? UPGRADE_LOG_NOTICE : 1;
    $trans = $DB->start_delegated_transaction();
    foreach ($statuses as $status) {
        // Update the record.
        $DB->set_field('facetoface_signups_status', 'statuscode', $declined_status, ['id' => $status->id]);

        // Add a log message.
        upgrade_log(
            $upgrade_log_notice,
            'mod_facetoface',
            'Invalid user signup cancelled: userid ' . $status->userid . ', facetofaceid ' . $status->facetofaceid
        );
    }
    $trans->allow_commit();
}