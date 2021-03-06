@editor @editor_atto @atto @atto_collapse
Feature: Atto collapse button
  To access all the tools in Atto, I need to toggle the toolbar

  @javascript
  Scenario: Toggle toolbar
    Given I log in as "admin"
    And I navigate to "Plugins > Filters > Manage filters" in site administration
    And I set the field with xpath "//table//tr[contains(.,'MathJax')]//*[@name='newstate']" to "On"
    And I open my profile in edit mode
    When I click on "Show more buttons" "button"
    Then "Equation editor" "button" should be visible
    And I click on "Show fewer buttons" "button"
    Then "Equation editor" "button" should not be visible
