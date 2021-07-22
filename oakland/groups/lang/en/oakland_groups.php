<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/11/15
 * Time: 1:44 PM
 */

$string['pluginname'] = 'Oakland Groups';
$string['managegroups'] = 'Manage Groups';
$string['groups'] = 'Groups';
$string['oakland_group_admin_report'] = 'Oakland Group Administration';
$string['name'] = 'Group Name';
$string['audiencename'] = 'Audience';
$string['dashboardname'] = 'Dashboard';
$string['coursename'] = 'Course';
$string['datecreated'] = 'Date Created';
$string['owner'] = 'Owner';
$string['actions'] = 'Actions';
$string['oaklandgroupadmin'] = 'Oakland Groups Administration';
$string['group_explain'] = '<p>This page allows for the administration of Oakland Groups and gives the current status of the three components that make up a group: An Audience, a Dashboard, and a Course.
    In the event that one of these components has gone missing or become corrupt, this page will offer the ability to restore that missing piece and fix the group.
    Direct links to each of these components is also provided for administrative purposes, but should <strong>not</strong> be used for the general editing of a group, its members, home page, or activities.</p>
    <p>You can also create, edit and delete groups from this page.</p>';
$string['creategroup'] = 'Create Group';
$string['editgroup'] = 'Edit Group';
$string['editmembers'] = 'Edit Members';
$string['deletegroup'] = 'Delete Group';
$string['groupdeletemessage'] = 'Are you sure you want to delete "{$a->name}"? This action delete all content related to the group and cannot be undone.';

// Find Groups
$string['oaklandfindgroups'] = 'Find Groups';
$string['oakland_find_groups_report'] = 'Find Groups';
$string['purpose'] = 'Purpose';
$string['member_count'] = 'Members';
$string['groupcreator'] = 'Group Creator';
$string['courselastupdated'] = 'Last Updated';

//Form Strings

$string['settings_header'] = 'Settings';
$string['visible'] = 'Visible';
$string['hidden'] = 'Hidden';
$string['public'] = 'Public';
$string['access'] = 'Access';
$string['private'] = 'Private';
$string['access'] = 'Access';
$string['description_header'] = 'Description';
$string['purpose'] = 'Purpose';
$string['description'] = 'Description';
$string['topics'] = 'Topics';
$string['group_image'] = 'Image';
$string['name'] = 'Group Name';
$string['google_header'] = 'Google Resource Links';
$string['g_calendar'] = 'Google Calendar';
$string['g_drive'] = 'Google Drive';
$string['g_hangouts'] = 'Google Hangouts';
$string['g_youtube'] = 'Youtube';

$string['name_help'] = 'The name of the group. This will be used to locate ht group in searches and be used for the header on the group screen.';
$string['hidden_help'] = 'Controls whether the group is returned during searches for groups. A visible group will be returned in the results, a hidden group will not.';
$string['private_help'] = 'Controls who can join the group. A public group can be joined by any user, a private one must have any questions to join approved by a group administrator.';
$string['purpose_help'] = 'The purpose for the formation of the group.';
$string['description_help'] = 'A description of the group. Limit: 500 characters.';
$string['topics_help'] = 'A series of keywords used to locate the group.';
$string['group_image_help'] = 'An image displayed on the group page';
$string['g_calendar_help'] = 'To set up a shared Google calendar for the group:<br><br>
1. Select My Calendars, click the drop down to the right<br>
2. Select Create new calendar<br>
3. Name the calendar with the group name<br>
4. Click the drop down arrow to the right of the new calendar<br>
5. Select Share this calendar<br>
6. Under the section, Share with specific people, enter the email address for the group';
$string['g_drive_help'] = 'To share a folder with other group members:<br><br>
1. Create a folder for the group in Google drive.<br>
2. Right click on the folder and select share<br>
3. Enter the group email address for the People prompt<br>
4. Click done';
$string['g_hangouts_help'] = 'To allow group members to join a Video hangout:<br><br>
1. Select the Hangouts app<br>
2. Select Video Hangouts<br>
3. Click start a video Hangout<br>
4. Copy the link at the top of the Hangout window into Google Hangouts field';
$string['g_youtube_help'] = "To create a Youtube channel to share with group members:<br><br>
1. Make sure you're signed in to YouTube.<br>
2. Go to All my channels.<br>
3. If you want to make a YouTube channel for a Google+ page that you manage, you can choose it here. Otherwise, click Create a new channel.<br>
4. Fill out the details to create your new channel.<br>
5. Click the new channel<br>
6. Copy the URL for the channel from the web browser";

$string['g_calendar_static'] = 'A URL that provides access to the shared Google calendar for the group.';
$string['g_drive_static'] = 'A URL that proves access to the Google Drive shared folder for the group.';
$string['g_hangouts_static'] = 'A URL that provides access to a link to a Hangout for the group.';
$string['g_youtube_static'] = 'The URL for the YouTube channel associated with the group.';

$string['error:shortnameinuse'] = 'This name is already in use by either a group or a course.';
$string['error:purposetoolong'] = 'Text is too long. Limit: 200 characters. You have {$a->count} characters.';
$string['error:descriptiontoolong'] = 'Text is too long. Limit: 500 characters. You have {$a->count} characters.';

$string['leavegrouptitle'] = 'Leaving Group';
$string['leavegroupstatic'] = 'You are about to leave this group. Please confirm that the group name above is the group you intend to leave, or press Cancel to go back without leaving this group.';

$string['grouprequeststitle'] = 'Group Process Request Page';
$string['grouprequestsheader'] = 'Group Membership Requests';
$string['grouprequestsheader_help'] = "<b>Approve: </b> Approving a request will result in the user being added as a member to the group. The new group member will be sent a welcome message with a link to the group's home page.<br><br>
<b>Deny: </b> Denying a request will result in the user being sent a rejection message and not being added to the group. However, they will be able to make additional requests in the future.<br><br>
<b>Cancel: </b> Exits from the approval page.";

$string['applytogrouptitle'] = 'Group Application Page';
$string['privateapply'] = 'Request to Join';
$string['publicapply']= 'Join This Group';
$string['privatestatictext'] = 'This is a restricted membership group. If you wish to join, please click the request access button. Your request will be sent to the group administrator for review.<br><br>
If your request is approved, you will receive a welcome message with a link to the group home page';
$string['publicstatictext'] = 'Click the button below to gain access.';
$string['existingmembertext'] = "You are already a member of this group. Click Continue to visit this group's page, or click Cancel to go back.";
$string['pendingjoingrequeststatictext'] = 'You already have a pending join request in the queue.  Click Continue to visit the Collaboratorium, or click Cancel to go back.';

$string['grouplogo'] = 'Group Logo';
$string['grouplogo_help'] = 'Your group\'s logo that will display on the dashboard. Note that the image may be cropped and re-sized to best fit for a the space it will appear in. For best results, the image should be proportionally square shaped and no larger than 400 px (wide) by 300px (tall).';
$string['currentlogo'] = 'Current Logo';
$string['group_image'] = 'Group Image';
$string['groupemail'] = 'Group Email';
$string['processrequest'] = 'Process Request';
$string['notauthorized'] = 'You don\'t appear to be authorized to join or create groups';

// OAKLAND CUSTOM STRINGS
$string['setalt'] = 'Save Selection';
$string['setalt_header'] = 'Set an Alternate Administrator';