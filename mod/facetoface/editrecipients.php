<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Francois Marier <francois@catalyst.net.nz>
 * @author Aaron Barnes <aaronb@catalyst.net.nz>
 * @package totara
 * @subpackage reportbuilder
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/facetoface/lib.php');

define('MAX_USERS_PER_PAGE', 1000);

$s              = required_param('s', PARAM_INT); // facetoface session ID
$add            = optional_param('add', 0, PARAM_BOOL);
$remove         = optional_param('remove', 0, PARAM_BOOL);
$recipients     = optional_param('recipients', '', PARAM_SEQUENCE);

list($session, $facetoface, $course, $cm, $context) = facetoface_get_env_session($s);

// Check essential permissions
require_login($course, false, $cm);
require_capability('mod/facetoface:viewattendees', $context);

// Recipients
$recipients = explode(',', $recipients);

foreach ($recipients as $key => $recipient) {
    if (!$recipient) {
        unset($recipients[$key]);
    }
}

// Handle the POST actions sent to the page
if ($frm = data_submitted()) {
    // Add button
    if ($add and !empty($frm->addselect) and confirm_sesskey()) {
        require_capability('mod/facetoface:addrecipients', $context);

        foreach ($frm->addselect as $adduser) {
            if (!$adduser = clean_param($adduser, PARAM_INT)) {
                continue; // invalid userid
            }

            $recipients[] = $adduser;
        }
    }
    // Remove button
    else if ($remove and !empty($frm->removeselect) and confirm_sesskey()) {
        require_capability('mod/facetoface:removerecipients', $context);

        foreach ($frm->removeselect as $removeuser) {
            if (!$removeuser = clean_param($removeuser, PARAM_INT)) {
                continue; // invalid userid
            }

            $recipients = array_diff($recipients, array($removeuser));
        }
    }
}

$usernamefields = get_all_user_name_fields(true);

// Main page
// Get the list of currently selected recipients
$existingusers = array();
if ($recipients) {
    list($insql, $params) = $DB->get_in_or_equal($recipients);

    $existingusers = $DB->get_records_sql('
        SELECT id, email, ' . $usernamefields . ' ' .
        'FROM {user}
        WHERE id ' . $insql, $params);
}

$existingcount = $existingusers ? count($existingusers) : 0;

$sql  = "
    FROM {user}
   WHERE id IN
        (
        SELECT s.userid
            FROM {facetoface_signups} s
            WHERE s.sessionid = ?
        )
   ORDER BY lastname ASC, firstname ASC
";

// Get all available attendees
$availableusers = $DB->get_records_sql('SELECT id, email, ' . $usernamefields . ' ' . $sql, array($session->id));
$availableusers = array_diff_key($availableusers, $existingusers);

$usercount = count($availableusers);
$url = new moodle_url('/mod/facetoface/editrecipients.php', array('s' => $s));

// Prints a form to add/remove users from the recipients list
?>

<form id="assignform" method="post" action="<?php echo $url; ?>">
<div>
<input type="hidden" name="sesskey" value="<?php p(sesskey()) ?>" />
<input type="hidden" name="add" value="" />
<input type="hidden" name="remove" value="" />
<input type="hidden" name="recipients" value="<?php echo implode(',', $recipients) ?>" />
  <table summary="" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
      <td valign="top" width="40%">
          <label for="removeselect"><?php print_string('existingrecipients', 'facetoface', $existingcount); ?></label>
<br />
<select name="removeselect[]" size="22" style="width: 100%;" id="removeselect" multiple="multiple"
        onfocus="getElementById('assignform').add.disabled=true;
                           getElementById('assignform').remove.disabled=false;
                           getElementById('assignform').addselect.selectedIndex=-1;">

    <?php
    $i = 0;
    if ($existingcount > 0) {
        foreach ($existingusers as $existinguser) {
            $fullname = fullname($existinguser, true);
            echo "<option value=\"{$existinguser->id}\">{$fullname}, {$existinguser->email}</option>\n";
            $i++;
        }
    }
    if ($i == 0) {
        echo '<option/>'; // empty select breaks xhtml strict
    }
    ?>

</select>
</td>
<td valign="middle" style="width: 20%; text-align: center;">
    <p class="arrow_button">
        <input name="add" id="add" type="submit" value="<?php echo $OUTPUT->larrow().'&nbsp;'.get_string('add'); ?>" title="<?php print_string('add'); ?>" style="width: 75%; text-align: center; margin: auto; " onClick="$('form#assignform input[name=add]').val(1);" />
        <br />
        <input name="remove" id="remove" type="submit" value="<?php echo $OUTPUT->rarrow().'&nbsp;'.get_string('remove'); ?>" title="<?php print_string('remove'); ?>" style="width: 75%; text-align: center;" onCLick="$('form#assignform input[name=remove]').val(1);" />
    </p>
</td>
<td valign="top" width="40%">
    <label for="addselect"><?php print_string('potentialrecipients', 'facetoface', $usercount); ?></label>
    <br />
    <select name="addselect[]" size="22" style="width: 100%;" id="addselect" multiple="multiple"
            onfocus="getElementById('assignform').add.disabled=false;
                           getElementById('assignform').remove.disabled=true;
                           getElementById('assignform').removeselect.selectedIndex=-1;">
        <?php
        $i = 0;
        if ($usercount) {
            foreach ($availableusers as $user) {
                $fullname = fullname($user, true);
                echo "<option value=\"{$user->id}\">{$fullname}, {$user->email}</option>\n";
                $i++;
            }
        }
        if ($i == 0) {
            echo '<option/>'; // empty select breaks xhtml strict
        }
        ?>
    </select>
</td>
</tr>
</table>

</div>
</form>