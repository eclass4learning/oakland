@javascript @mod_facetoface @totara @totara_reportbuilder
Feature: Test the seminar events report columns
In order to test the columns
As an admin
I need to create a course, create seminar with event, create seminar event report

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username  | firstname | lastname | email                |
      | teacher1  | Terry3    | Teacher  | teacher@example.com  |
      | student1  | Sam1      | Student1 | student1@example.com |
      | student2  | Sam2      | Student2 | student2@example.com |
      | student3  | Sam3      | Student3 | student3@example.com |
      | student4  | Sam4      | Student4 | student4@example.com |
    And the following "courses" exist:
      | fullname | shortname | category | enablecompletion |
      | Course 1 | C1        | 0        | 1                |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
      | student3 | C1     | student        |
      | student4 | C1     | student        |
    And I log in as "admin"
    And I set the following administration settings values:
      | Enable restricted access | 1 |
    And I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Seminar Events"
    And I set the field "Source" to "Seminar Events"
    And I press "Create report"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Seminar" to section "1" and I fill the form with:
      | Name        | Test seminar name        |
      | Description | Test seminar description |
      | Completion tracking           | Show activity as complete when conditions are met |
      | completionstatusrequired[100] | 1                                                 |
    And I navigate to "Course completion" node in "Course administration"
    And I expand all fieldsets
    And I set the following fields to these values:
      | Seminar - Test seminar name | 1 |
    And I press "Save changes"
    And I follow "View all events"
    And I follow "Add a new event"
    And I click on "Edit session" "link"
    And I fill seminar session with relative date in form data:
      | sessiontimezone    | Pacific/Auckland |
      | timestart[day]     | -1               |
      | timestart[month]   | 0                |
      | timestart[year]    | 0                |
      | timestart[hour]    | 0                |
      | timestart[minute]  | 0                |
      | timefinish[day]    | 0                |
      | timefinish[month]  | 0                |
      | timefinish[year]   | 0                |
      | timefinish[hour]   | 0                |
      | timefinish[minute] | -30              |
    And I press "OK"
    And I press "Save changes"
    And I click on "Attendees" "link"
    And I click on "Add users" "option" in the "#menuf2f-actions" "css_element"
    And I click on "Sam1 Student1, student1@example.com" "option"
    And I press exact "add"
    And I wait "1" seconds
    And I click on "Sam2 Student2, student2@example.com" "option"
    And I press exact "add"
    And I wait "1" seconds
    And I click on "Sam3 Student3, student3@example.com" "option"
    And I press exact "add"
    And I wait "1" seconds
    And I click on "Sam4 Student4, student4@example.com" "option"
    And I press exact "add"
    # We must wait here, because the refresh may not happen before the save button is clicked otherwise.
    And I wait "1" seconds
    And I press "Continue"
    And I press "Confirm"
    And I log out

  Scenario: Test Viewer's status seminar events report column
    Given I log in as "admin"
    And I navigate to "Manage user reports" node in "Site administration > Reports"
    And I follow "Seminar Events"
    And I switch to "Columns" tab
    And I add the "Viewer's status" column to the report
    And I click on "Reports" in the totara menu
    And I follow "Seminar Events"
    And I should see "Test seminar name" in the "Course 1" "table_row"
    And I should see "Course 1" in the "Test seminar name" "table_row"
    And I should see "Not set" in the "Test seminar name" "table_row"
