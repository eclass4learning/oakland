<?php
/*

Totara Learn Changelog

Release 12.27 (26th January 2021):
==================================


Security issues:

    TL-21540       Fixed potential XSS bug in developer debugging messages

                   Prior to this patch, the debuginfo part of developer debugging messages was
                   not properly escaped, which could lead to a situation where a cross-site
                   scripting attack was possible. The debuginfo message is only ever sent to
                   output when 'Debug messages' is set to developer, and
                   'Display debug messages' is on. This should never be the case on a
                   production site. Nevertheless, it is a potential attack vector on staging
                   or development sites and has been fixed.

Improvements:

    TL-29256       Improved performance of the badge award cron job when using audience criteria when just one of multiple audiences is needed to be completed

Bug fixes:

    TL-28070       Fixed cache not being updated after using the course completion editor
    TL-28900       Ensured the PDF annotation review panel is hidden for 'Online text' only assignment submissions
    TL-29004       Added user-friendly error when attempting to view a hidden category in the grid catalogue
    TL-29007       Fixed conditions for displaying a warning about pending updates for appraisal assignments

                   Previously, a warning about pending updates was wrongly displayed in the
                   assignments tab of the appraisal administration when there were users that
                   had completed that appraisal, even when no updates were pending. This has
                   been fixed with this patch.

    TL-29016       Fixed formatting of multi-lang names used in competency types, scales and frameworks
    TL-29159       Ensured notifications count is not displayed if notifications are disabled for the user
    TL-29217       Fixed updating of usernames when using user upload functionality

                   When updating usernames using 'oldusername' and the idnumber was present
                   the duplicate idnumber validation check would incorrectly report that the
                   username was a duplicate for users who were having their username changed.
                   The idnumber validation now works correctly with updating usernames.

    TL-29255       Removed aggressive user session cleanup code to eliminate some session timeouts on login page

Contributions:

    * Russell England, Kineo USA - TL-29159


Release 12.26 (24th December 2020):
===================================


Improvements:

    TL-28591       'Login as' functionality is now using full site-level switching from course profile if possible
    TL-28645       Added workaround for duplicate tag name detection when invalid accent insensitive collation used in MySQL

                   Note that accent and case insensitive collations are not compatible with
                   tags implementation, please consider upgrading MySQL 5.7 and MariaDB to
                   MySQL 8.0 and switching to utf8mb4_0900_as_cs collation.

    TL-28702       Improved performance of badge award cron job when using programs criteria when just one of multiple programs is needed to be completed

Bug fixes:

    TL-28101       Fixed legacy seminar signup records stuck between 'manager approval required' and 'booked'

                   This patch adds an upgrade step that originally shipped with Totara 9, to
                   change any remaining seminar signup records with the old 'approved' status
                   code to 'declined'. All changes are logged.

                   Signups with status code 'approved' were meant to transition immediately to
                   'booked' but because of bugs or race conditions there may be records which
                   got stuck at 'approved'. The associated users would not have been notified,
                   and they would not have been able to complete the activity on the affected
                   signup. Such a bug was discovered in 2014, and an upgrade step in Totara 9
                   should have removed any affected records, but the potential for this to
                   happen exists in Totara 9, 10, and 11.

                   The 'approved' status code (50) was removed in Totara 12; approved seminar
                   signups transition directly to 'booked' with no intermediate state.

    TL-28648       Fixed bulk user actions not respecting language-specific name order
    TL-28777       Fixed logic of delete unconfirmed users task

                   Unconfirmed users task was incorrectly using the firstaccess field to
                   determine when to delete an unconfirmed user however firstaccess is never
                   populated until the user first logs in (which requires them to be
                   confirmed). The task is now correctly using timecreated.

    TL-28784       Fixed competency being automatically completed when linked course completion deleted
    TL-28808       Fixed SQL in the 'In Progress' column of the Course Completions by Organisation Report Builder report
    TL-28853       Stopped event being fired twice when creating records in database activity
    TL-28961       Learner role now marked as compatible with role assignments in programs
    TL-29005       Fixed incorrect unique parameters in DML drivers

Contributions:

    * Alex Morris at Catalyst - TL-28101


Release 12.25 (27th November 2020):
===================================


Security issues:

    TL-28438       Fixed the logged in user's sesskey being shown in the audience dialogue request URLs
    TL-28439       Removed sesskey from the URL after restoring a course
    TL-28440       Updated legacy webservice calls to send sesskey as HTTP header rather than as a get parameter

                   The JavaScript AJAX wrapper automatically added the current sesskey to
                   every AJAX call as a get parameter, making it part of the URL. This could
                   lead to sesskey exposure via server or proxy logs.

                   The wrapper has been updated to send the sesskey in the X-Totara-Sesskey
                   HTTP header, and the confirm_sesskey() function has been updated to check
                   for a sesskey there.

    TL-28441       Removed sesskey from URL's when editing blocks
    TL-28460       Properly validated getnavbranch.php id values

                   The getnavbranch.php AJAX endpoint is used by the Navigation and Course
                   Navigation blocks to dynamically load collapsed sections of the navigation
                   tree. For most branch types, it was designed to use the parent item id to
                   load child items, but two values were allowed for the root node branch
                   type: 'courses' and 'mycourses'. As a result, the endpoint allowed any
                   alphanumeric value to be passed as an id.

                   These special values ('courses' and 'mycourses') are now the only strings
                   allowed, and only for the root node branch type; all other id values must
                   be integers, to prevent any potential SQL injection vulnerabilities.

    TL-28496       Removed sesskey from the URL when uploading a backup file during restore

Improvements:

    TL-25546       Added new report source for saved searches
    TL-27631       Improved help text when a whitelisted domain is set and hierarchy free texts are allowed in self-registration with approval
    TL-28419       Fixed phpunit DML test compatibility with MySQL 8.0.22
    TL-28447       Improved webservice entrypoint to show generic error message in production environment
    TL-28463       Allowed course custom fields to be both locked and required
    TL-28761       Added new column and filter for user's time zone in Report builder

Bug fixes:

    TL-20636       Removed mentions to old My Learning page in 'Display attempt status' SCORM setting
    TL-23555       Fixed User calendar entries to respect course visibility

                   Previously if the Seminar Calendar option is set to "Course", the learner
                   who was not enrolled into a course was able to see the Seminar events, now
                   this issue is fixed.

    TL-27593       Fixed bug permitting successful oAuth2 login to redirect away from site
    TL-27602       Removed a seminar send-to-recipients template which exposed PHP code

                   The html file /server/mod/facetoface/editrecipients.html was being used as
                   a template by the message users edit recipients endpoint, and included PHP
                   code which could be exposed on the server. The HTML has been moved into the
                   PHP endpoint.

    TL-27958       Fixed issue where loading a form with an atto editor shifted focus away from input to the body
    TL-28034       Added PHP 7.2 compatibility function getallheaders to ensure code that uses it doesn't break

                   Some functionality in Totara uses the function getallheaders which was
                   added in PHP 7.3. This adds a compatibility function to ensure running on
                   PHP 7.2 works as expected.

    TL-28196       Truncated name field to 255 chars when creating evidence item when using Course / Certification Completion upload
    TL-28279       Updated tablelib to not print empty rows after results
    TL-28371       Fixed admin settings form elements to correctly be reverted when reloading the page without saving changes
    TL-28379       Stopped a warning from being shown when an alert or task is being sent without setting msgtype
    TL-28387       Added an automated fix for certifications which were incorrectly reassigned

                   Prior to Totara 10.0, it was possible that a user who was unassigned from a
                   certification and then reassigned would not be put back into the correct
                   state. This patch provides an automated fix which can be applied to users
                   who were affected in this way.

    TL-28430       Ensured the 'currentstagename' and 'expectedstagecompletiondate' Totara legacy appraisal message placeholders work correctly
    TL-28444       Allowed guest users to see catalogue images for visible programs and certifications
    TL-28461       Removed remote unserialize() call from Flickr reverse geocoding method, and deprecated the method

                   The phpFlickr::getFriendlyGeodata() method, which was used to discover the
                   place name at a given latitude and longitude (reverse geocoding), relied on
                   a script on the developer's website which is no longer available.
                   Additionally, the response from the website was passed directly to PHP's
                   unserialize() function, which could lead to PHP object injection.

                   The method has been deprecated, and now always returns false.

    TL-28462       Added a workaround for elimination of duplicate records in the course completion logs
    TL-28564       Fixed Learning plan items so they maintain state changes correctly
    TL-28590       Ensured an alert is sent for failed certification completion imports
    TL-28592       Fixed incorrect system context id in quicklinks block installation
    TL-28612       Fixed a small coding error in PHPExcel writeAutoFilter function
    TL-28646       Fixed HR import to not skip records when column length limit is exceeded


Release 12.24 (22nd October 2020):
==================================


Improvements:

    TL-6557        Added User's mobile phone number column and filter to report sources using the user trait
    TL-28095       Added option for Totara Connect users to change their password from client sites

Bug fixes:

    TL-27663       Fixed keyboard navigation in the Quick Access menu and Message Menu
    TL-27860       Fixed 'My status' column for the Seminar sessions report source when a user has 'Not set' status
    TL-27947       Ensured user identities are displayed when allocating spaces to a Seminar event

                   This creates consistency between 'Add attendees' and 'Allocate spaces' user
                   selectors.

    TL-28089       Fixed link to Notification preferences in plain text body of notification emails
    TL-28090       Fixed incorrect validation message when checking for Course Question Category loops
    TL-28113       Fixed duplicate triggering of module created event in test generators
    TL-28166       Fixed invalid default substring length in MS SQL Server database driver
    TL-28195       Fixed warning when exporting report using wkhtmltopdf

                   Prevented PDF export warning being displayed. A plain text description for
                   a report is now displayed with links and embedded images stripped out.

Contributions:

    * Davo Smith from Synergy Learning - TL-28113
    * Michael Geering from Kineo UK - TL-28166


Release 12.23 (1st October 2020):
=================================


Security issues:

    TL-27855       Added filtering the filter HTML in the tag manager
    TL-27856       Prevented "Log in as" capability being used in course context to gain privilege escalation
    TL-27857       Added check in repositories to prevent unzipping if it will exceed allowed quota

Bug fixes:

    TL-26772       Fixed incorrect join condition in Seminar session report source

                   In the Seminar Sessions report source the join condition being used for the
                   Viewer's status column was joining to the incorrect column leading to the
                   column showing incorrect data in situations where there were multiple
                   Sessions (dates) for an Event.

    TL-26828       Updated the Report Builder 'dismiss_link' display class to only show link for users own messages
    TL-27482       Fixed aggregation support for certification status, disabled aggregation of complex certification status in record of learning
    TL-27512       Fixed a seminar notifiation template getting detached from the associated site-wide template when enabletrusttext was enabled

                   Note that enabletrusttext has been removed in Totara 13

    TL-27685       Errors are logged when admin incorrectly hardcodes session settings in web server configuration
    TL-27686       Fixed user links in user upload preview
    TL-27853       Add the EXIF PHP extension as a recommended option for Totara 13 in all environment.xml files

Contributions:

    * Wajdi Bshara of Xtractor - TL-26772


Release 12.22 (22nd September 2020):
====================================


Improvements:

    TL-26315       Improved inline help text for placeholders in Program and Certification messaging
    TL-26654       Added extra information when errors occurring while importing CSV files in Totara sync.
    TL-26740       Added warning for incompatible aggregation of report containing custom fields

                   If visibility of a custom field has been restricted, when it is used in a
                   report that contains aggregation the report produces incorrect results.
                   This combination of settings is not supported and will now show a message
                   at the top of the report to notify the user. Changing the visibility of the
                   custom field to be available to all users will cause the report to
                   aggregate correctly and the message will not show.

    TL-27416       Unlimited password expiration duration was replaced with disabling of password expiration

Bug fixes:

    TL-22420       Totara Connect SSO login timeout was eliminated to prevent errors when users leave browser at login page
    TL-26575       Updated seminar 'Add users via file upload' to use case-insensitive matching on email and username
    TL-26739       Fixed invalid duplicate message-ids when bulk sending emails via SMTP
    TL-26812       Fixed PHP 7.1 incompatible type hint that caused error when importing Certification completion history
    TL-26905       Course icons are now displayed at a max of 35px by 35px
    TL-26955       Fixed the manager reservation system not working on PHP 7.1
    TL-26956       Prevented HR Import element settings being saved without source being selected
    TL-26995       Updated SQL in program assignments when using 'Management hierarchy' to avoid duplicate id debug error
    TL-27129       Ensure user_auth_method display class handles empty of missing auth types
    TL-27286       Updated get_order_by_sql() to include order by id

                   When using order by 'sorttime' in cases where 'sorttime' is the same adding
                   id to the order ensures consistent results.

    TL-27443       Allowed to use the hyphen in the column short name
    TL-27445       Fixed a PHP undefined variable notice in validate_booked_type()
    TL-27506       Ensure the learner is directed to the correct course on enrolment

                   In some instances on enrolment the leaner was redirected to the wrong
                   course due to a previously saved 'wantsurl' session. This has now been
                   resolved.

    TL-27507       Improved validation for Rating (numeric scale) questions in appraisals

                   The previous validation was only checking to ensure the value input was a
                   number and allowed decimals (eg. 7.5). Entering a number that included a
                   decimal point would cause a database error when the appraisal form was
                   submitted. The field is now correctly validated and only allows whole
                   numbers (matching the behaviour of the slider version of this element).

    TL-27629       Fixed display of incorrect error message displayed on login screen

                   Before this patch an incorrect error message was displayed on the login
                   screen after a guest user tried to access a user profile page and clicked
                   on the confirmation message to return to the login page.

    TL-27649       Fixed incorrect system context id in block installation

Contributions:

    * Russell England at Kineo USA - TL-26575, TL-26654, TL-26995
    * Sam McCullough at Lambda Solutions - TL-26812


Release 12.21 (17th August 2020):
=================================


Important:

    TL-26248       Certification audience rules now use exactly the same logic as certification related reports

                   Prior to being fixed the logic within certification status audience rule
                   did not match the calculation of certification status. As a consequence the
                   status of users who had complicated history with multiple assignments and
                   unassignments may have been incorrectly calculated.
                   This has been addressed and the certification status audience rule logic is
                   now in sync with the status calculation throughout the product.
                   After upgrading to this version of Totara any users who had been
                   incorrectly included in an audience due to this bug will be removed from
                   the audience when cron next runs.

                   In addition to fixing the audience rule other areas displaying
                   certification status were reviewed. A minor issue was found with the
                   certification status report builder column and has been fixed at the same
                   time.

Improvements:

    TL-25504       Improved the performance of the certification status audience rule

Bug fixes:

    TL-26139       Corrected display issues with fixed text and fixed image appraisal questions
    TL-26227       Fixed misleading audience rule description based on custom date
    TL-26249       Added validate_field method in Totara Completion Import to ensure large values do not cause database errors
    TL-26271       Added new Report Builder proficiency and priority display classes for the Competency report source
    TL-26355       Fixed certification completion import not running when triggered by a user other than the primary admin

                   Certification completion import used to have a dependency
                   This is a regression from TL-24134 which was released in 12.19.
                   Certification completion imports was changed so that the import of records
                   happened during cron in order to free the user from having to wait for
                   large imports and to ensure that the task would be completed in its
                   entirety by freeing it from the dependency on the user session.
                   The regression was due to a remaining dependency on the uploading user,
                   which meant that completion imports were only successfully imported when
                   the user who uploaded the file also happened to be the primary admin.
                   This has now been fixed and the dependency removed.

    TL-26361       Cloning a dashboard now only clones blocks that the user has permission to see

                   Previously when cloning a dashboard to enable the user to customise it all
                   blocks would be cloned regardless of whether the user had permission to see
                   the block or not.
                   Once cloned the blocks would then be visible to the user.
                   This has been fixed, and only blocks the user can see are cloned when the
                   dashboard is cloned.

    TL-26559       Removed an unnecessary anchor tag impacting accessibility present when viewing a forum discussion

Contributions:

    * Julie Prescott at Innovate-Solutions  - TL-26559


Release 12.20 (23rd July 2020):
===============================


Improvements:

    TL-25440       Improved error text when using Redis without the Redis extension installed
    TL-25658       A new "Locale compatibility check" page was added to help diagnose date format issues

                   The new page is available only to users who hold either the
                   moodle/site:config capability or tool/langimport:managelanguages
                   capability.
                   It is available though the site administration menu, under the location
                   settings section.

    TL-25761       Improved the accessibility of the SCORM player when launched in a new window

Bug fixes:

    TL-19308       Program tiles can no longer be added to the featured links block with programs have been turned off
    TL-21420       Fixed a PHP warning when clicking changing the visibility of items within gradebook
    TL-23243       Orphaned program assignments belonging to deleted audiences are now cleaned up by cron

                   Cron now removes program assignments belonging to audiences that no longer
                   exist. After upgrade all such orphaned program assignments will be removed
                   when cron runs.

    TL-23361       Seminar confirmation email notifications now respect the `Receive confirmation by` setting
    TL-25447       Ensured course default setting for Audience-based visibility is used when creating courses

                   Previously any course created by user who had the capability to create
                   courses but not to manage audience based visibility would be created
                   visible to all users, regardless of what the default visibility was set
                   to.
                   This has been fixed, and newly created courses respect the default
                   visibility setting.

    TL-25529       Temporary managers are now correctly able to view team member goals
    TL-25534       Fixed "Full name link with icon" column display of the user's full name
    TL-25535       Ensured marking completion by RPL respects the 'moodle/course:markcomplete' capability
    TL-25657       Page titles are now correctly displayed when viewing lesson activity pages
    TL-25736       Fixed a missing language string when language pack update fails
    TL-25794       Ensured empty rows are not added to the Excel spreadsheet when exporting feedback activity analysis
    TL-25830       Fixed a single course learning item for a given user in Totara catalog
    TL-26259       Ensured that ical files attached to seminar notifications include the message text in the ical description
    TL-26291       Fixed an issue causing cached report builder report columns not to be indexed

                   This issue caused indexes not to be generated for cached reports. This
                   meant that filters were not performing as well as they would with indexes.

                   Note: This could cause performance issues when generating caches for large
                   reports. If this occurs report caching should be disabled.

API changes:

    TL-25574       Ensured the 'course_module_completion_updated' event is triggered when changed via the Course Completion Editor
    TL-25717       Improved course format API sanity checks when creating sections

                   Sanity checks now prevent the creation of invalid course section numbers.

    TL-25765       Improved activity completion state handling during course archiving

                   All mod_pluginname_archive_completion functions have been changed to stop
                   updating activity completion state and are now handling activity specific
                   data archiving only. For this reason, they have been marked as internal and
                   should not be used outside of the course archiving API.

Contributions:

    * Davo Smith at Synergy Learning - TL-26291
    * Kirill Astashov at Androgogic - TL-25574
    * Vesa Virta at Discendum - TL-25830
    * Wajdi Bshara at Xtractor - TL-25794


Release 12.19 (25th June 2020):
===============================


Security issues:

    TL-25145       Backported MDL-68410to prevent remote code execution by malformed SCORM package
    TL-25269       Updated default CDN of MathJax filter library to version 2.7.8

Improvements:

    TL-10390       Fixed upload users tool to validate duplicated IDnumbers
    TL-24134       Moved Certification completion import to adhoc task and optimised import

                   Certification completion import now only does a basic import of the records
                   immediately on CSV file upload in the UI. The records are then processed in
                   an adhoc task which is executed on the next cron run.

    TL-24976       Increased the maximum number of multi-select custom field options to 128
    TL-25239       Updated PHPMailer to the latest security release

                   PHPMailer library has been updated to version 5.2.28.

    TL-25253       Removed restriction limiting items shown in drop-down for 'Time uploaded' report filter in the Completion import reports

                   In the completion upload reports for both Course and Certification the
                   'Time uploaded' drop-down filter was missing the option for an import if it
                   had errors or if the records were uploaded as evidence (for the
                   Certification report). These restrictions have been removed and all upload
                   times are now shown.

    TL-25275       Improved reliability of SCORM packages saving progress
    TL-25358       Improved compatibility with MySQL 8.0.20

                   Fixed MySQL 8 integer length calculation

Bug fixes:

    TL-21424       Fixed multiple checkbox filters in Report Builder reports not working as expected when excluding options
    TL-22016       Fixed typo in Badges status_help string
    TL-24747       Fixed broken error detection when uploading course completion history for a course without manual enrolment

                   In a very specific circumstance uploading course completion history records
                   for a course without the manual enrolment plugin succeeds where it should
                   have failed. The error detection has been fixed.

    TL-25083       Made sure completed programs do not appear in the Current Learning block when added via a user's learning plan
    TL-25108       Fixed report builder select filters to matching the search when a value contains an ampersand
    TL-25141       Fixed validation of custom profile menu values when uploading user accounts via a CSV file
    TL-25153       Fixed user tours not working on the user profile editing page when site policy requiring consent is set up
    TL-25200       Ensured content displayed in a due dates dialog stays inside the dialog

                   When on the enrolled learning tab when viewing a cohort, clicking any link
                   inside a view due dates dialog caused the content to be loaded outside the
                   dialog. This has now been changed so that it stays inside the dialog.

    TL-25216       Fixed compatibility of theme rendering with non-standard authentication methods

                   This fixes fatal errors stating that the layout file does not contain the
                   main content placeholder.

    TL-25220       Fixed inactive tabs when creating a new program or certification

                   Previously, when a new program or certification was being created it was
                   possible to access "Overview" tab which would result in an error. This has
                   now been fixed and "Overview" tab is no longer active until the program or
                   certification exist.

    TL-25271       Fixed language strings being incorrectly concatenated in the heading of the My Bookings page
    TL-25289       Fixed various typos in the language strings
    TL-25301       Fixed regression preventing dot notation being used to access object properties in mustache helper functions
    TL-25303       Stopped mustache escape helper incorrectly throwing debugging warning
    TL-25313       Changed sort order of rooms and assets in seminar edit event page

                   The lists of rooms and assets available to be assigned to a seminar session
                   were inadvertently changed in Totara 12 to be sorted by order of creation.

                   This change has been reverted, and they are now sorted alphabetically.

    TL-25320       Fixed the order of courses display in Program and Certification Overview reports to be consistent across all columns
    TL-25338       Made sure years are accounted in relative date calculations when the date is more than 1 but less than 2 years in the past
    TL-25399       Removed user-related content options from the Seminar Events report

API changes:

    TL-24762       Added new user_can_view() function in the block_base class

                   This new function is used to check correct access when blocks are displayed
                   on Totara Dashboards ensuring against exploits. Prior to this addition,
                   blocks were not aware of access control for the dashboard they are added
                   on.

                   The plugin_file function of the HTML and Featured Links block also now uses
                   the user_can_view() function to ensure correct access.

Contributions:

    * Bhoj Raj Bhatta at Xtractor - TL-25220
    * John Phoon at Kineo Pacific - TL-10390
    * Russell England at Kineo USA - TL-25320


Release 12.18 (21st May 2020):
==============================


Performance improvements:

    TL-23284       Improved the performance of the certification completion dynamic audience rule

Improvements:

    TL-10672       Increased spacing between question's action columns in the question bank
    TL-24734       Added support for Trial type registrations

                   Currently when registering a subscription you can choose between
                   "Production", "Staging/QA", "Development" or "Demo". This change adds
                   "Trial" as a fifth option.

                   Trial should be used specifically when a trial subscription has been
                   requested and approved via the subscription portal. The type should be
                   updated to "Production" once the trial goes live.

    TL-24755       Improved the position of custom text in certificates when trainers' names are printed as well
    TL-24785       Removed legacy non-js interface for the file picker

                   An old version of the file picker still existed within the product. This
                   version was only used if JavaScript was not available within the client.
                   JavaScript has been a requirement for several major releases and this
                   interface is now redundant. In addition to this there were aspects of the
                   old interface that did not meet our security standards.
                   It is has now been removed in its entirety.

Bug fixes:

    TL-23626       Fixed mixed case language string IDs in plugins breaking language pack customisation
    TL-24110       Fixed inline editing when initiated by keypress

                   When attempting to edit a report title or administration menu item inline
                   using just the keyboard the user may have experienced the inline editing
                   enhancement displaying and then immediately disappearing.
                   This was due to an issue with how the inline editing control was setting
                   focus, and has now been fixed.

    TL-24792       Fixed a division by zero regression when uploading course completions

                   TL-23158 introduced a new "CSV grade format" option to the course
                   completion upload process in Totara 12.15. However it caused a regression,
                   in which an error will be encountered when attempting to upload course
                   completions into a course with a maximum grade of 0.
                   This has now been fixed, and a 0 maximum grade is correctly handled.

    TL-24826       The admin_settings_changed event has been deprecated

                   An event  \core\event\admin_settings_changed was introduced in Totara 12.0
                   to support the new catalogue.
                   The event has now been deprecated and replaced by a new hook
                   \core\hook\admin_setting_changed.
                   The event was causing information to be logged that should not have been
                   logged. When upgrading to this version of Totara an upgrade step will
                   remove all admin_settings_changed events from the logstore_standard_log
                   table to ensure that no sensitive information is persisted.

    TL-24845       Fixed a warning that occurred when viewing a workshop activity that had been graded
    TL-24855       Added missing admin setting update callbacks to the admin/cli/cfg.php script
    TL-24863       Fixed broken field mapping for typeidnumber in hierarchy HR Import elements

                   Field mapping was being done differently for database and CSV sources
                   resulting in import errors when typeidnumber was mapped to a different
                   field name. Both sources now map fields in a similar way.

    TL-24912       Fixed the display of appraisal questions when being viewed by a role that cannot answer them

                   Prior to this fix a manager who had permission to view an appriasal, but
                   did not have permission to answer questions would be shown "not yet
                   answered" for the questions despite not being able to answer them.
                   This has now been fixed, and the questions are properly treated as read
                   only.

    TL-24927       Tags displayed within report filters and columns now display in the correct case

                   Prior to this fix all tags were shown in lower case, which is how the
                   product tracks them internally. This has been fixed and tags are now
                   displayed correctly, using the same case that they were created in.

    TL-24928       Fixed an issue in which several cached reports did not include required availability columns

                   The following report sources have been fixed to ensure required
                   availability columns are included in cached data:
                    * totara/certification/rb_sources/rb_source_certification.php
                    * totara/plan/rb_sources/rb_source_dp_certification.php
                    * totara/plan/rb_sources/rb_source_dp_program.php
                    * totara/plan/rb_sources/rb_source_dp_program_recurring.php
                    * totara/program/rb_sources/rb_source_program.php

                   The fix requires that cache report data is regenerated. Any report that is
                   affected by this issue will be corrected the next time the cache is
                   generated after upgrading. Alternatively, the site administrator can run
                   the \totara_reportbuilder\task\refresh_cache_task scheduled task manually
                   which will forcefully regenerate all report caches.

    TL-25077       Fixed grade import preview to show CSV data as plain text
    TL-25114       Linking from any course activity to a label resource now takes users directly to the label HTML element on the course page

API changes:

    TL-25113       Added a hook to allow manipulation of the RequireJS config before it is used

                   A new hook \core\hook\requirejs_config_generated has been added and can be
                   used to manipulate RequireJS config after it has been generated, allowing
                   plugins to introduce shims and other common constructs if required.

Contributions:

    * Dustin Brisebois at Lambda Solutions - TL-23626


Release 12.17 (29th April 2020):
================================


Security issues:

    TL-23040       Added a check to the security overview report for poorly configured Oauth 2 issuers

                   A new check has been added to the security overview report that warns the
                   user if there are OAuth 2 issuers configured to not verify users email
                   addresses.
                   The warning is displayed as critical if in addition to this the site has
                   been configured to permit users to share email addresses.

    TL-24490       Shibboleth attributes are now validated against a blacklist of common $_SERVER variables

                   Prior to this change Shibboleth attribute mapping could access any
                   variables stored in $_SERVER, allowing for malicious configurations to be
                   created.
                   All user attributes are now validated to ensure that they are not in a list
                   of commonly available $_SERVER variables that do not belong to Shibboleth.

    TL-24587       HTML block no longer allows self-XSS

                   Prior to this change, users could perform XSS attacks on themselves by
                   adding an HTML block when customising their dashboard, giving it malicious
                   content, saving it, and then editing it again.
                   When customised, a dashboard is only visible to the owning user. However
                   admins could still experience the malicious block using the login as
                   functionality.

                   This has now been fixed, and when editing an HTML block on user pages the
                   content is cleaned before it is loaded into the editor.

    TL-24618       Backported MDL-67861: IP addresses can be spoofed using X-Forwarded-For

                   If your server is behind multiple reverse proxies that append to
                   the X-Forwarded-For header then you will need to specify a comma
                   separated list of ip addresses or subnets of the reverse proxies to be
                   ignored in order to find the users correct IP address.

Performance improvements:

    TL-24573       Improved performance of lesson status filter in SCORM reports
    TL-24574       Changed type of SCORM attempts filter to a number filter to improve performance by avoiding extra database calls

Improvements:

    TL-22533       Improved the accessibility of PDF exports generated by report builder

                   The improvements made include ensuring the table uses correct markup,
                   contains a caption, row and column headers, and improving the use of
                   heading tags.

    TL-23015       Improved accessibility of the full report link in the 'Report graph' block
    TL-23991       Improved the use of wai-aria roles within the primary navigation template
    TL-24173       Updated aria roles of notifications to more accurately reflect their importance
    TL-24433       Improved confirmation dialog information when cloning audiences
    TL-24555       The Redis cache store now warns against changing the serializer setting when in use

                   Added a new warning to the Redis cache store configuration to warn
                   administrators that the serializer setting must not be changed if there is
                   any data in the cache already

    TL-24643       Improved the calculation of months when displaying relative date information

                   Previously, to calculate relative date in months (e.g. "A month ago", "4
                   months ago", etc.) an average number of days per month ??? 30.5 days ???
                   was used in the calculations. As a result, shorter months like February did
                   not always produce the correct outcome. This has now been changed to use
                   date difference which returns more accurate results.

    TL-24676       Added support for hiding of Totara forms elements based on 'value in array' and 'value not in array' conditions
    TL-24825       CLI script admin/cli/cfg.php now logs all changes so that incorrect changes can be identified later

Bug fixes:

    TL-17294       Fixed a reference to the wrong language string within the "Alerts" block
    TL-18762       Learning plan comments now correctly respect the site wide setting to disable comments
    TL-23459       Made sure Quiz activity takes passing grade requirement into account when restoring from course backups made with Totara 2.7 or earlier
    TL-24450       Prevented markup from showing in the course activity grouping toggle's alt text

                   When the grouping was toggled the name of the activity contained a span
                   tag. This is now correctly stripped out.

    TL-24546       Improved the JavaScript validation for required user profile checkbox fields
    TL-24558       Clarified seminar 'Add via list of IDs' error message

                   When one or more user IDs in a list of potential seminar attendees cannot
                   be found, none of the attendees are signed up to the seminar. The error
                   message displayed when this happens has been updated to make that clear.

    TL-24624       Ensured that attendees created via seminar direct enrolment are always enrolled

                   Previously, when attendees enrolled themselves on a course by signing up to
                   a seminar that required 'Manager and Administrative' approval, and the
                   seminar was already at capacity, the attendees would be added to the
                   waitlist on approval, but not enrolled on the course.

                   This patch makes sure that they are enrolled when their attendance is
                   approved, even if they are added to a waitlist.

    TL-24640       Clarified 'Send later' options for seminar notifications

                   Previously, the options for sending a seminar notification later were
                   'before start of session', 'after end of event', and 'before registration
                   closes'. The 'before start of session' string was accidentally left
                   unchanged when sessions became seminar events in Totara 9.

                   The option labels have been updated to make it clear when notifications
                   will be sent. The new options are:
                    * before event (opening session start time)
                    * after event (closing session end time)
                    * before end of Sign-up period (cut-off point)

                   The logic driving the notifications has not changed, only the strings for
                   the labels.

    TL-24659       Added redirect to fix seminar manager approval links which were using the old endpoint

                   The mod/facetoface/attendees.php endpoint used by managers to approve or
                   deny seminar attendance was moved to mod/facetoface/attendees/approval.php
                   in Totara 12 without a redirect. This caused links in any notifications
                   generated before upgrade to lead to a page that no longer exists.

                   A redirect has been added to ensure that actions in old seminar
                   notifications continue to work.

    TL-24687       Fixed completion date content filter not showing for course completion history report
    TL-24779       Ensured "inlist" type audience rule SQL parameters use unique names

                   This occurred when multiple inlist rules were added to an audience and were
                   using the IS EMPTY operator.
                   If encountered a fatal error was produced.
                   The inlist rule has now been updated to ensure it uses unique parameter
                   names.

    TL-24781       Fixed missing language string in course upload sanity check error

API changes:

    TL-22910       Send filename* instead of filename in the Content-Disposition response header

                   This patch will particularly resolve the file name corruption (mojibake)
                   when downloading a file with name containing non-ASCII characters on
                   Microsoft Edge 18 or older, by sending the filename* field introduced in
                   RFC 6266.
                   On the other hand, the filename field (without asterisk) is no longer sent
                   to prevent a browser bug in Apple Safari.

    TL-24579       No longer warn via debugging if the selected theme is not available during installation and upgrade

Contributions:

    * Sergey Vidusov at Androgogic - TL-24779


Release 12.16 (27th March 2020):
================================


Improvements:

    TL-23853       Improved the UX when performing bulk actions on thousands of users within the course participants page
    TL-24281       Added support for text filters including multilang syntax in auth_approved signup instructions

Bug fixes:

    TL-21837       Ensured the category is passed correctly when creating courses from GO1 content marketplace
    TL-22965       Fixed a sequencing issue that was leading to seminar attendees being incorrectly booked despite the 'waitlist everyone' setting having being enabled for the event
    TL-23605       Fixed sorting of score columns in the SCORM report source

                   The score columns in the SCORM report source were not sorting correctly
                   because the data type of the column in the database was not numeric. These
                   fields are now cast to the correct type ensuring sorting works as expected.

    TL-23661       Fixed the alignment of the 'Share' flyout within the catalogue when using IE11
    TL-24184       Fixed debugging warnings when attempting to view archived feedback activities
    TL-24186       Ensured course upload works correctly when the default site format for courses is the singleactivity course format
    TL-24211       Ensured that the custom message is included emails sent when bulk rejecting signups through the self registration with approval auth plugin
    TL-24237       Fixed generation of user profile links in seminar administration screens and reports

                   Prior to this patch, user profile links were added to seminar attendee's
                   names, even when the user viewing the screen or report could not view the
                   profile. Additionally, course profiles were used when the user was not yet
                   enrolled, or no longer enrolled, in the course. Clicking any of these
                   profile links resulted in an error.

                   With this patch, seminar attendees names are only linked if the current
                   user can actually view the profile.

    TL-24238       The progress bar within the lesson module now correctly uses the theme defined colour palette
    TL-24242       Fixed the display of the user's fullname column within the "Seminar Interest" report source
    TL-24245       Fixed an error occurring when attempting to add a user tour to a course enrolments page
    TL-24257       Fixed a performance regression on the reports page that was introduced in 12.11

                   The regression was introduced in TL-22260 when we added new options to
                   control export formats of the scheduled reports. This has now been fixed.

    TL-24294       Fixed a missing include when a user attempted to export calendar events
    TL-24310       Fixed the display of the user's fullname column in report sources overriding the user display class
    TL-24314       Fixed an issue preventing some sitewide seminar events from display as expected within the calendar
    TL-24320       Navigation no longer appears above course details view in grid catalogue when viewed on mobile screens

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-24403       Fixed the language string in Seminar activity module

                   The language string "mod_facetoface/signupforthissession" has been changed
                   to include a hyperlink in Totara 12.8 in August 2019 release.
                   This change has been reverted to use a new language string
                   "signupforthissessionlink" instead.
                   If you have customised the language string, please review the changes.

    TL-24456       Fixed the display of the last sent message within the messaging interface
    TL-24479       Fixed 'Date started' and 'Date assigned' filters in the certification completion report

                   Previously the 'Date assigned' filter was mis-labelled and filtered records
                   based on the 'Date started' column. This filter has now been renamed to
                   'Date started' to correctly reflect the column name. A new 'Date assigned'
                   filter has been added to filter based on the 'Date assigned' column.

    TL-24508       Tidied up Redis cache store settings

API changes:

    TL-24287       Fixed phpunit test failures when custom modules are installed
    TL-24363       Moved the job_assignment_deleted event trigger outside database transaction in delete job assignment function
    TL-24382       Behat library versions were hardcoded in order to prevent future compatibility problems

Contributions:

    * Dustin Brisebois at Lambda Solutions - TL-24294
    * Wajdi Bshara at Xtractor - TL-24310


Release 12.15 (26th February 2020):
===================================


Important:

    TL-23764       Chrome 80: SameSite=None is now only set if you are using secure cookies and HTTPS

                   Prior to this change if you were not running your Totara site over HTTPS,
                   and upgraded to Chrome 80 then you not be able to log into your site.
                   This was because Chrome 80 was rejecting the cookie as it had the SameSite
                   attribute set to None and the Secure flag was not set (as you were not
                   running over HTTPS).

                   After upgrading SameSite will be left for Chrome to default a value for.
                   You will be able to log in, but may find that third party content on your
                   site does not work.
                   In order to ensure that your site performs correctly please upgrade your
                   site to use HTTPS and enable the Secure Cookies setting within Totara if it
                   is not already enabled.

Security issues:

    TL-23950       Added sanitisation of send messages before they are displayed in messaging interface
    TL-24133       Ensured content was encoded before being used within aria-labels when viewing the users list

Performance improvements:

    TL-22894       Added course, program, and certification visibility map tables to improve performance of visibility-related queries

                   Previously, the database query used to compute which learning items were
                   visible to the user involved a large number of joins and subqueries to
                   resolve the roles held by the user in each context, and whether those roles
                   had the capability to view hidden items. Additionally, it did not take into
                   account the ability of admins to prohibit roles in category contexts.

                   In some database management systems, particularly with large numbers of
                   courses and deep category trees, this approach resulted in unacceptable
                   performance. This was especially noticeable when computing the number of
                   items visible in each category in the 'Category' catalogue.

                   With this patch, we now pre-compute which roles can see each course,
                   program, and certification in the system, and store the resulting
                   visibility maps in the database. The creation of this map is quick, and
                   greatly simplifies queries that involve visibility. It also improves
                   accuracy in sites that prohibit the capability to view hidden learning
                   items in some categories.

                   There is a new scheduled
                   task, totara_core\task\visibility_map_regenerate_all, which regenerates
                   the visibility maps every hour by default. Also, whenever a category,
                   learning item, or roles is updated, an ad_hoc task is queued to regenerate
                   the appropriate map(s). As such, there may be a delay between when changes
                   are made, and when items are considered hidden/visible to particular roles
                   by queries which check visibility.

Improvements:

    TL-19290       HTTP only cookies are now enabled by default
    TL-22721       Backported MDL-57968 core_message: Remove multiple unnecessary AJAX requests
    TL-23127       Removed redundant 'Enable' checkbox for temporary manager expiry date

                   Temporary managers must always have an expiry date.

    TL-23158       Added a new option 'CSV Grade format' to the 'Upload Completion Records' page

    TL-23278       Improved UI for attendees with course completion archive records

                   Previously, if a trainer tried to remove seminar attendees with archived
                   course completions from seminar sessions, an error message appeared without
                   much explanation.

                   The behaviour of seminar signups when course completion records are
                   archived is unusual, as most activity records are removed during the
                   completion archive process. Seminar signups must be kept in the system for
                   reporting purposes, so they are flagged as archived and considered to be
                   locked and unalterable.

                   This patch makes the following clarifications for trainers and admins
                   around archived seminar signups:
                    * Attendees with archived course completions are disabled in the 'Remove
                   users' form, so a trainer cannot select and remove them from the past
                   seminar sessions.
                    * On the 'Take attendance' page, the attendance fields of attendees with
                   archived course completions are locked and disabled, signifying that they
                   may not be changed.
                    * A warning message appears at the top of the 'Take attendance' page if
                   attendees with archived course completions are present, explaining why
                   attendance fields are disabled for some or all attendees.

    TL-23683       Added support for activity tags in Seminar, SCORM, and Feedback modules
    TL-23691       Increased the width of the course selection menu in course completion settings so that longer course names are displayed in full
    TL-23832       Improved automated generation of label names

Bug fixes:

    TL-7631        Conditional fields when editing certification course sets are now correctly disabled when not relevant
    TL-23072       Fixed columns and filters for course and audience tags in the report builder
    TL-23081       Prevented learners from requesting manager approval for seminar events that conflict with their existing approval requests

                   Previously when multiple seminar events existed with manager approval and
                   the same date and time, learners were able to request approval for
                   conflicting events. This caused confusion when managers tried to approve
                   the request but got date conflict errors instead.

                   This patch ensures that learners can only request approval for seminars
                   that do not conflict with other seminars they have already requested
                   approval for.

    TL-23173       Fixed error displayed in report builder when user session timed out
    TL-23362       Stopped seminar manager reservation links from being displayed when sign-up period is not open
    TL-23420       Changed the 'Attendee name' column in seminar reports so that it displays 'Reserved' for manager reservations, instead of being blank
    TL-23577       Fixed URL validation in Totara Featured Links and Quick Links blocks to allow local URLs

                   With the release of Totara 12.9, URL validation in the Featured Link and
                   Quick Links blocks was changed to allow the use of grid catalogue URLs with
                   square brackets in the query part. The change removed the ability to use
                   local URLs (URIs starting with '/') in those blocks.

                   This fix reenables support for local URLs. Any Featured Links static tiles
                   that were created with local URLs prior to Totara 12.9, and edited with
                   Totara 12.9+, will have been converted to a standard URL, and will need to
                   be manually edited after upgrade and converted back to a local URL.

    TL-23625       Fixed being able to uncheck 'Send to self' for Report Builder scheduled reports
    TL-23632       Removed access_token class which references invalid database table
    TL-23647       Fixed 'Declare interest' functionality when a user is booked onto a past event

                   Previously a "When no upcoming events are available" option is enabled for
                   Seminar, the "Declare interest" functionality worked for no upcoming events
                   and no past events if a user is booked onto a past event. Now it is fixed
                   and the user can declare interest if there are no upcoming events and the
                   user booked onto past events.

    TL-23654       Made sure that all courses (completed and in progress) are being reset during re-certification window open stage

                   The behaviour of manual completions archive remains unchanged (i.e. only
                   completions or completions via RPL are archived during manual course
                   reset).

    TL-23659       Fixed OAuth compatibility with login block
    TL-23672       The log in block now uses the correct Totara connect icon
    TL-23673       Made sure audience name is correctly formatted in the breadcrumbs on the Rule Sets page
    TL-23674       Fixed the display of server status on Totara Connect Servers page in administration

                   Previously the server status would not be correctly displayed for a server
                   where deletion was in progress.

    TL-23677       Changed the warning language string about column aggregations to soften the message
    TL-23740       Fixed compatibility with UUID PHP extension
    TL-23751       Made sure "Manage user reports" and "Manage embedded reports" can be added to the admin dropdown menu
    TL-23755       Prevent upload files link on HR Import CSV source settings pages showing when configuration is not complete

                   When the configuration is not complete clicking the link would result in an
                   error being shown. The link no longer shows until the minimum configuration
                   is completed.

    TL-23757       Blocks in the bottom region are now contained in a HTML element with "region-bottom" id

                   Previously this element had the HTML id "region-top"

    TL-23772       Made sure export controls in hierarchy frameworks are present only when at least one framework is exists and visible to a user
    TL-23776       Made sure aria-hidden works correctly on the YUI dialogues
    TL-23808       Fixed seminar manager reservations always being sent to booked state

                   Prior to this patch, seminar manager reservations were always given a
                   booked signup state, even if the seminar was set to send bookings to the
                   waitlist.

                   This has been fixed, and manager reservations are treated like other
                   signups. This patch also fixes a bug in the events dashboard that
                   misrepresented the number of wait-listed users on an overbooked event.

    TL-23834       Added horizontal scrolling to wiki revisions table
    TL-23852       The current learning block no longer triggers a re-aggregation of program courseset completion

                   The current learning block in some situations was causing program courseset
                   completion to be re-aggregated, leading to courseset completion time being
                   incorrectly updated if the courseset had already been completed.
                   This has been fixed and the courseset completion date is no longer updated
                   after it has been initially set.

    TL-23903       Fixed slot id generation when displaying multianswer (cloze) questions
    TL-23949       Added missing task name string for OAuth system token refresh task

                   The name string for the OAuth2 system token refresh task was omitted from
                   TL-20583.

Contributions:

    *  Russell England at Kineo USA - TL-23625


Release 12.14 (22nd January 2020):
==================================


Performance improvements:

    TL-22824       Improved performance of bulk delete of questions in a quiz
    TL-23390       Improved performance of building the course category tree

Improvements:

    TL-22841       Added a cleanup step as the last task in the course restore process to prevent unnecessary creation of the random questions
    TL-23354       Improved alignment of the folder resource on the course page

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

Bug fixes:

    TL-23085       Fixed 'Type of activity' selector not being populated for roles in category when single activity format is default
    TL-23171       Fixed an issue where not having permission to view another user's learning plan would result in an exception

                   Users without permission to view the learning plan now see a proper error
                   message when trying to access it.

                   Updated get_component_setting() function in development_plan.class to
                   return false when setting not found (instead of throwing an exception).

    TL-23312       Fixed Seminar event 'normal cost' and 'discount cost' visibility for seminar notifications

                   Previously the 'Hide normal cost' and 'Hide discount cost' seminar global
                   settings were not taken into account when seminar notifications were sent.
                   This fix ensures that these global settings are always taken into account.

    TL-23314       Fixed a bug that had unnecessarily replaced non-HTML line breaks with HTML tags when displaying a course summary in the grid catalogue
    TL-23415       'Choose file' button in filepicker form element is now disabled correctly

                   Previously when using disabledIf form functionality, the 'Choose file ...'
                   button was not being disabled correctly. This is now been fixed.

    TL-23416       Removed unnecessary sanitisation of 'Consumer key' and 'Shared secret' fields in the LTI external tool configuration forms

                   Previously, the forms stripped out values or removed them completely if '<'
                   or '>' characters were entered.

    TL-23440       Made sure system context is set on the main menu item display

                   Previously when the multi-language content filter was disabled at the
                   course level the site's main menu would be displayed as unfiltered when
                   being viewed from the course page. This now has been fixed and the main
                   menu always takes the general system settings for content filtering into
                   account.

    TL-23441       Allowed the case of job assignment ID number to be changed when using a case-insensitive database
    TL-23446       Fixed migration of HR Import file settings when upgrading from Totara 11 to new major version

                   In Totara 12 we introduced separate file settings for each element in HR
                   Import. When migrating from Totara 11 to Totara 12+, the existing settings
                   were not correctly migrated to these new element-specific settings.

    TL-23452       Improved display of Messages contacts selector when searching within a course with a long name
    TL-23458       Fixed permission check when sending plan approval request message to manager

                   The permissions check done when sending a plan approval request message to
                   a manager was only checking if they had the 'Allow' permission in the plan
                   template for the Approve setting. The 'Approve' option for the setting is
                   also now checked.

    TL-23490       Fixed minor custom field display issues in auth_approved reports
    TL-23519       Fixed placement of the 'Upload HR Import files' menu option in HR Import

                   A performance fix applied in Totara 12.7 caused the 'Upload HR Import
                   files' menu item to appear in the middle of the list of elements in the HR
                   Import settings menu. This has now been fixed.

    TL-23529       Fixed an error while viewing evidence for another user when the teams feature is disabled
    TL-23559       CSS cursor when hovering over an item in the grid catalogue is now a hand icon

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-23593       Ensured HR Import database source classes have the USES_FILES defined as false
    TL-23613       Fixed seminar direct enrolment session table overflowing display on small screens

API changes:

    TL-23511       The new minimum required Node.js version has changed to 12

                   It is recommended now to run at least Node.js 12 to run grunt builds.
                   Node.js 8 is almost out of support; we recommend to use the latest Node.js
                   12 to run grunt builds. However to avoid compatibility issues in stable
                   releases running Node 8 is still supported.


Release 12.13 (30th December 2019):
===================================


Important:

    TL-22800       Reworked the appraisal role assignment process to prevent duplicate assignments

                   1) This patch adds a new index to the appraisal_user_assignment table ??? a
                      unique index on an appraisal ID/appraisee ID combination.

                      It is possible that a site's appraisal_user_assignment table already has
                      duplicates; in this case, the site upgrade will fail. Therefore before
                      doing this upgrade, back up the site and then run this SQL query:

                      SELECT userid, appraisalid, count(appraisalid) as duplicates
                      FROM mdl_appraisal_user_assignment
                      GROUP BY appraisalid, userid
                      HAVING count(appraisalid) > 1

                      If this query returns a result, it means that the table has duplicates, and
                      they must be resolved first before an upgrade can successfully run. For
                      help, get in touch with the Totara support team and indicate the site has
                      been affected by TL-22800.

                   2) The behaviour has changed when the 'Update now' button is pressed in the
                      appraisal assignment tab. This is only for dynamic appraisals and the
                      button appears when appraisal assignments are added/removed after
                      activation. Previously when the button was pressed, the assignments were
                      updated in real time and the user would wait until the operation completed.
                      The refreshed screen would then show the updated list of appraisees.

                      With this patch, pressing the button spawns an ad hoc task instead and the
                      refreshed screen does not show the updated list of appraisees. Only when
                      the ad hoc task runs (depending on the next cron run ??? usually in the
                      next minute) are the assignments updated. When the user revisits the
                      appraisal assignment page, it will show the updated list of appraisees.

Security issues:

    TL-21671       Legacy internal flag 'ignoresesskey' is now usable within one request only, to prevent any potential security issues

Improvements:

    TL-19291       Disabled the MathJax filter plugin for fresh installations

                   The MathJax library is being fetched through the official content
                   distribution network. While it is a secure way of distributing the library,
                   this introduces some extra security risks that are unnecessary if the
                   library is not being used. Therefore it is recommended to disable the
                   plugin if formulas are not used in the editor anywhere on the site.

    TL-22097       Added support for redirection to originally-requested page when users are required to add missing profile information
    TL-22118       Removed support for obsolete counted recordsets

                   SQL_CALC_FOUND_ROWS and FOUND_ROWS() were deprecated as of MySQL 8.0.17.
                   Both were used by the get_counted_recordset() function, which is now marked
                   as deprecated and all usages have been modified to use alternatives.

    TL-22598       Improved 'Course visibility' selector help text
    TL-22697       Added a label to the seminar sign-in sheet download form
    TL-22837       Added setting to allow Solr search engine-defined default search fields to be used instead of those sent by Totara
    TL-22979       Added screen reader text stating which filters are in use when using the Grid catalogue
    TL-22982       Improved button signification when using a screen reader on the Grid catalogue

                   The 'share', 'list view' and 'grid view' buttons are now correctly
                   signified as buttons when using screen reader on the Grid catalogue.

    TL-23169       Fixed an editor width issue when modifying database activity templates
    TL-23170       Improved user interface of the course enrolment page for guests and learning plan enrolment types
    TL-23261       Improved the error message when a user can not join a seminar wait-list due to not having a manager

                   Please note that users without managers cannot be added to a wait-listed
                   event if the seminar requires manager approval.

    TL-23265       Introduced an environment check for Totara 12 and below to prevent installation and upgrade if PHP 7.4 is being used
    TL-23274       Allowed seminar events dashboard to scroll left-to-right on small screens
    TL-23328       Improved the parsing of dates for custom profile fields obtained from LDAP servers

                   Previously the supported format of dates in LDAP servers was limited to
                   unix timestamps, this change adds parsing of multiple date formats
                   supported by PHP DateTime class.

Bug fixes:

    TL-19293       Replaced parsing of members in course groups using JavaScript eval function with JSON.parse
    TL-20745       Ensured that due dates are not changed on user reassignment of expired certifications
    TL-22914       Ensured MS Word-type document files display the correct flex icon
    TL-22945       Removed "Home", "Find Learning" and "Log in" menu items from the site policy page when users are not logged in

                   This includes a fix for site policies set up to use the 'multi-language
                   content' filter. Previously if a user changed languages on the site policy
                   page it would still display the 'en' content, now it displays the correct
                   content for the selected language when possible.

    TL-22952       Fixed a bug so that the 'session_deleted' event will always be triggered after deleting a seminar event
    TL-23047       Fixed SQL server version detection to work around problems with legacy database compatibility levels
    TL-23048       Fixed a bug causing a 'duplicate key value violates unique constraint' error when swapping dates between seminar sessions
    TL-23070       Fixed the certification active period translation in the Grid catalogue

                   Previously, the duration of the certification active period was displayed
                   as a hard-coded English-language string in the catalogue. It is now
                   converted to a language string.

    TL-23162       Fixed JavaScript error when loading a page with no results in Grid catalogue
    TL-23165       Fixed inconsistency of Bootstrap Javascript versions

                   Previously, the thirdpartylibs.xml stated that the bootstrap Javascript
                   version in use was 3.3.7, when in fact it was version 3.3.4.

                   There were no code changes and all security fixes included in 3.4.1 are
                   still present.

    TL-23236       Prevented the resetting of course format 'Number of sections' values during course upload update

                   Previously when updating a course through course upload update, the 'Number
                   of sections' of course format (say "topics" or "weeks") was reset with the
                   default value (under some conditions). If the actual number of sections
                   exceeded the default value, some activities were affected by being moved
                   to the Orphaned section of the course.

                   This fix ensures that the 'Number of sections' value is not reset.

    TL-23237       Fixed an issue where incorrect links were generated for certificate downloads

                   Previously the list of certificate files used to generate the links
                   included directories, and when generating the links the filenames were
                   overridden with the next one in the list. Due to the sort order of some
                   databases this could result in the filename in the link being replaced by
                   the full-stop representing the directory.

    TL-23260       Fixed an issue with Icals that could prevent seminar booking confirmation emails sending when 'One message per date' is enabled
    TL-23281       Fixed attendee bookings when manager reservation expires

                   Previously when a manager reservation expired, users who were not on the
                   wait-list were given priority to sign up over the wait-listed users. This
                   patch has fixed the issue, so that when a manager reservation expires, the
                   wait listed users are automatically added as attendees.

    TL-23291       Glossary auto-linking filter now works correctly in the Grid catalogue
    TL-23293       Fixed the enrol users modal to always appear above all other content on the page
    TL-23311       Reworked gradebook purging in course archiving

                   Manual grades as well as grade overrides are now consistently purged when
                   course completion is archived during certification reset or manual course
                   archiving.

    TL-23313       Fixed typos in various language strings in the English language pack
    TL-23344       Fixed 'Booked by' column displaying 'Reserved' for self-booked users instead of their name
    TL-23345       Fixed the capitalisation of tag names displayed in the grid catalogue filter

                   When configuring the Grid catalogue to use tags as a filter, the tag names
                   would always be displayed in lowercase, regardless of the case used when
                   creating them. The display case now reflects exactly what was entered by
                   the user when the tag was created.

API changes:

    TL-23143       Mustache autoloader no longer repetitively registers shutdown handlers
    TL-23144       Improved the bulk loading of table data within the XMLDB editor improving its overall performance

Contributions:

    * Derek Henderson at Remote-Learner - TL-22097


Release 12.12 (26th November 2019):
===================================


Security issues:

    TL-23017       Backport MDL-66228: Prevented open redirect when editing Content page in Lesson activity

Performance improvements:

    TL-22505       Improved performance of the certification completion audience rule when only one certification is selected
    TL-22827       Improved appraisal assignment tab performance

                   Some appraisal functions in the assignment page have been rewritten to use
                   bulk SQL queries to improve their performance. Previously, the code worked
                   with one entity at a time.

    TL-23076       Optimised SQL join query to include userid in the rb_source_dp_course report source

                   To improve report performance, if userid is supplied to the report page of
                   the "Record of Learning: Courses" report source, it is now included in the
                   'course_completion_history' join SQL query.

Improvements:

    TL-22122       Added on-screen notification to users trying to connect to the Mozilla Open Badges Backpack

                   Since Mozilla retired its Open Badges Backpack platform in August 2019,
                   users attempting a connection to the backpack from Totara experience a
                   connection time out.

                   This improvement notifies the user about the backpack's end-of-service and
                   no longer tries to connect to the backpack.

                   Also, on new installations, the 'Enable connection to external backpacks'
                   is now disabled by default, since no other external backpacks are currently
                   supported.

    TL-22490       Added 'Export formats' and 'Export format override?' columns and filters to the 'Reports' report source

                   The report builder's 'Reports' report source now has two new columns and
                   filters to allow site admins to easily track the export formats used by
                   reports and quickly identify reports with a file export format option
                   override:
                    * The 'Export formats' column and filter shows the formats made available
                   for each report.
                    * The 'Export format override?' column and filter shows reports that have
                   export options that differ from the general export options for the report.

                   This improvement builds upon a change in October's release (TL-22260) that
                   added controls to the report level to ensure they are exported to relevant
                   file formats, and allows admins to better manage report export formats
                   available across the site.

    TL-22627       Allowed empty usernames for HR Import configuration when using MSSQL server as an external database source

                   Microsoft IIS can use Windows Authentication to connect to an MSSQL
                   instance, eliminating the need to store database credentials within Totara.
                   HR Import now allows administrators to configure import from MSSQL using
                   Windows Authentication by leaving the username and password fields blank.

    TL-22840       Added system information to upgrade logs
    TL-22890       Backported TL-22783 / MDL-62891

                   Backported the following commits:
                    # [MDL-62891|https://tracker.moodle.org/browse/MDL-62891] core: Stop using
                   var_export() to describe callables
                    # [MDL-62891|https://tracker.moodle.org/browse/MDL-62891] core: Introduce
                   new get_callable_name() function

    TL-22905       Improved log descriptions of job assignment events

                   Added a new job_assignment_created event for creating job
                   assignments. Improved event log description texts for
                   viewed/updated/deleted job assignment events by adding an actual user ID,
                   affected user ID and job assignment ID.

    TL-22941       Added 'none' option to seminar event role multiselect to enable unassignment of all previously assigned event roles
    TL-22973       Improved the alt text when removing the search text on the catalogue
    TL-22976       Improved accessibility of the item details pane in the grid catalogue
    TL-23006       User tour buttons now use default styling (instead of secondary styling)

Bug fixes:

    TL-22442       Fixed seminar event descriptions to better indicate who triggered the event, and for which user's signup

                   The booking_booked, booking_cancelled, booking_requested,
                   booking_waitlisted, session_signup and signup_status_updated events were
                   modified to fix and improve the event description texts by adding the
                   affected user ID.

    TL-22601       Search results count changes in report builder are now read by screen readers

                   When searching a report builder report, screen readers now read changes in
                   search results counts.

    TL-22706       Fixed 'Upload completion records' when user has suspended manual enrolment in a course

                   Previously when using 'Upload completion records' for courses, if a user in
                   the uploaded CSV file has a suspended manual enrolment in a course an error
                   message was displayed and upload failed. This fix ensures that the admin
                   can upload a CSV file with a user that has a suspended manual enrolment.

    TL-22726       Ensured totara_core_totara_lang_testcase passes with custom subplugins
    TL-22804       Self-registration autocomplete fields now work on first click
    TL-22828       Ensured program message placeholders work correctly when sending to managers
    TL-22838       Fixed double encoded entities in report exports

                   This is a partial backport of TL-21275 where we replaced relevant report
                   builder calls to format_string() with calls to the report builder display
                   class format_string which correctly encodes the string according to the
                   output format.

    TL-22839       MS SQL Server driver in database authentication and enrolments now ignores incompatible charsets
    TL-22862       Fixed missing job assignment on seminar signup when 'select job assignment' is enabled but user has only one assignment
    TL-22863       Fixed use of MySQL 8 reserved keyword 'member' in Report builder sources
    TL-22864       Fixed email spam when seminar sign-up state could not be switched to declined by the scheduled task
    TL-22865       Fixed Log type field output when HR import log report is exported
    TL-22866       Fixed 'Add users via file upload' for seminar attendees when custom field has been hidden
    TL-22867       Fixed misalignment of videos in glossary activity
    TL-22884       Fixed self-registration flow when site policy is provided in more than one language
    TL-22886       Password length restriction was removed from user signup forms
    TL-22901       Fixed Report Builder multicheck filter to work correctly with empty values
    TL-22902       Separated the language strings used to describe override events which also appear in the Events Monitor list for Quiz, Lesson, and Assignment activities
    TL-22903       User tours now work in Glossary activities
    TL-22913       Ensured email field in HR Import user element is handled correctly when empty

                   When updating a user where the CSV source contains an empty email and the
                   'Empty string behaviour in CSV' field set to ignore, the user record is now
                   updated ignoring the empty email field. Previously the user was skipped.

                   When using a database source, with the email field set as null, the user is
                   also updated once again ignoring the empty email field. Previously the user
                   was updated with the email field being set as empty.

                   It is not be possible to create or update a user so that they have no
                   email.

    TL-22929       Allowed multi-language values in report builder course shortname column
    TL-22930       Made sure microphone and camera access is allowed from the iframe in the External Tool activities
    TL-22944       Fixed help link for OAuth 2 services to point to the correct location

                   On the admin pages for setting up OAuth 2 services the help link to
                   detailed setup instructions pointed to an invalid page.

    TL-22955       Fixed broken 'Turn editing off' link on the seminar attendees page
    TL-22975       Screen readers no longer read the item name twice in grid catalogue

                   When using the grid catalogue, a screen reader would read a course (or
                   program or certification) name twice in quick succession. This has changed
                   so that it only reads it once.

    TL-22981       Added role attributes to the grid catalogue

                   Previously, when using a screen reader on the grid catalogue all the tiles
                   would run into each other. This patch inserts a pause in speech between
                   tiles so that it is easier to differentiate between them.

    TL-23016       Improved layout of "Show responses" tab in a feedback activity

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-23028       Backport MDL-62307: Made sure HTML special characters are decoded when viewing feedback responses
    TL-23071       Gave Staff Manager role the ability to remove a team member from a seminar space allocation
    TL-23080       Prevented ad hoc tasks from getting stuck in the queue due to error conditions
    TL-23110       Changed how seminar notifications are marked as sent, in order to prevent sending multiple copies of the same notification

                   Previously, scheduled and custom seminar notifications were marked as sent
                   by the ad hoc task responsible for sending the email to the recipient. This
                   meant that on a system with a large number of ad hoc tasks in the queue,
                   the same notification-sending task might be queued again before the first
                   was marked as sent, resulting in duplicate emails.

                   Seminar notifications are now marked as sent at the time the ad hoc task
                   that will send them is created. While this could result in a notification
                   being marked as sent even though some later error condition prevents
                   sending the actual email, we consider it an acceptable trade-off to prevent
                   duplicate emails.

    TL-23116       Fixed a bug in seminars that was sending booking confirmation twice when waitlisted users are confirmed

Miscellaneous Moodle fixes:

    TL-22928       MDL-66140: Added fix to mitigate PECL solr extension regression
    TL-22946       Backport MDL-57741: No simple way to use LTI launch without cartridge support

Contributions:

    * Andrew McMonagle at Learning Pool - TL-23076
    * Davo Smith at Synergy Learning - TL-22601
    * Michael Trio at Kineo USA - TL-22726


Release 12.11 (25th October 2019):
==================================


Important:

    TL-22311       The SameSite cookie attribute is now set to None in Chrome 78 and above

                   Chrome, in an upcoming release, will be introducing a default for the
                   SameSite cookie attribute of 'Lax'.

                   The current behaviour in all supported browsers is to leave the SameSite
                   cookie attribute unset, when not explicitly provided by the server at the
                   time the cookie is first set. When unset or set to 'None', HTTP requests
                   initiated by another site will often contain the Totara session cookie.
                   When set to 'Lax', requests initiated by another site will no longer
                   provide the Totara session cookie with the request.

                   Many commonly used features in Totara rely on third-party requests
                   including the user's session cookie. Furthermore, there are inconsistencies
                   between browsers in how the SameSite=Lax behaviour works. For this reason,
                   we will be setting the SameSite to 'None' for the session cookie when
                   Chrome 78 or later is in use. This will ensure that Totara continues to
                   operate as it has previously in this browser.

                   Due to the earlier mentioned inconsistencies in other browsers, we will not
                   set the SameSite attribute in any other browsers for the time being.
                   TL-22692 has been opened to watch the situation as it evolves and make
                   further improvements to our product when the time is right.

                   This change is currently planned to be made in Chrome 80, which we
                   anticipate will be released Q1 2020.

                   Chrome 80 is bringing another related change as well. Insecure cookies that
                   set SameSite to 'None' will be rejected. This will require that sites both
                   run over HTTPS and have the 'Secure cookies only' setting enabled within
                   Totara (leading to the secure cookie attribute being enabled).

                   The following actions will need to be taken by all sites where users will
                   be running Chrome:
                    * Upgrade to this release of Totara, or a later one.
                    * Configure the site to run over HTTPS if it is not already doing so.
                    * Enable the 'Secure cookies only' [cookiesecure] setting within Totara

                   For more information on the two changes being made in Chrome please see the
                   following:
                    * [https://www.chromestatus.com/feature/5088147346030592] Cookies default
                   to SameSite=Lax
                    * [https://www.chromestatus.com/feature/5633521622188032] Reject insecure
                   SameSite=None cookies

    TL-22408       Fixed two issues that could cause the tag name upgrade step to fail

                   As part of the fix for TL-21055 (introduced in  11.17, and 12.8), we
                   created an upgrade step to revert HTML-encoded tag names to their original,
                   non-encoded canonical state.

                   The upgrade step failed to take two conditions into account: multiple tag
                   instances on the same item that resolve to the same canonical tag; and
                   module tags that resolve to the same canonical tag as a course. In these
                   cases, the upgrade simply failed with a database exception.

                   The tags upgrade step has been rewritten to work properly in all
                   situations.

    TL-22560       Improved the handling of conditional access restrictions that reference deleted items

                   Fixed a bug that prevented editing the access restrictions when a deleted
                   audience, position, or organisation was used as part of a restriction set
                   in them

                   We also made changes to stop removing course activity access restrictions
                   that refer to those deleted items.

                   In previous versions, whenever an audience, position, or organisation was
                   deleted, all access restrictions referring to the deleted item were
                   removed.

                   This could result in learners being able to access sections and activities
                   that they were unable to access before.

                   With this release, access restrictions are kept in place if the item(s)
                   they refer to are deleted. Such restrictions are updated to refer to a
                   'missing audience', 'missing position', or 'missing organisation', and will
                   prevent access to everybody until either removed or associated with a
                   different item.

                   Please note:

                   * If a 'missing' restriction is part of an OR condition, it will simply not
                   match rather than preventing access.

                   * If a 'must not' restriction is 'missing,' then learners who formerly met
                   the restriction condition will not be restricted any more.

                   * There is no way to recover access restrictions that were previously
                   deleted because they referred to deleted items.

    TL-22621       SCORM no longer uses synchronous XHR requests for interaction

                   Chrome, in an upcoming release, will be removing the ability to make
                   synchronous XHR requests during page unload events, including beforeunload,
                   unload, pagehide and visibilitychanged.
                   If JavaScript code attempts to make such a request, the request will fail.

                   This functionality is often used by SCORM to perform a last-second save of
                   the user's progress at the time the user leaves the page. Totara sends this
                   request to the server using XHR. As a consequence of the change Chrome is
                   making, the user's progress would not be saved.

                   The fix introduced with this patch detects page unload events, and if the
                   SCORM package attempts to save state or communicate with the server during
                   unload, the navigation.sendBeacon API will be used (if available) instead
                   of a synchronous XHR request. The purpose of the navigation.sendBeacon API
                   is in line with this use, and it is one of two approaches recommended by
                   Chrome.

                   The original timeframe for this change in Chrome was with Chrome 78 due out
                   this month. However Chrome has pushed this back now to Chrome 80. More
                   information on this change in Chrome can be found at
                   [https://www.chromestatus.com/feature/4664843055398912]

                   We recommend all sites that make use of SCORM and who have users running
                   Chrome to update their Totara installations in advance of the Chrome 80
                   release.

Performance improvements:

    TL-21839       Improved the performance of the 'Progress' column within program and certification report sources
    TL-22262       Improved the performance of the calendar on sites with a large number of seminars per month

                   Calendar events are now filtered for visibility as they are loaded from the
                   database, rather than being checked one-by-one after being loaded.

    TL-22323       Improved overall in-browser page performance when a large number of popovers are present
    TL-22421       Added a new database index on role_capabilities (capability, roleid, permission)

                   This index has a profound impact on query performance when resolving
                   capability checks directly against the database.

    TL-22457       Added a new database index on cache_flags (flagtype, expiry, timemodified)
    TL-22461       Added a new database index on course (category,sortorder)
    TL-22471       Initialising HR Import settings for administrators no longer queries the database
    TL-22473       Optimised the course category management page to preload courses and course counts
    TL-22474       Optimised the navigation structure performance using totara_course_is_viewable

                   Site navigation was originally using totara_visibility_where when resolving
                   course visibility.
                   It has now been converted to use totara_course_is_viewable, given that the
                   number of courses loaded into the navigation is limited, and in displaying
                   the course within the navigation we must load all of the information to
                   resolve its visibility.

    TL-22475       Optimised enrol_get_my_courses to use totara_course_is_viewable

                   The enrol_get_my_courses method was originally using
                   totara_visibility_where when resolving course visibility. It has now been
                   converted to use totara_course_is_viewable, given the information available
                   within the function and that in most circumstances enrolled users can see
                   the courses they are enrolled it.

    TL-22478       Added a new database index on block_instances (blockname)

                   There are several queries within the platform, some that are executed on
                   most pages, that look up block instances by name.
                   While the queries are simple the table itself can grow significantly and
                   having an index on the blockname column aids database performance.

    TL-22611       Optimised the enrol_get_all_users_courses function to use totara_course_is_viewable

Improvements:

    TL-22051       Implemented a means to bulk delete unstarted course completions belonging to users who are no longer enrolled

                   When users are mistakenly enrolled, and then immediately unenrolled in a
                   course, a course completion record is left behind. This record is marked as
                   un-started, and can be manually deleted using the course completion
                   editor.

                   Manually deleting many thousands of records, as might need to happen when a
                   large audience is accidentally enrolled and then unenrolled from a course,
                   is prohibitively time-consuming. A new CLI script has been added at
                   'admin/cli/delete_unused_course_completions.php' to accomplish these
                   deletions in bulk.

                   Using the script will trigger an 'Admin CLI script execution, records
                   deleted' event that includes the parameters it was called with.

    TL-22260       Added the control of Report Builder export options at a report level
    TL-22389       Migrated all documentation links to point to help.totaralearning.com

                   All links to external documentation within the product take the user to the
                   appropriate page in our public product documentation available at
                   help.totaralearning.com

Bug fixes:

    TL-20100       Removed a redundant and inaccurate help popup for appraisal descriptions
    TL-21346       Fixed seminar restoration when the original seminar had been deleted but still existed within the recycle bin
    TL-21828       Fixed Behat scenarios attempting to click on select input options after recent changes in Chrome

                   Recent changes in Chrome have led to sporadic issues through our Behat
                   features relating back to the definitions that attempt to click on options
                   within a select input. All affected feature files have been converted to a
                   method that works in all versions of Chrome.

    TL-22207       Block permissions for authenticated users and guests are now restored corectly when restoring a course

                   In addition to the above noted fix the screen displaying information on
                   roles that cannot be mapped during restore now correctly displays built-in
                   role names and overridable roles.

    TL-22224       Fixed the grade functions of the seminar component to be compatible with other activity components
    TL-22243       Removed double-escaping of ampersands in the featured links block
    TL-22257       Added a missing capability check to stop an error message while adding an enrolled audience to a course
    TL-22348       Changed the default timesort for the Grid catalogue

                   Previously the time-based sorting method for the new grid-style catalogue
                   was based on the 'timemodified' value of the learning items being
                   displayed. This has been changed to use the 'timecreated' value instead,
                   giving a more consistent ordering of the learning items.

                   Note: Though the back-end code has been updated, this change will not be
                   visible on the catalogue until the refresh_catalog_data scheduled task is
                   run. If you want to see this change immediately please manually run the
                   task after upgrading, otherwise this change will only be updated on the
                   next scheduled run.

    TL-22349       Fixed custom navigation parent elements being un-deletable after upgrade
    TL-22394       Fixed the overflow of long category names within the Grid catalogue

                   Previously if long category names could extend under the tiles when using
                   the Grid catalogue. The maximum width of the category name is now managed,
                   and an ellipsis presented when the category name would exceed the available
                   space.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-22398       Fixed a potential problem in role_unassign_all_bulk() related to cascaded manual role unassignment

                   The problem may only affect third-party code because the problematic
                   parameter is not used in standard distribution.

    TL-22401       Removed unnecessary use of set context on report builder filters page
    TL-22419       Corrected link colours within a label course activity

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-22435       Fixed context used for capability checks during goal userdata exports

                   Previously the goal exports were checking if a user could see their own
                   goals in the system context, however the capability is meant to be used in
                   the user context. This has been rectified where possible.

    TL-22453       Featured links blocks no longer uses the text colour specified in Basis theme settings

                   The text colour specified in Basis theme settings is designed to work on
                   light backgrounds, not the dark background that the featured links block
                   uses.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-22511       Deleting course completions either via the interface or via CLI now correctly purges completion caches
    TL-22517       Fixed an error in first login tasks when a user has future assignments for multiple programs or certifications

                   If a user had multiple future assignments (due to setting a program due
                   date based on first login for a user who hadn't yet logged in), when the
                   first_login_assignments task ran, an error would be generated and users
                   wouldn't be assigned to the program / certification correctly.

    TL-22518       Fixed email-based self registration with position, organisation, and manager while logged in as a guest
    TL-22559       Seminar notification sender no longer reuses the sending user object

                   The reuse of the sending user object when sending notifications from within
                   the seminar occasionally led to an issue where notifications would appear
                   to come from random users. This has now been fixed by ensuring a fresh
                   sending user object is used for each notification.

    TL-22567       Made sure the 'Front page settings' page can be bookmarked in the administration menu
    TL-22576       All areas displaying a program or certification fullname are now formatted consistently

                   Prior to this change there were a handful of areas not correctly formatting
                   program and certification full names before displaying them. These have all
                   been tidied up and program and certification fullname is now formatted
                   correctly and consistently.

    TL-22582       Fixed a race condition in the Grid catalogue page that inadvertently reset filter settings

                   A race condition was identified in the auto-initialise AMD module, whereby
                   components could have been initialised out of order. In situations where
                   the anticipated order of execution was being relied upon this could
                   manifest visible errors such as catalogue filters from a shared URL being
                   incorrectly excluded.

                   This race condition has now been fixed and calls to require AMD modules are
                   now processed in advance of auto-initialisation.

    TL-22610       Fixed error in HR Import when importing organisation or position custom fields when typeidnumber is mapped

                   When importing organisation or position custom fields using HR Import, if a
                   field mapping was used in the typeidnumber field an error would be thrown
                   causing the import to fail. This has been fixed.

    TL-22622       Fixed an incorrect count of visible programs which led to missing pagination controls in the interface

                   This is a regression introduced by TL-22060 released in 12.10 last month.

                    The total count variable within prog_get_programs_page() was being
                   incorrectly populated after that change. It is now correctly populated.

    TL-22632       Fixed the report builder default fetch method setting as its value was not being respected
    TL-22658       Fixed the display of programs and certifications when folders are added to their image field

                   Folders are now ignored when added to the image field for programs and
                   certifications.
                   The ability to add folders will be removed in a future version by TL-22659.

API changes:

    TL-22248       Checks to confirm a user can log in as another user are now performed by the user access controller

                   'Login as' permission checks have been consolidated into a new method on
                   the user access controller, \core_user\access_controller::can_loginas()
                    Existing checks within the product have all been converted to use this new
                   method, ensuring that the control checks are applied consistently.

    TL-22392       Added course creation post definition and validation hooks

                   This change introduced two new hooks:

                   * \core_course\hook\edit_form_definition_after_data
                   * \core_course\hook\edit_form_validation

    TL-22472       Removed incorrect use of $DB->sql_like() for non-unicode fields

                   SQL code executing LIKE statements on the context path in the database was
                   in some situations using sql_like, which led to collation management being
                   introduced. This was leading to an unnecessary overhead as path is a
                   calculated field for which we always know the character range used.

    TL-22477       Active string filters per context are now cached using MUC

                   Previously a static variable $FILTERLIB_PRIVATE was used to cache active
                   filters by context during the lifetime of a request. This has been
                   converted to an MUC request cache, allowing for better management and
                   handling both in product and in our testing environments.

Contributions:

    * Brad Simpson at Kineo US - TL-22243
    * Davo Smith at Synergy Learning - TL-22518
    * Peter Spicer at Catalyst EU - TL-22392


Release 12.10 (19th September 2019):
====================================


Performance improvements:

    TL-22055       Materialization in MariaDB is now forced off for audience-based visibility queries

                   We became aware of an issue that affects the performance of audience-based
                   visibility check within MariaDB in situations where the context tree was
                   deep (many nested categories), there were lots of role assignments or
                   capability overrides.
                   In this situation the MariaDB query planner would inevitably choose to use
                   materialization in the query, however as MariaDB does not fully support
                   subquery condition pushdowns, this particular query would not perform well
                   with materialized subqueries.

                   This patch implements the ability to force materialization off for a single
                   query on MariaDB and then forces it off for the audience-based visibility
                   query.

    TL-22056       Improved performance of check_access_audience_visibility() function
    TL-22057       Optimised SQL capability checks for commonly encountered environments
    TL-22060       Improved the performance of course/program/certification management pages
    TL-22345       Performance improvements to 'Manage Course Category' page

                   Removed the very expensive 'can the current user delete all the
                   courses/subcategories?' check when displaying the 'Manage course category'
                   page. The system performs this check anyway when a user selects the
                   'delete' menu item on a category item.

Improvements:

    TL-22080       Default context maintenance task frequency was reduced to once a day to prevent overloading of database servers

Bug fixes:

    TL-21855       Fixed issue causing SCORM packages to fail to launch on IIS
    TL-21871       Fixed seminar 'Cancel booking' link displayed in calendar for users who have already cancelled their booking
    TL-21976       Fixed incorrect capability check when removing attendees from a seminar event

                   Previously, the 'Add attendees to a seminar event' capability was checked
                   when a user removed seminar attendees. With this patch, the correct 'Remove
                   attendees from a seminar event' capability is checked instead.

    TL-21995       Rangy selector spans are no longer added when editing HTML in the Atto editor

                   This will not remove pre-existing rangy selector spans.

    TL-22032       MDL-61996: Fixed login with the site policy enabled

                   Previously when the 'Force users to log in' setting was turned off, and the
                   site policies were enabled, it was possible for new users to log in,
                   immediately navigate to the homepage, and view the site content without
                   having consented to the policy.
                   This fix ensures that user has consented to the policy and can't view the
                   site content without it.

    TL-22042       Fixed seminar restore when rooms/assets were deleted from the system between backup and restore
    TL-22044       Removed accidentally included non-functional API changes for login page tokens
    TL-22045       Login form now is only submitted once per page load
    TL-22083       Fixed an error in the help text for URL pattern matching in User Tours
    TL-22112       Preserve activity completion status for activity completed via record of prior learning (RPL)

                   Previously, when an activity was marked as complete via record of prior
                   learning (RPL), the apparent completion status of the activity could change
                   to not completed, depending on activity completion criteria. The RPL
                   information was still there, and counted toward course completion, but the
                   activity itself could appear to be incomplete.

                   This has been fixed. Activities which are marked as complete via RPL will
                   always appear completed. The completion status may change to
                   complete-with-pass or complete-with-fail depending on activity completion
                   criteria and whether a passing grade has been entered in the grade book,
                   but they will never appear as not completed.

    TL-22123       Fixed HR Import failing to import users when using a MSSQL database

                   HR Import uses temporary tables to import records and in some situations
                   MSSQL runs into problems with updating records in temporary tables. We now
                   preload the database record set to work around this issue.

    TL-22133       Fixed language strings for Google reCaptcha
    TL-22135       Ensured that changing a seminar event does not empty its waitlist when 'Send all bookings to waitlist' is enabled
    TL-22182       Fixed incorrectly changed default value which caused program summary to not be shown

                   A default value was incorrectly changed in a previous fix which caused the
                   program / certification summary to not be shown on the program view page.
                   This now works as expected.

    TL-22208       Fixed file support in Totara form editor element

                   Prior to this patch, when using an editor element with Totara forms, images
                   that had previously been uploaded to the field were not displaying properly
                   during editing.

                   Note: This form element is not currently in use anywhere in a way that
                   would be affected by this.

    TL-22209       Fixed category titles being coloured incorrectly in the administration menu when using theme style overrides
    TL-22212       Fixed course default image on course creation workflow page

                   Prior to this patch, when a content marketplace was enabled and a default
                   image was configured in course default settings, the course creation
                   workflow page still showed the course default image of the basis theme.
                   With this patch the default image defined in the course default settings
                   will be shown.

    TL-22229       Fixed certif_completion_progress report builder display function

                   The display function certif_completion_progress in some circumstances was
                   using incorrect variables when trying to calculate the progress. This would
                   cause a PHP error and the progress would not be displayed.

    TL-22259       Fixed display of position and organisation names when exported in Report Builder

                   In the Position and Organisation report sources names that included '&'
                   would not be displayed correctly when exported.

    TL-22265       Ensured cloning Report Builder reports copies textarea files

                   When a report was cloned, any images added to the report description were
                   not copied. This patch ensures that they are.

    TL-22269       Removed user content restrictions from Course completion by Organisation report source

                   Whenever user content restrictions were added to the report, an error would
                   be generated because there is no user-related information in the report
                   source. Due to the purpose of the source, adding the user content
                   restrictions would not work. Therefore we have removed these content
                   restrictions.

    TL-22280       Backported MDL-65908 to fix an issue with PDF annotation in assignments when changing screen resolutions
    TL-22289       Ensured cloning an Audience also copies textarea files

                   When an Audience was cloned, any images added to the report description
                   were not copied. This patch ensure that they are.

    TL-22319       Fixed manager approval ignoring the 'sign-up for an event' permission

                   Before allowing learners to request manager approval for a seminar event,
                   the system now checks the 'sign-up for an event' capability
                   (mod/facetoface:signup).

                   Note that the capability check will be skipped for signup via the seminar
                   direct enrolment plugin.


Release 12.9.1 (26th August 2019):
==================================


Important:

    TL-22087       Fixed a logic bug in the upgrade step cleaning-up orphaned prog_completion records

                   The fix for TL-8836 that was recently released as part of Totara 11.18,
                   12.9 and Evergreen 20190822 contained a dataloss regression. The fix was
                   designed to remove orphaned program completion records, which previously
                   occurred when a program course set was deleted. Due to this logic bug the
                   upgrade step deleted all program completion records with a coursesetid of
                   0, these records are used to mark the users completion state within a
                   program.

                   This issue sees that logic bug fixed, and the removal of orphaned
                   completion records completed correctly.

                   It does not fix data lost for those who have already upgraded to Totara
                   11.18, 12.9 or Evergreen 20190822.
                   If you have upgraded to one of these versions please get in touch with our
                   help desk as soon as possible.


Release 12.9 (22nd August 2019):
================================


Security issues:

    TL-8385        Fixed users still having the ability to edit evidence despite lacking the capability

                   Previously when a user did not have the 'Edit one's own site-level
                   evidence' capability, they were still able to edit and delete their own
                   evidence.

                   With this patch, users without the capability are now prevented from
                   editing and deleting their own evidence.

    TL-21743       Prevented invalid email addresses in user upload

                   Prior to this fix validation of user emails uploaded by the site
                   administrator through the upload user administration tool was not
                   consistent with the rest of the platform. Email addresses were validated,
                   but if invalid they were not rejected or fixed, and the invalid email
                   address was saved for the user.

                   This fix ensures that user email address validation is consistent in all
                   parts of the code base.

    TL-21928       Ensured capabilities are checked when creating a course using single activity format

                   When creating a course using the single activity course format, permissions
                   weren't being checked to ensure the user was allowed to create an instance
                   of an activity. Permissions are now checked correctly and users can only
                   create single activity courses using activities they have permission to
                   create.

Performance improvements:

    TL-21841       Improved performance of filtering by organisation in Report builder

Improvements:

    TL-18671       Added Totara 13 environment requirements including new check for 32-bit systems

    TL-21437       Added button to allow manual downloading of site registration data

                   It is now possible to manually download an encrypted copy of site
                   registration data from the register page, in cases where a site cannot be
                   registered automatically.

    TL-21469       Improved the fade transition functionality in the gallery tile of the Featured links block

                   The fade transition in the gallery tile had a white flash that was quite
                   noticeable. The updates changed the background colour to grey (#666666)
                   from white (#FFFFF) to make it less noticeable.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-21565       Improved long category name tiles display in the Grid catalogue

                   Previously the category name length affected tile size. This has now been
                   fixed so that tiles for courses in any category are the same width.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-21708       Ensured a new resource_link_id is generated for users re-attempting LTI activity

                   Previously, when course completion was archived, LTI submissions were
                   reset, but a new resource_link_id was not generated. This ID is used by
                   external tool providers to ensure users can start a new attempt of the
                   activity. With this change, when completion is archived, historic LTI
                   submission records are stored, which allows the generation of a new
                   resource_link_id for each new attempt.

    TL-21772       Added setting to prevent automatic progression of dynamic appraisals with missing roles

                   A new setting 'Dynamic Appraisals Automatic Progression' was added, which
                   is on by default. When on, the previous behaviour is maintained, which
                   causes appraisals to automatically progress to the next stage if one or
                   more required roles are not filled (assuming at least one required role is
                   filled and all filled required roles have completed the stage). When
                   dynamic appraisals is enabled and the new setting is switched off, all
                   required roles need to complete the stage. Empty required roles will need
                   to have users assigned before the stage can be progressed.

Bug fixes:

    TL-8836        Ensured Program course set completion records are cleaned up after deleting a course set

                   Previously when deleting a course set from a program, any related program
                   completion records were not being removed, leading to orphaned records in
                   the prog_completion table. The associated prog_completion records are now
                   removed when a course set is deleted and existing orphaned records are
                   cleaned up by an upgrade.

    TL-20590       Fixed usability problem with group delete control on the quick access menu settings page

                   The ???X??? icon for deleting an entire menu group was easily misconstrued
                   as an icon to trigger closing of the expanded group accordion. The delete
                   function is now accessed via a text link after clicking a cog icon, which
                   reduces the likelihood of a user inadvertently deleting an entire menu
                   group.

    TL-20951       Ensured program completion records are cleaned up correctly after a program is deleted

                   Records in the tables prog_completion, prog_completion_history and
                   prog_completion_log were being orphaned when the related program was
                   deleted. These records are now removed when the program is deleted.

    TL-21234       Added totara_visibility_where for Audience Based Visibility to Upcoming Certifications block

                   Before this patch, when using Audience Based Visibility, the block would
                   display regardless of how the visibility is set.

                   The block now adheres to visibility either set via Audience Based
                   Visibility or via Show/Hide in the Certification settings.

    TL-21358       Fixed a permission error preventing a user from viewing their own goals in complex hierarchies

                   Prior to this fix if a user had two or more job assignments where they were
                   the manager of, and team member of, another user at the same time, they
                   would encounter a permissions error when they attempted to view their own
                   goals pages.
                   This has now been fixed, and users in this situation can view their own
                   goals.

    TL-21400       Ensured 'totara/plan:accessanyplan' and 'totara/plan:manageanyplan' capabilities work correctly

                   Previously, if a learning plan template permission was set to 'Deny' for a
                   manager, users with the 'totara/plan:accessanyplan' and
                   'totara/plan:manageanyplan' capabilities were also denied. This patch
                   ensures that these capabilities take precedence over how the learning plan
                   templates permissions have been set.

    TL-21425       Fixed seminar calendar events displaying a user booked message even after a user cancels their booking
    TL-21453       Ensure HTML entities display correctly in subject line of sent emails

                   The core_text::entities_to_utf8() function is now being used in the
                   email_to_user() function for the subject of the email.

    TL-21465       Prevented MSSQL Server from locking during some backup and restore operations
    TL-21508       Fixed bug causing ghost certifications to remain in Grid catalogue
    TL-21519       Fixed sort order on 'All appraisals' page

                   Prior to this patch, the 'All appraisals' page had an undefined sort order
                   for appraisals with multiple learners assigned when viewed by a manager.
                   This patch adds alphabetical sorting by learner's name, after the existing
                   sorting by status and appraisal start date.

    TL-21577       Fixed bug preventing seminar signup when a user has an inactive course enrolment
    TL-21581       Added 'debugstringids' configuration setting support to core_string_manager

                   Fixed issue when "Show origin of languages strings" in Development >
                   Debugging is enabled, in some rare cases, not all strings origins were
                   displayed.

    TL-21584       Ensured 'Assigned roles' menu is displayed in program administration to users with correct permissions

                   Previously, someone with a 'moodle/role:assign' capability assigned at the
                   program level had no link in the program administration to assign other
                   roles at that level. This option was displayed to site administrators
                   only.

                   This has been fixed and any user with the 'moodle/role:assign' capability
                   in a program can now assign other roles in the context of that program.

    TL-21585       Fixed a table name collision within the Grid catalogue when using two category filters

                   If the catalogue was configured to display both the category panel filter
                   and the category browse filter, and a user select a category in each, then
                   a fatal error would be encountered due to a table name collision as both
                   filters used the same table alias.

                   Each filter now has a unique table alias.

    TL-21615       Fixed the render_image_icon() function maintained for third-party plugin compatibility
    TL-21617       Fixed bug in completion editor caused by incomplete activity creation

                   Uploading a SCORM file via drag-and-drop on the course homepage creates a
                   record in the course_modules table, which is later updated with the ID of
                   the activity when created. However, an invalid file (or other failure)
                   could cause the activity creation process to abort, leaving a
                   course_modules record with no associated activity.

                   With this release, any orphaned SCORM course_modules records are cleaned
                   up, and the course module deletion code now properly deletes such records.

    TL-21621       Fixed the inconsistent display of information under the 'Answers tolerance parameters' section in the Calculated multichoice question type
    TL-21623       Fixed an issue where forum discussions RSS was incorrectly fetching deleted discussions instead of active ones
    TL-21630       Ensured value in the 'Is user assigned?' column takes exception resolution into account

                   If any user program or certification assignments generated exceptions which
                   have not been resolved, the "Program/Certification Completion" report will
                   display such users as not being currently assigned to the
                   program/certification.

    TL-21670       Fixed JavaScript error when all available blocks have been added to a page
    TL-21680       Fixed undefined adhoc task execution order

                   Previously, the execution order of adhoc tasks was arbitrary, which could
                   result in random PHPUnit failures. This has been fixed, the execution order
                   is now predictable.

    TL-21681       Fixed event context level checks when purging glossary entries
    TL-21683       Fixed the display of the Grid catalogue when viewing on a mobile screen with no filters applied

                   Previously 'show filters (-1)' was being  displayed on the Grid catalogue
                   when viewing on a mobile screen with no filters applied, now the 'show
                   filters' text is displayed as expected.

    TL-21684       Fixed seminar event roles not being deleted when associated user is deleted
    TL-21698       Fixed learners' ability to request learning items to be added to their learning plans based on the manager-driven workflow
    TL-21707       Fixed seminar 'Allow cancellations until specified period' setting

                   If the seminar 'Allow cancellations' setting was set to 'Until a specified
                   period', learners could still cancel their seminar signups at any time
                   until the start of the event. This has been fixed, and the setting now
                   works as expected.

    TL-21709       Fixed JavaScript initialisation from being incorrectly called twice for the Learning Plan block which resulted in an error
    TL-21727       Fixed missing image on course creation workflow page

                   This patch fixes an image that was missing on the course creation workflow
                   page when a content marketplace was enabled.

    TL-21775       URL validation and cleaning was updated to accept previously rejected URLs

                   Prior to this patch, URL validation code was rejecting some valid URLs,
                   such as the Grid Catalogue URL, with a query string including array
                   parameters.

                   With this patch the featured link block now supports URLs with a query
                   string that has parameter values as an array, such as those used in Grid
                   Catalogue URLs. The same applies to the quick links block that was
                   converted to use the new URL form field with the updated validation.

    TL-21779       Prevented users from signing up for a seminar outside of the designated sign-up period
    TL-21820       Removed an arbitrary limit on the number of course and program custom icons allowed
    TL-21821       Course completion caching was redesigned to be more reliable
    TL-21854       Fixed an issue where some Seminar attendees requiring manager approval could not be approved by their manager

                   When the 'Users Select Manager' setting is enabled for seminars, and a user
                   signing up for a seminar does not select a manager when requesting
                   approval, then a notice with an approval URL is sent to their immediate
                   manager(s).

                   Previously while managers who could approve any booking request would be
                   able to use the URL to approve the request, managers who did not have that
                   capability could not.

                   This has now been fixed.

    TL-21879       Fixed quiz navigation block where clicking on a question link did not scroll to the question on the page that required scrolling
    TL-21886       Fixed typos in the reportbuilder language strings

                   The following language strings were updated:
                   - reportbuilderjobassignmentfilter
                   - reportbuildertag_help
                   - occurredthisfinancialyear
                   - contentdesc_usertemp

Contributions:

    * Carlos Jurado at Kineo UK - TL-21615
    * Dustin Brisebois at Lambda Solutions - TL-21617
    * Jo Jones at Kineo UK - TL-21581
    * Michael Geering at Kineo UK - TL-21854


Release 12.8 (17th July 2019):
==============================


API changes:

    TL-21370       Method resetAfterTest() in PHPUnit tests has been deprecated

                   Since the introduction of parallel PHPUnit testing the order of test
                   execution is no longer defined, which means that tests cannot rely on state
                   (database and file system) to be carried over from one test into another.

                   Existing PHPUnit tests need to be updated to prepare data at the beginning
                   of each test method separately.

Performance improvements:

    TL-21541       The source filter for report builder sources has been optimised

                   Previously the options for this filter were loaded, even when not needed.
                   This was an expensive operation, often done needlessly. The options are now
                   only loaded when absolutely needed.

Improvements:

    TL-17691       Added site policies to the self-registration process

                   To comply with GDPR policies, when self-registration is enabled, new users
                   are now required to accept mandatory site policies before being able to
                   request a new account, as apposed to the users only viewing the site
                   policies after registering and logging in.

    TL-17745       Improved the program assignments user interface to better handle a large number of assignments

                   The previous user interface for program assignments would load every
                   assignment onto a single page, and in some situations where a very large
                   number of assignments were added to a single program or certification the
                   page would time out on load. The page now has a search, and filter, and
                   prevents too many records being loaded at the same time.

    TL-20760       Added support for search metadata within Courses, Programs, and Certifications.

                   New text field added to Courses, Programs, and Certifications settings
                   where search keywords can be added. These keywords will not be displayed
                   anywhere on pages but will be used in Full Text Search.

                   By default these fields are empty.

    TL-20761       Added wildcard support for full text search in catalog

                   When asterisk "*" is placed as a last character of a single keyword in
                   catalog it will return all partial matches starting with the given
                   keyword.  Asterisk can be placed only in the end of keyword search (this
                   is limitations of wildcard support in databases) and at this stage only
                   single keywords are supported (no whitespaces).

    TL-20834       Enabled unaccented Full Text Search in catalog

                   PostgreSQL and MS SQL have built in support for accent insensitive full
                   text searches.

                   By default, database configuration is used (typically accent sensitivity is
                   on).

                   To change accent sensitivity of full test searches for either PostgreSQL or
                   MS SQL you can set the
                   following options in config.php:
                   $CFG->dboptions['ftsaccentsensitivity'] = true; // Accent sensitive search
                   $CFG->dboptions['ftsaccentsensitivity'] = false; // Accent insensitive
                   search

                   After changing the accent sensitivity setting you need to run the following
                   scripts in the listed order:
                   php admin/cli/fts_rebuild_indexes.php
                   php admin/cli/fts_repopulate_tables.php

    TL-20886       Added ngram support for MySQL full text search

                   Added support of ngram in MySQL. ngram is a Full Text parser that mainly
                   designed to support Chinese, Japanese, and Korean (CJK) langauges. The
                   ngram parser tokenises a words into a contiguous sequence of n-characters.
                   More information about ngram can be found in MySQL documentation.

                   While it is designed more for CJK languages, it is also useful to parse
                   text on languages that use words concatenation, like German or Swedish.
                   However, it can produce large number of false-positive search results
                   (albeit with lower rating), so doing proper testing after enabling is
                   recommended.

                   This support is not enabled by default. To enable ngram support, add option
                   into your config.php:

                   $CFG->dboptions['ftsngram'] = true;

                   and run  FTS scripts to re-index content:

                   php admin/cli/fts_rebuild_indexes.php
                   php admin/cli/fts_repopulate_tables.php

    TL-21056       Added a warning about incompatible column selection in the report builder

                   In some cases, a combination of columns selected in a report source may
                   have caused unexpected results or a broken report. This usually happened
                   when a column that already relies on the aggregated data internally (e.g.
                   'Course Short Name' in the 'Program Overview' report) was combined with
                   columns aggregated via 'Aggregation or grouping' (e.g. count or comma
                   separated values).

                   Previously, using this type of combination on certain database types would
                   have resulted in an error. This change adds a warning to inform users about
                   the use of any incompatible columns at the time the report is being set up.

    TL-21247       Added configuration, a new CLI script and a scheduled task to execute the 'ANALYZE TABLE' query

                   The new 'analyze_table_task' scheduled task is configured to run every late
                   night. It is required that the task be configured to run at off-peak times on
                   your site.

    TL-21359       Fixed the Atto editor incorrectly applying formatting to previously selected text

                   Fixed an intermittent problem with the Atto editor when formatting was
                   applied to previously selected text instead of the currently selected text.
                   The 'mouse select' functionality works reliably now.

    TL-21426       New SCORM setting has been added that implements session timeout prevention in SCORM player

                   The new setting "Enable the SCORM player to keep the user session alive" is
                   available under the Admin settings in the SCORM plugin. It can be used in
                   order to prevent unwanted session timeouts during SCORM attempts.

                   Due to the fact that it keeps user session alive while SCORM attempt is in
                   progress, it may be considered a minor security concern and has been added
                   to the Security overview report as such.

Bug fixes:

    TL-18560       Fixed the 'Publish room for use in other sessions' checkbox in the edit custom room dialogue

                   When creating or editing a seminar event, it is possible to create a custom
                   room that can only be used by other events in the same seminar activity.
                   The editing form for these rooms can include a checkbox (if you have
                   sufficient permission) that allows them to be easily converted to sitewide
                   rooms.

                   This checkbox was always checked, and did not work as expected. This has
                   been fixed.

    TL-19138       Fixed warning message when deleting a report builder saved search

                   If a report builder saved search is deleted, any scheduled reports that use
                   that saved search are also deleted. The warning message to confirm the
                   deletion of the saved search now also correctly displays any scheduled
                   reports that will also be deleted.

    TL-19324       Fixed a bug within select tree where the drop-down would disappear when clicking the scrollbar

                   Improved the select tree component functionality. The scrollbar within
                   select tree components works reliably now.

    TL-20143       Fixed un-reversable block visiblity change when editing dashboard

                   When editing a dashboard it was possible to change the 'Administration'
                   block (or any other block) to only be visible on that dashboard. Once the
                   change was saved there was no way to change the block to display on 'Any
                   page' again. This patch allows the setting to be changed back.

    TL-20555       Removed Report Builder calls to a non-existent display function 'rb_display_nice_date()'

                   This is only an issue for any 'custom' created report sources that are
                   calling the 'rb_display_prog_date()' or 'rb_display_list_to_newline_date()'
                   display functions directly.

    TL-20960       Fixed the completion editor to schedule the recalculation of completion status if necessary

                   When saving activity completion status in the completion editor, the
                   reaggregate flag was set to schedule reaggregation of the associated course
                   completion record only if:
                    * completion criteria activity is modified in completion editor
                    * and the flag has not been set since the last cron run

                   Added a transaction log about 'reaggregation scheduled' if the conditions
                   above are met.

                   (If the reaggregate flag is set, then the next cron run will pick up the
                   corresponding course completion record, recalculate the completion status
                   and clear the flag.)

    TL-21055       Fixed the encoding of special HTML characters in tags

                   Prior to this patch, tag names were HTML-encoded before saving, with no
                   provision made to prevent re-encoding. This meant that whenever a course
                   (or program, or certification, or other tag-using component) was edited,
                   any attached tags would be re-encoded and saved as new tags.

                   This behaviour has been fixed. Upgrading to this release will fix any tags
                   that have been encoded multiple times, merging them with their original,
                   un-encoded selves as necessary.

    TL-21074       Fixed logging when restoring a backup including course completion history

                   Prior to the patch, when restoring the completion history, the restore step
                   would log the course completion instead of its history (which was not its
                   responsibility).

                   With this patch, the completion history restore step now logs the
                   completion history.

    TL-21257       Prevented background controls from being active when viewing program assignments
    TL-21261       Fixed the filtering of spaces in the 'Add a block' popover
    TL-21277       Fixed compatibility of Behat integration with ChromeDriver 75 and later
    TL-21293       Fixed an error with visibility checks in the fetch_and_start_tour() external function

                   Prior to this patch an error was generated when the external function
                   fetch_and_start_tour() was called and the tour should not be shown to the
                   user.

                   The check for whether the tour should be shown to the user or not is now
                   correctly handled by the JavaScript.

    TL-21295       Fixed bug where Grid catalogue course category updates ran interactively instead of as an adhoc task

                   The category update tasks can take a long time to complete when run
                   interactively on sites with many courses or programs.  The updates have
                   been moved to run as adhoc tasks instead.

    TL-21299       Fixed seminar direct enrolment Terms and Conditions link
    TL-21324       Fixed adding approvers to seminar

                   Prior to this patch, when a new approver was added to a seminar instance,
                   the previously added approvers (if any) were removed and replaced with the
                   new one.

                   With this patch, the previously added approvers (if any) will remain
                   without change.

    TL-21328       Fixed exception thrown when user is not assigned to a program in their Learning Plan
    TL-21361       Fixed deletion process for Seminar event custom room

                   If Seminar event has more then one sessions with the same date, different
                   hours and one custom room for these sessions, the system was unable to
                   delete the room if a user deletes the seminar event. The issue has been
                   fixed.

    TL-21384       Fixed export value of the 'Previous completions' column in the 'Record of Learning: Certifications' report source

                   HTML markup is no longer displayed in the export file for this column.

    TL-21398       Fixed bug causing the front page course to be listed in the Grid course catalogue

                   Previously, if the site summary on the front page course was edited, the
                   front page would appear as a learning item in the Grid course catalogue.
                   The front page course should never appear in the catalogue; this has now
                   been fixed.

    TL-21411       Default program and certification images are now overridable by theme
    TL-21413       Fixed the user 'full name link' report builder column to take admin role into account

                   Prior to this patch, the display function for the 'full name link' report
                   builder columns did not provide a URL for viewing profile at site level.
                   Even though, the actor was able to view the site level profile of another
                   user.

                   With this patch, a profile URL at site level will be produced, if the actor
                   is able to view the site profile of another user.

    TL-21419       Fixed rendering of password fields to ensure they are displayed as mandatory
    TL-21454       Fixed export value of the 'Name' column in the 'Organisations' and 'Positions' report sources

                   HTML markup is no longer displayed in the export file for these columns.

    TL-21460       Fixed Seminar previous events using time period and room filter

                   The previous seminar events with time period support Room filter.
                   Previously viewing a previous seminar events and adding a Room filter will
                   ignore the filter.

    TL-21464       Fixed custom validation of multi-select custom fields to prevent forms incorrectly failing validation

                   In some cases, validation of multi-select custom fields would try to apply
                   validation to fields that didn't exist in the current form. This caused the
                   form to fail validation without a warning, leading to unexpected behaviour
                   when submitting forms.

    TL-21467       Fixed an issue where the 'User tours' menu item could not be added to the administration drop-down menu
    TL-21468       Added support for completion records archiving in LTI activity module
    TL-21535       Removed display of invalid negative grades when scale grade is selected in the lesson module

                   When the grading scale is used in the lesson module, the value stored in
                   the grade column is the database ID of the scale. This was incorrectly
                   being used to calculate the grade and displayed to the users, when in fact
                   this grade should not have been calculated when using the scale grading
                   option.

    TL-21543       Ensured correct capability is checked when viewing 'Comments Monitoring' page

                   Previously, viewing 'Comments Monitoring' page in the administration menu
                   checked only the 'moodle/site:viewreports' capability, but accessing the
                   page required an additional 'moodle/comment:delete' capability. This led to
                   inconsistencies where users would see the page in their navigation, but
                   would get an error when trying to access it.

                   This behaviour has now been made consistent, and users with
                   'moodle/site:viewreports' capability can access and view the page without
                   needing to be able to delete the comments. Deleting comments still performs
                   the 'moodle/comment:delete' capability check.

    TL-21564       Fixed an issue with the parameters passed to the check_access_audience_visibility() function

                   This was not replicable within core code. But if a call to
                   check_access_audience_visibility() used an integer instead of an object,
                   the function would try to fetch the expected record from the database using
                   the integer as an id. That database call was incorrectly formatted
                   resulting in an error, this has been fixed.

Contributions:

    * John Phoon at Kineo Pacific  - TL-21564


Release 12.7 (19th June 2019):
==============================


Important:

    TL-21080       Prevented automatic completion of appraisal stages without any populated roles

                   Before this patch, completion of an appraisal stage could lead to automatic
                   completion of the following stage if that contained only unpopulated
                   appraisal roles.
                   With this patch automatic completion of subsequent stages only happens
                   when all populated roles have completed the stage and at least one role
                   (populated or not) has completed the stage.
                   This fixes a change in behaviour introduced in TL-19824.

                   This patch does not change affected appraisals on upgrade. For affected
                   appraisals, completed stages can be manually reset using the stage editing
                   tool in the appraisal administration's "assignments" tab.

Security issues:

    TL-21071       MDL-64708: Removed an open redirect within the audience upload form
    TL-21243       Added sesskey checks to prevent CSRF in several Learning Plan dialogs

Performance improvements:

    TL-20772       Optimised SQL base query to include userid in the rb_source_dp_course report source

                   To improve report performance, if userid is supplied to the report page of
                   the "Record of Learning: Courses" report source, it is now included in the
                   base SQL query.

                   Please note that the "Record of Learning: Courses" report source no longer
                   supports caching.

Improvements:

    TL-20512       Improved the accessibility of the seminar take attendance form

                   Attached a human-readable aria-label text to form elements.

    TL-20575       Added an event for Program and Certification user completion state change via the completion editor

                   An event will now log the old and new completion state when changed for a
                   user using the completion editor for a Program or Certification together
                   with the user who made the change

Bug fixes:

    TL-20034       Added a new scheduled task to purge orphaned course completion records

                   On large course datasets it was possible for a background cron job to start
                   running before an interactive course delete action had completed. This
                   could result in data integrity issues, e.g. the system having course
                   completion data for a course that no longer exists. A scheduled task has
                   been added to clean up any orphaned course completion data that might
                   exist, by default this task will run once a day at 1:54 am.

    TL-20533       Changed the seminar 'Allow Manager reservations' functionality to allow suspended users to be enrolled into seminar events
    TL-20716       Seminar session date time columns within report builder sources are now accurately described

                   Language strings used to describe the session start and finish date/time
                   columns within seminar report sources have been improved.

    TL-20885       Ensured email address validation within HR Import is used when the 'Allow duplicate emails' setting is enabled

                   Prior to this patch, if 'Allow duplicate emails' was set, email address
                   validation was inadvertently being ignored, making it possible for an
                   invalid email address to be set for imported users.

                   This patch ensures the email address is validated correctly, but cannot fix
                   any existing invalid email addresses. If you have been using this setting,
                   it is recommended to manually check any imported user email addresses.

    TL-20925       Fixed a PHP warning that was encountered when redirecting with a message before the session had been started
    TL-20927       Fixed the alignment of the name column within the grader report when the browser is zoomed
    TL-21054       Fixed alias name preventing seminar sessions report from correctly applying content filters

                   A bug has been fixed in the seminar sessions report builder source that was
                   causing a system error when trying to join content filters.

    TL-21069       Fixed duplicate 'Event under minimum bookings' notifications after mod_facetoface upgrade

                   The seminar notification for events that do not achieve a minimum number of
                   bookings was implemented in a way that caused it to be sent again (and
                   again) for past seminar events whenever mod_facetoface was upgraded.

                   The 'Event under minimum bookings' notification has been reimplemented as a
                   real seminar notification, with an editable template and the ability to
                   customise it at the activity level. This means outgoing instances of this
                   notification will be tracked to prevent duplicates.

                   Any seminar events that have not started yet, and that are eligible to
                   receive an 'Event under minimum bookings' notification, may receive one
                   final duplicate notification after upgrade to this release.

    TL-21090       The "Booked by" column within the seminar sign-in sheet report source no longer produces a fatal error
    TL-21096       Fixed incorrect classname checks in set_totara_menu_selected()
    TL-21099       The menu of choices custom field filter in report builder now correctly handles "Any value"
    TL-21175       Added the ability to fix out of order competency scale values

                   Previously when a competency scale was assigned to a framework, and users
                   had achieved values from that scale, it was not possible to correct any
                   ordering issues involving proficient values being below non-proficient
                   values.

                   Warnings are now shown when proficient values are out of order, and it is
                   possible to change the proficiency settings of these scales to correct this
                   situation.

    TL-21181       Fixed an HR Import Hierarchy circular reference sanity check timeout issue when assigning parents
    TL-21183       Fixed non-escaped characters being used in an SQL like statement during message provider upgrade

                   Prior to this patch, if a developer created a customisation that renamed or
                   deleted a message provider in a plugin, and the key of another message
                   provider in the same plugin began with the same key being removed, then,
                   during upgrade, the default message preference for the other message
                   provider was being deleted. This could have led to an exception when
                   messages based on the other message provider were being sent. Now, only the
                   correct record is being deleted.

    TL-21184       Fixed the display of the feedback activity long text answer text box
    TL-21189       Made the user 'full name link' report builder column take active enrolment into account

                   Prior to this patch, when a user was no longer enrolled in a course, but
                   the records were still stored within the course, report builder would
                   include the course ID in the user's full name link. Unfortunately, if the
                   link was clicked, a fatal error would be produced as the user was no longer
                   enrolled in the course.

                   With this patch, if the viewer is not able to view a user's profile within
                   the course, then there will be no link produced for that user's full name
                   in reports.

    TL-21208       Deleting report builder columns used by disabled graphs is no longer prevented

                   Before this change, if a column was used in a graph then, even if the graph
                   was later disabled, the column could not be deleted until it had been
                   removed from the graph. This resulted in having to re-activate the graph
                   just to remove the column from the data source field.

                   This change has updated the check to determine whether the affected graph
                   is enabled, only preventing deletion of the column when it is.

    TL-21223       The audience name report builder column no longer outputs HTML when exporting to another format

                   Previously the audience name column would always export an HTML link, even
                   when exporting to CSV or Excel.
                   This has been fixed so that the HTML link is only output when producing the
                   report for the web.

    TL-21238       Added validation of seminar signup state classes to ensure that only valid classes are used

                   Seminar signup state transitions rely on the correct PHP classes being
                   loaded at runtime. A validation routine has been added to ensure that unit
                   tests will fail, and developers will receive debugging messages, if a
                   non-existent state class is used in seminar code.

    TL-21239       Fixed a bug within Atto editor where text alignment could not be changed within IE11 or Edge

                   Previously the alignment of text within the Atto editor would fail to
                   change alignment in IE11 or Edge, if the text had already been aligned by
                   another user in a different browser (such as Firefox or Chrome).
                   This has now been fixed so that IE11 and Edge users can change the
                   alignment of text previously aligned in Firefox or Chrome.

    TL-21242       Fixed a bug preventing the modification of job assignments if the assignment name contained a space
    TL-21258       The course progress block now creates the embedded report it requires if it does not already exist

Contributions:

    * Ayman Al Kurdi at iLearn - TL-20772
    * Georgi Dimitrov at LearnChamp - TL-21090
    * Russell England at Kineo - TL-21183


Release 12.6 (22nd May 2019):
=============================


Security issues:

    TL-20730       Course grouping descriptions are now consistently cleaned

                   Prior to this fix grouping descriptions for the most part were consistently
                   cleaned.
                   There was however one use of the description field that was not cleaned in
                   the same way as all other uses.
                   This fix was to make that one use consistent with all other uses.

    TL-20803       Improved the sanitisation of user ID number field for display in various places

                   The user ID number field is treated as raw, unfiltered text, which means
                   that HTML tags are not removed when a user's profile is saved. While it is
                   desirable to treat it that way, for compatibility with systems that might
                   allow HTML entities to be part of user IDs, it is extremely important to
                   properly sanitise ID numbers whenever they are used in output.

                   This patch explicitly sanitises user ID numbers in all places where they
                   are known to be displayed.

                   Even with this patch, admins are strongly encouraged to set the 'Show user
                   identity' setting so that the display of ID number is disabled.

    TL-20822       Applied fix to prevent prototype pollution vulnerability via jQuery

                   Code within jQuery was recently found to be vulnerable to a JavaScript
                   exploit known as prototype pollution if good practices are not adhered to
                   around sanitisation of user input. Totara was not found to be vulnerable to
                   this type of exploit via jQuery. However, a fix has been applied to the
                   version of jQuery we currently use out of caution, and as a safeguard for
                   future changes.

Performance improvements:

    TL-20858       Improved record of learning performance by adding an index to the 'course_completions' table

New features:

    TL-20583       Cherry-pick OAuth2 from Moodle

                   Implementation of OAuth2 user authentication for identity providers such as
                   Facebook, Google and Microsoft.

                   Note: Please ensure that the "Allow accounts with same email" setting is
                   disabled when OAuth2 authentication is enabled.

Improvements:

    TL-20508       Added a new database option to configure maximum number of IN-clause parameters in SQL queries

                   Previously the maximum number of parameters was always set to 30 000. With
                   this change, it is now possible to override this number via the
                   'maxinparams' dboptions setting in config.php.

    TL-20511       Added aria-label lookup to Behat field label selector

                   Previously, when looking for form field inputs, Behat was only able to look
                   for matching <label> elements. This meant that form fields without a
                   <label> were difficult to select.

                   Behat is now able to check the aria-label attributes of form fields to see
                   if the text matches the requested label. So for example, a step like 'And I
                   set the field "export" to "csv"' will find the first field with either a
                   <label> element or an aria-label attribute that matches 'export', and set
                   it to 'csv'.

                   This means that labels that were only visible to screen readers are
                   replaceable using <input aria-label="label name"> without any changes to
                   behat steps. In addition, steps matching form fields with CSS or XPath
                   could be changed to be more readable, and more robust, provided the form
                   field is uniquely identifiable by aria-label text.

                   This patch could break existing Behat tests. In cases where an input with a
                   matching aria-label attribute appears before a second input with a matching
                   <label> element, the first field will now be matched, whereas before it
                   would have been ignored.

    TL-20872       Clarified explanatory text for the 'Update all activities' setting in seminar notification templates

Bug fixes:

    TL-20429       Requests for theme images by Google Image Proxy no longer return SVGs

                   It came to our attention that the Google Image Proxy system used by the
                   likes of Gmail does not support SVG.

                   When serving theme images now, we check if the request is coming from the
                   Google Image Proxy system and return an appropriate version of the image if
                   it is.

    TL-20489       Fixed occasional delay between enrolment via seminar sign-up and learner appearing in the grader report

                   When a learner was enrolled in a course by signing up or being manually
                   added to a seminar, the user sometimes could not immediately see the
                   course, and was not visible in the grader report for the first 50 seconds.

                   This delay has been fixed. Learners enrolled in a course via seminar will
                   be immediately visible in the grader report, and able to see the course.

    TL-20519       Made sure grade override is taken into account when calculating SCORM activity completion

                   Previously, SCORM activity completion relied only on the package tracking
                   data to calculate learner's activity progress. In cases where grades were
                   manually overridden they were not taken into account and the activity would
                   still appear as incomplete. This has now been fixed, and manually added
                   grades are included into the SCORM completion progress calculations where
                   they are required for completing the activity.

    TL-20682       Ensured new random questions are created when duplicating quiz activity

                   Previously when a quiz was duplicated via activity/course backup and
                   restore process, random questions in the new quiz were still linked to the
                   random questions in the original quiz. This has now been fixed and the new
                   random questions are created during activity duplication.

    TL-20721       Fixed the grader report not taking hidden access restrictions into account

                   Previously if an activity had an access restriction using 'Member of
                   Audience', and the restriction was set to 'hide entirely' rather than
                   'display greyed out', the activity was not visible on the grader report
                   even if the viewer was part of the audience.

                   The activity will now be correctly displayed on the grader report as long
                   as the restriction is met.

    TL-20767       Removed duplicate settings and unused headings from course default settings
    TL-20787       Fixed grid catalogue to display the tag name in the same case as the value entered by the user

                   Prior to this patch, when tags were configured to be displayed in the grid
                   catalogue, the tag name was displayed in all lowercase.

                   With this patch, the tag name will be displayed in the same case as the
                   value entered by the user.

    TL-20788       Fixed bug causing grid catalogue to display incorrect information for the certification ID number
    TL-20792       Fixed goal user assignment 'timemodified' and 'usermodified' fields not being updated

                   When a user re-met the criteria for a company goal, the 'timemodified' and
                   'usermodified' fields were not being updated. This has been corrected.

    TL-20805       Fixed course's custom fields to have a unique name for each static element

                   Prior to this patch, when a course had custom fields with the description
                   that was not unique for a static element in the form, then the form would
                   display a debugging message to notify developers that the name of static
                   element was missing.

                   With this patch, each static element now has a unique name associated with
                   it.

    TL-20813       Fixed a bug that displayed the Totara favicon instead of the theme's favicon on new SCORM windows
    TL-20832       Fixed a missing require statement in the unit tests for assignment module reports
    TL-20860       Fixed bug preventing course gallery tile visibility being set by audience rule
    TL-20912       Fixed parsing of program availability date

                   Previously, programs were created with the 'Available until' value set to
                   the beginning of the day (00:00:00), while subsequent editing of a program
                   set the date to the end of the day (23:59:59). This has now been fixed and
                   the dates during program creation and program editing are always set to the
                   end of the selected date (23:59:59).

    TL-20936       Fixed multi-language filtering for course/program/certification tile in the 'Featured links' block

                   Prior to this patch, the multi-language filter was not being applied for
                   the learning tile's heading.

                   With this patch, the multi-language filter is applied.

    TL-20956       Fixed user tours being incorrectly aligned when a using a backdrop
    TL-20966       Fixed an exception error created by seminar 'Message users' when a message failed to send

API changes:

    TL-20825       Fixed a typo in seminar function name introduced during refactoring

                   Function name 'seminar_event_list::form_seminar()' has been renamed
                   'seminar_event_list::from_seminar()'.

Contributions:

    * Krzysztof Kozubek at Webanywhere - TL-20860
    * Marek Han????ek at e-Learnmedia - TL-20966


Release 12.5 (29th April 2019):
===============================


Security issues:

    TL-20532       Fixed a file path serialisation issue in TCPDF library

                   Prior to this fix an attacker could trigger a deserialisation of arbitrary
                   data by targeting the phar:// stream wrapped in PHP.
                   In Totara 11, 12 and above The TCPDF library  has been upgraded to version
                   6.2.26.
                   In all older versions the fix from the TCPDF library for this issue has
                   been cherry-picked into Totara.

    TL-20607       Improved HTML sanitisation of Bootstrap tool-tips and popovers

                   An XSS vulnerability was recently identified and fix in the Bootstrap 3
                   library that we use.
                   The vulnerability arose from a lack of sanitisation on attribute values for
                   the popover component.
                   The fix developed by Bootstrap has now been cherry-picked into all affected
                   branches.

    TL-20614       Removed session key from page URL on seminar attendance and cancellation note editing screens
    TL-20615       Fixed external database credentials being passed as URL parameters in HR Import

                   When using the HR Import database sync, the external DB credentials were
                   passed to the server via query parameters in the URL. This meant that these
                   values could be unintentionally preserved in a user's browser history, or
                   network logs.

                   This doesn't pose any risk of compromise to the Totara database, but does
                   leave external databases vulnerable, and any other services that share its
                   credentials.

                   If you have used HR Import's external database import, it is recommended
                   that you update the external database credentials, as well as clear browser
                   histories and remove any network logs that might have captured the
                   parameters.

    TL-20622       Totara form editor now consistently cleans content before loading it into the editor

Improvements:

    TL-20147       Improved the help text in programs and certifications by specifying that course scores have to be whole numerical values.
    TL-20360       Improved the enrolment type filter for course completion reports

                   Previously the enrolment type filter was a text search against a database
                   value stored for enrolments, this was particularly a problem for audience
                   enrolments since the database value was 'cohort' even though it was
                   displayed as 'Audience Sync'. While the filter worked if you searched on
                   'cohort', this wasn't immediately obvious. This filter has been updated to
                   a multiple-select interface which has options for each enabled enrolment
                   plugin. To maintain all available functionality the multi-select interface
                   for filters has also had its operators updated from "Any/All" to include
                   "Not Any/Not All".

    TL-20402       Decoupled profile editing from administration menu editing

                   Users no longer require 'moodle/user:editownprofile' capability to be able
                   to edit their own administration menu preferences.
                   In order to edit their administration menu preferences they need just the
                   'totara/core:editownquickaccessmenu' capability.

    TL-20407       Added a Basis theme setting to override the colour of submit buttons

                   A new 'Primary button color' setting provides a way to override the
                   background colour of submit buttons in the Basis theme. The appearance of
                   other types of buttons is still controlled by the 'Button color' setting.

                   The 'Preview' buttons on the Basis theme settings form did not work as
                   intended and have been removed. Theme designers are encouraged to use the
                   Element Library to view the effects of theme colour changes immediately
                   after update.

    TL-20516       Changed ambiguous wording for confirmation button in the appraisal unlock stage page

                   In the appraisal unlock stage page, the confirmation button had potentially
                   confusing text. It was not clear that clicking 'Save changes' without
                   making any changes on the form would still have some effect. This patch
                   changes the wording to 'Apply' instead.

                   Also, the unlock stage interface on the Appraisal Assignments page has been
                   improved.

    TL-20517       Improved compatibility with Solr 7
    TL-20537       Added an event for enabling and disabling authentication methods

                   Prior to this patch, when an admin enabled or disabled an authentication
                   method, there was no event triggered. This patch adds an event there for
                   auditing purposes.

    TL-20538       Added enable/disable course end date to course defaults

                   Added a new setting in the course defaults page to enable/disable the
                   course end date by default when creating a new course.

    TL-20554       Improved navigation to user profile page after adding or updating a user

                   Changes have been made to user administration in order to streamline adding
                   and updating users. Prior to this patch, administrators were redirected to
                   the list of users after adding a user, and to the previous screen when
                   editing a user profile. These are not always desired behaviours.

                   A 'Create and view' button has been added to the 'Add user' forms, in order
                   to give administrators the ability to navigate to the new user's profile
                   after creating it. Likewise, an 'Update and view' button has been added to
                   the 'Edit user profile' form in cases where the the default behaviour would
                   be to redirect the administrator to the list of users or elsewhere.

    TL-20610       Added event triggers for changing site administration group

                   Prior to this patch, when an admin assigned users to or unassigned users
                   from the site administration group, then there was no event to be
                   triggered, and consequently, the system was not able to log the event.

                   This patch introduces a new event triggered by changes to the site
                   administration group, allowing the system to be able to log the event.

    TL-20674       Added a 'scheduled task updated' event to log changes to scheduled tasks
    TL-20695       Added timezone option to the appraisal and feedback 360 date question type

                   The option 'Include timezone as well as time' was added when adding a date
                   picker question to an appraisal or feedback 360. When enabled, the date
                   question will include a timezone selector, defaulting to the user's current
                   time zone. When the appraisal or feedback 360 is saved, other users will
                   see the answer to the date question in the timezone that the user selected,
                   rather their own time zone.

    TL-20705       Improved validation for checkbox audience rules

                   As part of server-side validation of audience rule forms, this now checks
                   that a value has been submitted and that it is either 0 (not checked) or 1
                   (checked).

    TL-20707       Converted seminar wait-list tab to an embedded report
    TL-20710       Feedback activity UI for editing questions now reflects actual question and page break order

                   Previously, when dragging an item and dropping it outside of appropriate
                   drop zone, the UI would change however the database was not updated to
                   reflect the change. Now when the item is dropped outside of the
                   appropriate drop zone, the item will snap back to the point of origin.

Bug fixes:

    TL-13902       Updated the title for the seminar event 'more info' page for attendees

                   Previously the header title text used on the 'more info' page for a seminar
                   event said 'Sign up for [seminar name]' even if a user was already signed
                   up.

                   This has been fixed to show just the seminar name if the user is an
                   attendee.

    TL-14355       Fixed validation for menu type audience rules

                   Previously audience rules using the menu interface were lacking validation
                   on empty submissions, so if you attempted to save without selecting a value
                   there would be an exception thrown, a broken rule would be added, and you
                   would be redirected away from the page, which meant that you would have to
                   navigate back and remove the rule. Now the form submission is halted and a
                   warning is shown to enter a value.

                   Affected audience rules are:
                    * position type
                    * position menu customfields
                    * organisation type
                    * organisation menu customfields
                    * user menu customfields

    TL-19820       Fixed bugs in quiz 'Review options' marks settings

                   A quiz can be set to hide marks (grade) from learners at various times,
                   using the 'Review options' checkboxes in quiz settings. For example, a quiz
                   can withhold a learner's grade until the quiz has closed.

                   Prior to this patch, the 'Review options' marks setting also affected the
                   recording of activity completion. If marks were hidden from the learner,
                   then activity completion was recorded as 'Complete' when all conditions
                   were met, rather than as 'Complete with pass' or 'Complete with fail'.
                   Activity completion was not updated later if the marks became visible to
                   the learner, and was not consistent with the way grades are recorded:
                   grades are always visible to a trainer, whether learners can see them or
                   not.

                   With this patch, quizzes (or any other activities with grade items hidden
                   from learners) are always marked as 'Complete with pass' or 'Complete with
                   fail' if a grade is required for completion. When learners view the course
                   homepage, activity completion tick marks are modified to hide pass/fail
                   status if the grade is hidden. Trainers will always see the true status.

                   This patch also ensures that grade items are correctly show/hidden
                   according to a quiz's 'Review options' marks settings, with the exception
                   that grades that have already been revealed are not hidden later.

    TL-20148       Fixed a web services error that occurred when the current language resolved to a language that was not installed
    TL-20149       Fixed secondary navbar not showing when browsing third level child page
    TL-20258       Fixed incorrectly appended context links when sending alerts

                   Prior to this patch messages sent as alerts could, in some cases, have
                   superfluous text appended related to context links.

    TL-20448       Fixed a display issue with conditional access when audience, position, or organisation restrictions were in use

                   Prior to this fix in situations where a restriction set contained an
                   audience, position or organisation restriction the controls for
                   manipulating the restriction set would be hidden, making it impossible to
                   edit the restriction set.

    TL-20466       The approveanyrequest capability is now correctly checked when processing a seminar approval request

                   Users who hold the 'mod/facetoface:approveanyrequest' capability previously
                   would encounter an error when attempting to approve a signup request in a
                   context where they held the capability but did not meet any other required
                   conditions.
                   This has been fixed to ensure that the capability is correctly checked when
                   processing a users approval request.

    TL-20468       The grade overview report now correctly respects audience based visibility
    TL-20475       Fixed seminar grades not being correctly updated when the override flag is removed on a gradebook

                   The third argument of facetoface_update_grades() was changed as follows.
                   In previous releases, the system set NULL as grade if true is passed.
                   From now on, the system sets a default grade if true is passed.
                   The default grade is calculated by using grading method in T13, and the
                   last saved attendance state in T12.

    TL-20482       Fixed 'View dates' link on program/certification assignment page

                   TL-19190 introduced a regression where clicking on the 'View dates' link
                   against a group assignment on the assignments page would display a pop-up
                   with all the users assigned to the program. This has now been fixed and
                   only users from the specific assigned group are displayed.

    TL-20488       Added batch processing of users when being unassigned from or reassigned to a program
    TL-20500       Fixed a bug where a manual data purge of certification assignments and completion did not purge deleted users' records
    TL-20504       Made sure that learning plan access is being checked before sending out comment notifications

                   Previously, any user that interacted with a learning plan by leaving a
                   comment would continue to receive notifications about other users' comments
                   to the plan, even if the user no longer had access to the plan. Now only
                   plan owners, active managers, and users with the
                   'totara/plan:accessanyplan' and 'totara/plan:manageanyplan' capabilities
                   receive notifications about new comments.

    TL-20515       Fixed bug that could leave a job assignment linked to seminar signup records after the job assignment was deleted
    TL-20522       Fixed IE11 visual bugs and broken buttons when editing the administration menu
    TL-20523       Fixed the display of site logs for legacy seminar status codes
    TL-20526       Check course setting and 'grade:view' capability in course details

                   Previously the report-based course catalogue displayed grades for all
                   completed courses without taking into account the "Show gradebook to
                   learners" course setting or the 'moodle/grade:view' capability of a report
                   viewer. This has now been fixed.

    TL-20534       Fixed a bug preventing grid catalogue filters from properly recognising unicode characters

                   Previously grid catalogue filters were unable to identify courses to list
                   when a course custom multi-select field contained options with unicode
                   characters, e.g. Mat??j, Dvo????k. This patch fixes the search
                   functionality so that options with unicode characters can be correctly
                   identified.

    TL-20535       Included helptooltip as a dialog-nobind class condition in totara_dialog.js
    TL-20568       Fixed misleading 'not answered' text for appraisal questions

                   TL-20052 was supposed to fix this; however that patch was found to address
                   the case when only the learner needed to answer questions. The bug still
                   occurred if the appraisal had a mix of questions and permissions that other
                   roles need to answer.

                   This patch fixes the latter problem.

    TL-20586       Fixed event generation when deleting hierarchy items

                   Prior to the patch the same event was generated for all descendant
                   hierarchy items when deleting an item with children.

                   As a side effect this patch fixes course activity access restrictions based
                   on a position or organisation. Prior to the patch if a child position or
                   organisation was used to restrict access to a course activity and then its
                   parent was deleted, the restriction setup menu for this activity was
                   broken.

    TL-20592       Removed block display when restoring an activity backup

                   Blocks are not displayed while restoring a course backup, because users are
                   expected to move though the restore workflow using the navigation buttons
                   at the bottom of the screen, and because the 'Add a block' feature doesn't
                   work during restore.

                   Because of a bug, blocks had been displayed while restoring an activity
                   backup. This has been fixed, and no blocks should display during any type
                   of multiple-step restore.

                   A renderer bug that resulted in an unclosed <div> tag on the second screen
                   of the restore process has also been fixed.

    TL-20598       Fixed the available actions on seminar attendees pages so they respect the 'mod/facetoface:addattendees' capability

                   Prior to this patch, both the 'add' and 'remove' attendees options were
                   shown in the drop-down menu on the seminar event attendees pages, even if a
                   user only had the 'mod/facetoface:removeattendees' capability.

                   The 'add attendees' option will now only be displayed for users with
                   'mod/facetoface:addattendees' capability.

    TL-20609       Fixed an issue in the main menu where a certain combination of preset rules caused an infinite loop
    TL-20634       Improved security and transparency of seminar 'Message users' feature

                   In previous versions, any user who had the seminar 'Take attendance'
                   capability could use the 'Message users' form to see attendee email
                   addresses and send messages to one or more attendees.

                   'Message users' has been changed to require three permissions in the
                   context of the seminar activity: 'Send messages to any user'
                   (moodle/site:sendmessage), 'Send a message to many people'
                   (moodle/course:bulkmessaging) and 'View attendance list and attendees'
                   (mod/facetoface:viewattendees). These permissions continue to be enabled by
                   default for trainers and editing trainers.

                   Also, when a user views the 'Message users' form, a 'Messages users viewed'
                   event is logged. When the form is used to send messages, a 'Message sent'
                   event is logged.

    TL-20635       Fixed the destination for the 'room name link' column in seminar reports

                   Recent improvements to seminars changed the destination of the links to the
                   rooms edit page, which can only be accessed by certain roles. The link now
                   directs users to a less-restricted 'view details' page again.

    TL-20637       Fixed 'Bulk add attendees' form when signup capability is disabled for learner role

                   When the learner role had the 'Sign-up for an event' capability disabled,
                   it was not possible for an administrator to add a learner to a seminar
                   event. The system now checks the permissions of the person who is
                   performing the action, rather than the permissions of the person being
                   signed up.

    TL-20638       Ensured that quiz question ids are unique when they are rendered on the page

                   Previously, when a quiz question was displayed, the outer div of the
                   question had an id="q123" added. Unfortunately, this id was not unique in
                   all cases which lead to the issues in manual grading where multiple
                   responses for the same question were displayed. This has now been fixed.

    TL-20643       Ensured HR Import checks for unique user profile fields are not performed on empty or null values

                   User custom fields that are set as being unique where the source value is
                   an empty string or null are no longer included in the checks to ensure
                   uniqueness.

                   Previously where multiple records contained empty strings where uniqueness
                   was being enforced, the entire user record was failing and not imported.

    TL-20661       Fixed sending of activation emails for all of manager's appraisals

                   Previously upon appraisal activation, a manager would only receive one
                   email, regardless of how many appraisees they had. This was true even if
                   the activation notification content explicitly included appraisee details,
                   e.g. appraisee full name.

                   This patch fixes this; now the manager gets emails for individual
                   appraisees. However, if the message is a generic one (i.e. one that did not
                   have placeholders to differentiate emails to different people), then they
                   will still only get one email.

                   Note: the one generic email per manager only happens if all the appraisees
                   automatically get a job assignments upon appraisal activation (i.e.
                   multiple job assignments is off). If the appraisee still has to view the
                   appraisal to indicate the job assignment, then the manager will receive
                   multiple generic emails each time their appraisee first views an appraisal.

    TL-20668       Primary admin and web service users are no longer required to provide their required profile fields information
    TL-20670       Fixed infinite recursion when generating API documentation
    TL-20681       Made sure course completion value in the Record of Learning report export doesn't contain HTML
    TL-20683       Fixed totara core upgrade to avoid using the system API

                   Prior to this patch, the upgrade path for evergreen was using system API,
                   which was involving the user session to perform actions. Therefore, it
                   failed to upgrade to evergreen from CLI.

                   With this patch, it is possible to upgrade to evergreen with CLI.

    TL-20689       Fixed the display of submission grade and status in the "Assignment submission" report
    TL-20700       Fixed misleading count of users with role

                   A user can be assigned the same role from different contexts. The Users
                   With Role count was incorrectly double-counting such instances leading to
                   inaccurate totals being displayed. With this fix the system counts only the
                   distinct users per role, not the number of assignments per role.

    TL-20703       Fixed incorrect offset when creating a user tour targeting the main navigation
    TL-20712       Fixed feedback preview with a "pagebreak" item at the top on the page
    TL-20720       Fixed issue with grades been saved as 0.0000 on seminar table

                   Since Totara 12.0, seminar grades have been saved as 0.0000 in the
                   facetoface_signups_status table, regardless of attendance state.

                   Gradebook grades were not affected by this bug.

                   Previous versions correctly set the grade field to null until attendance
                   was taken, and then set it to a grade based on attendance. This patch fixes
                   the regression. In summary:
                    * The correct grade value will always be saved into
                      facetoface_signups_status table, regardless of seminar grade settings
                    * If attendance state is 'Not set' when taking attendance, the grade field
                      will be set to null
                    * Incorrect facetoface_signups_status grade values will be rewritten with
                      a correct value, based on attendance state, during this upgrade
                    * If the system detects backup data made with any affected version during
                      course or activity restore, the correct grade will be used instead of the
                      backed-up grade

    TL-20727       Ensure email notifications work correctly in HR Import after upgrade

                   Upgrading to Totara 12 or 13 from Totara 11 or earlier may have stopped
                   email notification from being sent in HR Import. This change ensures that
                   they are sent correctly.

    TL-20747       Restored 'Update all activities' functionality for custom seminar notification templates
    TL-20751       Fixed 'fullname' column option in user columns to return NULL when empty

                   Previously the column returned a space character when no value was
                   available which prevented users from applying "is empty" filter

    TL-20764       Added horizontal scroll bar to user multiselect

                   This will not work in IE11 or Firefox (Due to
                   https://bugzilla.mozilla.org/show_bug.cgi?id=1294313).

    TL-20773       Fixed unit test failure for third-party activity plugins that do not support Totara generators
    TL-20779       Removed redundant database update call in Learning Plan Evidence
    TL-20794       Added missing format value on Seminar 'Download sign-in sheet' hidden field

API changes:

    TL-20572       Improved in-code documentation for the recommends_counted_recordset() method

                   Previously the documentation contained a link to our internal tracked.
                   This has been removed as it is not accessible to those outside of the
                   Totara development team.
                   Additionally performance testing results have been directly added to the
                   base method as defined in the moodle_database class.

Miscellaneous Moodle fixes:

    TL-20467       MDL-57486: Delete items when context already deleted
    TL-15552       MDL-57769: Remove 'numsections' from topics and weeks, allow teachers to create and delete sections as they are needed

                   This patch does not remove the 'numsections' setting from the topics and
                   weeks course formats, but it does make it optional for other course
                   formats. It also implements section management methods expected by
                   third-party course format plugins.

    TL-20563       MDL-61950: Fixed display of random questions in the statistics calculator in the quiz module

                   Prior to this patch, if a quiz had random questions in it, then viewing the
                   statistics report would sometimes have questions missing from the report.

Contributions:

    * Haitham Gasim - Kineo USA - TL-20794
    * Kineo UK - TL-20751
    * Think Learning - TL-20764


Release 12.4 (22nd March 2019):
===============================


Security issues:

    TL-20498       MDL-64651: Prevented links in comments from including the referring URL when followed
    TL-20518       Changed the Secure page layout to use layout/secure.php

                   Previously the secure page layout was using the standard layout PHP file in
                   both Roots and Basis themes and unless otherwise specified, in child
                   themes.

API changes:

    TL-19859       Added experimental support for paratest to run PHPUnit tests in parallel

Performance improvements:

    TL-19933       Improved Report Builder counting performance

                   Each database engine now provides a recommendation on whether counted
                   recordsets should be used.

                   A new plugin setting 'Default result fetch method' has been added for those
                   wanting to control the choice manually rather than rely on the database
                   recommendation.

    TL-20212       Improved the performance of Report Builder access checks

Improvements:

    TL-20106       Improved the handling of invalid UTF-8 strings in block names

                   Fixed javascript failure when one or more block names are translated using
                   invalid UTF-8 sequences.

    TL-20252       Added seminar global setting ???Previous events time period??? to restrict number of events listed on the events dashboard

                   The seminar activity page could take a long time to load when there were a
                   high number of events in the activity. A new global setting for seminars
                   ??? ???Previous events time period??? ??? was added which determines the
                   maximum age of events that can be listed on the dashboard, restricting
                   those shown to include only the most recent ones, in order to improve page
                   load time.

    TL-20306       Added a 'Link to approval requests' column to the Seminar Sign-ups report source
    TL-20358       Added the ability to unlock all roles in an appraisal at once

                   Before this change, when an appraisal was unlocked for a specific role in a
                   user's appraisal, all roles could make changes to their answers at the
                   given stage (within the normal appraisal rules), but only the unlocked role
                   was required to mark each stage complete again. With this change, a new
                   option 'All roles' is available, and when selected every role will be
                   required to mark each unlocked stage complete again.

    TL-20390       Improved the clean up of records from the 'prog_user_assignment' table
    TL-20410       MDL-57878: Added expected completion date function
    TL-20428       Updated dompdf to version 0.8.3

Bug fixes:

    TL-19369       Fixed the display of images and videos in the summary of course catalogue items
    TL-19840       Fixed divide by zero errors in report builder grade columns

                   If you uploaded or manually set grades for users, but didn't set up the
                   grades for the associated course, the grade percentage columns in report
                   builder would attempt to divide by zero. The report builder now displays a
                   '-' instead.

    TL-19934       Removed duplicate records from the attendees list for seminar events with multiple sessions

                   Prior to this patch, when a seminar event had more than one session date,
                   then the attendees list of the event would duplicate the attendee records
                   based on the number of session dates of an event.

                   With this patch, the attendees list of seminar event with multiple session
                   dates will not duplicate the attendees record based on the number of
                   session dates, unless the admin adds columns that are related to sessions
                   specifically.

    TL-19962       Made the Auto-fill form element always show the result of the most recent search term

                   Previously there was a chance that the result of a previous search term
                   would override the results of a newer search term when using a Moodle form
                   auto-fill element. This change ensures that more recent results are shown.

    TL-19963       Stopped seminar booking confirmation notifications being sent to managers when unchecked.

                   Seminar session signup notification emails were incorrectly being sent to
                   manager when "Send booking confirmation to new attendees managers" was not
                   selected on the seminar session sign-up confirmation page. The behaviour
                   has been corrected to not send the manager copy of confirmation unless
                   specifically requested to do so.

    TL-19966       Added sanity checks to the course duration setting

                   Previously setting the default course duration to 0 did not disable the
                   course end date, but instead the system had an undocumented implementation
                   where '0' was treated as '365 days'. This change has added validation to
                   the field to prevent zero to prevent the issue, as a result the minimum
                   acceptable default course duration is now at least 1 hour.

    TL-20033       Fixed the SQL pattern for word matching regular expressions in MySQL 8
    TL-20045       Improved the wording of the cohort-type filters in course/program/certification reports

    TL-20052       Fixed misleading 'not answered' text for appraisal questions

                   With the 'view answer' permission, a manager is able to see a learner's
                   appraisal answers even if he does not need to fill in the appraisal
                   himself.

                   Previously however, not only would he see the learner's answers. he would
                   also see "Not yet answered" for each question he didn't answer. This is
                   misleading because it implied the manager needed to answer questions even
                   though this was not the case.

                   This patch removes that "Not yet answered" text.

    TL-20108       Fixed the removal of users who "declared interest" in a seminar event when the event gets deleted
    TL-20118       Fixed the prevention of Site Manager from managing Site Policies
    TL-20127       Changed the grpconcat_date Report Builder filter to use 'AND' operator when both a before and after date has been set

                   Before this patch an 'OR' operator was being used that gave inconsistent
                   results

    TL-20131       Fixed an error when hierarchy frameworks had more than one user entering data concurrently
    TL-20139       Added unique identifiers to each navigation item so they can be targeted by user tours
    TL-20151       Fixed the display of email addresses with non-standard characters in reports
    TL-20153       Fixed Javascript error when a block has no heading
    TL-20159       Browser local storage is now cleared after upgrade/cache purge
    TL-20160       Added audience-based visibility check for access to a course when user attempts to sign up to a seminar via direct sign-up link

                   Users who should have been prevented from enrolling (via audience-based
                   visibility) in a course were still able to sign up to a seminar session in
                   that course when accessing the sign-up link directly. They are now
                   prevented from doing so.

    TL-20210       The seminar 'allow cancellations' setting no longer takes precedence over the remove attendees capability

                   This change restores previous behaviour whereby a user with the
                   'mod/facetoface:removeattendees' capability is able to cancel a users'
                   seminar booking, regardless of what the "Allow cancellations" setting is
                   set to.

    TL-20211       Added a new capability to allow the addition of attendees to a seminar event outside of the sign-up registration period

                   The new capability 'mod/facetoface:surpasssignupperiod' is enabled by
                   default for the editingtrainer and manager roles, on upgrade it will be
                   enabled for any role that currently has the 'mod/facetoface:editevents'
                   capability to maintain current functionality.

    TL-20214       Fixed icons in quiz results page overlaying text
    TL-20222       Fixed duplicate 'ID' SQL failure, when a seminar's event has more than one session date
    TL-20233       Fixed problems with complex company goal assignments

                   Before this patch, there were several problems relating to company goal
                   assignments. These included the 'Include children' hierarchy option not
                   working, and problems relating to users who might be assigned due to
                   several reasons, such as meeting multiple goal assignment criteria, or
                   having multiple job assignments.

                   With this patch, each separate reason that a user is assigned to a company
                   goal is correctly recorded in the database, including those caused by the
                   use of 'Include children'. When a user no longer meets the criteria for
                   assignment, the related assignment record is marked 'old'. When a user
                   again meets the criteria, the old record is changed back into an 'active'
                   record.

    TL-20234       Fixed display of Totara logo in IE11 on Windows 7 & 8
    TL-20245       Ensured program and certification messages are displayed correctly when adding and editing

                   The subject and message content were displaying special characters as HTML
                   entities in the add edit form. These now display correctly.

    TL-20256       Fixed user tours based on URLs with multiple parameters
    TL-20272       Fixed missing permissions check on Menu settings link in quickaccess menu

                   Prior to this patch, the link to edit the quick access menu would be shown
                   to users who didn't have the editownprofile capability. The link is now
                   only displayed if the user has this permission.

    TL-20302       Fixed 'Allow cancellations' form setting for users without 'Configure cancellation' capability when adding an event
    TL-20303       Fixed a bug that prevented attendance export from the seminar events dashboard when a deleted user was in the attendees list
    TL-20318       Fixed the 'edit attendee note' action for seminar events which enable reservations

                   Previously when 'Reserve spaces for team' was enabled but no attendees had
                   been added yet, the attendees list page was still displaying a record with
                   the 'Reserve' status to inform other managers about the number of
                   reservations/bookings used. This allowed the update of the Attendee Note
                   without an associated user, causing an error. This patch hides the update
                   attendee note action until a learner is added.

    TL-20324       Included custom room information in notification emails about cancelled seminar events

                   Prior to this patch, when a seminar event had a custom room assigned to one
                   or more sessions and an admin/editor/trainer cancelled the event, the room
                   information would not be included in the notification emails sent to
                   attendees.

                   With this patch, a custom room's information will be included in emails
                   sent to attendees when an event is cancelled.

    TL-20339       Fixed deletion of multiple goals when a single goal was unassigned from a user

                   When a user is assigned to the same organisation via several job
                   assignments and then simultaneously unassigned from the organisation, the
                   goals assigned to this user via an organisation are converted to individual
                   duplicated goal assignments. Previously, when a single goal was deleted,
                   the duplicate records were deleted as well. After the patch, the individual
                   goal assignments are removed separately.

    TL-20355       Fixed course's default image to not store the domain name of the system inside the database

                   Prior to this patch, when an admin uploaded the default image for course,
                   then the URL (including the domain name of a hosting system) would be
                   stored in the config table. This meant the image could no longer be
                   displayed if the domain name changed.

                   With this patch, the domain name will be stripped out for the default
                   course image.

    TL-20424       Fixed drag-and-drop accessible text showing block contents instead of title
    TL-20426       Fixed incorrect page layout set on the program management page
    TL-20442       MDL-58015: Set organisation identifier correctly for SCORM package displayed in a popup mode
    TL-20460       Fixed incorrect notification being sent to trainers who are unassigned from seminar events

                   Previously trainers who were removed from seminar events, received a
                   notification saying that they had been assigned to the event. They will now
                   receive the correct 'unassignment' notification.

    TL-20461       Reverted the conditions around seminar state transitions to allow attendance taking for in-progress events

                   The previous changes to the seminar booking system ??? primarily the rules
                   around state transitions ??? were limiting attendance taking to events that
                   had completely finished. The rules have been updated to allow attendance
                   when events are in-progress again.

Contributions:

    * Learning Pool - TL-20212
    * Michael Trio, Kineo USA - TL-19933
    * Think Learning - TL-20108


Release 12.3 (14th February 2019):
==================================


Important:

    TL-20156       Omitted environment tests have been reintroduced

                   It was discovered that several environment tests within Totara core had not
                   been running for sites installing or upgrading to Totara 11 or 12. The
                   following tests have been reintroduced to ensure that during installation
                   and upgrade the following criteria are met:

                   * Linear upgrades - Enforces linear upgrades; a site must upgrade to a
                     higher version of Totara that was released on or after the current version
                     they are running. For instance if you are running Totara 11.11 then you can
                     only upgrade to Totara 12.2 or higher.
                   * XML External entities are not present - Checks to make sure that there
                     are no XML files within libraries that are loading external entities by
                     default.
                   * MySQL engine - Checks that if MySQL is being used that either InnoDB or
                     XtraDB are being used. Other engines are known to cause problems in
                     Totara.
                   * MSSQL required permissions - Ensures that during installation and upgrade
                     on MSSQL the database user has sufficient permissions to complete the
                     operation.
                   * MSSQL read committed snapshots - Ensures that the MSSQL setting "Read
                     committed snapshots" is turned on for the database Totara is using.

    TL-20173       Fixed user cancellation when taking attendance if attendance status is not set

                   When taking seminar attendance, signups for which attendance was not set
                   would get cancelled. If this happened, attendees needed to be re-added and
                   attendance taken for them. This fix keeps attendees in their current state
                   if attendance is not set for them and current state is not attendance
                   related.

API changes:

    TL-20109       Added a default value for $activeusers3mth when calling core_admin_renderer::admin_notifications_page()

                   TL-18789 introduced an additional parameter to
                   core_admin_renderer::admin_notifications_page() which was not indicated and
                   will cause issues with themes that override this function (which
                   bootstrapbase did in Totara 9). This issue adds a default value for this
                   function and also fixes the PHP error when using themes derived off
                   bootstrap base in Totara 9.

Performance improvements:

    TL-19810       Removed unnecessary caching from the URL sanitisation in page redirection code

                   Prior to this fix several functions within Totara, including the redirect
                   function, were using either clean_text() or purify_html() to clean and
                   sanitise URL's that were going to be output. Both functions were designed
                   for larger bodies of text, and as such cached the result after cleaning in
                   order to improve performance. The uses of these functions were leading to
                   cache bloat, that on a large site could be have a noticeable impact upon
                   performance.

                   After this fix, places that were previously using clean_text() or
                   purify_html() to clean URL's now use purify_uri() instead. This function
                   does not cache the result, and is optimised specifically for its purpose.

    TL-20026       Removed an unused index on the 'element' column in the 'scorm_scoes_track' table
    TL-20053       Improved handling of the ignored report sources and ignored embedded reports in Report Builder

                   The Report Builder API has been changed to allow checking whether a report
                   should be ignored without initialising the report. This change is fully
                   backwards compatible, but to benefit from the performance improvement it
                   will require the updating of any custom report sources and embedded reports
                   that override is_ignored() method.

                   For more technical information, please refer to the Report Builder
                   upgrade.txt file.

Improvements:

    TL-19824       Added ability to unlock closed appraisal stages

                   It is now possible to let one or more users in a learner's appraisal move
                   back to an earlier stage, allowing them to make changes to answers on
                   stages that may have become locked. An 'Edit current stage' button has been
                   added to the list of assigned learners in the appraisal administration
                   interface. To see this button, users must be granted the new capability
                   'totara/appraisal:unlockstages' (given to site managers by default), and
                   must have permission to view the Assignments tab in appraisal
                   administration (requires 'totara/appraisal:manageappraisals' and
                   'totara/appraisal:viewassignedusers').

    TL-19985       Added a hook to the course catalogue to allow modifying the queried result before rendering the courses
    TL-20132       Menu type dynamic audience rules now allow horizontal scrolling of long content when required

                   When options for a menu dynamic audience rule are sufficiently long enough,
                   the dialog containing them will scroll horizontally to display them.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-20152       Fixed content width restrictions when selecting badge criteria

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

Bug fixes:

    TL-19454       Fixed accordion and add group behaviour on admin menu settings page
    TL-19494       The render_tabobject now respects the linkedwhenselected parameter in the learning plans tab
    TL-19838       SCORM AICC suspend data is now correctly stored

                   This was a regression introduced in Totara 10.0 and it affected all later
                   versions. Suspend data on the affected versions was not correctly recorded,
                   resulting in users returning to an in-progress attempt not being returned
                   to their last location within the activity. This has now been fixed and
                   suspend data is correctly stored and returned.

    TL-19895       Added notification message communicating the outcome when performing a seminar approval via task block

                   Previously, when a manager performed a seminar approval via the task block,
                   there was no feedback to the manager as to whether or not it had been
                   successful.

                   An example of where this could have been problematic was when a seminar
                   event required manager approval and the signup period had closed: the task
                   would be dismissed after the manager had completed the approval process,
                   but they would not be informed that approval had not in fact taken place
                   (due to the signup period being closed).

                   With this patch, a message will now be displayed to the user after
                   attempting to perform an approval, communicating whether the approval was
                   successful or not.

    TL-19916       MySQL Derived merge has been turned off for all versions 5.7.20 / 8.0.4 and lower

                   The derived merge optimisation for MySQL is now forcibly turned off when
                   connecting to MySQL, if the version of MySQL that is running is 5.7.20 /
                   8.0.4 or lower. This was done to work around a known bug  in MySQL which
                   could lead to the wrong results being returned for queries that were using
                   a LEFT join to eliminate rows, this issue was fixed in versions 5.7.21 /
                   8.0.4 of MySQL and above and can be found in their changelogs as issue #26627181:
                    * https://dev.mysql.com/doc/relnotes/mysql/5.7/en/news-5-7-21.html
                    * https://dev.mysql.com/doc/relnotes/mysql/8.0/en/news-8-0-4.html

                   In some cases this can affect performance, so we strongly recommend all
                   sites running MySQL 5.7.20 / 8.0.4 or lower upgrade both Totara, and their
                   version of MySQL.

    TL-19935       Fixed $PAGE->totara_menu_selected not correctly highlighting menu items
    TL-19936       Fixed text display for yes/no options in multiple choice questions

                   Originally, when defining a yes/no multiple choice type question, the page
                   showed 'selected by default' and 'unselect' for each allowed option. This
                   text now only appears when a default option has been selected.

    TL-19938       Fixed database deadlocking issues in job assignments sync

                   Refactored how HR Import processes unchanged job assignment records. Prior
                   to this fix if processing a large number of job assignments through HR
                   Import, the action of removing unchanged records from the process queue
                   could lead to a deadlock situation in the database.

                   The code in question has now been refactored to avoid this deadlock
                   situation, and to greatly improve performance when running an import with
                   hundreds of thousands of job assignments.

    TL-19994       Prevented the featured links title from taking up the full width in IE 11

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19996       Updated and renamed the 'Progress' column in the 'Record of Learning: Courses' Report Builder report source

                   The 'Progress' column displays progress for a course within a Learning
                   Plan. As this column is related to Learning plans, the 'type' of the column
                   has been moved from 'course_completion' to 'plan' and renamed from
                   'Progress' to 'Course progress'.

                   Please note that if a Learning plan has multiple courses assigned to it,
                   multiple rows will be displayed for the Learning Plan within the 'Record of
                   Learning: Courses' report if there are any 'plan' type columns included.

    TL-19997       Added limit to individual assignment dialog in program assignments
    TL-20008       Allowed users with page editing permissions to add blocks on 'My goals' page

                   Previously the 'Turn editing on' button was not available on the 'My goals'
                   page, preventing users from adding blocks to the page. This has now been
                   fixed.

    TL-20018       Removed exception modal when version tracking script fails to contact community
    TL-20019       Fixed a bug that prevented cancelling a seminar booking when one of a learner's job assignments was deleted
    TL-20055       Fixed bug that prevented learners from accessing the 'category' and 'report' catalogues when the Miscellaneous category was hidden
    TL-20102       Fixed certificates not rendering text in RTL languages.
    TL-20113       Fixed the filtering of menu custom fields within report builder reports

                   This is a regression from TL-19739 which was introduced in 12.2.

    TL-20128       Fixed 'missing parameter' error in column sorting for the Seminar notification table
    TL-20141       Fixed 'Date started' and 'Date assigned' filters in the program completion report

                   Previously the 'Date assigned' filter was mis-labelled and filtered records
                   based on the 'Date started' column. This filter has now been renamed to
                   'Date started' to correctly reflect the column name. A new 'Date assigned'
                   filter has been added to filter based on the 'Date assigned' column.

    TL-20155       Ensured that site policy content format was only ever set once during upgrade

                   Prior to this fix if the site policy editor upgrade was run multiple times
                   it could lead to site policy text format being incorrectly force to plain
                   text. Multiple upgrades should not be possible, and this issue lead to the
                   discovery of TL-20156.

                   Anyone affected by this will need to edit and reformat their site policy.

    TL-20192       Fixed deletion of seminar event after attendance was taken for learners

                   Previously, attempting to delete a seminar event where attendance for at
                   least one learner had been taken resulted in an error. Now, seminar event
                   deletion will be successful regardless of whether attendance has been taken
                   or not.


Release 12.2 (24th January 2019):
=================================


Security issues:

    TL-19900       Applied fixes for Bootstrap XSS issues

                   Bootstrap recently included security fixes in their latest set of releases.
                   To avoid affecting functionality using the current versions of Bootstrap,
                   only the security fixes have been applied rather than upgrading the version
                   of Bootstrap used.

                   It is expected that there was no exploit that could be carried out in
                   Totara due to this vulnerability, as the necessary user input does not go
                   into the affected attributes when using Bootstrap components. However we
                   have applied these fixes to minimise the risk of becoming vulnerable in the
                   future.

                   The Bootstrap library is used by the Roots theme.

    TL-19965       Corrected the encoding applied to long text feedback answers

                   Answers to long text questions for the feedback module may not have been
                   correctly encoded in some previous versions of Totara. The correct encoding
                   is applied where necessary on upgrade and is now also applied when a user
                   submits their answer.

Performance improvements:

    TL-4241        Converted the bulk query into chunk queries, within loading the list of users to be added/removed from an audience

Improvements:

    TL-18759       Improved the display of user's enrolment status

                   Added clarification to the Status field on the course enrolments page. If
                   editing a user's enrolment while the corresponding enrolment module is
                   disabled, the status will now be displayed as 'Effectively suspended'.

    TL-19306       Added CSV delimiter setting for attendee bulk upload

                   1) Added an admin setting on event global settings that determines the
                      sitewide default CSV delimiter for seminar with the following options:
                      * Automatic <-- default for t13
                      * , (comma) <-- default for t12, this is a current default setting, for
                                      case a client using Totara API.
                      * ; (semi-colon)
                      * : (colon)
                      * \t (tab)
                   2) Added a CSV file delimiter under CSV file encoding setting with the same
                      options as above defaulting to the selection

    TL-19666       Extended functionality for the 'Allow user's conflict' option on seminar event attendees

                   Prior to this patch, the 'Allow user's conflict' option was only applied on
                   the seminar event roles to bypass the conflict check. However it was not
                   applied to the attendees of the seminar event. With this patch the
                   functionality is now applied for attendees as well.

    TL-19721       Made help text for uploading seminar attendees from file more intuitive

                   The help text displayed when adding users to a seminar event via file
                   upload was worded in a way that made it difficult to understand. There was
                   also a formatting issue causing additional fields in the bulleted list to
                   be indented too far.

                   The string 'scvtextfile_help' was deprecated, and replaced by a new string,
                   'csvtextfile_help', to make it clear that only one of the three possible
                   user-identifying fields (username, idnumber, or email) should be used and
                   that all columns must be present in the file.

                   Additionally, the code that renders the upload form was modified so that
                   all listed fields have the same list indent level.

    TL-19823       Updated appraisal summaries to show the actual user who completed a stage

                   The actual user who completes an appraisal stage is now recorded and shown
                   when viewing the appraisal summary. This shows when a user was 'logged in
                   as' another user and completed the stage on their behalf. This also
                   continues to show the original user who participated in the appraisal, even
                   after a job assignment change results in a change to which users fulfill
                   those participant roles at the time the appraisal summary is viewed.

    TL-19825       Added 'login as' real name column to the logstore report source
    TL-19848       Upgraded PHPUnit to version 7.5

                   This patch upgrades the PHPUnit version to 7.5. Two major versions lie in
                   between the last version and this upgrade.

                   The following backwards compatibility issues have to be addressed in custom
                   code:
                   1) All PHPUnit classes are now namespaced, i.e. 'PHPUnit_Framework_TestCase' is now 'PHPUnit\Framework\TestCase'
                   2) The following previously deprecated methods got removed:
                      * getMock(),
                      * getMockWithoutInvokingTheOriginalConstructor(),
                      * setExpectedException(),
                      * setExpectedExceptionRegExp(),
                      * hasPerformedExpectationsOnOutput()
                   3) The risky check for useless tests is now active by default.

                   The phpunit.xml configuration 'beStrictAboutTestsThatDoNotTestAnything' was
                   set to 'false' to keep the previous behaviour to not show risky tests by
                   default.

                   To make the transition easier all methods removed in PHPUnit were added in
                   the base_testcase class and the functionality is proxied to new methods of
                   PHPUnit. These methods now trigger a debugging message to help developers
                   to migrate their tests to the new methods.

                   Old class names were added to renamedclasses.php to support migration to
                   new namespaced classes.

                   More information about the upgrade to 7.5:
                    * [https://phpunit.de/announcements/phpunit-6.html]
                    * [https://phpunit.de/announcements/phpunit-7.html]

    TL-19852       Fixed the wording of the 'Try another question like this one' button in the quiz module

                   The "Try another question like this one" button has been renamed into "Redo
                   question". Help text for the "Allow redo within an attempt" quiz setting
                   has been updated to clarify its behaviour.

    TL-19896       The maximum width of Select HTML elements within a Totara dialogue is now limited by the size of the dialogue
    TL-19904       Added appraisal page and stage completion events for logging
    TL-19909       Removed limit on the number of options available when creating a dynamic audience rule based on a User profile field

                   When creating a dynamic audience rule by choosing one or more values of a
                   text input User profile field, there was a limit of 2500 options to choose
                   from.

                   This was an arbitrary limit, and has been removed.

                   Note that very large numbers of options (more than ~50,000) may have an
                   effect on browser performance during the selection process. Selecting a
                   large number of options (more than ~10,000 selections) may cause the
                   receiving script to run out of memory.

Bug fixes:

    TL-4458        Added multi-language support for position organisation custom field names in audience rules
    TL-18732       Changed enrolment message sending for programs to be more consistent

                   If a program (or certification) is created with required course sets (all
                   optional) the program is marked as complete straight away for any assigned
                   users. Previously the enrolment message would not be sent to users in this
                   case. We now send the enrolment message to users even if the program is
                   complete.

    TL-19471       Fixed unavailable programs not showing in user's Record of Learning items when the user had started the program
    TL-19489       Ignore embedded reports for report-based catalog when the feature is off
    TL-19691       Expired images now have the expired badge stamped on top

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19728       Fixed the sending of duplicate emails on appraisal activation
    TL-19739       Fixed select filters when page switching on embedded reports with default values set

                   Previously if an embedded report had defined a default value for a select
                   filter, then changing that filter to 'is any value'  and hitting search
                   would correctly show all results, however if the report has multiple pages
                   then switching to any other page in the report would revert back to the
                   default value. The filter change in now maintained across pages.

    TL-19782       Fixed javascript regression in audience's 'visible learning'

                   Prior to this patch: the AJAX request was being sent twice to the server
                   when deleting a course from an audience's 'visible learning'. It caused the
                   second request to be regarded as an invalid request, because the first one
                   had already been processed and the record successfully deleted.

                   After this patch: the event will be triggered once in audience's 'visible
                   learning', and it will send only one AJAX request.

    TL-19791       Fixed an issue with audiences in course access restrictions

                   Previously the audience restrictions did not work when searching for
                   audience names which contained non-alphanumeric characters.

    TL-19797       Fixed minimum bookings notification being sent for cancelled events
    TL-19804       Fixed an issue where overridden grades were not reset during completion archiving
    TL-19811       Fixed a seminar's custom room not appearing in search results from a different seminar

                   Prior to this patch: A custom room (unpublished room) that had been used in
                   a seminar's event would appear in a search result from a query of a
                   different seminar.

                   With this patch: The custom room (unpublished room) will not appear in the
                   search result of a different seminar.

    TL-19813       Fixed a regression caused by TL-17450

                   TL-17450 caused a regression in the position of the Quiz and Lesson
                   activity menu blocks that made them appear full width. This undoes the
                   unintentional change in layout for these two activities.

    TL-19822       Fixed encoding of search text in catalogue

                   There was a problem which caused accented characters to be passed to the
                   server in an incorrect format when entered into the search text box in the
                   grid catalogue. This resulted in search not working correctly and has been
                   fixed.

    TL-19828       Fixed sanity check for external mssql database that checks that the specified table exists
    TL-19844       Fixed the position of the quick access menu for RTL languages

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19845       Fixed RTL when using gallery tiles in the featured links block

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19846       Fixed typo that caused Appraisal detail report to throw a fatal error
    TL-19847       Fixed removing attendees of past seminar events

                   User could not be removed from past events using the 'Attendees' tab. This
                   is fixed now, however, the user who performs the action will need to have
                   the 'mod/facetoface:signuppastevents' permission to do this.

    TL-19849       Fixed bug in report builder that prevented graphing of grade percentages

                   User reports created in Totara 10 and 11 allowed the Course Completion
                   'Grade' column to be displayed as a graph at the top of the report.

                   Prior to this patch, this behaviour was prevented in Totara 12. It is now
                   possible to graph these grades again.

    TL-19856       Fixed missing data attributes bug affecting search functionality for seminar rooms and assets
    TL-19857       Fixed toggling of restrictions on quiz questions

                   An invalid flex icon was being specified so when toggling restrictions on
                   quiz questions the icon would disappear and be replaced with the alt text
                   and then switch to a different icon set. Toggling the restriction is now
                   consistent and works as expected.

    TL-19865       Fixed sort order for question scale values in user data export for review questions
    TL-19866       Fixed date assigned shown on the program detail page

                   When a user is assigned to a program that they would have completed in the
                   past due to the courses in that program being complete, the date they were
                   assigned to the program was incorrectly displayed. Previously this date was
                   the date they completed the program (in the past). This now displays as the
                   actual date they were assigned, which is consistent with the 'Date
                   assigned' column in the Program record of learning report.

    TL-19871       Fixed bug that placed top-level site course in catalogue.

                   The Totara homepage is a special course that can hold activities. Adding an
                   activity to it caused it to be listed in the Find Learning catalogue, with
                   a blank tile.

                   This has been fixed by preventing the site from being included in the
                   catalogue when an activity is added or removed from the homepage, and by
                   excluding courses with the 'site' format from the catalogue's periodic cron
                   update script.

                   If you have a blank tile in the catalogue because of this issue, it will be
                   removed on the next hourly cron run.

    TL-19872       Fixed a PHP debug message when a quick access menu group has been deleted
    TL-19873       Fixed PHP error in the report with a 'course (multi line)' filter in the saved search where selected course has been deleted
    TL-19877       Fixed bug where multi-framework rules were flagged as deleted in Audiences dynamic rules
    TL-19894       Added batch processing of users when being assigned to a Program
    TL-19903       Fixed removing value of hierarchy multi select custom fields using HR Import

                   When syncing Positions or Organisations and changing the value of a
                   multi-select custom field, if a field was left blank then it would
                   incorrectly be ignored instead of removing the value (adhering to the empty
                   field behaviour setting). Empty fields for this type of custom field now
                   remove the existing value as expected.

    TL-19908       Fixed a debug notice being generated when adding deferred Program assignments
    TL-19912       Fixed bug that prevented learners from accessing the catalogue when the Miscellaneous category was hidden
    TL-19917       Fixed wrong table reference in the main menu
    TL-19922       Enabled Rooms/Assets 'Manage searches' buttons

                   When managing rooms or assets, it is possible to save a search for
                   rooms/assets by name and/or availability, and to share those searches with
                   other managers. In order to edit or delete saved searches, the manager
                   clicks on a "Manage searches" button.

                   Prior to this patch, clicking the button did nothing. The button now works
                   correctly, opening the Manage searches dialogue.

    TL-19923       Fixed due date format in "Competency update" emails

                   When a manager changes the due date of a competency in a learner's Learning
                   plan, the email sent to the learner now contains the correct dates.

    TL-19947       Increased the limit on number of choices available in autocomplete menu when restricting an activity by audience
    TL-19953       Fixed missing icon for appraisal previews

                   This was supposed to be fixed in TL-19780 but it still failed in IE11
                   because of the way IE behaves with missing icons compared to other
                   browsers.

                   This has now been fixed so that IE also displays the preview correctly.

    TL-19961       Removed exception in HR Import clean_fields() function when a field is not used

                   Fields can be present in HR Import source CSV file that are not required
                   and are outside of the list of possible fields to import. We do not need to
                   clean these fields as they are not used and have removed the execution
                   generated.

    TL-19982       Fixed duplication of seminar booking approval request message when learner has both manager and temporary manager set
    TL-20007       Fixed an error with audience rules relying on a removed user-defined field value

                   This affected the 'choose' type of audience rules on text input user custom
                   fields. If a user-defined input value was used in the rule definition, and
                   that value was then subsequently removed as a field input, a fatal error
                   was thrown when viewing the audience. This is now handled gracefully,
                   rather than displaying an object being used as an array error the missing
                   value can now be removed from the rule.


Release 12.1 (19th December 2018):
==================================


Important:

    TL-17182       Fixed the use of the "moodle/course:viewhiddencourses" capability in report builder reports

                   Previously, users with "moodle/course:viewhiddencourses" capability could
                   not see hidden courses and related content with enabled "Audience
                   visibility" consistently in Report Builder reports (including embedded
                   reports). This permission was largely applicable in System or Course
                   context but had no effect in Course category and other context levels.

                   Also this rule had no effect when Course Audience-based Visibility was set
                   to "Enrolled users only" or "Enrolled users and members of the selected
                   audiences".

                   Now, each course-related record is checked against this capability in the
                   course and all parent contexts regardless of Audience-based Visibility
                   setting.

Security issues:

    TL-19593       Improved handling of seminar attendee export fields

                   Validation was improved for fields that are set by a site admin to be
                   included when exporting seminar attendance, making user information that
                   can be exported consistent with other parts of the application.

                   Permissions checks are now also made to ensure that the user exporting has
                   permission to access the information of each user in the report.

Improvements:

    TL-19292       Added behat test coverage to content marketplace filters
    TL-19442       Enable course completion via RPL in Programs when the course is not visible to the learner

                   Previously when a course was not visible to the learner it could not be
                   marked as complete in the required learning UI. Now users with permission
                   to mark courses as complete can grant RPL even if the course is not
                   available to the learner.

    TL-19448       Modified grid catalogue search placeholder text
    TL-19647       Changed the title of an email sent out to confirm trainer for waitlisted seminar event

                   Prior to this patch: When a trainer was added into a waitlisted seminar
                   event, an email would be sent out to the trainer. The title of the email
                   was confusing because it included 'unknown date' and 'unknown time' (due to
                   waitlisted event).

                   With this patch: These keywords 'unknown date' and 'unknown time' are no
                   longer in the title of confirmation email sent out to the trainer. Instead,
                   a string 'location and time to be announced later' appears in the title.
                   This is achieved by the introduction of new placeholder "[eventperiod]"
                   that converts to "[starttime]-[finishtime], [sessiondate]" when date is
                   present and to "location and time to be announced later" when date is not
                   present.

                   To update existing notifications, replace placeholders
                   "[starttime]-[finishtime], [sessiondate]" with "[eventperiod]" manually.

Bug fixes:

    TL-18858       Fixed mismatching date format patterns in the Excel writer

                   Previously when exporting report builder reports to Excel, any dates that
                   were not otherwise explicitly formatted would be displayed in the mm/dd/yy
                   format, regardless of the user's locale. These dates are now formatted to a
                   default state so that they are displayed as per the user's operating system
                   locale when opening the Excel file.

    TL-18892       Fixed problem with redisplayed goal question in appraisals

                   Formerly, a redisplayed goal question would display the goal status as a
                   drop-down list - whether or not the user had rights to change/answer the
                   question. However, when the goal was changed, it was ignored. This patch
                   changes the drop-down into a text string when necessary so that it cannot
                   be changed.

    TL-18903       Deprecated the facetoface_fromaddress setting as all emails are now sent from the no reply address

                   The TL-13922 changes were required to deprecate the facetoface_fromaddress
                   setting.

    TL-19305       Fixed manager allocations on full seminar events

                   Previously when managers allocated users to a full seminar event, they
                   could end up in the "Approval required" state instead of being wait-listed
                   or overbooking the event.

    TL-19311       Added event observers for course restoration to update the course format

                   Prior to this patch, when uploading a course using "Restore from this
                   course after upload" where the existing and uploaded course formats differ,
                   there was no action to update the course activities based on its format.
                   After this patch, the course activities will be updated, via the event
                   observer.

    TL-19373       Added two new seminar date columns which support export

                   The new columns are "Local Session Start Date/Time" and "Local Session
                   Finish Date/Time" and they support exporting to Excel and Open Document
                   formats.

    TL-19481       Fixed the course restoration process for seminar event multi-select customfields

                   Previously during course restoration, the seminar event multi-select
                   customfield was losing the value(s) if there was more than one value
                   selected.

    TL-19485       Made tables scrollable when on iOS

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19507       Expand and collapse icons in the current learning block are now displayed correctly in IE11

                   Previously when someone using IE11 was viewing the current learning block
                   with a program inside it, the expand and collapse icons were not displayed
                   if there was more than one course in the program.

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance

    TL-19579       Enabled multi-language support on the maintenance mode message
    TL-19599       Fixed deletion of filters and columns in the "All User's Job Assignments" section
    TL-19615       Fixed a permission error when a user tried to edit a seminar calendar event
    TL-19679       Removed remaining references to cohorts changing to audiences
    TL-19690       Fixed bug on Seminar Cancellations tab that caused Time Signed Up to be 1 January 1970 for some users

                   When a Seminar event that required manager approval was cancelled,
                   attendees awaiting approval would show 1 January 1970 in the Time Signed Up
                   column of the Attendees View Cancellations tab.

                   The Time Signed Up for attendees awaiting approval when the event was
                   cancelled is now the date and time that attendance was requested.

    TL-19692       Fixed a naming error for an undefined user profile datatype in the observer class unit tests
    TL-19693       Role names now wrap when assigning them to a user inside a course

                   This will require CSS to be regenerated for themes that use LESS
                   inheritance.

    TL-19694       Fixed a capability notification for the launch of SCORM content

                   This fixed a small regression from TL-19014 where a notification about
                   requiring the 'mod/scorm:launch' capability was being displayed when it
                   should not have been.

    TL-19696       Fixed the handling of calendar events when editing the calendar display settings of a seminar with multiple sessions

                   Previously with Seminar *Calendar display settings = None* and if the
                   seminar with multiple events was updated, the user calendar seminar dates
                   were hidden and the user couldn't see the seminar event in the calendar.

    TL-19698       Fixed appraisal preview regression from TL-16015

                   TL-16015 caused a regression in which previewing the questions in an
                   appraisal displayed the text "Not yet answered". This patch fixes this and
                   now the actual UI control appears; e.g. for a file question, it is a file
                   picker, and for a date question, it is a date entry field.

                   Note that although values can be "entered" into the UI controls, nothing is
                   saved when closing the preview window.

    TL-19726       Fixed the string identifier that has been declared incorrectly for facetoface's notification scheduling
    TL-19760       Fixed multi-language support for custom headings in Report Builder
    TL-19778       Fixed an error in seminar report filters when generating SQL for a search

                   Prior to this patch: the columns relating to the filters could not be added
                   because these columns were in the wrong place, they would only be added if
                   the GLOBAL setting (facetoface_hidecost) of the seminar was set to FALSE.
                   Therefore it was causing the sql error due to the columns and sql joins not
                   being found.

                   With this patch: the columns are now put in the correct place, and these
                   columns will no longer be affected by the GLOBAL setting
                   facetoface_hidecost.

    TL-19779       Fixed an error when signing up to a seminar event that requires approval with no job assignment and temporary managers disabled

Contributions:

    * Ghada El-Zoghbi at Catalyst AU - TL-19692
    * Learning Pool - TL-19779
    * Michael Dunstan at Androgogic - TL-19292


Totara Learn 12.0 release
=========================

System requirement changes
--------------------------

    * Added support for PHP 7.3
    * Added an upgrade path from Moodle 3.3 to Totara 12
    * PostgreSQL minimum require version increased to 9.4
    * MSSQL now requires the Full-Text search component to be installed
    * Safari minimum supported version increased to recent versions of 11


Key:           +   Included in one or more stable releases as well

New features
------------

    TL-17902       Added HR Import for competencies

                   Competencies can now be created, updated and deleted via HR Import.

                   Each competency must reference an existing framework via its ID Number. Values for types and custom
                   fields may also be imported for each competency, providing these exist on the site that the import
                   is run on.

    TL-17752       New course, program and certification catalogue

                   Implemented a new, modern, media-rich catalogue focused on improving the user experience while
                   browsing for content.

                   The new catalogue is intended as a replacement for the 'Enhanced catalogue' which has been renamed
                   'Report-based catalogue'.

                   Improvements include:
                    * One area to search for courses, programs and certifications
                    * Ability to browse learning items by tile or list views
                    * Flexibility for administrators to configure display of different metadata
                    * Ability to show icons related to the learning item
                    * Ability to show learning item images
                    * Ability to search by tags
                    * Ability to visually highlight recommended training
                    * Search beyond title and description using tags, metadata, summary, etc
                    * Ability to share the URL of search criteria

                   Please also note the following:
                    * After upgrading cron must be run in order to populate the catalogue
                    * Search within the catalogue uses your databases full-text search capability. As such the search in each database works slightly differently.

    TL-17475       Added support for pluggable course creation workflows

                   This patch adds support for general purpose, pluggable workflows which provide an extensible way to
                   provide different workflows for a specific task.

                   The first workflow type to be implemented is the course creation workflow, which provides a way to
                   design custom workflows to collect information and generate specific types of courses.

                   See our developer documentation for more information:
                   https://help.totaralearning.com/display/DEV/Workflows

    TL-17426   +   Add Totara content marketplace and GO1 marketplace

                   Totara content marketplace provides support for browsing and importing external content from content
                   providers directly into your site.

                   Content providers can implement a new "marketplace" plugin type to integrate their content into
                   Totara Learn. The release includes a marketplace plugin for GO1 ([https://totara.go1.com/]), which
                   provides direct access to search and include GO1 aggregated content.

                   When first installed the content marketplace plugin will send an internal notification to site
                   administrators and site managers on the next cron run, letting them know that content marketplaces
                   are available. To prevent this notification and completely disable marketplaces add
                   $CFG->enablecontentmarketplaces = false; in your site's config.php *before* you upgrade your site.


Navigation improvements
-----------------------

    TL-19620       General improvements to the main menu administration

                   The issue saw the main menu code refactored in order to provide better support containers, and to
                   ensure that a smooth upgrade path exists.

                   Please note that when upgrading as the main menu no longer supports branches that are also items,
                   any menu items that were added may have been moved to an "Unused" container that is not visible on
                   the menu, in order to ensure that they are not lost.
                   We strongly recommend visiting the main menu administration page after upgrading.

    TL-19595       Replaced navigation block with course navigation blocks in each course

                   All instances of the navigation block have been removed. 'Navigation' block
                   is no longer required by theme and can be added and removed as needed.

                   To facilitate navigation between the course section new 'Course navigation'
                   block has been added to all existing courses and any new course created
                   from now on. This block behaves similarly to the 'Navigation' block, but is
                   limited in scope to the course and its activities only.

    TL-18995       Added a new block to link administrators of new sites to the Totara Community
    TL-18713       Reduced space between the main navigation and blocks when there are no breadcrumbs
    TL-18712       The site logo link now takes the user to their default home page

                   Previously when the user clicked on the site logo they were taken to the site's home page.
                   Now they are taken to their default home page, which may be the site home page, or one of their
                   dashboards.

    TL-17941       New administration menu

                   The new quick access menu is a replacement for the old Site Administration
                   menu and is customisable for each user. The menu will only be available if
                   a user has capabilities to perform one or more administration tasks.

    TL-17719       Converted front page content to use the new centre block region

                   The following blocks have been introduced for backward compatibility:
                    * Course progress report
                    * Courses and categories
                    * Course search

                   "Course progress report" and "Courses and categories" blocks are disabled by default in new
                   installations, and only enabled on upgrade if the respective front page content settings were
                   enabled.

    TL-17495       Redesigned main menu, for a more compact style with added support for a third level of links

                   Reworked the existing navigation, improving the user journey and added support for third-level links
                   which will allow us to tie all of the Totara products together.
                    * Redesigned navigation
                    * Added third-level navigation
                    * Moved logo into navigation bar
                    * Moved messages and alerts into navigation
                    * Moved language selector into navigation
                    * Moved user menu into navigation

                   The old navigation menu is now deprecated, but still available with some changes in code. See the
                   following page for details: https://help.totaralearning.com/display/DES/Totara+v12+navigation+revert

    TL-17494       Improved the work flow of adding blocks to editable regions

                   * Removed the existing "Add a block" block.
                   * Each editable region now has a dotted border, when editing is enabled.
                   * Added a "+" icon button to the centre of every block region.
                   * Clicking the "+" button opens a modal dialogue with a list of all available block types and a
                     search input.
                   * The search input provides real-time filtering of the block type list.
                   * Clicking a block name reloads the page and that block will be added to the same region.

    TL-17450       Added full width top and bottom block regions to the homepage and dashboard

                   In addition to existing block regions (side-pre, main, side-post), there are now 2 new regions (top,
                   bottom) that can show blocks as well. These new regions have already been added to the roots and
                   basis themes; if you want them in custom themes, you need to explicitly add them in.

                   Note: Just because existing blocks can be shown in these regions does not mean those blocks are
                   suited to these areas. There could be excess space or undesirable aesthetics involved. The best
                   blocks for these new regions are those that can display their information in wide columns, for
                   example tabular data, listings or banners.

    TL-17124       The main menu block is no longer added to the home page by default for new installations
    TL-16848   +   Renamed the "Site policies" side menu item in the "Security" section

                   The Security > "Site policies" side menu item has been renamed to "Security settings" to avoid
                   confusion with the new "Site policies" item when GDPR site policies are enabled.


Seminar improvements
--------------------

    TL-19184       Improved the appearance of seminar's notification form to resolve the confusion of notification's recipients

                   Prior to this patch, on a creating new seminar's notification page, the label 'All booked' within
                   the recipients section was misaligned, causing confusion.

                   After the patch, the label 'All booked' has been changed into 'All (past and present booked)'.
                   Furthermore, there is an improvement on form's UI, in which the 'Booked type' option is no longer a
                   checkbox, but a selection element instead.

    TL-18597   +   Improved the help text for the 'Notification recipients' global seminar setting

                   The setting is located under the notifications header on the site administration > seminars > global
                   settings page, the string changed was 'setting:sessionrolesnotify' within the EN language pack.
                   Full updated text is: This setting affects *minimum booking* and *minimum booking
                   cut-off* notifications. Make sure you select roles that can manage seminar events. Automated
                   warnings will be sent to all users with selected role(s) in seminar activity, course, category, or
                   system level.

    TL-18565   +   Moved 'Override user conflicts' action out of the seminar event setting page and into a 'save' modal dialog

                   The 'Override user scheduling conflicts' setting was initially intended for use with new events
                   where the assigned roles resulted in conflicts with existing events. It was not originally designed
                   to work with existing events.
                   We improved the wording to clarify this feature without further changes in the UI and workflow.

    TL-17288   +   Missing seminar notifications can now be restored by a single bulk action

                   During Totara upgrades from earlier versions to Totara Learn 9 and above, existing seminars are missing
                   the new default notification templates. There is existing functionality to restore them by visiting each
                   seminar notification one by one, which will take some time if there are a lot of seminars. This
                   patch introduces new functionality to restore any missing templates for ALL existing seminars at
                   once.

    TL-16864   +   Improved the template of seminar date/time change notifications to accommodate booked and wait-listed users

                   Clarified Seminar notification messages to specifically say that it is related to the session that
                   you are booked on, or are on the waitlist for. Also removed the iCal invitations/cancellations from
                   the templates of users on the waitlist so that there is no confusion, as previously users who were
                   on the waitlist when the date of a seminar was changed received an email saying that the session you
                   are booked on has changed along with an iCal invitation which was misleading.

    TL-16255   +   Added a "readonly" state to the Totara reserved custom fields to prevent users from changing the pre-existing seminar custom fields
    TL-15818       Refactored seminar code to allow multi-language notifications and consistent booking state processing

                   Multi-language:
                   Added support for the "Multi-Language Content" filter plugin in Seminar notifications. Notification
                   content will now be filtered according to each recipient's language settings.

                   Booking system:
                   The main target of refactoring was to bring consistency to the bookings state changes throughout all
                   related code, leading to predictable and controllable rules for each state transition. For this
                   purpose we have implemented a simplified Finite State Machine with a definition for each state,
                   following states and rules that must be matched for state transition to happen. This will greatly
                   reduce complexity during further changes to how booking states are managed.

                   Despite our efforts to maintain existing behaviour, some inconsistencies in old code forced some
                   minor changes in behaviour. We have identified the following changes:

                   1) Enable waitlist and overbooking - Previously when a Seminar's event had the setting 'Enable
                   Waitlist' enabled, then all the attendees that got signed up by an admin or any user that has
                   capability would have a status as booked. Now users will be booked until the event's room capacity
                   has been reached, the rest of the users will be added to the waitlist. Later on an admin or another
                   user with the "mod/facetoface:signupwaitlist" capability will be able to confirm users on the
                   waitlist, overbooking the event.

                   2) Events without session - Administrators could previously book users onto events without sessions
                   by confirming users on the waitlist. Now as the booked state requires a session to be set, this
                   attempt will return error until a session is created.

                   3) Action buttons labels -  Removed some inconsistencies with "Sign-up", "Join waitlist" buttons and
                   added "Request approval" when approval is required. Previously calendar and upcoming events block
                   would display a "sign-up" button, while the sign-up page would offer "Join waitlist". These
                   inconsistencies were largely removed by using the same prediction logic for all three source of
                   actions (course view, calendar, and sign-up page). Also, when approval is required, the user is now
                   properly informed that approval will be required.

                   API changes:
                   The API has been significantly changed. We have moved to a proper class structure for all Seminar
                   entities and their relationships. Along with that we didn't change the database structure, except
                   for some varchar fields that were converted to text to allow the multi-language filter to work
                   properly. We have also minimised front-end changes as much as possible. All functions that were
                   likely to be used by third-party code have been kept in the code base and deprecated. Deprecated
                   functions from main lib.php file were moved to deprecatedlib.php file (which is required by lib.php
                   file).

                   In order to reduce API changes we've deprecated mostly functions that were relevant to state machine
                   (booking states), and functions that were completely covered by OOP (e.g. rooms, assets,
                   reservations, calendar).

    TL-11243   +   Removed ambiguity from the confirmation messages for seminar booking requests
    TL-5964    +   Added settings to seminars that improve the control over multiple signups

                   This change introduces three new settings to both the settings form and the activity defaults admin
                   page for seminars. These new settings are:

                   1) How many times the user can sign-up? - This setting replaces the old 'multiple signups enabled',
                      it allows you to choose values between 1-10 or unlimited. To maintain current behaviour for existing
                      sites, they will have this set to 1 if 'multiple signups enabled' was not ticked, or unlimited if it
                      was ticked. Note: cancelled or declined sign-ups are not considered as part of this setting, neither
                      are sign-ups that have been archived by certifications.

                   2) Restrict subsequent sign-ups to - This setting restricts subsequent sign-ups to the seminar based
                      on the state of the current sign-up, the options are the attendance states 'fully attended',
                      'partially attended', and 'no show'. Selecting any of these options will restrict users to a single
                      concurrent sign-up, until the attendance has been taken for that event. Not selecting any of these
                      options will allow users to have as many concurrent sign-up as they want, up to the limit specified
                      by the setting above.

                   3) Clear expired waitlists - If enabled waitlisted sign-ups to seminar events will be cancelled by a
                      cron task after the event has begun, allowing those users to sign up for another seminar event.
                      Along with this setting there is also a new notification added to seminars, the 'Waitlisted sign-up
                      expired' notification. This can be used to inform users that their sign-up has been automatically
                      cancelled, and prompt them to go and sign-up to another event.


Report builder improvements
---------------------------

    TL-19111       Removed obsolete non-functional support for report builder report and source groups
    TL-19098       Automatic report builder data grouping was deprecated and affected report sources were rewritten to use subqueries
    TL-18639   +   Added support for custom help tooltips for Report Builder filters

                   When a report source is defined it is now possible to define a custom filter option to override the
                   default help tooltip for the given filter.

    TL-17872       Added an audience-based content restriction to all user-oriented report builder sources

                   Report builder sources that focus report on user's have a new content restriction that can be used
                   to restrict the user's appearing in the report to just those who are a member of an audience.

    TL-17353   +   Updated the description for "Minimum scheduled report frequency" in the report builder general settings
    TL-16729       Converted all report builder display functions into classes

                   All the Report Builder display functions have been deprecated and converted into display classes for
                   better control over how data is displayed and for improved performance.

                   This patch however does not introduce any changes in the current display of data within the reports.

    TL-16728       Ensured all Report Builder columns have a display class defined

                   To improve Report Builder performance, all columns now need to define a display class best suited to
                   the data type being displayed. This reduces unnecessary formatting.

                   A PHP Unit test is included to assert new columns have the 'displayfunc' option defined.
                   Run 'vendor/bin/phpunit totara_reportbuilder_display_testcase
                   totara/reportbuilder/tests/display_test.php' to find any local customisations that should be
                   updated.

    TL-16727       Moved all report builder functions that added columns, filters and joins from base source in to traits

                   All function that added columns, filters and joins have been deprecated and moved into traits within
                   the report sources associated component.

    TL-16726       Refactored Report builder initialisation

                    * Report builder constructor should no longer be used for initialising the report instances. New
                      factory methods were added to facilitate report initialisation: create() and create_embedded(). In
                      the future, Report builder constructor will be made private.
                    * New class rb_config was added to be used with the factory methods for passing report configuration
                      settings to the report initialisation. Instances of the rb_config can be shared between the reports,
                      but cannot be changed once they are finalised during the report initialisation.
                    * All described API changes are fully backwards compatible, however debugging messages will be
                      displayed when a site is running in developer mode to warn about any required changes in
                      customisations.

    TL-14966       Added a new conditional access restriction based on time since activity completion

                   Access to an activity can now be restricted based on time since completing another activity.

    TL-14939       Made it possible for report builder columns to be flagged as deprecated
    TL-13960       Moved all report builder customfield-related functions that added columns, filters, and joins from base source into traits

                   All function that added columns, filters, and joins for custom fields have been deprecated and moved
                   into traits within the report sources associated 'customfield' component.

    TL-10295   +   Added link validation for report builder rb_display functions

                   In some cases if a param value in rb_display function is empty the function
                   returns the HTML link with empty text which breaks a page's accessibility.


User data and site policy improvements
--------------------------------------

    TL-17383   +   Improved the wording and grouping of user data items
    TL-17378   +   Implemented user data item for the transaction information of the PayPal enrolment plugin

                   When the user enrols via PayPal the transaction details are sent to the IPN
                   endpoint in Totara which records the information in the enrol_paypal
                   table. The user data item takes care of purging, exporting and counting
                   this transaction information.

    TL-17374   +   Implemented user data item for course requests
    TL-17373   +   Implemented user data item for external blogs

                   This user data items takes care of the exporting and purging of external blogs. It includes all
                   external blogs created by the user, including tags assigned to it, all synced posts, and all
                   comments made on the blogs.

    TL-17362   +   Implemented user data item for portfolios

                   Implemented user data elements for portfolios. This allows the exporting and purging of user data
                   kept in relation to exporting of data to portfolios.

    TL-17354   +   Ordered all user data item groups alphabetically
    TL-17227   +   Implemented user data item for role assignments
    TL-17142   +   Enabled use of the HTML editor when creating site policy statements and added the ability to preview

                   An HTML editor is now used when adding and editing Site Policy statements and translations. A
                   preview function was also added. This enables the policy creator to view how the policy will be
                   rendered to users.

                   Anyone upgrading from an earlier version of Totara 11 who has previously added site policies and
                   wants to use html formatting will need to:
                    * Edit the policy text
                    * The text will still be displayed in a text editor, but you will have an option to change the
                      entered format
                    * Make sure you have a copy of the current text somewhere (copy/paste)
                    * Change the format to "HTML format"
                    * Save and re-open the policy OR Preview and click "Continue editing". The policy text will be
                      shown in the HTML editor but will most likely contain no formatting
                    * Replace the current (unformatted) text by pasting back in the copy of the original text
                    * Save

                   Please note that this will be considered a new version of the policy, and users will be required to
                   accept it again.

    TL-17137   +   The site policy user consent report now appears in the settings block

                   A user consent report exists for the new site policy tool, however it was never linked to from the
                   current navigation. This user consent report is now linked to from the Settings block, you can find
                   it by navigating to Security > Site policies > User consent report.

    TL-17130   +   Added consent statement filter for the Site policies report

                   This patch adds support for a consent statement filter for the Site policies report as well as a few
                   minor improvements to the site policy filters including:
                    * Removing the filter Current Version (Primary Policy)
                    * Replacing plain text version filter to a smart dropdown menu, which includes now the list of
                      available versions as well as the option to select current version of the policy
                    * Adding policy filter which allows you to filter only by policy
                    * Making user consent statement a simple filter
                    * Added custom help for consent statement filter
                    * Added custom help for policy version filter

                   Now to select the current version of the policy it is a matter of using 2 filters:
                    * Policy filter to select appropriate policy
                    * Version filter to select current version

                   Please note, that this patch will also remove Current Version (Primary Policy) filter from any saved
                   search using it.

    TL-16936   +   Implemented user data item for Competency progress

                   The competency progress item is specifically for the comp_criteria_record table; other competency
                   tables are handled by the competency status item.

    TL-16877   +   Implemented user data items for comments and HTML blocks

                   Now it is possible to purge, export and audit the data stored in the comments and HTML blocks.

                   In case of the comments block item, all comments made by users in all created comment blocks are
                   purged or exported. This affects the front page, personal dashboards and courses.

                   In case of the HTML block item, all blocks created by the users in their personal dashboards are
                   purged and exported. HTML blocks in other contexts (front page, courses) are not affected as they
                   are related to the course or the site and not personal to the user.

    TL-16840   +   Implemented user data item for user data export requests
    TL-16777   +   Implemented user data item for the Featured links block
    TL-16775   +   Implemented user data item for RSS client
    TL-16739   +   Implemented user data items for program and certification completion

                   This includes exporting and purging of program and certification assignments, completion records
                   (including completion history and logs). It also includes exceptions, program extensions and the log
                   of program messages sent to the user.

                   Users are unassigned from any program or certification regardless of the assignment type. If users
                   were assigned via audience, position or organisation it's possible that they will be reassigned
                   automatically as soon as the next scheduled task for dynamic user assignment is triggered.

    TL-16738   +   Implemented user data items for grades

                   The following user data items have been introduced:
                    * Grades - This item takes care of the Gradebook records, supporting both export and purge.
                    * Temp import - This item is a fail-safe cleanup for the tables which are used by grade import
                      script for temporary storage, supporting only purge.
                    * Improved Individual assignments item - This item includes feedback and grades awarded via
                      advanced grading (Guide and Rubric), supporting both purge and export.

    TL-16736   +   Implemented user data items for course enrolments

                   Added two user data items that allow exporting and purging:
                    * An item for course enrolment data.
                    * An item for pending enrolments that belong to the Flat file enrolment plugin.

    TL-16367   +   Implemented user data items for standard and legacy logs
    TL-16365   +   Implemented user data items for the Wiki module

                   The following user data items have been introduced:
                    * Individual wiki as a whole.
                    * Collaborative wiki files export files uploaded by the user to the collaborative wiki.
                    * Collaborative wiki comments exports\purges user's comments for collaborative wiki pages.
                    * Collaborative wiki versions exports collaborative wiki page versions submitted by the user.

    TL-16360   +   Implemented user data item for glossary entries, comments and ratings
    TL-16357   +   Implemented user data item for LTI submissions
    TL-16356   +   Implemented user data item for the database module
    TL-16350   +   Implemented user data items for appraisals

                   Added five user data items:
                    * "Appraisals" - purge all appraisal data where the user is a learner
                    * "As the learner, excluding hidden answers from other roles" - export all appraisal content that
                      the user can see as a learner
                    * "As the learner, including hidden answers from other roles" - export all appraisal content,
                      including all answers from other roles, regardless of visibility settings, where the user is the
                      learner
                    * "Participation in other users' appraisals" - export all other users' appraisals that the user is
                      currently participating in
                    * "Participation history" - export the history of participation in other users' appraisals

    TL-16349   +   Implemented user data items for Learning Plans and Evidence

                   This allows user data for Learning Plans and Evidence items to be purged and exported.

    TL-16346   +   Implemented user data items for feedback 360

                   Feedback360 has two user data items, both implementing export and purge:
                    * The user assignments item, this covers all of a user's assignments to a Feedback360 and all
                      responses to their requests.
                    * The response assignments item, this covers all of a user's responses to other user's Feedback360
                      requests.

                   It is worth noting that self evaluation responses will be included in both user data items.

    TL-16345   +   Implemented user data item for event monitor subscriptions

                   Implemented user data item for event monitor subscriptions to allow the exporting and purging of
                   user data kept in relation to event monitoring.

    TL-16344   +   Implemented user data item for the "Self-registration with approval" authentication plugin
    TL-16334   +   Implemented user data items for component and plugin user preference data

                   It is now possible to export and purge user preference data being used by all parts of the system.
                   These preferences store a range of information, all pertaining to the user, and the state of things
                   that they have interacted with on the site, or the decisions that they have made.
                   Some examples are:
                    * What user tours the user has completed, and when.
                    * The admin bookmarks that they have saved.
                    * Their preferences for the course overview block.
                    * Whether they have docked the admin and navigation blocks.
                    * Their preferred display mode for forums.
                    * What regions within a workshop activity they have collapsed.

    TL-16332   +   Implemented user data items for Audience memberships

                   Items for exporting and purging a user's audience membership has been added. This is split into two
                   items: Set audience membership and dynamic audience membership.

    TL-16327   +   Implemented user data items for report builder

                   Added items that allow exporting and purging of user-made saved searches (private and public),
                   scheduled reports, and their participation in global report restriction.


Frontend improvements
---------------------

    TL-18927   +   Totara form load deferred object now resolves after the form initialise JavaScript is called

                   Previously, the Totara form initialise code was run after the load deferred object had been
                   resolved. This meant that calls to getFormInstance(formid) would return null on load.done(), and not
                   the form that was requested.

    TL-17603       Added reusable UI grid component

                   Added a reusable UI component for displaying content in a grid format. The component includes events
                   for setting an active tile state based on user clicks.

    TL-16649       Added reusable select and region UI components

                   The new select components are:
                    * Multi select - Similar to a multiple select and can return multiple options
                    * Single select tree - Similar to a single select dropdown that allows nestable options
                    * Text search - A stylised text input field with search icon

                   These are designed for use inside the added region container which has 'clear all' functionality.
                   Initially these will be used in the new catalogue.

    TL-19264       Switched to using standardised URL querystring parameters for the multi select component
    TL-19322       Added additional UX options to the select tree component

                   Extended the select tree component to also support the following features:

                   A select tree can be provided a call to action string value (e.g. 'Please select an option...' )
                   which isn't included in the select list & doesn't provide a value. This is an alternative to the
                   default value.

                   A select option with child nodes can either be:
                    * A clickable link itself which provides a selected value
                    * A click target for expanding/collapsing child nodes which provides no selected value

    TL-19288       Increased z-index of YUI dialogs to match other dialogs
    TL-19045       Centered login panel vertically
    TL-18709       Changed font size in header navigation from 16px to 14px
    TL-18557       Added new base class for output elements that are using templates

                   Output widgets can now extend \core\output\template. Once extended they can be given directly to a
                   renderer's render method, and that renderer will render them from the template. With this approach
                   there is no need to define any render methods at all, or to implement renderers for output widgets.

    TL-17910       The single button output component now supports a "primary" state
    TL-17891   +   Changed the Change password page to use the standard page layout

                   This gives the Change password page the standard navigation and blocks

    TL-17850       Improved colour of text input placeholders in Totara forms
    TL-17835       Improved calendar popover

                   Previously this was using a YUI module. This has now been updated to use the Bootstrap popover.

    TL-17795   +   Tooltips in the "Current learning" block are now displayed when focused via the tab key
    TL-17790   +   Improved the HTML of the change password page

                   Previously the "Change password" heading was in a legend, this patch moves it to a proper HTML heading.

    TL-17580       Refactored and simplified the Flex icon AMD JavaScript module
    TL-17517   +   Improved the user interface for course import when no courses match a search term
    TL-17439       Split block configuration settings into two sections

                   The general section contains all the settings common to every block, and the new custom section
                   contains settings specific to the block type.

                   If you have any custom blocks please refer to the blocks/upgrade.txt file for more information.

    TL-17403   +   Removed calls to deprecated table() and cellpadding() functions within forum ratings and external blogs
    TL-17372       Deprecated footer navigation in the Basis theme

                   The footer menu no longer shows when using Basis as your theme (and themes that include
                   "theme/basis/layout/partials/footer.php"). The functionality that provides this has been deprecated
                   and will be removed in a future version of Totara.

                   If you would like to keep this functionality beyond Totara 12, we recommend you copy the following
                   files into a custom theme that inherits Basis:
                    * theme/basis/templates/page_footer_nav.mustache
                    * theme/basis/classes/renderer.php (2 functions that have been deprecated)
                    * theme/basis/classes/output/page_footer_nav.php
                    * theme/basis/less/totara/page-footer.less

    TL-17143       AMD modules can now be initialised using data attributes in HTML markup

                   It is now possible to initialise AMD modules using data attributes in HTML markup. This is intended
                   primarily for templates.

    TL-16918       Removed Polyfills required for IE9

                   As of Totara 10, IE9 was no longer supported. This issue removes the polyfills that enabled IE9 to
                   have the same functionality as more modern browsers.

    TL-16881       Update jQuery to 3.3.1
    TL-16797   +   Standardised the use of styling in the details of activity access restrictions

                   When some new activity access restrictions were introduced in Totara 11.0, the display of
                   restriction details in the course was not in bold like existing restrictions. This patch corrects
                   the styling.

    TL-16731       Added LESS structure to help maintain consistency with common styles
    TL-16178       Atto autosave notifications now use standardised components
    TL-16171       Improved the warning notification in the Assignments module

                   When grading and viewing an assignment, the CSS classes alert and alert-error were being used. These
                   have been removed in favour of adding a warning icon before the message.

    TL-16157   +   Improved the layout of progress bars inside the current learning block
    TL-14714       Added onchange support to radio form elements

                   Allow radio groups to use the onchange client action in the Totara forms library.

    TL-10852       Improved footer appearance to fill bottom of the page
    TL-9414   +    Required totara form Checkbox lists are validated in the browser (as opposed to a page reload)

Please note that several of the changes above will require CSS to be regenerated for themes that use LESS inheritance.


Performance improvements
------------------------

    TL-19084   +   Enrolment type column in course completion report source is now using subqueries to improve compatibility of other general columns in the same report
    TL-19053       Improved the performance of full text searches within PostgreSQL
    TL-18998   +   Improved performance of language pack installation by changing to gzip

                   Language pack installation and updates now utilise gzip instead of zip.
                   Extract of gzip files is much quicker than zip files within Totara.
                   Manual installation and updates using zip files are still supported and will continue to operate.
                   All online installations and updates will now use tgz files exclusively.

    TL-18929       Added two indexes to speed up queries accessing the block_totara_stats table

                   In quite a few places throughout the code we query the table 'block_totara_stats' using two
                   combinations of columns. In adding indexes on these column combinations query speed will be
                   improved, especially with a lot of entries in the table.

    TL-18845   +   Removed a superfluous unique index on the job_assignment.id column
    TL-18693       Fixed memory leaks in PHPUnit test by resetting properties in tearDown() method

                   Additionally this patch introduces a check in the advanced_testcase which checks after each test for
                   properties which weren't reset. It fails any test where it finds unreset instance properties to
                   prevent creating more memory leaks in the future. There is an option to disable this check if needed
                   by setting the constant PHPUNIT_DISABLE_UNRESET_PROPERTIES_CHECK in phpunit.xml.

    TL-18686       Optimised the performance of dynamic audiences

                   With this patch, the scheduled task (Dynamic Audiences update) is now sorting audiences in order of
                   their dependencies on other audiences. Audiences that depend on other audiences will be updated
                   after their dependencies updates.

                   This allows faster and more consistent propagation of audience changes (ideally in one task run).

    TL-18666       Improved AMD module loading by converting the core/first AMD module to use RequireJS bundling instead
    TL-18640   +   Updated certif_completion join to use 'UNION ALL'

                   The 'certif_completion' join in the 'rb_source_dp_certification' report source now uses 'UNION ALL',
                   previously 'UNION', which will aid performance.

    TL-18591       Added an index to the moduleinstance column of the course_completion_criteria database table
    TL-17661       Enabled missing gzip compression for uncached js files
    TL-17586   +   Greatly improved the performance of the update competencies scheduled task

                   The scheduled task to reaggregate the competencies "\totara_hierarchy\task\update_competencies_task"
                   was refactored to fix a memory leak. The scheduled task now loops through the users and loads and
                   reaggregates items per user and not in one huge query as before. This minimises impact on memory but
                   increases number of queries and runtime.

    TL-17414   +   Improved information around the 'completions archive' functionality

                   It now explicitly expresses that completion data will be permanently deleted and mentions that the
                   data that will be archived is limited to: id, courseid, userid, timecompleted, and grade. It also
                   mentions that this information will be available in the learner's Record of Learning.


Developer improvements
----------------------

    TL-18985   +   Unit tests may now override lang strings
    TL-18909   +   Fixed compatibility issues with PHP 7.3
    TL-18777   +   Allowed plugins to have custom plugininfo class instead of just type class
    TL-17877       Regenerate lintignore files: Regenerated ignore files for linters
    TL-17746       Removed minified AMD modules with no Source files

                   The following minified AMD JavaScript modules were removed as they are not used and have no source files:
                    * 'block_totara_featured_links/course_dialog'
                    * 'block_totara_featured_links/icon_picker'
                    * 'totara_form/form_clientaction_autosubmit'

    TL-17668       Added support for full text searching

                   This improvement saw the introduction of the following full text search features:
                   * Full text search indexes can now be added to fields within the Totara database.
                   * Full text searches can now be run on these indexes.

                   This functionality is used by the new catalog to provide better searching.

                   To get the best possible result from full text searches, sites should set the full text search
                   language that will be used in the creation of indexes within their sites config.php file. For more
                   information on how to do this, please refer to the config-dist.php file provided with Totara. All
                   information is under the "FULL TEXT SEARCH" heading.

                   Technical documentation for developers can be found at
                   https://help.totaralearning.com/display/DEV/Full+text+search
                   For those intending to add full text search to their plugins and customisations, we recommend that
                   you read and follow the instructions in the technical documentation. Most importantly always define
                   a new table to use for full text searching, have a cron routine that ensures it is kept up to date,
                   and use event observers to keep it up to date with live changes.

    TL-17384   +   composer.json now includes PHP version and extension requirements
    TL-17347       Code related to previously disabled $CFG->loginhttps setting was removed and public API was deprecated
    TL-17357   +   Unsupported symlinks are now ignored in phpunit tests
    TL-17352   +   PHPUnit and Behat do not show composer suggestions any more to minimise developer confusion
    TL-17268   +   Upgraded Node.js requirements to v8 LTS
    TL-16912       Added JavaScript polyfill in IE11 to support basic ECMAScript 6 functionality

                   For more information please refer to our developer documentation
                   https://help.totaralearning.com/display/DEV/ES+6+functionality

    TL-6630    +   Added functionality to perform capability checks directly against the database

                   A new get_has_capability_sql() function has been introduced that returns an SQL snippet to resolve
                   capability checks against the database.
                   Among other uses this allows Totara to resolve visibility state much more efficiently than before
                   without sacrificing accuracy.

                   As part of this change a new table containing flattened context data will be created and
                   maintained.
                   There are a couple of important things to note about this:

                   During upgrade to this release the table will be created and populated. This upgrade step could take
                   several minutes on large sites.
                   The table is kept up-to-date automatically by the access API. If you have third party plugins or
                   customisations that are directly manipulating access data then you will need to review these.
                   We have extensively tested the performance of this change during our QA process and are confident
                   with the results. If you experience any problems please let us know immediately.


Platform improvements
---------------------

    TL-19476       Added custom field 'created' and 'updated' events

                   These new events are also observed by the new catalogue in order to update the search indexes when
                   new fields are added, or existing fields are updated.

    TL-19066       Database table context_temp is now a real temporary table

                   The original context_temp table has now been dropped.
                   This table was only ever intended as an internal store, and should not have been used by anything
                   other than the access API.

    TL-18983   +   Added workaround for missing support for PDF embedding on iOS devices

                   Web browsers on iOS devices have very limited support for embedding PDF files ??? for example, only
                   the first page is displayed and users cannot scroll to next page. A new workaround was added to PDF
                   embedding in File resource to allow iPhone and iPad users to open a PDF in full-screen mode after
                   clicking on an embedded PDF.

    TL-18921       Removed the Memcache cache store from core

                   Not to be confused with the Memcached cache store.
                   The Memcache PHP extension is not compatible with PHP7, and as such the Memcache cache store could
                   not be used.
                   It has now been removed from core.

                   If you are currently using the Memcache cache store and plan to upgrade in future, this may be an issue.

    TL-18852   +   Database table prefix is now required for all new installations

                   Previously MySQL did not require database prefix to be set in config.php, since MySQL 8.0 the prefix
                   is however required. To prevent problems in future upgrades Totara now requires table prefix for all
                   databases.

    TL-18722       Added critical notifications type, which go into their own section above the navbar
    TL-18626       Moodle: De-moodle strings: Replaced some Moodle strings with Totara equivalents
    TL-18554       Introduced common block settings and API to manage those

                   The idea of the common block settings API is to allow core developers to have predictable common
                   settings storage for all the blocks and if necessary,  introduce properties which cover all block
                   types without interfering with settings provided the by third-party block developers.
                   It also includes a few minor changes for block configuration: hiding, docking and show header/border
                   settings now use checkboxes instead of radio buttons. Moreover, to provide better backwards
                   compatibility a setting "Override default block title" has been introduced and unless it is checked
                   the block retains pre-patch behaviour for the title supplied by the block developer.

    TL-17905       Updated the default value for the 'docroot' setting

                   Previously, error pages included a link to Moodle documentation, which often didn't exist for
                   Totara-specific errors. This change removes the default documentation root so the 'More information
                   about this error' link is no longer shown.

                   If you wish to restore the links, set the docroot back to
                   http://docs.moodle.org after upgrading.

    TL-17738   +   Changed data-vocabulary.org URL in metadata to be https

                   This URL is used to provide extra information for navigation breadcrumbs to search engines when your
                   site is indexed.

    TL-17280   +   Improved compatibility for browsers with disabled HTTP referrers
    TL-17214       InnoDB upgrade tool and deprecated authentication plugins were removed from distribution

                   The following authentication plugins were removed:
                    # auth_fc
                    # auth_imap
                    # auth_nntp
                    # auth_none
                    # auth_pam
                    # auth_pop3

                   The following upgrade tool was removed: tool_innodb

    TL-17024   +   Added detection of pending upgrades to admin settings related pages
    TL-16958   +   Updated language strings to replace outdated references to system roles

                   This issue is a follow up to TL-16582 with further updates to language strings to ensure any
                   outdated references to systems roles are corrected and consistent, in particular changing student to
                   learner and teacher to trainer.

    TL-16582   +   Updated language contextual help strings to use terminology consistent with the rest of Totara

                   This change updates the contextual help information displayed against form labels. For example this
                   includes references to System roles, such as student and teacher, have been replaced with learner
                   and trainer.

                   In addition, HTML mark-up has been removed in the affected strings and replaced with Markdown.

    TL-15739   +   Imported HTMLPurifier library v4.10.0
    TL-14282   +   Imported ADOdb library v5.20.12


Miscellaneous improvements
--------------------------

    TL-19145   +   Improved terminology for non-graded assignment strings
    TL-19014   +   Implemented new capabilities for controlling the access to SCORM content

                   Previously all users who could enter a course were able to launch SCORM
                   activities.
                   The only way to limit access was to make the activity hidden and then to
                   use the moodle/course:viewhiddenactivities capability to grant access.

                   Two new capabilities have been added to allow better control of access to
                   SCORM activities.
                    * mod/scorm:view
                    * mod/scorm:launch

    TL-19002       Changed the legacy programs/certifications catalogue UI to be consistent with course catalogue as a model

                   Changes are made for the legacy programs/certifications catalogue UI (it uses one base code) to be
                   consistent with course catalogue as a model when enhanced catalogue is disabled
                    # Search box is moved to the top-left of the catalogue page
                    # Added 16px margin-bottom space for the top-left search box
                    # Search box label is removed
                    # The "Add new program/certification" button is moved to center of the page
                    # Course/program/certification titles font is changed from H3 to standard font
                    # Programs/certifications dropdown box with the categories/sub-categories options is moved to the right of the page
                    # Fixed program/certifications breadcrumbs
                    # Fixed if program has any associated overview files
                    # Fixed behat test after new UI applied

    TL-18978       Improved the validation display for dynamic audience rules that use a date selector
    TL-18963   +   Improved the help text for the 'Enable messaging system' setting on the advanced settings page
    TL-18896       Date pickers in forms now use the same order of day, month and year fields as current language full date and time display format
    TL-18840       Added a new dynamic audience rule for user's certification completion date
    TL-18793   +   Improved display of course details in the course and categories management page
    TL-18770   +   Disabled the site policy translation interface language selector when only a single language is available
    TL-18757   +   Send notifications to new appraisees for an already activated appraisal

                   Previously the appraisals module only sent out notifications to learners when the appraisal was activated.
                   If new learners were added to the appraisal after activation, they did not receive any notification.

                   With this patch, notifications are sent out when new learners are added to the appraisal after activation.

    TL-18718       Added upgrade step to set new redis cache store settings 'test_password' and 'test_serializer' to default values when not already set

                   In a previous patch new settings 'test_password' and 'test_serializer' for the Redis Cache Store
                   were introduced. If the site hasn't already been upgraded to a version which includes these settings
                   we set the password to an empty string and the serializer to PHP's default value to ensure that
                   previous functionality works as before. These settings can still be changed in the appropriate
                   section of the Site Administration.

    TL-18697       Totara Connect login error handling was improved and diagnostic logging was added
    TL-18675   +   Added 'not applicable' text to visibility column names when audience-based visibility is enabled

                   When audience based visibility is enabled it takes priority over other types of visibility. Having
                   multiple visibility columns added to a report may cause confusion as to which type of visibility is
                   being used. '(not applicable)' is now suffixed to the visibility column to clarify which type of
                   visibility is inactive, e.g. 'Program Visible (not applicable)'.

    TL-18646       HR Import allows HTML tags for fields where this is permitted

                   Fields such as descriptions or text area custom fields allow HTML tags when a value is added via the
                   interface. However, HR Import was stripping these tags. Cleaning of these fields is now the same
                   whether values are added via the interface or HR Import, i.e. they retain their HTML tags.

    TL-18601       Added 'type ID number' column to the 'Manage types' hierarchy tables to allow administrators to have one place to go to to identify the available typeidnumbers
    TL-18600       Import of custom field values allows for duplicate shortnames

                   When using HR Import to create and update positions or organisations, custom field short names had
                   to be unique across the site, despite the only restriction in the UI being that they are unique
                   within a given type. HR Import now accounts for this configuration when importing custom fields for
                   hierarchies, such as position and organisation.

    TL-18596   +   Added a filter for the Number of Job Assignments for a user

                   A filter has been added for the Number of Job Assignments column and is available in all report
                   sources that include the Job Assignments filters. This filter adds a way to filter users that have
                   no Job Assignments.

    TL-18575       A limitation of 255 characters is now consistently applied when validating course shortname

                   The course shortname field in the database has always been 255 characters.
                   However the course creation form arbitrarily limited course shortname length to 100 characters.
                   As of this change the course shortname form now checks that the user-entered value is no longer than
                   255 characters, matching the database limitation.

    TL-18481   +   Improved the help strings for the 'Minimum time required' field within a program or certification course set

                   Program and certification 'Course set due' and 'Course set overdue' message help strings have also
                   been updated to convey that the 'Minimum time required' field is used to determine when a course set
                   is due.

    TL-17974       Site-wide settings for HR Import can now be overridden by element

                   The HR Import page for 'General settings' has been renamed to 'Default settings'. This page includes
                   the same settings as previously, but will also list which elements are using a given setting area.

                   Element setting pages now contain settings relating to file access, notifications and scheduling.
                   These settings allow you to select the default settings to apply or to override them with values
                   that will apply to that element.

                   Following the upgrade, values from 'General settings' will remain unchanged in the 'Default
                   settings' page. Any enabled elements will use the default settings until changed.

    TL-17920   +   Added support for the 'coursetype' field in the 'upload courses' tool

                   The 'coursetype' field will now accept either a string or an integer value from the map below:
                    * 0 => elearning
                    * 1 => blended
                    * 2 => facetoface

                   Within the 'upload courses' CSV file, the value for the 'coursetype' field can be either an integer
                   value or a string value. If the value of 'coursetype' was not within the expected range of values
                   (as above), then the system will throw an error message when attempting to upload the course(s) or
                   while previewing the course(s).

                   If the field is missing from the CSV file or the value is empty, then the 'coursetype' will be set
                   to 'E-learning' by default. This is consistent with previous behaviour.

    TL-17901       Hierarchy export improvements

                   Hierarchy export has been improved as follows:
                    * Competency items can now be exported in the same manner as any other type of hierarchy
                    * The default export file format has been changed. By default the file will now contain all item
                      data allowing it to be used for re-import via HR Import.
                      To revert back to the old hierarchical format (not suitable for HR Import), add the following line
                      to config.php:
                          _$CFG->hierarchylegacyexport = 0;_
                    * An option has been added to the Manage _<hierarchy>_ pages allowing the user to export all items
                      in all frameworks to a single file

    TL-17780   +   Added a warning message about certification changes not affecting users until they re-certify
    TL-17720   +   Added 'audience visible' default course option to the upload course tool
    TL-17626   +   Prevented report managers from seeing performance management data without specific capabilities

                   Site managers will no longer have access to the following report columns as a default:

                   Appraisal Answers: Learner's Answers, Learner's Rating Answers, Learner's Score, Manager's
                   Answers, Manager's Rating Answers, Manager's Score, Manager's Manager Answers, Manager's Manager
                   Rating Answers, Manager's Manager Score, Appraiser's Answers, Appraiser's Rating Answers,
                   Appraiser's Score, All Roles' Answers, All Roles' Rating Answers, All Roles' Score.

                   Goals: Goal Name, Goal Description

                   This has been implemented to ensure site managers cannot access users' performance-related personal
                   data. To give site managers access to this data the role must be updated with the following
                   permissions:
                   * totara/appraisal:viewallappraisals
                   * totara/hierarchy:viewallgoals

    TL-17613   +   Added a hook to the last course accessed block to allow extra data to be passed to template

                   This enables extra data to be passed through to the Last Course Accessed block template so that the
                   display can be more easily modified without changing core code.

    TL-17611   +   Added a hook to the last course accessed block to allow courses to be excluded from being displayed

                   This hook allows specified courses to be excluded from being displayed in the Last Course Accessed
                   block. If the most recently accessed course is excluded then the next most recently accessed course
                   is displayed.

    TL-17390   +   Enabled the "Force users to log in to view user pictures" setting by default for new installations to improve privacy
    TL-17261       Multiple improvements in the authentication plugins

                   * Authentication plugins are now required to use new settings.php for plugin configuration.
                   * CLI sync scripts were converted to scheduled tasks.
                   * External Database authentication supports PDO.
                   * Shibboleth user may change their passwords.

    TL-17232   +   Made the "Self-registration with approval" authentication type use the standard notification system

                   The "Self-registration with approval" authentication plugin is now using standard notifications
                   instead of alerts, for "unconfirmed request" and "confirmed request awaiting approval" messages. A
                   new notification was also added for "automatically approved request" messages when the "require
                   approval" setting is disabled.

    TL-17170   +   Included hidden items while updating the sort order of programs and certifications
    TL-17149   +   Fixed undefined index for the 'Audience visibility' column in report builder when there is no course present
    TL-16921   +   Converted utc10 Totara form field to use the same date picker that the date time field uses

                   This only affects desktop browsers

    TL-16914   +   Added contextual details to the notification about broken audience rules

                   Additional information about broken rules and rule sets are added to email notifications. This
                   information is similar to what is displayed on audiences "Overview" and "Rule Sets" tabs and
                   contains the broken audience name, the rule set with broken rule, and the internal name of the
                   broken rule.

                   This will be helpful to investigate the cause of the notifications if a rule was fixed before
                   administrator visited the audience pages.

    TL-16909   +   Increased the limit for the defaultid column in hierarchy scale database tables

                   Previously the defaultid column in the comp_scale and goal_scale tables was a smallint, however the
                   column contained the id of a corresponding <type>_scale_values record which was a bigint. It is
                   highly unlikely anyone has encountered this limit, unless there are more than 32,000 scale values on
                   your site, however the defaultid column has been updated to remove any possibility of a conflict.

    TL-16893       Removed unused content options from the program report source

                   The program report source's "Hide currently unavailable content" setting had no effect and has been
                   removed. The code governing the setting has also been deprecated. The functionality it previously
                   offered is already provided by the Report Builder's visibility controls and capabilities relating to
                   this.

    TL-16150       Added image for course and program tiles in featured links
    TL-16149       Added the ability to have images associated with courses, programs and certifications

                   This improvement saw three notable changes made:

                   1) An image can now be set for courses, programs, and certifications via their respective settings pages.
                   2) An out of the box default image has been added for courses, programs, and certifications.
                   3) The default image for courses, programs, and certifications can be overridden by an admin.

    TL-16143       Added more configuration options to the Gallery Tile in the Featured Links block

                   Options Added:
                    * Transition
                    ** Fade
                    ** Slide
                    * Order
                    ** Random
                    ** Sequential
                    * Controls
                    ** Prev/Next (Arrows on side of tile)
                    ** Position indicator (Dots at the bottom)
                    * Autoplay (Whether the gallery tile should automatically move)
                    * Repeat (If the tile should go back to the start when it gets to the end)
                    * Pause on hover (if hovering over the tile then it will stop moving)

                   The switcher.js JavaScript that changes the gallery tile has been rewritten to use the 3rd party
                   library Slick. This caused large changes to the structure of the html as Slick added a number of
                   elements.

    TL-16140       Added the ability for gallery tiles in the featured links block to contain other tiles

                   Gallery tile content is now based on other tiles rather than a set of images. Each tile in a gallery
                   tile still has all the normal configuration and visibility associated with it, along with an
                   additional meta tile interface for any tile that can contain other tiles. This is so that meta tiles
                   can define that they cannot contain other meta tiles. There is a new database column for parentid
                   added to the block_totara_featured_links_tiles table, this remembers the relationship between the
                   gallery tile and sub tiles.

                   Note: If there are any custom tiles based on the gallery tile then there is a high probability that
                   they will no longer work as they used to, as the templates and structure has changed.

    TL-16139       Added the ability to add icons into static tiles in the featured links block

                   In the edit content form of a featured links block, there is now an option to select an icon that
                   will show in the background at various sizes. The available icons are all from the themes that have
                   been installed.

    TL-14114   +   Added support for Google ReCaptcha v2

                   Google deprecated reCAPTCHA V1 in May 2016 and it will not work for newer
                   sites. reCAPTCHA v1 is no longer supported by Google and continued
                   functionality can not be guaranteed.

    TL-13987   +   Improved approval request messages sent to managers for Learning Plans

                   Prior to this fix if a user requested approval for a learning plan then a message was sent to the
                   user's manager with a link to approve the request, regardless of whether the manager actually had
                   permission to view or approve the request. This fix sends more appropriate messages depending on the
                   view and approve settings in the learning plan template.

    TL-12955       Added a dynamic audience rule for user's authentication method
    TL-12620   +   Automated the selection of job assignments upon a users assignment to an appraisal when possible

                   When an appraisal is activated or when learners are dynamically or manually added to an active
                   appraisal, a learner's job assignment is now automatically linked to their appraisal assignment.
                   Before this change, the learner had to open the appraisal for this to happen.

                   This will only come into effect if the setting "Allow multiple job assignments" is turned OFF.

                   If a user has multiple job assignments, this automatic assignment will not apply. If a user has no
                   job assignment, an empty job assignment will still be automatically created.

    TL-12393   +   Added new system role filter for reports using standard user filters
    TL-12253       Removed completionstartonenrol setting from course settings screen
    TL-10651       HR Import now handles empty fields consistently

                   Empty fields being imported into HR Import were inconsistently handled across field types, sources
                   and elements. This makes changes to introduce consistency so if a field is left empty in the CSV or
                   database then it will delete the existing data (except if the "Empty string behaviour in CSV"
                   setting is set to "Empty strings are ignored").

                   The main change in behaviour is with empty fields when custom fields are included in the import.
                   Prior to this patch custom fields would sometimes not be erased when an empty field was imported.
                   These should now be erased correctly (for CSV this is only when "Empty strings erase existing data"
                   is set).

    TL-8092    +   Added a 'Date Completed' filter to the program overview report source
    TL-7918    +   Added a new dynamic audience rule for user's certification status
    TL-6152    +   Added an RPL note column to the course completion report source

                   A new column "RPL note" has been added to the Course completion report source.
                   This column contains the note provided when users were manually awarded an RPL completion.
                   If it is not an RPL completion, or if no note was provided then the column will be empty.
                   The new column was added to the course completion report source only.

    TL-4186    +   Improved the calculation and display of program and certification progress

                   The calculation of a user's progress towards completion of a program or certification has been
                   improved to take progress of all involved courses into consideration. This progress is now
                   displayed as a true percentage in a progress bar.


Bug fixes
---------

    TL-19682       Fixed populating the default values when editing an existing default tile in featured link gallery
    TL-19673       Fixed an error preventing the creation of course tiles within a featured links block

                   Prior to this patch: when user was adding a new course tile to a gallery featured link, there would
                   be an exception thrown, due to function not found.

                   With this patch: given the same scenario, user will be able to add a new course tile into a gallery
                   featured link.

    TL-19625       Fixed an error when previewing an appraisal

                   Prior to this patch: when user previewed an appraisal, the system will throw a warning message
                   stating that the data was not populated properly (it only happened if $CFG->debug is being set to E_ALL)

                   With this patch, given the same scenario, the data is being populated with the default value and
                   system will not throw any warnings.

    TL-19617       Fixed display failure message on sign up page when user is trying to book for a session that is in a past
    TL-19606       Fixed scalability of add block popover with browser minimum fonts

                   Fixed the add block pop-over to display it's content correctly when a reasonable browser minimum
                   font size has been set.

    TL-19600       Improved the display of the certification due soon message
    TL-19562       Fixed theme style overrides on admin navigation menu

                   The theme style overrides are now consistent on both the top level navigation & the admin expanded menu.

    TL-19350       Fixed an issue with hierarchy field mapping in HR Import
    TL-19334       Removed unused coursetagging admin setting

                   Course tagging has been controlled since the general enable tags setting as of Totara 9.0.
                   The setting was missed in the clean up and remained in the product but did nothing.
                   It has now been removed.

    TL-19325       Fixed enabling/disabling antivirus plugins
    TL-19311       Added event's observers for course restoring to update the course format

                   Prior to this patch, when restoring the course, there is no action on
                   updating the course's activities base on its format.

                   After this patch, the course's activities will be updated, via the event's
                   observer

    TL-19302       Navigation on audiences pages is now consistent across them all

                   Multilang support was fixed on all pages at the same time.

    TL-19157       Removed popper.js source map path

                   The popper.js library included a path to a non-existent source map which caused a warning message in
                   the browser console.

    TL-19129       Reduced space between Totara menu & page content
    TL-19043       Fixed php undefined property notice in assignment grading when changing 'Enrolment ends' to a date in the past
    TL-19026       Changed the date format of seminar report builder Dates and Times related columns report source

                   Previously the report columns 'Event created', 'Last Updated', 'Sign-up Period', 'Sign-up Start
                   Date', 'Sign-up End Date', 'Cancellation date', 'Time of sign-up', 'Event Start time', 'Event finish
                   time' and 'Approval time' were formatted differently than the 'Session Start' and 'Session Finish'
                   columns. These columns are now formatted consistently.

    TL-18904       Fixed up the context level of the totara/contentmarketplace:add capability

                   It now shares the same configuration as the moodle/course:create capability.

                   Coding style within the component and single plugin was tidied up at the same time.

    TL-18746       Fixed performance by removing multiple course_in_progress event triggers

                   Performance is improved by removing multiple course_in_progress event triggers when activity or
                   course completion is triggered.

                   Event \core\event\course_in_progress was triggered every time when
                   completion_completion::mark_in_progress() was called. Now this event is triggered only once per user
                   enrolment (when timestarted is not yet set). This is a change in behaviour since events will not be
                   triggered anymore. This behaviour will affect sites that have callbacks assuming that
                   course_in_progress will be fired each time when mark_in_progress is called.

    TL-18727       Fixed galleries in the featured links block not being reinstated after update
    TL-18706       Fixed the incompatible version message shown when attempting to restore an old backup

                   The "This backup file has been created with Totara ..." error message was incorrectly referring to
                   Moodle version instated of Totara version

    TL-18615       Removed duplicated options in the 'Show with backdrop' selector on the add new step form in user tours
    TL-18569       Removed 'export to portfolio' links from assignment grading interfaces

                   The 'export to portfolio' functionality is designed for a user to export their own assignment
                   submissions to their portfolio. The link was being shown to trainers in the grading interface but
                   displayed an error if it was clicked.

    TL-17919       Fixed the display of the main region in core themes
    TL-17852       onchange Totara form actions now support comparing against arrays
    TL-17725       Fixed display issue when selecting a course icon

                   When selecting a course icon, if the last icon in a row was selected, the first icon in the
                   following row previously appeared directly below the selected icon.

    TL-17652       Removed 'Update activities' checkbox from seminar notification template form when new customer notification template is added
    TL-17645       Mustache esc helper now supports full mustache syntax
    TL-17632       Ensured that recursion in mustache helpers is prevented when debugging is off
    TL-17417       Fixed an issue with links not being generated correctly within the totara_message component

                   This was primarily an issue with the "more details" link in messages sent when commenting on a
                   user's learning plan.

    TL-14015       Deprecated unused totara/core/js/goal.item.js file

Upstream improvements from Moodle
---------------------------------

    TL-19399       MDL-62497: Protect against QuickForm remote code execution

                   This vulnerability had already been fixed in a previous Totara patch (see TL-18491 from previous
                   releases of Totara).

                   An additional fix was added from this set of Moodle fixes which ensures that the Feedback module
                   uses the QuickForm API correctly and safely, making sure that type checking of values is done as
                   specified.

    TL-19396       MDL-62880: Dropped support for legacy question import format
    TL-19392       MDL-63101: Improved accuracy of cache event invalidation
    TL-19387       MDL-63050: Made session check compatible with Redis 4.0

    TL-18944       MDL-53848: Added hideIf functionality to Moodle forms

                   Elements can now be hidden based on the value of another element. Usage matches that of the
                   disabledIf functionality that was already available in the Moodle forms.

    TL-18662       MDL-62210: Improved validation when exporting assignments to portfolio
    TL-18661       MDL-62232: Improved validation when exporting forum attachments to portfolio

                   Validation has been added in a previous Totara patch. This aligns it with Moodle's solution for compatibility.

    TL-18660       MDL-62233: Added validation on callback class when exporting to portfolio

                   Validation had been applied to the callback class in a previous Totara patch. This adds the Moodle
                   solution for compatibility.

    TL-18656       MDL-62790: Added capability check in core_course_get_categories for Web Service
    TL-18655       MDL-62820: Made sure questions text is properly encoded before display after question bank import
    TL-18539       MDL-62200: Prevented modals from adding another backdrop when being loaded in from another modal
    TL-18469       MDL-60793: Fixed compatibility issue with MySQL 8

                   The chat module used a database field where the name is a reserved word in
                   MySQL 8. This could have caused errors during some database operations. The
                   field has been renamed.

    TL-18301       MDL-61905: Removed unused Workshop tables from database

                   A number of tables that were used by the Workshop module in versions 1.1 and earlier have been kept
                   but unused since upgrading to version 2.0. Those tables were suffixed with '_old'.

                   If your installation was originally a Moodle or Totara version 1.x, we recommend confirming whether
                   these tables may contain data that should be kept before upgrading as these tables will be dropped.

    TL-18298       MDL-61309: Implemented a new deleted flag for forum posts and adapted userdata purging to use it

                   A new 'deleted' column for forum posts was introduced. Now deleted posts and discussions display a
                   placeholder instead of the original text. Purging of user data was modified to set the new deleted
                   flag and empty the title, and body, of the forum posts and discussions. Previously the title and
                   body were replaced by a placeholder instead of dynamically showing it.

    TL-18270       MDL-59453: Fixed filtering of lesson content in external functions
    TL-18267       MDL-59649: Fixed type of content exporter field to the correct value
    TL-18266       MDL-59627: Fixed data_search_entries function in the database module wasn't calculating total count correctly
    TL-18265       MDL-59619: Fixed get_fields Web Services not working properly if database has no fields
    TL-18260       MDL-59532: Fixed check_update callback failing when the activity uses separated groups
    TL-18252       MDL-59820: Removed unnecessary CSS class on calendar

                   The course selector now uses the standard HTML/CSS as used by other single
                   selects.

    TL-18240       MDL-60485: Fixed being able to change grade types when grades already exist
    TL-18233       MDL-60104: Fixed SCORM description text to no longer extend outside the page
    TL-18231       MDL-60433: Fixed users being able to view all groups even if they were not allowed to
    TL-18229       MDL-60789: Added length validation rule for a workshop title submission
    TL-18228       MDL-60741: Refactored admin purge caches page to call admin_externalpage_setup first
    TL-18227       MDL-60693: Added multilang filter to activity titles in course backup and restore
    TL-18226       MDL-60675: Fixed an exception in single selects without a default value
    TL-18224       MDL-59876: Fixed the Web Service user preference name field type
    TL-18222       MDL-60810: Removed string referencing PostNuke from auth/db
    TL-18221       MDL-60809: Fixed missing filelib include in XML-RPC function
    TL-18220       MDL-60773: Added pendingJS checks for autocomplete interactions
    TL-18219       MDL-60637: Removed unnecessary group id number validation on Web Services
    TL-18216       MDL-60253: Ensured both LTI ToolURL and SecureToolURL are used for automatic matching
    TL-18215       MDL-60187: Ensured grade items are not created when grades are disabled

                   When editing LTI titles inline, it makes it appear in the Gradebook even if the privacy option
                   'Accept grades from the tool' is disabled.

    TL-18213       MDL-58817: Ensured LTI icons are not overwritten by cartridge params
    TL-18212       MDL-56253: Added multilang support to course module name in grades interface
    TL-18211       MDL-55808: Fixed glossary entries search not working with ratings enabled
    TL-18210       MDL-27886: Fixed handling of course backup settings and dependencies

                   The dependency of backup settings was not working properly. If a default setting was disabled (not
                   locked) then the dependent settings in the backup were locked and could not be changed as expected.
                   The check for locked dependencies has been changed to fix this.

    TL-18208       MDL-60838: Fixed Solr files upload to honour timeout restrictions
    TL-18207       MDL-60738: Fixed Web Service theme and language parameters not being cleaned properly
    TL-18206       MDL-60669: Fixed duplicate entry issue when restoring forum subscriptions
    TL-18205       MDL-60591: Fixed forum inbound processor discarding the inline images if a message contains quoted text
    TL-18204       MDL-60249: Ensured feedback comments text area is resizeable
    TL-18203       MDL-60188: Implemented cache for user's groups and groupings
    TL-18201       MDL-57569: Fixed a large badge image being unaccessible for the future use
    TL-18199       MDL-46768: Loosened the restriction on the badge name filter to allow quotes
    TL-18198       MDL-45068: Improved group import code, prevented PHP displaying notices and warning for certain CSV files
    TL-18197       MDL-27230: Ensured that changes to Quiz group overrides are reflected in the calendar
    TL-18196       MDL-24678: Fixed a race condition in the chat activities leading to multiple messages being returned as the latest message
    TL-18192       MDL-60801: User defaults are now applied when uploading new users
    TL-18191       MDL-60443: Improved validation error message when a requested data format does not exist
    TL-18190       MDL-60219: The 'no blocks' setting in an LTI activity now uses the 'incourse' page layout with blocks disabled
    TL-18188       MDL-37757: Added missing clean up external files on removal of a repository
    TL-18187       MDL-34161: Fixed LTI backup and restore to support course and site tools and submissions
    TL-18181       MDL-60945: Stopped unneeded completion data being retrieved in Web Service function
    TL-18178       MDL-59866: Added retries for connecting to Redis in the session handler before failing
    TL-18174       MDL-56864: Fixed removal of tags if usage of standard tags is set to force
    TL-18171       MDL-54021: Fixed an issue where "Course completion status" block didn't show activity name in correct language
    TL-18169       MDL-45500: Enabled ability to uninstall grading plugins
    TL-18168       MDL-44667: Fixed minor field existence checks in three plugins

                   The following three plugins each had one call to a database function that was attempting to validate
                   the existence of the field incorrectly. The affected plugins were:
                   * Assignment file submission
                   * Assignment online text submission
                   * Multi-answer question type

    TL-18166       MDL-40790: Fixed Lesson content button to no longer run off the edge of the page
    TL-18165       MDL-61045: Made sure the 'After the quiz is closed' review option is disabled if the quiz does not have a close date
    TL-18164       MDL-61042: Fixed undefined variable error when viewing detailed statistics report on empty lesson
    TL-18163       MDL-61040: Improved spacing around the "Remove my choice" link within a choice activity
    TL-18162       MDL-61022: Added acceptance test for user groups restore functionality
    TL-18161       MDL-60938: Fixed the rendering of users in the choice activity responses table
    TL-18160       MDL-60767: Fixed a visual bug causing validation errors to not be shown when saving changes to several admin settings in a single action
    TL-18159       MDL-60653: Fixed the incorrect indentation of navigation nodes when their identifier happened to be an integer
    TL-18156       MDL-60161: Ensured that OAuth curl headers are only ever sent once
    TL-18155       MDL-59999: Added a status column to the Essay question grading interface within Lesson
    TL-18154       MDL-59709: Fixed export to portfolio button in assignment grading interface for Online Text submissions
    TL-18153       MDL-59200: Fixed an issue where a user is unable to enter assignment feedback after grade override

                   Fixes an issue where a user would be unable to enter assignment feedback after grade override and if
                   there was no original assignment grade set.

    TL-18152       MDL-58888: Added sort-order for choice_get_my_response() results by optionid
    TL-18150       MDL-57431: Shuffle question help icon in Quiz is now outside the HTML label
    TL-18149       MDL-54967: Fixed IMS Common Cartridge import incorrectly decoded html entities in URLs
    TL-18148       MDL-52100: Fixed filearea to not delete files uploaded by users without file size restrictions
    TL-18147       MDL-49995: Fixed overwriting of files to not leave orphaned files in the system
    TL-18146       MDL-42676: Fixed issue that prevented assignment submissions when grade override was used
    TL-18145       MDL-34389: Fixed users with capability 'moodle/course:changecategory' were able to only select current course category and not its subcategories
    TL-18144       MDL-31521: Fixed calculated questions were displaying a warning when more than one unit with multiplier equal to 1
    TL-18143       MDL-60942: Fixed format_string doesn't account for filter in static cache key
    TL-18139       MDL-58983: Fixed display of grade button in assignments when user doesn't have capability

                   The "grade" button is now hidden if a user doesn't have the capability to grade assignments.

    TL-18138       MDL-51089: Improved accessibility when accessing the 'add question' action menu
    TL-18137       MDL-43827: Improved accessibility when editing uploaded files on the server
    TL-18136       MDL-33886: Added graceful error handling when backup filename is too long
    TL-18135       MDL-61107: Made sure invalid maximum grade input is handled correctly in quiz activity
    TL-18134       MDL-57727: Fixed Activity completion report to have a default sort order
    TL-18132       MDL-23887: Replaced deprecated System Tables calls to System Views calls in sql generator for MSSQL
    TL-18130       MDL-61098: Fixed trainers ability to edit or delete WebDav repositories that they have created at a course level
    TL-18129       MDL-61068: Changed rounding for timed forum posts to the nearest 60 seconds to ensure all neighbouring posts are correctly selected
    TL-18127       MDL-60943: Improved error message for preg_replace errors during global search indexing
    TL-18126       MDL-60742: Allow customisation of 12/24h time format strings
    TL-18125       MDL-60415: Fixed error messages in LTI launch.php when custom parameters are used
    TL-18124       MDL-60079: Fixed 'User tours' leaving unnecessary aria tags in the page
    TL-18123       MDL-57786: Fixed word count for online text submission in assignment module
    TL-18122       MDL-53985: Prevented assignment PDF annotations being removed when a submission is revert back to draft
    TL-18121       MDL-43042: Improved layout of multichoice question response in a lesson
    TL-18117       MDL-61010: Added unread posts link for the counter in "Blog-like" forum which takes a user to the first unread post in the discussion
    TL-18116       MDL-60776: Fixed error in enrolled users listing when custom fullnamedisplay format contains a comma
    TL-18115       MDL-60549: Ensured LTI return link works when content is outside of an iframe
    TL-18114       MDL-55382: Changed quicklist order to be alphabetical when annotating File submission assignments
    TL-18113       MDL-37390: Set course start date when a course is approved to the user's midnight
    TL-18112       MDL-61234: Fixed race condition in user tours while resolving the fetchTour promise
    TL-18111       MDL-61224: Added length validation for short name when creating a role
    TL-18109       MDL-61077: Made quiz statistics calculations more robust
    TL-18108       MDL-60918: Made sure current user is used in message preference update
    TL-18107       MDL-60181: Glossary ratings are now displayed in their entry

                   Previously the entry appeared to be in the following glossary entry.

    TL-18105       MDL-58006: Fixed blind marking status not being reset by course reset in assignment module
    TL-18102       MDL-61253: Fixed referenced files were not added to archive when trying to download a folder
    TL-18101       MDL-61250: Omitted leading space in question preview link
    TL-18098       MDL-60997: Added replytoname property to the core_message class allowing to specify "Reply to" field on outgoing emails
    TL-18097       MDL-60646: Fixed undefined string when managing a user's portfolio
    TL-18096       MDL-60077: Fixed the display of the pop-up triangle next to rounded corners in User Tours
    TL-18092       MDL-61251: Corrected a message to 'Enable RSS feeds' to point to the proper settings section
    TL-18091       MDL-61168: Prevented the 'Export to portfolio' button from getting truncated by collapsed online text submissions

                   When a long 'Online Text' submission is made the entry is truncated and is expandable. The 'Export
                   to portfolio' button, if enabled, was also being truncated. Only the submitted text is truncated now.

    TL-18090       MDL-61027: Fix an issue with datetime profile fields when using non-Gregorian calendars
    TL-18088       MDL-52832: Fixed an issue where quiz page did not take user/group overrides into account when displaying the quiz close date
    TL-18087       MDL-51189: Fixed an issue in the quiz module where trainers were unable to edit override if quiz was not available to student
    TL-18086       MDL-42764: Added missing error message for user accounts without email address
    TL-18081       MDL-61344: Added display of additional files when adding submissions in assignment module
    TL-18080       MDL-61305: Added a lock to prevent 'coursemodinfo' cache to be built multiple times in parallel

                   To reduce impact on the performance, the building of the coursemodinfo cache cannot happen in
                   parallel anymore. There's now a database lock in place to prevent that.

    TL-18079       MDL-61236: Fixed bug where course welcome message email was not sent from the course contact who was first assigned the role of trainer
    TL-18078       MDL-61153: Made lesson detailed statistics report column widths consistent
    TL-18077       MDL-61150: Corrected wrong "path" attribute in some core install.xml files
    TL-18076       MDL-56688: Fixed the order of grade items in single view and export of the Gradebook

                   All views of grade items now show in the order set in the Gradebook setup.

    TL-18074       MDL-61408: Added default button class when checking quiz results
    TL-18073       MDL-61324: Fixed detection of changed grades during LTI sync

                   Improved the detection of changed grades during LTI sync so that unchanged grades are not synced
                   every time the grade sync task is run anymore.

    TL-18072       MDL-61289: Fixed choice activity didn't include extra user profile fields on export
    TL-18071       MDL-61005: Fixed an issue in which system level audiences were potentially excluded when searching audiences in some interfaces
    TL-18070       MDL-58845: The Choice activity report for reviewing answers now respects the 'Display unanswered questions' setting
    TL-18069       MDL-61480: Added a check to ensure plugins are installed within get_plugins_with_function()
    TL-18065       MDL-61453: Fixed accepted file type when uploading user pictures

                   When uploading multiple user pictures, the list of accepted file types for the file picker was not
                   limited to ZIP only. This has been fixed. Attempts to upload non-ZIP files led to an error message.

    TL-18064       MDL-61322: The time column within the log and live log reports now displays the year as part of the date
    TL-18061       MDL-61196: Ensured activity titles are correctly formatted when included in the subject for notifications
    TL-18060       MDL-60658: Fixed validation of the 'grade to pass' activity setting to ensure that localisations are correctly handled
    TL-18058       MDL-55153: Fixed an issue with customised language strings that have been removed still showing up in language customisation interface
    TL-18057       MDL-36157: Fixed HTML entities in RSS feeds that were not displayed correctly
    TL-18051       MDL-61261: Added validation for requests to 'Open badges' backpack to prevent possible self-XSS
    TL-18050       MDL-60398: Fixed an issue with downloading resource of type "Folder" with name of 200+ bytes
    TL-18049       MDL-60241: Fixed visible value of general section in course

                   On upgrade to Moodle 3.3 it was possible that the general section of a course was set to visible =
                   0. Even if this has no effect in Totara this patch reverts this and sets all general sections back
                   to visible = 1.

    TL-18048       MDL-59070: Fixed enrol database plugin bug where the 'enablecompletion' value was not loaded
    TL-18047       MDL-61658: Fixed display of user's country in course participant list and 'Logged in user' block

                   If a country was excluded from the setting 'allcountrycodes', the country code was not translated to
                   the country name in the 'Logged in user' block and on the course participants list.

    TL-18044       MDL-58179: Converted uses of "label" CSS class to "mod_lesson_label"

                   Bootstrap causes HTML elements with the CSS class to have white text. As a result text was not being
                   displayed correctly. This change only affects the lesson activity module.

    TL-18043       MDL-52989: Fixed question clusters occasionally displaying a blank page when a student restarts half way through
    TL-18041       MDL-61733: Fixed creation of tables in Atto editor for Database activity templates
    TL-18040       MDL-61656: Fixed missing role name on the security report for incorrectly defined front page role
    TL-18039       MDL-61576: Ensured the lti_build_custom_parameters function contains all necessary parameters
    TL-18038       MDL-61328: Fixed the sorting of User tours steps when moving steps up or down
    TL-18037       MDL-61321: Fixed a bug in mod_feedback_get_responses_analysis Web Services preventing return of more than first 10 feedback responses
    TL-18036       MDL-61257: Fixed the 'Course module completion updated' link in the course log report

                   The link was previously pointing to the course completion report instead of the activity completion
                   report, this has been fixed.

    TL-18034       MDL-60762: tool_usertours blocks upgrade if admin directory renamed
    TL-18033       MDL-55532: Fixed a hard-coded reference to the admin directory within the User tours tool
    TL-18027       MDL-61689: Unexpected and unhandled output during unit tests will now result in the tests being marked as Risky
    TL-18026       MDL-61522: Made sure glossary paging bar links do not use relative URLs
    TL-18025       MDL-61502: Added a test for multi-lingual "Select missing words" questions
    TL-18023       MDL-61163: Fixed a bug preventing guest users from viewing Wiki pages belonging to Wiki activities added to the page
    TL-18022       MDL-61127: Added improved keyboard navigation when using the file picker
    TL-18021       MDL-61020: Fixed Video.js media player timeline progress bar being flipped in RTL mode
    TL-18020       MDL-60726: Fixed alignment of assignment submission confirmation message
    TL-18019       MDL-60115: Fixed a silently failing redirect when creating a new book resource
    TL-18017       MDL-61860: Fixed require path for config.php on authentication test settings page
    TL-18016       MDL-61581: Added styling to the 'returning to lesson' navigation buttons
    TL-18014       MDL-61129: Added 'colgroup' attribute to the survey question tables
    TL-18013       MDL-61033: Fixed an error when editing a quiz while a preview is open in another browser window
    TL-18012       MDL-60196: Fixed the display of custom LTI icons
    TL-18010       MDL-58697: Fixed issue with assignment submission when toggling group submission

                   When assignment submission was set to group submission and then turned off, the status was not
                   showing an assignment as submitted even if there was a file submitted. The group assignment status
                   is now only considered if group assignment submission is enabled.

    TL-18009       MDL-61741: Fixed the IPN verification endpoint URL of the Paypal Enrolment plugin
    TL-18008       MDL-61708: Fixed LTI to respect fullnamedispaly settings for fullname field in the requests
    TL-18006       MDL-61928: Made frozen form sections collapsible an expandable
    TL-18003       MDL-61520: Fixed references to xhtml in Quiz statistics report
    TL-18002       MDL-61348: Fixed incorrect group grade averages in quiz reports
    TL-18001       MDL-59857: Increased the length of the 'completionscorerequired' field in SCORM database table
    TL-17999       MDL-62042: Filtered out some unicode non-characters when building index for Solr
    TL-17997       MDL-62011: Fixed an issue where approval of a course request fails if a new course with the same name has been created prior to request approval
    TL-17996       MDL-61715: Fixed Question type chooser displaying headings for empty sections under certain conditions
    TL-17995       MDL-60882: Prevent deletion of all responses if the external function delete_choice_responses() is called without responses specified

                   The external function mod_choice_external::delete_choice_responses has changed behaviour - if this
                   function is called by a user who has the 'mod/choice:deleteresponses' capability with no responses
                   specified then only the user's responses will be deleted, rather than all responses for all users
                   within the choice. To delete all responses from all users, all response IDs must be specified.

    TL-17993       MDL-61012: Allow module name to be guessed only if not set by subclass of the moodleform_mod class
    TL-17990       MDL-61800: Reset the OUTPUT and PAGE for each task on cron execution
    TL-17989       MDL-61521: Fixed missing text formatting for category name in get_categories Web Service
    TL-17985       MDL-62500: Fixed an issue where a checkbox label wasn't updated after updating a tag
    TL-17983       MDL-62408: Fixed profile_guided_allocate() function to help split behat scenarios better for parallel runs never being executed in behat_config_util
    TL-17981       MDL-62588: Added missing instanceid database field to the Paypal enrolment plugin
    TL-17337   +   MDL-61392: Improved the IPN notifications handling in Paypal enrollment plugin
    TL-17335   +   MDL-61269: Set composer license to GPL-3.0-or-later
    TL-17326   +   MDL-60436: Improved the performance of block loading
    TL-17089   +   MDL-58699: Improved the security of the quiz module while using browser security settings

                   When the "Browser Security" setting is set to "Full screen pop-up with some JavaScript security",
                   the "Attempt quiz" button is no longer visible if a user has JavaScript disabled.

    TL-17083   +   MDL-59858: After closing a modal factory modal, focus goes back to the element that triggered it.
    TL-17058   +   MDL-60535: Improved style of button when adding questions from a question bank to a quiz
    TL-17057   +   MDL-51892: Added a proper description of the login errors
    TL-17055   +   MDL-60571: Styled "Save and go to next page" as a primary button when manually grading quiz questions
    TL-17053   +   MDL-36580: Added encryption of secrets in backup and restore functionality

                   LTI (external tool) activity secret and key are encrypted during backup and decrypted during restore
                   using aes-256-cbc encryption algorithm. Encryption key is stored in the site configuration so
                   backup made with encryption will be restored with lti key and secret on the same site, and without
                   these values on different site.

    TL-17050   +   MDL-60489: Content height changes when using the modal library are now smooth transitions
    TL-17043   +   MDL-60449: Various language strings improvements in courses and administration
    TL-17013   +   MDL-54540: Added allowfullscreen attribute to LTI iFrames to ensure the full screen can be used

                   This change adds attributes to the LTI iframe allowing the content to be viewed in full screen.

    TL-16995   +   MDL-35849: Added "alert" role HTML attribute to the log in errors

                   This allows screen readers to identify when a user has not logged in correctly

    TL-15708       MDL-59132: Fixed anonymous response numbering in feedback Web Service
    TL-15684       MDL-58857: User session is now terminated when a major upgrade is required
    TL-15682       MDL-58860: Fixed Web Service mod_lesson_get_attempts_overview when no attempts made
    TL-15639       MDL-58659: Added enddate parameter to Web Services returning course information
    TL-15636       MDL-58681: Split the checkbox and advcheckbox behat tests

                   Advanced checkboxes cannot be tested without a real browser because Goutte does not support the
                   hidden+checkbox duality.

    TL-15635       MDL-51932: Improved UX when setting up a workshop

                   When setting up a workshop activity, the stage switch has been updated to state which stage they
                   will take you to.

    TL-15630       MDL-58415: Multiple bug fixes in the new lesson web services

                   * Avoid inappropriate http redirections
                   * Added missing answer fields
                   * Various code fixes, including ensuring correct variable types are used where necessary

    TL-15620       MDL-58412: Fixed several bugs in the new feedback web services
    TL-15619       MDL-58530: Updated the video.js library to v5.18.4
    TL-15604       MDL-58502: Fixed error when cancelling feedback
    TL-15598       MDL-58574: Removed an unnecessary check for delete icon when working with permissions in an activity module
    TL-15594       MDL-58549: Added version of jabber/XMPP libraries to thirdpartylibraries.xml
    TL-15589       MDL-58493: Converted the delete enrolment icon to a font icon

                   When managing enrolments in a course, if a role was added, the delete icon
                   was an image (instead of a font icon) before the page was reloaded. This
                   has been corrected.

    TL-15583       MDL-57573: Updated PHPmailer library to v5.2.23
    TL-15579       MDL-58552: Fixed alignment of quiz icon
    TL-15575       MDL-57553: Fixed user tour steps so that they do not inherit attributes from CSS selector

                   Updated the flexitour component to v0.10.0 and the popper.js library to v1.0.8 in the process.

    TL-15569       MDL-56632: Moved the "Turn editing on\off" link to the top of the book administration menu
    TL-15567       MDL-58311: Added support for password-protected Redis Session and Cache Store connections

                   Support for setting a password for the Redis Cache and Session Store was added. Password for the
                   cache store can be set when adding or editing the cache store instance settings.

                   The password for the Redis session store can be set with the config $CFG->session_redis_auth.

    TL-15565       MDL-58453: Refactored get_non_respondents Web Service
    TL-15564       MDL-57813: Added Web Service mod_feedback_get_last_completed
    TL-15559       MDL-58361: Made core_media_manager final to prevent from being subclassed
    TL-15558       MDL-58399: Return additional file fields in Web Services to be able to handle external repositories files

                   See mod/upgrade.txt and course/upgrade.txt for details.

    TL-15557       MDL-58444: Added number of unread posts to get_forums_by_courses  Web Services
    TL-15556       MDL-51998: Improved manage forum subscribers button
    TL-15555       MDL-57821: Added Web Service mod_feedback_get_responses_analysis
    TL-15553       MDL-53343: Migrated scorm_cron into new tasks API
    TL-15514       MDL-58265: Refactored behat to use a new step "I am on the course homepage"

                   The new step directly accesses the course page without following the path from the homepage to the
                   course. A shortcut step "I am on course homepage with editing mode on" was also added to allow
                   accessing a course and turn editing mode on.

    TL-15496       MDL-57503: Allow course ids for enrol_get_my_courses

                   This adds a new parameter for enrol_get_my_courses() to filter the list returned to specific courses.

    TL-15466       MDL-55941: Improved UX of alpha chooser / initialbar in tablelib and made it responsive
    TL-15464       MDL-48771: Improved quiz question editing interface

                   The quiz editing interface has been improved to allow selection of multiple questions to be deleted.

    TL-15461       MDL-57411: mod_check_updates now returns information based on user capabilities
    TL-15445       MDL-50970: Added new Web Service core_block_get_course_blocks
    TL-15444       MDL-57925: Implemented check_updates_since callback
    TL-15443       MDL-57924: Added new Web Service mod_data_update_entry
    TL-15442       MDL-57923: Added new Web Service mod_data_add_entry
    TL-15441       MDL-57922: Added new Web Service mod_data_delete_entry
    TL-15440       MDL-57921: Added new Web Service mod_data_approve_entry
    TL-15439       MDL-57920: Added new Web Service mod_data_search_entrie
    TL-15438       MDL-57919: Added new Web Service mod_data_get_fields
    TL-15437       MDL-57918: Added new Web Service mod_data_get_entry
    TL-15436       MDL-49409: Added new Web Service mod_data_get_entries
    TL-15434       MDL-57822: Added new Web Service mod_feedback_get_non_respondents
    TL-15433       MDL-58230: Added new Web Service mod_feedback_get_finished_responses
    TL-15432       MDL-55139: Added code coverage filter in component phpunit.xml files
    TL-15431       MDL-58070: Reworded "visible" core string used in course visibility

                   Additionally we aligned the name and value strings of the course visibility default settings.
                   Previously the value strings were different to the actual course settings.

    TL-15430       MDL-57965: Enabled gzip compression for SVG files
    TL-15428       MDL-58329: Added new Web Service mod_lesson_get_lesson
    TL-15427       MDL-57760: Added new Web Service mod_lesson_get_pages_possible_jumps
    TL-15426       MDL-57762: Added check updates functionality to the lesson module
    TL-15424       MDL-57757: Added new Web Service mod_lesson_get_user_attempt
    TL-15423       MDL-57754: Added new Web Service mod_lesson_get_attempts_overview
    TL-15422       MDL-57724: Added new Web Service mod_lesson_finish_attempt
    TL-15421       MDL-57696: Added new Web Service mod_lesson_process_page
    TL-15420       MDL-57693: Added new Web Service mod_lesson_get_page_data
    TL-15419       MDL-57688: Added new Web Service mod_lesson_launch_attempt
    TL-15418       MDL-58229: Added new Web Service get_unfinished_responses
    TL-15417       MDL-57820: Added new Web Service mod_feedback_get_analysis
    TL-15415       MDL-57818: Added new Web Service mod_feedback_process_page
    TL-15414       MDL-57817: Added new Web Service mod_feedback_get_page_items
    TL-15413       MDL-57816: Added new Web Service mod_feedback_launch_feedback
    TL-15412       MDL-57685: Added new Web Service mod_lesson_get_pages
    TL-15411       MDL-55267: Removed deprecated field datasourceaggregate
    TL-15410       MDL-57815: Added new Web Service mod_feedback_get_items
    TL-15409       MDL-57823: Implemented the check_updates callback in the feedback module
    TL-15408       MDL-57814: Added new Web Service mod_feedback_get_current_completed_tmp
    TL-15407       MDL-57916: Added new Web Service mod_data_get_access_information
    TL-15406       MDL-57811: Added new Web Service mod_feedback_view_feedback
    TL-15404       MDL-57812: Added new Web Service get_feedback_access_information
    TL-15402       MDL-57665: Added new Web Service mod_lesson_get_user_timers
    TL-15401       MDL-57664: Added new lesson Web Service get_content_pages_viewed
    TL-15398       MDL-57657: Added new Web Service mod_lesson_get_user_grade
    TL-15397       MDL-40759: Added additional Font Awesome support

                   A small number of icons have been converted to Font Awesome icons, and a number of remaining
                   locations where image icons were used have been replaced with font icons.

    TL-15396       MDL-57390: Added capabilities/permission information to Web Service forum_can_add_discussion response
    TL-15394       MDL-57648: Added new web service mod_lesson_get_questions_attempts
    TL-15393       MDL-57645: Added new web service mod_lesson_view_lesson
    TL-15392       MDL-57643: Added new Web Service mod_lesson_get_lesson_access_information
    TL-15388       MDL-50538: Added new Web Service mod_feedback_get_feedbacks_by_courses
    TL-15386       MDL-57631: Implemented scheduled task for LDAP Enrolments Sync

                   The previous CLI script has been deprecated in favour of the new scheduled task. The new task is
                   disabled by default.

    TL-15385       MDL-58109: Added check for preventexecpath in the Security Report

                   If the config value $CFG->preventexecpath is set to 'false' this will show up in the Security Report
                   as a warning.

    TL-15383       MDL-58217: Added data generators for feedback items
    TL-15382       MDL-57915: Added Web Service mod_data_view_database
    TL-15380       MDL-57914: Refactored get_databases_by_courses
    TL-15379       MDL-57975: Added HTML5 session storage.

                   This can be used by developers using the core/sessionstorage AMD module in much the same way
                   developers can use core/localstorage

                   This also adds a core_get_user_dates and userdate mustache helper.

    TL-15377       MDL-57999: Add itemname to gradereport_user_get_grade_items  Web Service
    TL-15376       MDL-57280: Added the ability to create modal types via a registry

                   More information can be found at https://help.totaralearning.com/display/DEV/Modal+registry

    TL-15375       MDL-45584: Made cache identifiers part of loaded caches
    TL-15374       MDL-57972: Added shortentext mustache helper
    TL-15371       MDL-57887: Support nginx and other webservers for logging of username in access logs

                   Support for logging usernames to webserver access logs has been extended to allow sending the
                   username as a custom header which can be logged and stripped out if needed.

    TL-15368       MDL-53978: Added extra plugin callbacks for every major stage of page render + swap user tours to use them
    TL-15366       MDL-57527: Changed course reports to use CSS instead of SVG rotation
    TL-15365       MDL-57633: Added new Web Service mod_lesson_get_lessons_by_courses
    TL-15363       MDL-57602: Added 'Granted extension' filter for grading table
    TL-15362       MDL-57619: Removed behat steps deprecated in Moodle 2.9 or earlier
    TL-15358       MDL-57687: Removed unnecessary init_toggle_class_on_click JavaScript functionality
    TL-15357       MDL-57890: Improved all get_by_courses Web Services to include the coursemodule (cmid) in the results
    TL-15356       MDL-57896: Added command line tool to read and change configuration settings in the database
    TL-15355       MDL-55476: Removed loginpasswordautocomplete option

                   The a loginpasswordautocomplete option simply appends autocomplete="off" to the password field in
                   the form. As most of the browsers dropped support for this attribute it is removed.

    TL-15354       MDL-57697: Converted survey validation JavaScript from YUI2 to AMD
    TL-15350       MDL-57586: Changed $workshop variable from protected to public in class

                   Changed $workshop from protected to public in class workshop_example_submission to make it easier
                   for renderers in themes to access data instead of retrieving it from the database.

    TL-15349       MDL-57638: Improved the handling of failed RSS feeds in the RSS block

                   Previously if the cron could not read the RSS feed configured in a block this failure was not
                   visible to the administrator in the interface. Additionally every time the block displayed it tried
                   to fetch the feeds regardless of its status.
                   With this patch the RSS blocks do not try to request the feeds if the 'skiptime' and 'skipuntil'
                   values are set. If there are failed feeds then an error message will be shown to the administrator
                   but not to a learner.

    TL-15348       MDL-56808: Removed use of eval in SCORM JavaScript files
    TL-15346       MDL-57273: Added generic exporter, persistent and persistent form classes

                   This patch adds new model classes following an active record pattern to represent, fetch and store
                   data in the database. The persistent class also provides basic validation.

                   Exporters convert objects to stdClasses. The exporter contains the definition of all properties and
                   optionally related objects.

    TL-15345       MDL-57655: Added support for the igbinary serializer in the Redis Session Handler

                   If igbinary is installed and $CFG->session_redis_serializer_use_igbinary is set to true the Redis
                   session handler uses igbinary for serializing the data.

    TL-15344       MDL-57690: Stopped loading mcore YUI rollup on each page

                   This may expose areas in custom JavaScript that use YUI modules without loading them correctly.

    TL-15343       MDL-49423: Added support for optiongroups inside admin selects
    TL-15342       MDL-50539: Added new Web Service to retrieve a list of folders from several courses
    TL-15341       MDL-50545: Added new Web Service to retrieve a list of pages from several courses
    TL-15340       MDL-56449: Provided a more detailed description of group submission problems
    TL-15339       MDL-57550: Updated advanced forum search to use AMD modules
    TL-15338       MDL-50547: Added new Web Service to retrieve a list of resources from several courses

                   Added a new Web Service which returns a list of files in a provided list of courses. If no list is
                   provided all files that the user can view will be returned.

    TL-15336       MDL-57490: Converted Select all/none functionality to use JavaScript

                   In the quiz, SCORM and lesson modules, there was some inline JavaScript handlers. These have been
                   converted to pure JavaScript event listeners.

    TL-15335       MDL-57570: Added support for the igbinary serializer in the Static Cache Store

                   If igbinary is installed the static cache store automatically makes use of it.

    TL-15333       MDL-57488: Replaced and deprecated M.util.focus_login_form and M.util.focus_login_error
    TL-15330       MDL-50542: Added new Web Service to retrieve a list of labels from several courses
    TL-15329       MDL-50549: Added new Web Service to retrieve a list of URLs from several courses
    TL-15328       MDL-57627: Added new field to forum Web Service to get tracking status of the user
    TL-15326       MDL-56519: Added linting for behat .feature files

                   The linting enforces the following rules on .feature files:
                    * Indentation (in spaces):
                    ** Feature: 0
                    *** Background: 2
                    *** Scenario: 2
                    **** Step: 4
                    **** Given: 4
                    **** And: 4
                    **** Examples: 4
                    **** Example: 6
                    * Other rules:
                    ** Feature names must be unique
                    ** Empty feature files are not allowed anymore
                    ** Feature files w/o scenarios are not allowed anymore
                    ** Partially commented tag lines are not allowed
                    ** Trailing spaces are not allowed
                    ** Unnamed features are not allowed
                    ** Unnamed scenarios are not allowed
                    ** Scenario outlines w/o examples are not allowed

    TL-15325       MDL-57572: Added support for the igbinary serializer in the Redis Cache Store

                   Added setting to switch the serializer to either the builtin php or the igbinary serialiser. The
                   igbinary serialiser stores data structures in compact binary form and savings can be significant for
                   storing cached data in Redis.

    TL-15324       MDL-57282: Deprecated the behat step "I go to X in the course gradebook"
    TL-15323       MDL-57149: Made the language import administration page compatible with Bootstrap
    TL-15322       MDL-57392: Modified external function core_course_external::get_courses_by_field to return the course filters list and status
    TL-15321       MDL-55461: Fixed placement of cursor in Atto equation editor on repeated insertions from predefined buttons
    TL-15319       MDL-44172: Removed example htaccess file
    TL-15317       MDL-57395: Added new Web Service core_course_get_updates_since
    TL-15316       MDL-57471: Deprecated init_javascript_enhancement() and smartselect code
    TL-15315       MDL-57472: Removed fix_column_widths Internet Explorer 6 hack

                   Removed old Internet Explorer 6 hack and added deprecated warnings.

    TL-15314       MDL-56581: Highlighted row when permission is overriden in a course
    TL-15312       MDL-56640: Converted single selects and URL selects to mustache templates

                   This has also deprecated the YUI auto submit JavaScript.

    TL-15311       MDL-56320: Allow uninstall of unused web service plugins
    TL-15309       MDL-57143: Removed check for Windows when using SQL Server (sqlsrv) drivers

                   When using the SQL driver for Linux there was an error message during initialisation stating that
                   the driver is only available for Windows. This is not true anymore as there is a Linux driver, thus
                   the message got removed.

     TL-15306      MDL-53814: Show question type icons when manually grading a quiz


Contributions:

    * James Voong from Catalyst - TL-17357
    * Jo Jones at Kineo UK - TL-18686, TL-18640, TL-18591
    * Joby Harding at 77 Gears Ltd - TL-19045, TL-10852
    * Michael Dunstan at Androgogic - TL-18931
    * Russell England at Kineo USA - TL-18746, TL-17149

*/
