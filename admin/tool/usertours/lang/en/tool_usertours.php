<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for tool_usertours.
 *
 * @package   tool_usertours
 * @copyright 2016 Andrew Nicols <andrew@nicols.co.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['above'] = 'Above';
$string['actions'] = 'Actions';
$string['appliesto'] = 'Applies to';
$string['below'] = 'Below';
$string['block'] = 'Block';
$string['block_named'] = 'Block named \'{$a}\'';
$string['cachedef_stepdata'] = 'List of user tour steps';
$string['cachedef_tourdata'] = 'List of enabled user tours information which is fetched on every page';
$string['description'] = 'Description';
$string['confirmstepremovalquestion'] = 'Are you sure that you wish to remove this step?';
$string['confirmstepremovaltitle'] = 'Confirm step removal';
$string['confirmtourremovalquestion'] = 'Are you sure that you wish to remove this tour?';
$string['confirmtourremovaltitle'] = 'Confirm tour removal';
$string['content'] = 'Content';
$string['content_heading'] = 'Content';
$string['content_help'] = 'Content describing the step may be added as plain text, enclosed in multilang tags (for use with the multi-language content filter) if required.

Alternatively, a language string ID may be entered in the format identifier,component (with no brackets or space after the comma).';
$string['cssselector'] = 'CSS selector';
$string['defaultvalue'] = 'Default ({$a})';
$string['delay'] = 'Delay before showing the step';
$string['done'] = 'Done';
$string['editstep'] = 'Editing "{$a}"';
$string['tourisenabled'] = 'Tour is enabled';
$string['enabled'] = 'Enabled';
$string['event_tour_started'] = 'Tour started';
$string['event_tour_reset'] = 'Tour reset';
$string['event_tour_ended'] = 'Tour ended';
$string['event_step_shown'] = 'Step shown';
$string['exporttour'] = 'Export tour';
$string['filter_header'] = 'Tour filters';
$string['filter_help'] = 'Select the conditions under which the tour will be shown. All of the filters must match for a tour to be shown to a user.';
$string['filter_theme'] = 'Theme';
$string['filter_theme_help'] = 'Show the tour when the user is using one of the selected themes.';
$string['filter_role'] = 'Role';
$string['filter_role_help'] = 'A tour may be restricted to users with selected roles in the context where the tour is shown. For example, restricting a Dashboard tour to users with the role of learner won\'t work if users have the role of learner in a course (as is generally the case). A Dashboard tour can only be restricted to users with a system role.';
$string['importtour'] = 'Import tour';
$string['left'] = 'Left';
$string['modifyshippedtourwarning'] = 'This is a user tour that has shipped with Totara. Any modifications you make may be overridden during your next site upgrade.';
$string['movestepdown'] = 'Move step down';
$string['movestepup'] = 'Move step up';
$string['movetourdown'] = 'Move tour down';
$string['movetourup'] = 'Move tour up';
$string['name'] = 'Name';
$string['newstep'] = 'Create step';
$string['newstep'] = 'New step';
$string['newtour'] = 'Create a new tour';
$string['next'] = 'Next';
$string['options_heading'] = 'Options';
$string['pathmatch'] = 'Apply to URL match';
$string['pathmatch_help'] = 'Tours will be displayed on any page whose URL matches this value.

You can use the % character as a wildcard to mean anything.
Some example values include:

* /totara/dashboard/% - to match the Dashboard
* /course/view.php?id=2 - to match a specific course
* /mod/forum/view.php% - to match the forum discussion list
* /user/profile.php% - to match the user profile page

If you wish to display a tour on the Site Home page, you can use the value: **FRONTPAGE**.';
$string['placement'] = 'Placement';
$string['pluginname'] = 'User tours';
$string['resettouronpage'] = 'Reset user tour on this page';
$string['right'] = 'Right';
$string['select_block'] = 'Select a block';
$string['targettype_help'] = 'Each step is associated with a part of the page - the target. Target types are:

* **Block**: For displaying a step next to a specified block.
* **CSS selector**: For accurately defining the target area using CSS.
* **Display in middle of page**: For a step which does not need to be associated with a specific part of the page.';
$string['selector_defaulttitle'] = 'Enter a descriptive title';
$string['selectordisplayname'] = 'A CSS selector matching \'{$a}\'';
$string['skip'] = 'Skip';
$string['target'] = 'Target';
$string['target_heading'] = 'Step target';
$string['target_block'] = 'Block';
$string['target_selector'] = 'Selector';
$string['target_unattached'] = 'Display in middle of page';
$string['targettype'] = 'Target type';
$string['title'] = 'Title';
$string['title_help'] = 'The title of a step may be added as plain text, enclosed in multilang tags (for use with the multi-language content filter) if required.

Alternatively, a language string ID may be entered in the format identifier,component (with no brackets or space after the comma).';
$string['tourconfig'] = 'Tour configuration file to import';
$string['tourlist_explanation'] = 'You can create as many tours as you like and enable them for different parts of Totara. Only one tour can be created per page.';
$string['tours'] = 'Tours';
$string['pausetour'] = 'Pause';
$string['resumetour'] = 'Resume';
$string['endtour'] = 'End tour';
$string['orphan'] = 'Show if target not found';
$string['orphan_help'] = 'Show the step if the target could not be found on the page.';
$string['backdrop'] = 'Show with backdrop';
$string['backdrop_help'] = 'You can use a backdrop to highlight the part of the page that you are pointing to.

Note that backdrops are not compatible with some parts of the page such as the navigation bar.';
$string['reflex'] = 'Proceed on click';
$string['reflex_help'] = 'Proceed to the next step when the target is clicked on.';
$string['placement_help'] = 'A step may be placed above, below, left or right of the target. Above or below is recommended, as these adjust better for mobile display.

If the step does not fit on a particular page in the specified place, it will be automatically placed elsewhere.';
$string['delay_help'] = 'You can optionally choose to add a delay before the step is displayed.

This delay is in milliseconds.';
$string['selecttype'] = 'Select step type';
$string['sharedtourslink'] = 'Tour repository';
$string['usertours'] = 'User tours';
$string['usertours:managetours'] = 'Create, edit, and remove user tours';
$string['target_selector_targetvalue'] = 'CSS selectors';
$string['target_selector_targetvalue_help'] = 'A CSS selector can be used to target almost any element on the page. The appropriate selector can be easily found using the developer tools for your web browser.';
$string['viewtour_info'] = 'This is the \'{$a->tourname}\' tour. It applies to the path \'{$a->path}\'.';
$string['viewtour_edit'] = 'You can <a href="{$a->editlink}">edit the tour defaults</a> and <a href="{$a->resetlink}">force the tour to be displayed</a> to all users again.';
$string['tour_resetforall'] = 'The state of the tour has been reset. It will be displayed to all users again.';
