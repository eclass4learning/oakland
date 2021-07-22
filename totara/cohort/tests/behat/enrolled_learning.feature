@totara_cohort @totara
Feature: Assign enrolled learning to cohort
  In order to efficiently control enrolments to learning items
  As an admin
  I need to assign courses, programs and certifications to an audience

  Background:
    Given I am on a totara site
    And the following "users" exist:
      | username    | firstname    | lastname   | email                |
      | learner1    | First        | Learner    | learner1@example.com |
      | learner2    | Second       | Learner    | learner2@example.com |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | co1      |
      | Cohort 2 | co2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | learner1 | co1    |
      | learner2 | co1    |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
      | Course 2 | C2        |
      | Course 3 | C3        |
    And the following "programs" exist in "totara_program" plugin:
      | fullname  | shortname |
      | Program 1 | P1        |
      | Program 2 | P2        |
      | Program 3 | P3        |
    And the following "certifications" exist in "totara_program" plugin:
      | fullname        | shortname |
      | Certification 1 | Cert1     |
      | Certification 2 | Cert2     |
      | Certification 3 | Cert3     |
    And I log in as "admin"

  @javascript
  Scenario: Assign courses as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 1"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then I should see "Course 1" in the "td.associations_nameiconlink" "css_element"
    And I should not see "Course 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 2"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should not see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

  @javascript
  Scenario: Assign programs as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 1"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then I should see "Program 1" in the "td.associations_nameiconlink" "css_element"
    And I should not see "Program 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Programs" in the totara menu
    And I follow "Program 1"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 2"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign certifications as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 1"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then I should see "Certification 1" in the "td.associations_nameiconlink" "css_element"
    And I should not see "Certification 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 1"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 2"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Search for courses to assign to cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add courses"
    And I click on "Search" "link" in the "ul.ui-tabs-nav" "css_element"
    And I set the field "id_query" to "Course 2"

    When I click on "Search" "button" in the "#learningitemcourses" "css_element"
    Then I should see "Course 2" in the "Add Courses to Enrolled Learning" "totaradialogue"
    And I should not see "No results found" in the "Add Courses to Enrolled Learning" "totaradialogue"
    And I should not see "Course 1" in the "Add Courses to Enrolled Learning" "totaradialogue"

    When I follow "Course 2"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then I should not see "Course 1" in the "td.associations_nameiconlink" "css_element"
    And I should see "Course 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should not see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 2"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

  @javascript
  Scenario: Search for programs to assign to cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add programs"
    And I click on "Search" "link" in the "ul.ui-tabs-nav" "css_element"
    And I set the field "id_query" to "Program 2"
    When I click on "Search" "button" in the "#learningitemprograms" "css_element"
    Then I should see "Program 2" in the "Add Programs to Enrolled Learning" "totaradialogue"
    And I should not see "No results found" in the "Add Programs to Enrolled Learning" "totaradialogue"
    And I should not see "Program 1" in the "Add Programs to Enrolled Learning" "totaradialogue"

    When I follow "Program 2"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then I should not see "Program 1" in the "td.associations_nameiconlink" "css_element"
    And I should see "Program 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Programs" in the totara menu
    And I follow "Program 1"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 2"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Search for certifications to assign to cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add certifications"
    And I click on "Search" "link" in the "ul.ui-tabs-nav" "css_element"
    And I set the field "id_query" to "Certification 2"
    When I click on "Search" "button" in the "#learningitemcertifications" "css_element"
    Then I should see "Certification 2" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    And I should not see "No results found" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    And I should not see "Certification 1" in the "Add Certifications to Enrolled Learning" "totaradialogue"

    When I follow "Certification 2"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then I should not see "Certification 1" in the "td.associations_nameiconlink" "css_element"
    And I should see "Certification 2" in the "td.associations_nameiconlink" "css_element"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 1"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 2"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign multiple courses as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 1"
    And I follow "Course 2"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name     | Type   |
      | Course 1 | Course |
      | Course 2 | Course |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name     | Type   |
      | Course 3 | Course |

    When I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 2"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

  @javascript
  Scenario: Assign multiple programs as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 1"
    And I follow "Program 2"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name      | Type    |
      | Program 1 | Program |
      | Program 2 | Program |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name      | Type    |
      | Program 3 | Program |

    When I click on "Programs" in the totara menu
    And I follow "Program 1"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 2"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign multiple certifications as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 1"
    And I follow "Certification 2"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Certification 3 | Certification |

    When I click on "Certifications" in the totara menu
    And I follow "Certification 1"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 2"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign courses and remove courses as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 1"
    And I follow "Course 2"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
     | Name     | Type   |
     | Course 1 | Course |
     | Course 2 | Course |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name     | Type   |
      | Course 3 | Course |

    When I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 3"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name     | Type   |
      | Course 1 | Course |
      | Course 2 | Course |
      | Course 3 | Course |
    And I click to delete the cohort enrolled learning association on "Course 2"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 2"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should not see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 3"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

  @javascript
  Scenario: Assign programs and remove programs as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 1"
    And I follow "Program 2"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Program 1 | Program |
      | Program 2 | Program |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Program 3 | Program |

    When I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 3"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Program 1 | Program |
      | Program 2 | Program |
      | Program 3 | Program |
    And I click to delete the cohort enrolled learning association on "Program 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 1"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 2"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 3"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign certifications and remove certifications as enrolled learning to a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    And I follow "Enrolled learning"
    And I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 1"
    And I follow "Certification 2"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Certification 3 | Certification |

    When I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 3"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name      | Type   |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Certification 3 | Certification |
    And I click to delete the cohort enrolled learning association on "Certification 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 1"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 2"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 3"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

  @javascript
  Scenario: Assign all types of enrolled learning and remove enrolled learning from a cohort
    Given I navigate to "Audiences" node in "Site administration > Users > Accounts"
    And I follow "Cohort 1"
    When I follow "Enrolled learning"
    Then the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Course 3        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Certification 3 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |

    When I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 1"
    And I follow "Course 2"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 3        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Certification 3 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |

    And I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 1"
    And I follow "Certification 2"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 3        | Course        |
      | Certification 3 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |

    And I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 1"
    And I follow "Program 2"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 3        | Course        |
      | Certification 3 | Certification |
      | Program 3       | Program       |

    When I press "Add programs"
    And I follow "Miscellaneous"
    And I follow "Program 3"
    And I click on "Save" "button" in the "Add Programs to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 3        | Course        |
      | Certification 3 | Certification |

    When I press "Add courses"
    And I follow "Miscellaneous"
    And I follow "Course 3"
    And I click on "Save" "button" in the "Add Courses to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Course 3        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Certification 3 | Certification |

    When I press "Add certifications"
    And I follow "Miscellaneous"
    And I follow "Certification 3"
    And I click on "Save" "button" in the "Add Certifications to Enrolled Learning" "totaradialogue"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 2        | Course        |
      | Course 3        | Course        |
      | Certification 1 | Certification |
      | Certification 2 | Certification |
      | Certification 3 | Certification |
      | Program 1       | Program       |
      | Program 2       | Program       |
      | Program 3       | Program       |

    When I click to delete the cohort enrolled learning association on "Certification 2"
    And I click to delete the cohort enrolled learning association on "Program 2"
    And I click to delete the cohort enrolled learning association on "Course 2"
    Then the following should exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 1        | Course        |
      | Course 3        | Course        |
      | Certification 1 | Certification |
      | Certification 3 | Certification |
      | Program 1       | Program       |
      | Program 3       | Program       |
    And the following should not exist in the "cohort_associations_enrolled" table:
      | Name            | Type          |
      | Course 2        | Course        |
      | Certification 2 | Certification |
      | Program 2       | Program       |

    When I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 2"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should not see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Find Learning" in the totara menu
    And I follow "Course 3"
    And I navigate to "Enrolment methods" node in "Course administration > Users"
    Then I should see "Audience sync (Cohort 1 - Learner)"
    And I should not see "Audience sync (Cohort 2 - Learner)"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 1"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 2"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Certifications" in the totara menu
    And I follow "Certification 3"
    And I press "Edit certification details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 1"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 2"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should not see "Cohort 1"
    And I should not see "Cohort 2"

    When I click on "Programs" in the totara menu
    And I follow "Program 3"
    And I press "Edit program details"
    And I switch to "Assignments" tab
    Then I should see "Cohort 1"
    And I should not see "Cohort 2"

    # Check that the user can access the courses they should be able to.
    When I log out
    And I log in as "learner1"
    And I click on "Dashboard" in the totara menu
    And I trigger cron
    And I am on homepage
    And I click on "Dashboard" in the totara menu
    Then I should see "Course 1"
    And I should not see "Course 2"
    And I should see "Course 3"

  @javascript
  Scenario: Edit course visibility for a particular course
    Given I am on homepage
    And I click on "Find Learning" in the totara menu
    And I follow "Course 1"
    And I follow "Edit settings"
    And I click on "Add enrolled audiences" "button"
    And I follow "Cohort 1"
    And I click on "OK" "link_or_button" in the "div[aria-describedby='course-cohorts-enrolled-dialog']" "css_element"
    Then I should see "Cohort 1" in the "course-cohorts-table-enrolled" "table"
    And I should not see "Cohort 2" in the "course-cohorts-table-enrolled" "table"
