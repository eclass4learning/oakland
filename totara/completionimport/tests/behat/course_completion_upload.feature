@totara @totara_completion_upload @totara_courseprogressbar @javascript @_file_upload
Feature: Verify course completion data can be successfully uploaded.

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname  | lastname  | email                |
      | learner1 | Bob1       | Learner1  | learner1@example.com |

    And the following "courses" exist:
      | fullname | shortname | idnumber |
      | Course 1 | C1        | 1        |
      | Course 2 | C2        | 2        |

  Scenario: Verify an empty course completion upload fails.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records with data errors - these were ignored"

  Scenario: Verify an course completion with no username fails.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1a.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records with data errors - these were ignored"
    And I should see "1 Records in total"
    And I follow "Course import report"
    Then I should see "Blank user name" in the "1" "table_row"

  Scenario: Verify a successful course completion with no courseshortname.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1b.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

  Scenario: Verify a successful course completion with no courseidnumber.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1c.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

  Scenario: Verify an course completion with no completiondate fails.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1d.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records with data errors - these were ignored"
    And I should see "1 Records in total"
    And I follow "Course import report"
    Then I should see "Blank completion date" in the "1" "table_row"

  Scenario: Verify an course completion with no grade fails.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1e.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records with data errors - these were ignored"
    And I should see "1 Records in total"
    And I follow "Course import report"
    Then I should see "Blank grade" in the "1" "table_row"


  Scenario: Verify a successful course completion upload.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_2.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records created as evidence"
    And I should see "2 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

    When I follow "Other Evidence"
    And I click on "Completed course : thisisevidence" "link" in the "tbody" "css_element"
    Then I should see "Completed course : thisisevidence"
    And I should see "Course ID number : notacourse"
    And I should see "Grade : 100"
    And I should see "Date completed : 1 January 2015"

    # As admin I am able to edit the evidence.
    And "Edit details" "button" should exist
    When I click on "Edit details" "button"
    Then I should see "Edit evidence"
    And I log out

    # As the learner I should not be able to edit the evidence.
    When I log in as "learner1"
    And I click on "Record of Learning" in the totara menu
    And I follow "Other Evidence"
    And I click on "Completed course : thisisevidence" "link" in the "tbody" "css_element"
    Then "Edit details" "button" should not exist

  Scenario: Verify a successful course completion upload specifying custom fields to store evidence.
    Given I log in as "admin"
    # Create a datetime custom field to store the evidence date completed.
    When I navigate to "Evidence custom fields" node in "Site administration > Learning Plans"
    And I set the field "Create a new custom field" to "Date/time"
    And I set the following fields to these values:
      | Full name  | CUSTOM - Date completed  |
      | Short name | customdatetime1          |
    And I press "Save changes"
    Then I should see "CUSTOM - Date completed"
    # Create a textarea custom field to store the evidence description.
    When I set the field "Create a new custom field" to "Text area"
    And I set the following fields to these values:
      | Full name     | CUSTOM - Description  |
      | Short name    | customtextarea1       |
    And I press "Save changes"
    Then I should see "CUSTOM - Description"

    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_2.csv" file to "Choose course file to upload" filemanager
    And I set the field "Evidence field for completion date" to "CUSTOM - Date completed"
    And I set the field "Evidence field for the description" to "CUSTOM - Description"
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records created as evidence"
    And I should see "2 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

    When I follow "Other Evidence"
    And I click on "Completed course : thisisevidence" "link" in the "tbody" "css_element"

    Then I should see "CUSTOM - Date completed : 1 January 2015"
    And I should see "CUSTOM - Description :"
    And I should see "Course ID number : notacourse"
    And I should see "Grade : 100"

  Scenario: Verify a successful course completion upload without specifying custom fields to store evidence.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_2.csv" file to "Choose course file to upload" filemanager
    And I set the field "Evidence field for completion date" to "Select an evidence completion date field"
    And I set the field "Evidence field for the description" to "Select an evidence description field"
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records created as evidence"
    And I should see "2 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

    When I follow "Other Evidence"
    And I click on "Completed course : thisisevidence" "link" in the "tbody" "css_element"
    Then I should see "Completed course : thisisevidence"
    And I should not see "Course ID number : notacourse"
    And I should not see "Grade : 100"
    And I should not see "Date completed : 1 January 2015"

  Scenario: Course completions can be successfully uploaded with a file that uses CR for line endings
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_CR_line_endings.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records created as evidence"
    And I should see "2 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

    When I follow "Other Evidence"
    And I click on "Completed course : thisisevidence" "link" in the "tbody" "css_element"
    Then I should see "Completed course : thisisevidence"
    And I should see "Course ID number : notacourse"
    And I should see "Grade : 100"
    And I should see "Date completed : 1 January 2015"

  Scenario: Course completions can not be uploaded via a directory if config setting completionimportdir is not set
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I click on "Alternatively upload csv files via a directory on the server" "link"
    Then I should see "Additional configuration settings are required to specify a file location on the server. Please contact your system administrator."
    When I click on "Alternatively upload csv files via a form" "link"
    Then I should see "Choose course file to upload"

  Scenario: Verify a course completion import csv with incorrect columns shows an error
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_badcolumns.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "There were errors while importing the courses"
    And I should see "Unknown column 'badcolumn'"
    And I should see "Missing required column 'courseidnumber'"
    And I should see "No records were imported"

  Scenario: Verify a successful course completion with User participation in course is suspended
    Given I log in as "admin"
    And I am on "Course 1" course homepage
    And I enrol "Bob1 Learner1" user as "Learner"
    And I navigate to "Enrolled users" node in "Course administration > Users"
    And I click on "Edit enrolment" "link"
    And I set the field "Status" to "Suspended"
    And I press "Save changes"

    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_1b.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records successfully imported as courses"
    And I should see "1 Records in total"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    Then I should see "100%" in the "Course 1" "table_row"

  Scenario: Verify long field values are handled in the course completion upload
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/course_completion_long_fields.csv" file to "Choose course file to upload" filemanager
    And I click on "Upload" "button" in the "#mform1" "css_element"
    Then I should see "CSV import completed"
    And I should see "1 Records with data errors - these were ignored"
    And I should see "2 Records created as evidence"
    And I should see "0 Records successfully imported as courses"
    And I should see "3 Records in total"

    When I follow "Course import report"
    Then I should see "3 records shown"
    And "1" row "Errors" column of "completionimport_course" table should contain "Field 'username' is too long. The maximum length is 100"
    And "1" row "Errors" column of "completionimport_course" table should contain "Field 'courseshortname' is too long. The maximum length is 255"
    And "1" row "Errors" column of "completionimport_course" table should contain "Field 'courseidnumber' is too long. The maximum length is 100"
    And "1" row "Errors" column of "completionimport_course" table should contain "Field 'completiondate' is too long. The maximum length is 10"
    And "1" row "Errors" column of "completionimport_course" table should contain "Field 'grade' is too long. The maximum length is 10"
    And "1" row "Username to import" column of "completionimport_course" table should contain "101charsintheusernamefieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Course Shortname" column of "completionimport_course" table should contain "256charsinthecourseshortnamefieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Course ID Number" column of "completionimport_course" table should contain "101charsinthecourseidnumberfieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Completion date" column of "completionimport_course" table should contain "11chars..."
    And "1" row "Grade" column of "completionimport_course" table should contain "11chars..."
    And "2" row "Errors" column of "completionimport_course" table should contain ""
    And "2" row "Username to import" column of "completionimport_course" table should contain "learner1"
    And "2" row "Course Shortname" column of "completionimport_course" table should contain "test course 1"
    And "2" row "Course ID Number" column of "completionimport_course" table should contain "testcourse1"
    And "2" row "Completion date" column of "completionimport_course" table should contain "2015-01-01"
    And "2" row "Grade" column of "completionimport_course" table should contain "77"
    And "3" row "Errors" column of "completionimport_course" table should contain ""
    And "3" row "Username to import" column of "completionimport_course" table should contain "learner1"
    And "3" row "Course Shortname" column of "completionimport_course" table should contain "255charsinthecourseshortnamefieldsshouldpassxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx!"
    And "3" row "Course ID Number" column of "completionimport_course" table should contain "testcourse2"
    And "3" row "Completion date" column of "completionimport_course" table should contain "2015-01-01"
    And "3" row "Grade" column of "completionimport_course" table should contain "77"
    And I log out

    When I log in as "learner1"
    And I follow "Record of Learning"
    Then I should see "Record of Learning : Other Evidence"
    And I should see "2 records shown"
    And I should see "Completed course : test course 1"
    And I should see "Completed course : 255charsinthecourseshortnamefieldsshouldpassxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
