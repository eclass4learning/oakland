@enrol @javascript @totara @enrol_totara_facetoface @mod_facetoface
Feature: Admin can change default Seminar direct enrolment plugin settings
  In order to change Seminar direct enrolment settings
  As a admin
  I need to enable Seminar direct enrolment plugin

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Student   | 1        | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |

    And I log in as "admin"
    And I navigate to "Manage enrol plugins" node in "Site administration > Plugins > Enrolments"
    And I click on "Enable" "link" in the "Seminar direct enrolment" "table_row"
    And I am on "Course 1" course homepage
    And I add "Seminar direct enrolment" enrolment method with:
      | Custom instance name | Seminar direct enrolment |
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Seminar" to section "1" and I fill the form with:
      | Name        | Test seminar name        |
      | Description | Test seminar description |
      | No Approval | 1                        |
    And I follow "View all events"
    And I follow "Add a new event"
    And I click on "Edit session" "link"
    And I set the following fields to these values:
      | timestart[day]     | 1    |
      | timestart[month]   | 1    |
      | timestart[year]    | ## next year ## Y ## |
      | timefinish[day]    | 1    |
      | timefinish[month]  | 1    |
      | timefinish[year]   | ## next year ## Y ## |
    And I click on "OK" "button" in the "Select date" "totaradialogue"
    And I press "Save changes"
    And I follow "Add a new event"
    And I click on "Edit session" "link"
    And I set the following fields to these values:
      | timestart[day]     | 2    |
      | timestart[month]   | 1    |
      | timestart[year]    | ## next year ## Y ## |
      | timefinish[day]    | 2    |
      | timefinish[month]  | 1    |
      | timefinish[year]   | ## next year ## Y ## |
    And I click on "OK" "button" in the "Select date" "totaradialogue"
    And I press "Save changes"
    And I follow "Add a new event"
    And I click on "Edit session" "link"
    And I set the following fields to these values:
      | timestart[day]     | 3    |
      | timestart[month]   | 1    |
      | timestart[year]    | ## next year ## Y ## |
      | timefinish[day]    | 3    |
      | timefinish[month]  | 1    |
      | timefinish[year]   | ## next year ## Y ## |
    And I click on "OK" "button" in the "Select date" "totaradialogue"
    And I press "Save changes"
    And I log out

  Scenario: Change Enrolment displayed on course page setting from default setting to a new one
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should see "Booking open" in the "1 January" "table_row"
    And I should see "Booking open" in the "2 January" "table_row"
    And I should see "Booking open" in the "3 January" "table_row"
    And I log out

    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    And I click on "Edit" "link" in the "Seminar direct enrolment" "table_row"
    And I set the following fields to these values:
      | Enrolments displayed on course page | 2 |
    And I press "Save changes"
    And I log out

    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should see "Booking open" in the "1 January" "table_row"
    And I should see "Booking open" in the "2 January" "table_row"
    And I should not see "3 January" in the "Booking open" "table_row"