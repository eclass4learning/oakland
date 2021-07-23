@totara @totara_program @totara_reportbuilder @javascript
Feature: Program courses can be ordered within a courseset

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username | firstname | lastname | email               |
      | user001  | fn_001    | ln_001   | user001@example.com |
      | user002  | fn_002    | ln_002   | user002@example.com |
      | user003  | fn_003    | ln_003   | user003@example.com |
    And the following "courses" exist:
      | fullname      | shortname     | format  | enablecompletion |
      | Test Course 1 | TestC1        | topics  | 1                |
      | Test Course 2 | TestC2        | topics  | 1                |
      | Test Course 3 | TestC3        | topics  | 1                |
      | Test Course 4 | TestC4        | topics  | 1                |
    And the following "programs" exist in "totara_program" plugin:
      | fullname       | shortname  |
      | Test Program 1 | program1   |
    And the following "program assignments" exist in "totara_program" plugin:
      | program  | user    |
      | program1 | user001 |

  Scenario: Order courses within a program
    Given I log in as "admin"
    When I navigate to "Manage programs" node in "Site administration > Programs"
    And I click on "Miscellaneous" "link"
    And I click on "Test Program 1" "link"
    And I click on "Edit program details" "button"
    And I switch to "Content" tab
    And I click on "addcontent_ce" "button" in the "#edit-program-content" "css_element"
    And I click on "Miscellaneous" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 1" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 4" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 3" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 2" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Ok" "button" in the "addmulticourse" "totaradialogue"
    When I press "Save changes"
    And I click on "Save all changes" "button"
    Then "Test Course 1" "text" should appear before "Test Course 3" "text"
    And "Test Course 4" "text" should appear before "Test Course 3" "text"
    And "Test Course 2" "text" should appear after "Test Course 3" "text"
    And "Test Course 3" "text" should appear after "Test Course 4" "text"
    And "Test Course 2" "text" should appear after "Test Course 1" "text"
    And I log out
    # Ensure the courses display correctly for the learner.
    When I log in as "user001"
    And I click on "Dashboard" in the totara menu
    And I should see "Test Program 1" in the "Current Learning" "block"
    And I toggle "Test Program 1" in the current learning block
    Then "Test Course 1" "text" should appear before "Test Course 3" "text"
    And "Test Course 4" "text" should appear before "Test Course 3" "text"
    And "Test Course 2" "text" should appear after "Test Course 3" "text"
    And "Test Course 3" "text" should appear after "Test Course 4" "text"
    And "Test Course 2" "text" should appear after "Test Course 1" "text"
    When I click on "Test Program 1" "link"
    Then "Test Course 1" "text" should appear before "Test Course 3" "text"
    And "Test Course 4" "text" should appear before "Test Course 3" "text"
    And "Test Course 2" "text" should appear after "Test Course 3" "text"
    And "Test Course 3" "text" should appear after "Test Course 4" "text"
    And "Test Course 2" "text" should appear after "Test Course 1" "text"

  Scenario: Ensure ordered courses within a program appear correctly in the Program Overview report builder report
    Given I log in as "admin"
    When I navigate to "Manage programs" node in "Site administration > Programs"
    And I click on "Miscellaneous" "link"
    And I click on "Test Program 1" "link"
    And I click on "Edit program details" "button"
    And I switch to "Content" tab
    And I click on "addcontent_ce" "button" in the "#edit-program-content" "css_element"
    And I click on "Miscellaneous" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 1" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 4" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 3" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 2" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Ok" "button" in the "addmulticourse" "totaradialogue"
    When I press "Save changes"
    And I click on "Save all changes" "button"
    Then "Test Course 1" "text" should appear before "Test Course 3" "text"
    And "Test Course 4" "text" should appear before "Test Course 3" "text"
    And "Test Course 2" "text" should appear after "Test Course 3" "text"
    And "Test Course 3" "text" should appear after "Test Course 4" "text"
    And "Test Course 2" "text" should appear after "Test Course 1" "text"

    When I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Program Overview"
    And I set the field "Source" to "Program Overview"
    And I press "Create report"
    Then I should see "Edit Report 'Program Overview'"
    When I switch to "Access" tab
    And I set the following fields to these values:
      | Authenticated user | 1 |
    And I press "Save changes"
    Then I should see "Report Updated"
    And I log out

    When I log in as "user001"
    And I click on "Reports" in the totara menu
    And I follow "Program Overview"
    Then "TestC1" "text" should appear before "TestC3" "text"
    And "TestC4" "text" should appear before "TestC3" "text"
    And "TestC2" "text" should appear after "TestC3" "text"
    And "TestC3" "text" should appear after "TestC4" "text"
    And "TestC2" "text" should appear after "TestC1" "text"

  Scenario: Ensure ordered courses within a certification appear correctly in the Certification Overview report builder report
    Given the following "certifications" exist in "totara_program" plugin:
      | fullname             | shortname        |
      | Test Certification 1 | certification1   |
    And the following "program assignments" exist in "totara_program" plugin:
      | program        | user    |
      | certification1 | user001 |
    When I log in as "admin"
    And I navigate to "Manage certifications" node in "Site administration > Certifications"
    And I click on "Miscellaneous" "link"
    And I click on "Test Certification 1" "link"
    And I click on "Edit certification details" "button"
    And I switch to "Content" tab
    And I click on "addcontent_ce" "button" in the "#edit-program-content" "css_element"
    And I click on "Miscellaneous" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 1" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 4" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 3" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Test Course 2" "link" in the "addmulticourse" "totaradialogue"
    And I click on "Ok" "button" in the "addmulticourse" "totaradialogue"
    When I press "Save changes"
    And I click on "Save all changes" "button"
    Then "Test Course 1" "text" should appear before "Test Course 3" "text"
    And "Test Course 4" "text" should appear before "Test Course 3" "text"
    And "Test Course 2" "text" should appear after "Test Course 3" "text"
    And "Test Course 3" "text" should appear after "Test Course 4" "text"
    And "Test Course 2" "text" should appear after "Test Course 1" "text"

    When I navigate to "Manage user reports" node in "Site administration > Reports"
    And I press "Create report"
    And I set the field "Report Name" to "Certification Overview"
    And I set the field "Source" to "Certification Overview"
    And I press "Create report"
    Then I should see "Edit Report 'Certification Overview'"
    When I switch to "Access" tab
    And I set the following fields to these values:
      | Authenticated user | 1 |
    And I press "Save changes"
    Then I should see "Report Updated"
    And I log out

    When I log in as "user001"
    And I click on "Reports" in the totara menu
    And I follow "Certification Overview"
    Then "TestC1" "text" should appear before "TestC3" "text"
    And "TestC4" "text" should appear before "TestC3" "text"
    And "TestC2" "text" should appear after "TestC3" "text"
    And "TestC3" "text" should appear after "TestC4" "text"
    And "TestC2" "text" should appear after "TestC1" "text"
