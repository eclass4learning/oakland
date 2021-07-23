@totara @totara_completion_upload @javascript @_file_upload
Feature: Verify certification completion data can be successfully uploaded.

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname  | lastname  | email                |
      | learner1 | Bob1       | Learner1  | learner1@example.com |

    And the following "certifications" exist in "totara_program" plugin:
      | fullname        | shortname | idnumber |
      | Certification 1 | Cert1     | 1        |
      | Certification 2 | Cert2     | 2        |

  Scenario: Verify a successful simple certification completion upload.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/certification_completion_1.csv" file to "Choose certification file to upload" filemanager
    And I set the field "Import action" to "Certify uncertified users"
    And I click on "Upload" "button" in the "#mform2" "css_element"
    Then I should see "Certification completion file successfully imported"
    And I should see "2 Records imported pending processing"
    And I run all adhoc tasks

    When I click on "Dashboard" in the totara menu
    Then I should see "Certification completion import successfully completed"

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    And I switch to "Certifications" tab
    Then I should see "Expired" in the "Certification 1" "table_row"

    When I follow "Other Evidence"
    And I follow "Completed certification : thisisevidence"
    Then I should see "Description :"
    And I should see "Certification Short name : thisisevidence"
    And I should see "Certification ID number : notacertification"
    And I should see "Date completed : 1 January 2015"

  Scenario: Verify a successful simple certification completion upload specifying custom fields to store evidence.
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
    And I upload "totara/completionimport/tests/behat/fixtures/certification_completion_1.csv" file to "Choose certification file to upload" filemanager
    And I set the field with xpath "(//select[@id='id_evidencedatefield'])[2]" to "CUSTOM - Date completed"
    And I set the field with xpath "(//select[@id='id_evidencedescriptionfield'])[2]" to "CUSTOM - Description"
    And I set the field "Import action" to "Certify uncertified users"
    And I click on "Upload" "button" in the "#mform2" "css_element"
    Then I should see "Certification completion file successfully imported"
    And I should see "2 Records imported pending processing"
    And I run all adhoc tasks

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    And I switch to "Certifications" tab
    Then I should see "Expired" in the "Certification 1" "table_row"

    When I follow "Other Evidence"
    And I follow "Completed certification : thisisevidence"
    Then I should see "CUSTOM - Date completed : 1 January 2015"
    And I should see "CUSTOM - Description :"
    And I should see "Certification Short name : thisisevidence"
    And I should see "Certification ID number : notacertification"


  Scenario: Verify a successful simple certification completion upload without specifying custom fields to store evidence.
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/certification_completion_1.csv" file to "Choose certification file to upload" filemanager
    And I set the field with xpath "(//select[@id='id_evidencedatefield'])[2]" to "Select an evidence completion date field"
    And I set the field with xpath "(//select[@id='id_evidencedescriptionfield'])[2]" to "Select an evidence description field"
    And I set the field "Import action" to "Certify uncertified users"
    And I click on "Upload" "button" in the "#mform2" "css_element"
    Then I should see "Certification completion file successfully imported"
    And I should see "2 Records imported pending processing"
    And I run all adhoc tasks

    When I navigate to "Browse list of users" node in "Site administration > Users"
    And I follow "Bob1 Learner1"
    And I click on "Record of Learning" "link" in the ".profile_tree" "css_element"
    And I switch to "Certifications" tab
    Then I should see "Expired" in the "Certification 1" "table_row"

    When I follow "Other Evidence"
    And I follow "Completed certification : thisisevidence"
    Then I should not see "Certification Short name : thisisevidence"
    And I should not see "Certification ID number : notacertification"
    And I should not see "Date completed : 1 January 2015"

  Scenario: Verify a certification completion import csv with incorrect columns shows an error
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/certification_completion_badcolumns.csv" file to "Choose certification file to upload" filemanager
    And I set the field "Import action" to "Certify uncertified users"
    And I click on "Upload" "button" in the "#mform2" "css_element"
    Then I should see "There were errors while importing the certifications"
    And I should see "Unknown column 'badcolumn'"
    And I should see "Missing required column 'duedate'"
    And I should see "No records were imported"

  Scenario: Verify long field values are handled in the certification completion upload
    Given I log in as "admin"
    When I navigate to "Upload Completion Records" node in "Site administration > Courses > Upload Completion Records"
    And I upload "totara/completionimport/tests/behat/fixtures/certification_completion_long_fields.csv" file to "Choose certification file to upload" filemanager
    And I click on "Upload" "button" in the "#mform2" "css_element"
    Then I should see "3 Records imported pending processing"
    And I run all adhoc tasks

    When I click on "Dashboard" in the totara menu
    Then I should see "Certification completion import completed with errors"
    And I click on "View all alerts" "link"
    And I should see "was processed but contains 1 error(s)"

    When I follow "Certification import report"
    Then I should see "3 records shown"
    And "1" row "Errors" column of "completionimport_certification" table should contain "Field 'username' is too long. The maximum length is 100"
    And "1" row "Errors" column of "completionimport_certification" table should contain "Field 'certificationshortname' is too long. The maximum length is 255"
    And "1" row "Errors" column of "completionimport_certification" table should contain "Field 'certificationidnumber' is too long. The maximum length is 100"
    And "1" row "Errors" column of "completionimport_certification" table should contain "Field 'completiondate' is too long. The maximum length is 10"
    And "1" row "Errors" column of "completionimport_certification" table should contain "Field 'duedate' is too long. The maximum length is 10"
    And "1" row "Username to import" column of "completionimport_certification" table should contain "101charsintheusernamefieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Certification Shortname" column of "completionimport_certification" table should contain "256charsinthecertificationshortnamefieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Certification ID Number" column of "completionimport_certification" table should contain "101charsinthecertificationidnumberfieldsshouldfailxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
    And "1" row "Completion date" column of "completionimport_certification" table should contain "11chars..."
    And "1" row "Due date" column of "completionimport_certification" table should contain "11chars..."
    And "2" row "Errors" column of "completionimport_certification" table should contain ""
    And "2" row "Username to import" column of "completionimport_certification" table should contain "learner1"
    And "2" row "Certification Shortname" column of "completionimport_certification" table should contain "Certification 1"
    And "2" row "Certification ID Number" column of "completionimport_certification" table should contain "Cert1"
    And "2" row "Completion date" column of "completionimport_certification" table should contain "2020-01-01"
    And "2" row "Due date" column of "completionimport_certification" table should contain "2020-07-01"
    And "3" row "Errors" column of "completionimport_certification" table should contain ""
    And "3" row "Username to import" column of "completionimport_certification" table should contain "learner1"
    And "3" row "Certification Shortname" column of "completionimport_certification" table should contain "255charsinthecertificationshortnamefieldsshouldpassxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    And "3" row "Certification ID Number" column of "completionimport_certification" table should contain "Cert2"
    And "3" row "Completion date" column of "completionimport_certification" table should contain "2020-01-01"
    And "3" row "Due date" column of "completionimport_certification" table should contain "2020-07-01"
    And I log out

    When I log in as "learner1"
    And I follow "Record of Learning"
    Then I should see "Record of Learning : Other Evidence"
    And I should see "2 records shown"
    And I should see "Completed certification : Certification 1"
    And I should see "Completed certification : 255charsinthecertificationshortnamefieldsshouldpassxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
