<?php // $Id$
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @package totara
 * @subpackage reportbuilder
 */

/**
 * Abstract base content class to be extended to create report builder
 * content restrictions. This file also contains some core content restrictions
 * that can be used by any report builder source
 *
 * Defines the properties and methods required by content restrictions
 */
abstract class rb_base_content {

    public $reportfor;

    /*
     * @param integer $reportfor User ID to determine who the report is for
     *                           Typically this will be $USER->id, except
     *                           in the case of scheduled reports run by cron
     */
    public function __construct($reportfor=null) {
        $this->reportfor = $reportfor;
    }

    /*
     * All sub classes must define the following functions
     */
    abstract public function sql_restriction($fields, $reportid);
    abstract public function text_restriction($title, $reportid);
    abstract public function form_template(&$mform, $reportid, $title);
    abstract public function form_process($reportid, $fromform);

}

///////////////////////////////////////////////////////////////////////////


/**
 * Restrict content by a position ID
 *
 * Pass in an integer that represents the position ID
 */
class rb_current_pos_content extends rb_base_content {

    // Define some constants for the selector options.
    const CONTENT_POS_EQUAL = 0;
    const CONTENT_POS_EQUALANDBELOW = 1;
    const CONTENT_POS_BELOW = 2;

    /**
     * Generate the SQL to apply this content restriction
     *
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = $settings['recursive'];
        $userid = $this->reportfor;

        $jobs = \totara_job\job_assignment::get_all($userid);
        $posids = array();
        foreach ($jobs as $job) {
            if ($job->positionid) {
                $posids[] = $job->positionid;
            }
        }

        if (empty($posids)) {
            // There will be no match, no need to run the big query, empty result will do.
            return array("$field = NULL", array());
        }

        list($possql, $params) = $DB->get_in_or_equal($posids, SQL_PARAMS_NAMED, 'posid');
        $viewpospath = $DB->sql_concat('viewerpos.path', "'/%'");

        if ($restriction == self::CONTENT_POS_EQUAL) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {pos} viewerpos ON viewerpos.id = ja.positionid
             WHERE viewerpos.id $possql)";

            return array($wheresql, $params);
        }

        if ($restriction == self::CONTENT_POS_BELOW) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {pos} pos ON pos.id = ja.positionid
              JOIN {pos} viewerpos ON pos.path LIKE $viewpospath
             WHERE viewerpos.id $possql)";

            return array($wheresql, $params);
        }

        if ($restriction == self::CONTENT_POS_EQUALANDBELOW) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {pos} pos ON pos.id = ja.positionid
              JOIN {pos} viewerpos ON pos.path LIKE $viewpospath OR viewerpos.id = pos.id
             WHERE viewerpos.id $possql)";

            return array($wheresql, $params);
        }

        // Invalid restriction, empty result will do.
        debugging('Invalid restriction type detected', DEBUG_DEVELOPER);
        return array("$field = NULL", array());
    }

    /**
     * Return hierarchy prefix to which this restriction applies
     *
     * @return string Hierarchy prefix
     */
    public function sql_hierarchy_restriction_prefix() {
        return 'pos';
    }

    /**
     * Generate the SQL to apply this content restriction to position queries
     * in position dialogs used in reports.
     *
     * NOTE: always return parent categories even if user is not allowed to see data from them,
     *       this is necessary for trees in dialogs.
     *
     * @param string $field position id SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_hierarchy_restriction($field, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = $settings['recursive'];
        $userid = $this->reportfor;

        $jobs = \totara_job\job_assignment::get_all($userid);
        $posids = array();
        foreach ($jobs as $job) {
            if ($job->positionid) {
                $posids[] = $job->positionid;
            }
        }

        if (empty($posids)) {
            // There will be no match, NULL is not equal to anything, not even NULL.
            return array("{$field} = NULL", array());
        }

        list($possql, $params) = $DB->get_in_or_equal($posids, SQL_PARAMS_NAMED, 'posid');
        $viewpospath = $DB->sql_concat('viewerpos.path', "'/%'");
        $parentpospath = $DB->sql_concat('pos.path', "'/%'");

        $sql = "SELECT pos.id
                  FROM {pos} pos
                  JOIN {pos} viewerpos ON viewerpos.path LIKE $parentpospath
                 WHERE viewerpos.id $possql";
        $parents = $DB->get_records_sql($sql, $params);
        $parentids = array_keys($parents);

        if ($restriction == self::CONTENT_POS_EQUAL) {
            $itemids = $posids;
        } else if ($restriction == self::CONTENT_POS_BELOW || $restriction == self::CONTENT_POS_EQUALANDBELOW) {
            // Hierarchy has to include full tree from parent to the current restriction,
            // otherwise we won't be able to build a selector dialog.
            $sql = "SELECT pos.id
                      FROM {pos} pos
                      JOIN {pos} viewerpos ON pos.path LIKE $viewpospath OR viewerpos.id = pos.id
                     WHERE viewerpos.id $possql";
            $items = $DB->get_records_sql($sql, $params);
            $itemids = array_keys($items);
        } else {
            // Invalid restriction, NULL is not equal to anything, not even NULL.
            debugging('Invalid restriction type detected', DEBUG_DEVELOPER);
            return array("{$field} = NULL", array());
        }

        if (!$itemids and !$parentids) {
            return array("{$field} = NULL", array());
        }

        list($idsql, $params) = $DB->get_in_or_equal(array_merge($itemids, $parentids), SQL_PARAMS_NAMED, 'posid');
        return array("{$field} $idsql", $params);
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        global $DB;

        $userid = $this->reportfor;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        $posnames = $DB->get_fieldset_sql('SELECT p.fullname FROM {pos} p WHERE EXISTS (SELECT ja.positionid FROM {job_assignment} ja WHERE ja.userid = ? AND p.id = ja.positionid)',
            array($userid));

        $delim = get_string('contentdesc_delim', 'totara_reportbuilder');
        switch ($settings['recursive']) {
            case self::CONTENT_POS_EQUAL:
                return get_string('contentdesc_posequal', 'totara_reportbuilder', format_string(implode($delim, $posnames)));
            case self::CONTENT_POS_EQUALANDBELOW:
                return get_string('contentdesc_posboth', 'totara_reportbuilder', format_string(implode($delim, $posnames)));
            case self::CONTENT_POS_BELOW:
                return get_string('contentdesc_posbelow', 'totara_reportbuilder', format_string(implode($delim, $posnames)));
            default:
                return '';
        }
    }

    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        // get current settings
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $recursive = reportbuilder::get_setting($reportid, $type, 'recursive');

        $mform->addElement('header', 'current_pos_header',
            get_string('showbyx', 'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('current_pos_header');
        $mform->addElement('checkbox', 'current_pos_enable', '',
            get_string('currentposenable', 'totara_reportbuilder'));
        $mform->setDefault('current_pos_enable', $enable);
        $mform->disabledIf('current_pos_enable', 'contentenabled', 'eq', 0);
        $radiogroup = array();
        $radiogroup[] =& $mform->createElement('radio', 'current_pos_recursive',
            '', get_string('showrecordsinposandbelow', 'totara_reportbuilder'), self::CONTENT_POS_EQUALANDBELOW);
        $radiogroup[] =& $mform->createElement('radio', 'current_pos_recursive',
            '', get_string('showrecordsinpos', 'totara_reportbuilder'), self::CONTENT_POS_EQUAL);
        $radiogroup[] =& $mform->createElement('radio', 'current_pos_recursive',
            '', get_string('showrecordsbelowposonly', 'totara_reportbuilder'), self::CONTENT_POS_BELOW);
        $mform->addGroup($radiogroup, 'current_pos_recursive_group',
            get_string('includechildpos', 'totara_reportbuilder'), html_writer::empty_tag('br'), false);
        $mform->setDefault('current_pos_recursive', $recursive);
        $mform->disabledIf('current_pos_recursive_group', 'contentenabled', 'eq', 0);
        $mform->disabledIf('current_pos_recursive_group', 'current_pos_enable', 'notchecked');
        $mform->addHelpButton('current_pos_header', 'reportbuildercurrentpos', 'totara_reportbuilder');
    }

    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        $status = true;
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->current_pos_enable) &&
            $fromform->current_pos_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // recursive radio option
        $recursive = isset($fromform->current_pos_recursive) ?
            $fromform->current_pos_recursive : 0;

        $status = $status && reportbuilder::update_setting($reportid, $type,
            'recursive', $recursive);

        return $status;
    }
}


/**
 * Restrict content by an organisation ID
 *
 * Pass in an integer that represents the organisation ID
 */
class rb_current_org_content extends rb_base_content {

    // Define some constants for the selector options.
    const CONTENT_ORG_EQUAL = 0;
    const CONTENT_ORG_EQUALANDBELOW = 1;
    const CONTENT_ORG_BELOW = 2;

    /**
     * Generate the SQL to apply this content restriction
     *
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = $settings['recursive'];
        $userid = $this->reportfor;

        $jobs = \totara_job\job_assignment::get_all($userid);
        $orgids = array();
        foreach ($jobs as $job) {
            if ($job->organisationid) {
                $orgids[] = $job->organisationid;
            }
        }

        if (empty($orgids)) {
            // There will be no match, no need to run the big query, empty result will do.
            return array("{$field} = NULL", array());
        }

        list($orgsql, $params) = $DB->get_in_or_equal($orgids, SQL_PARAMS_NAMED, 'orgid');
        $vieworgpath = $DB->sql_concat('viewerorg.path', "'/%'");

        if ($restriction == self::CONTENT_ORG_EQUAL) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {org} viewerorg ON viewerorg.id = ja.organisationid
             WHERE viewerorg.id $orgsql)";

            return array($wheresql, $params);
        }

        if ($restriction == self::CONTENT_ORG_BELOW) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {org} org ON org.id = ja.organisationid
              JOIN {org} viewerorg ON org.path LIKE $vieworgpath
             WHERE viewerorg.id $orgsql)";

            return array($wheresql, $params);
        }

        if ($restriction == self::CONTENT_ORG_EQUALANDBELOW) {
            $wheresql = "$field IN (
            SELECT ja.userid
              FROM {job_assignment} ja
              JOIN {org} org ON org.id = ja.organisationid
              JOIN {org} viewerorg ON org.path LIKE $vieworgpath OR viewerorg.id = org.id
             WHERE viewerorg.id $orgsql)";

            return array($wheresql, $params);
        }

        // Invalid restriction, empty result will do.
        debugging('Invalid restriction type detected', DEBUG_DEVELOPER);
        return array("{$field} = NULL", array());
    }

    /**
     * Return hierarchy prefix to which this restriction applies
     *
     * @return string Hierarchy prefix
     */
    public function sql_hierarchy_restriction_prefix() {
        return 'org';
    }

    /**
     * Generate the SQL to apply this content restriction to organisation queries
     * in organisation dialogs used in reports.
     *
     * NOTE: always return parent categories even if user is not allowed to see data from them,
     *       this is necessary for trees in dialogs.
     *
     * @param string $field organisation id SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_hierarchy_restriction($field, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = $settings['recursive'];
        $userid = $this->reportfor;

        $jobs = \totara_job\job_assignment::get_all($userid);
        $orgids = array();
        foreach ($jobs as $job) {
            if ($job->organisationid) {
                $orgids[] = $job->organisationid;
            }
        }

        if (empty($orgids)) {
            // There will be no match, NULL is not equal to anything, not even NULL.
            return array("{$field} = NULL", array());
        }

        list($orgsql, $params) = $DB->get_in_or_equal($orgids, SQL_PARAMS_NAMED, 'orgid');
        $vieworgpath = $DB->sql_concat('viewerorg.path', "'/%'");
        $parentorgpath = $DB->sql_concat('org.path', "'/%'");

        $sql = "SELECT org.id
                  FROM {org} org
                  JOIN {org} viewerorg ON viewerorg.path LIKE $parentorgpath
                 WHERE viewerorg.id $orgsql";
        $parents = $DB->get_records_sql($sql, $params);
        $parentids = array_keys($parents);

        if ($restriction == self::CONTENT_ORG_EQUAL) {
            $itemids = $orgids;
        } else if ($restriction == self::CONTENT_ORG_BELOW || $restriction == self::CONTENT_ORG_EQUALANDBELOW) {
            // Hierarchy has to include full tree from parent to the current restriction,
            // otherwise we won't be able to build a selector dialog.
            $sql = "SELECT org.id
                      FROM {org} org
                      JOIN {org} viewerorg ON org.path LIKE $vieworgpath OR viewerorg.id = org.id
                     WHERE viewerorg.id $orgsql";
            $items = $DB->get_records_sql($sql, $params);
            $itemids = array_keys($items);
        } else {
            // Invalid restriction, NULL is not equal to anything, not even NULL.
            debugging('Invalid restriction type detected', DEBUG_DEVELOPER);
            return array("{$field} = NULL", array());
        }

        if (!$itemids and !$parentids) {
            return array("{$field} = NULL", array());
        }

        list($idsql, $params) = $DB->get_in_or_equal(array_merge($itemids, $parentids), SQL_PARAMS_NAMED, 'orgid');
        return array("{$field} $idsql", $params);
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        global $DB;

        $userid = $this->reportfor;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        $orgnames = $DB->get_fieldset_sql('SELECT p.fullname FROM {org} p WHERE EXISTS (SELECT ja.organisationid FROM {job_assignment} ja WHERE ja.userid = ? AND p.id = ja.organisationid)',
            array($userid));

        $delim = get_string('contentdesc_delim', 'totara_reportbuilder');
        switch ($settings['recursive']) {
            case self::CONTENT_ORG_EQUAL:
                return get_string('contentdesc_orgequal', 'totara_reportbuilder', format_string(implode($delim, $orgnames)));
            case self::CONTENT_ORG_EQUALANDBELOW:
                return get_string('contentdesc_orgboth', 'totara_reportbuilder', format_string(implode($delim, $orgnames)));
            case self::CONTENT_ORG_BELOW:
                return get_string('contentdesc_orgbelow', 'totara_reportbuilder', format_string(implode($delim, $orgnames)));
            default:
                return '';
        }
    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        // get current settings
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $recursive = reportbuilder::get_setting($reportid, $type, 'recursive');

        $mform->addElement('header', 'current_org_header',
            get_string('showbyx', 'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('current_org_header');
        $mform->addElement('checkbox', 'current_org_enable', '',
            get_string('currentorgenable', 'totara_reportbuilder'));
        $mform->setDefault('current_org_enable', $enable);
        $mform->disabledIf('current_org_enable', 'contentenabled', 'eq', 0);
        $radiogroup = array();
        $radiogroup[] =& $mform->createElement('radio', 'current_org_recursive',
            '', get_string('showrecordsinorgandbelow', 'totara_reportbuilder'), self::CONTENT_ORG_EQUALANDBELOW);
        $radiogroup[] =& $mform->createElement('radio', 'current_org_recursive',
            '', get_string('showrecordsinorg', 'totara_reportbuilder'), self::CONTENT_ORG_EQUAL);
        $radiogroup[] =& $mform->createElement('radio', 'current_org_recursive',
            '', get_string('showrecordsbeloworgonly', 'totara_reportbuilder'), self::CONTENT_ORG_BELOW);
        $mform->addGroup($radiogroup, 'current_org_recursive_group',
            get_string('includechildorgs', 'totara_reportbuilder'), html_writer::empty_tag('br'), false);
        $mform->setDefault('current_org_recursive', $recursive);
        $mform->disabledIf('current_org_recursive_group', 'contentenabled',
            'eq', 0);
        $mform->disabledIf('current_org_recursive_group', 'current_org_enable',
            'notchecked');
        $mform->addHelpButton('current_org_header', 'reportbuildercurrentorg', 'totara_reportbuilder');
    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        $status = true;
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->current_org_enable) &&
            $fromform->current_org_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // recursive radio option
        $recursive = isset($fromform->current_org_recursive) ?
            $fromform->current_org_recursive : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'recursive', $recursive);

        return $status;
    }
}


/*
 * Restrict content by an organisation at time of completion
 *
 * Pass in an integer that represents an organisation ID
 */
class rb_completed_org_content extends rb_base_content {
    const CONTENT_ORGCOMP_EQUAL = 0;
    const CONTENT_ORGCOMP_EQUALANDBELOW = 1;
    const CONTENT_ORGCOMP_BELOW = 2;

    /**
     * Generate the SQL to apply this content restriction
     *
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/totara/hierarchy/lib.php');
        require_once($CFG->dirroot . '/totara/hierarchy/prefix/position/lib.php');

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = $settings['recursive'];
        $userid = $this->reportfor;

        // get the user's primary organisation path
        $orgpaths = $DB->get_fieldset_sql(
            "SELECT o.path
               FROM {job_assignment} ja
               JOIN {org} o ON ja.organisationid = o.id
              WHERE ja.userid = ?",
              array($userid));

        // we need the user to have a valid organisation path
        if (empty($orgpaths)) {
            // using 1=0 instead of FALSE for MSSQL support
            return array('1=0', array());
        }

        $constraints = array();
        $params = array();
        switch ($restriction) {
            case self::CONTENT_ORGCOMP_EQUAL:
                foreach ($orgpaths as $orgpath) {
                    $paramname = rb_unique_param('ccor');
                    $constraints[] = "$field = :$paramname";
                    $params[$paramname] = $orgpath;
                }
                break;
            case self::CONTENT_ORGCOMP_BELOW:
                foreach ($orgpaths as $orgpath) {
                    $paramname = rb_unique_param('ccor');
                    $constraints[] = $DB->sql_like($field, ":{$paramname}");
                    $params[$paramname] = $DB->sql_like_escape($orgpath) . '/%';
                }
                break;
            case self::CONTENT_ORGCOMP_EQUALANDBELOW:
                foreach ($orgpaths as $orgpath) {
                    $paramname = rb_unique_param('ccor1');
                    $constraints[] = "$field = :{$paramname}";
                    $params[$paramname] = $orgpath;

                    $paramname = rb_unique_param('ccors');
                    $constraints[] = $DB->sql_like($field, ":$paramname");
                    $params[$paramname] = $DB->sql_like_escape($orgpath) . '/%';
                }
                break;
        }
        $sql = implode(' OR ', $constraints);

        return array("({$sql})", $params);
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        global $DB;

        $userid = $this->reportfor;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        $orgid = $DB->get_field('job_assignment', 'organisationid', array('userid' => $userid, 'sortorder' => 1));
        if (empty($orgid)) {
            return $title . ' ' . get_string('is', 'totara_reportbuilder') . ' "UNASSIGNED"';
        }
        $orgname = $DB->get_field('org', 'fullname', array('id' => $orgid));

        switch ($settings['recursive']) {
            case self::CONTENT_ORGCOMP_EQUAL:
                return $title . ' ' . get_string('is', 'totara_reportbuilder') .
                    ': "' . $orgname . '"';
            case self::CONTENT_ORGCOMP_EQUALANDBELOW:
                return $title . ' ' . get_string('is', 'totara_reportbuilder') .
                    ': "' . $orgname . '" ' . get_string('orsuborg', 'totara_reportbuilder');
            case self::CONTENT_ORGCOMP_BELOW:
                return $title . ' ' . get_string('isbelow', 'totara_reportbuilder') .
                    ': "' . $orgname . '"';
            default:
                return '';
        }
    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        // get current settings
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $recursive = reportbuilder::get_setting($reportid, $type, 'recursive');

        $mform->addElement('header', 'completed_org_header',
            get_string('showbyx', 'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('completed_org_header');
        $mform->addElement('checkbox', 'completed_org_enable', '',
            get_string('completedorgenable', 'totara_reportbuilder'));
        $mform->setDefault('completed_org_enable', $enable);
        $mform->disabledIf('completed_org_enable', 'contentenabled', 'eq', 0);
        $radiogroup = array();
        $radiogroup[] =& $mform->createElement('radio', 'completed_org_recursive',
            '', get_string('showrecordsinorgandbelow', 'totara_reportbuilder'), self::CONTENT_ORGCOMP_EQUALANDBELOW);
        $radiogroup[] =& $mform->createElement('radio', 'completed_org_recursive',
            '', get_string('showrecordsinorg', 'totara_reportbuilder'), self::CONTENT_ORGCOMP_EQUAL);
        $radiogroup[] =& $mform->createElement('radio', 'completed_org_recursive',
            '', get_string('showrecordsbeloworgonly', 'totara_reportbuilder'), self::CONTENT_ORGCOMP_BELOW);
        $mform->addGroup($radiogroup, 'completed_org_recursive_group',
            get_string('includechildorgs', 'totara_reportbuilder'), html_writer::empty_tag('br'), false);
        $mform->setDefault('completed_org_recursive', $recursive);
        $mform->disabledIf('completed_org_recursive_group', 'contentenabled',
            'eq', 0);
        $mform->disabledIf('completed_org_recursive_group',
            'completed_org_enable', 'notchecked');
        $mform->addHelpButton('completed_org_header', 'reportbuildercompletedorg', 'totara_reportbuilder');
    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        $status = true;
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->completed_org_enable) &&
            $fromform->completed_org_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // recursive radio option
        $recursive = isset($fromform->completed_org_recursive) ?
            $fromform->completed_org_recursive : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'recursive', $recursive);

        return $status;
    }
}


/*
 * Restrict content by a particular user or group of users
 */
class rb_user_content extends rb_base_content {

    const USER_OWN = 1;
    const USER_DIRECT_REPORTS = 2;
    const USER_INDIRECT_REPORTS = 4;
    const USER_TEMP_REPORTS = 8;

    /**
     * Generate the SQL to apply this content restriction.
     *
     * @param array $field      SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $CFG, $DB;

        $userid = $this->reportfor;

        // remove rb_ from start of classname.
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $restriction = isset($settings['who']) ? $settings['who'] : null;
        $userid = $this->reportfor;


        if (empty($restriction)) {
            return array(' (1 = 1) ', array());
        }

        $conditions = array();
        $params = array();

        $viewownrecord = ($restriction & self::USER_OWN) == self::USER_OWN;
        if ($viewownrecord) {
            $conditions[] = "{$field} = :self";
            $params['self'] = $userid;
        }

        if (($restriction & self::USER_DIRECT_REPORTS) == self::USER_DIRECT_REPORTS) {
            $conditions[] = "EXISTS (SELECT 1
                                       FROM {user} u1
                                 INNER JOIN {job_assignment} u1ja
                                         ON u1ja.userid = u1.id
                                 INNER JOIN {job_assignment} d1ja
                                         ON d1ja.managerjaid = u1ja.id
                                      WHERE u1.id = :viewer1
                                        AND d1ja.userid = {$field}
                                        AND d1ja.userid != u1.id
                                     )";
            $params['viewer1'] = $userid;
        }

        if (($restriction & self::USER_INDIRECT_REPORTS) == self::USER_INDIRECT_REPORTS) {
            $ilikesql = $DB->sql_concat('u2ja.managerjapath', "'/%'");
            $conditions[] = "EXISTS (SELECT 1
                                       FROM {user} u2
                                 INNER JOIN {job_assignment} u2ja
                                         ON u2ja.userid = u2.id
                                 INNER JOIN {job_assignment} i2ja
                                         ON i2ja.managerjapath LIKE {$ilikesql}
                                      WHERE u2.id = :viewer2
                                        AND i2ja.userid = {$field}
                                        AND i2ja.userid != u2.id
                                        AND i2ja.managerjaid != u2ja.id
                                    )";
            $params['viewer2'] = $userid;
        }

        if (($restriction & self::USER_TEMP_REPORTS) == self::USER_TEMP_REPORTS) {
            $conditions[] = "EXISTS (SELECT 1
                                       FROM {user} u3
                                 INNER JOIN {job_assignment} u3ja
                                         ON u3ja.userid = u3.id
                                 INNER JOIN {job_assignment} t3ja
                                         ON t3ja.tempmanagerjaid = u3ja.id
                                      WHERE u3.id = :viewer3
                                        AND t3ja.userid = {$field}
                                        AND t3ja.userid != u3.id
                                    )";
            $params['viewer3'] = $userid;
        }

        $sql = implode(' OR ', $conditions);

        return array(" ($sql) ", $params);
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $who = isset($settings['who']) ? $settings['who'] : 0;
        $userid = $this->reportfor;

        $user = $DB->get_record('user', array('id' => $userid));

        $strings = array();
        $strparams = array('field' => $title, 'user' => fullname($user));

        if (($who & self::USER_OWN) == self::USER_OWN) {
            $strings[] = get_string('contentdesc_userown', 'totara_reportbuilder', $strparams);
        }

        if (($who & self::USER_DIRECT_REPORTS) == self::USER_DIRECT_REPORTS) {
            $strings[] = get_string('contentdesc_userdirect', 'totara_reportbuilder', $strparams);
        }

        if (($who & self::USER_INDIRECT_REPORTS) == self::USER_INDIRECT_REPORTS) {
            $strings[] = get_string('contentdesc_userindirect', 'totara_reportbuilder', $strparams);
        }

        if (($who & self::USER_TEMP_REPORTS) == self::USER_TEMP_REPORTS) {
            $strings[] = get_string('contentdesc_usertemp', 'totara_reportbuilder', $strparams);
        }

        if (empty($strings)) {
            return $title . ' ' . get_string('isnotfound', 'totara_reportbuilder');
        }

        return implode(get_string('or', 'totara_reportbuilder'), $strings);
    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {

        // get current settings
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $who = reportbuilder::get_setting($reportid, $type, 'who');

        $mform->addElement('header', 'user_header', get_string('showbyx',
            'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('user_header');
        $mform->addElement('checkbox', 'user_enable', '',
            get_string('showbasedonx', 'totara_reportbuilder', lcfirst($title)));
        $mform->disabledIf('user_enable', 'contentenabled', 'eq', 0);
        $mform->setDefault('user_enable', $enable);
        $checkgroup = array();
        $checkgroup[] =& $mform->createElement('advcheckbox', 'user_who['.self::USER_OWN.']', '',
            get_string('userownrecords', 'totara_reportbuilder'), null, array(0, 1));
        $mform->setType('user_who['.self::USER_OWN.']', PARAM_INT);
        $checkgroup[] =& $mform->createElement('advcheckbox', 'user_who['.self::USER_DIRECT_REPORTS.']', '',
            get_string('userdirectreports', 'totara_reportbuilder'), null, array(0, 1));
        $mform->setType('user_who['.self::USER_DIRECT_REPORTS.']', PARAM_INT);
        $checkgroup[] =& $mform->createElement('advcheckbox', 'user_who['.self::USER_INDIRECT_REPORTS.']', '',
            get_string('userindirectreports', 'totara_reportbuilder'), null, array(0, 1));
        $mform->setType('user_who['.self::USER_INDIRECT_REPORTS.']', PARAM_INT);
        $checkgroup[] =& $mform->createElement('advcheckbox', 'user_who['.self::USER_TEMP_REPORTS.']', '',
            get_string('usertempreports', 'totara_reportbuilder'), null, array(0, 1));
        $mform->setType('user_who['.self::USER_TEMP_REPORTS.']', PARAM_INT);

        $mform->addGroup($checkgroup, 'user_who_group',
            get_string('includeuserrecords', 'totara_reportbuilder'), html_writer::empty_tag('br'), false);
        $usergroups = array(self::USER_OWN, self::USER_DIRECT_REPORTS, self::USER_INDIRECT_REPORTS, self::USER_TEMP_REPORTS);
        foreach ($usergroups as $usergroup) {
            // Bitwise comparison.
            if (($who & $usergroup) == $usergroup) {
                $mform->setDefault('user_who['.$usergroup.']', 1);
            }
        }
        $mform->disabledIf('user_who_group', 'contentenabled', 'eq', 0);
        $mform->disabledIf('user_who_group', 'user_enable', 'notchecked');
        $mform->addHelpButton('user_header', 'reportbuilderuser', 'totara_reportbuilder');
    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        $status = true;
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->user_enable) &&
            $fromform->user_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // Who checkbox option.
        // Enabled options are stored as user_who[key] = 1 when enabled.
        // Key is a bitwise value to be summed and stored.
        $whovalue = 0;
        $who = isset($fromform->user_who) ?
            $fromform->user_who : array();
        foreach ($who as $key => $option) {
            if ($option) {
                $whovalue += $key;
            }
        }
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'who', $whovalue);

        return $status;
    }
}


/*
 * Restrict content by a particular date
 *
 * Pass in an integer that contains a unix timestamp
 */
class rb_date_content extends rb_base_content {
    /**
     * Generate the SQL to apply this content restriction
     *
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $DB;
        $now = time();
        $financialyear = get_config('reportbuilder', 'financialyear');
        $month = substr($financialyear, 2, 2);
        $day = substr($financialyear, 0, 2);

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        // option to include empty date fields
        $includenulls = (isset($settings['incnulls']) &&
            $settings['incnulls']) ?
            " OR {$field} IS NULL OR {$field} = 0 " : " AND {$field} != 0 ";

        switch ($settings['when']) {
        case 'past':
            return array("({$field} < {$now} {$includenulls})", array());
        case 'future':
            return array("({$field} > {$now} {$includenulls})", array());
        case 'last30days':
            $sql = "( ({$field} < {$now}  AND {$field}  >
                ({$now} - 60*60*24*30)) {$includenulls})";
            return array($sql, array());
        case 'next30days':
            $sql = "( ({$field} > {$now} AND {$field} <
                ({$now} + 60*60*24*30)) {$includenulls})";
            return array($sql, array());
        case 'currentfinancial':
            $required_year = date('Y', $now);
            $year_before = $required_year - 1;
            $year_after = $required_year + 1;
            if (date('z', $now) >= date('z', mktime(0, 0, 0, $month, $day, $required_year))) {
                $start = mktime(0, 0, 0, $month, $day, $required_year);
                $end = mktime(0, 0, 0, $month, $day, $year_after);
            } else {
                $start = mktime(0, 0, 0, $month, $day, $year_before);
                $end = mktime(0, 0, 0, $month, $day, $required_year);
            }
            $sql = "( ({$field} >= {$start} AND {$field} <
                {$end}) {$includenulls})";
            return array($sql, array());
        case 'lastfinancial':
            $required_year = date('Y', $now) - 1;
            $year_before = $required_year - 1;
            $year_after = $required_year + 1;
            if (date('z', $now) >= date('z', mktime(0, 0, 0, $month, $day, $required_year))) {
                $start = mktime(0, 0, 0, $month, $day, $required_year);
                $end = mktime(0, 0, 0, $month, $day, $year_after);
            } else {
                $start = mktime(0, 0, 0, $month, $day, $year_before);
                $end = mktime(0, 0, 0, $month, $day, $required_year);
            }
            $sql = "( ({$field} >= {$start} AND {$field} <
                {$end}) {$includenulls})";
            return array($sql, array());
        default:
            // no match
            // using 1=0 instead of FALSE for MSSQL support
            return array("(1=0 {$includenulls})", array());
        }

    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        // option to include empty date fields
        $includenulls = (isset($settings['incnulls']) &&
                         $settings['incnulls']) ? " (or $title is empty)" : '';

        switch ($settings['when']) {
        case 'past':
            return $title . ' ' . get_string('occurredbefore', 'totara_reportbuilder') . ' ' .
                userdate(time(), '%c'). $includenulls;
        case 'future':
            return $title . ' ' . get_string('occurredafter', 'totara_reportbuilder') . ' ' .
                userdate(time(), '%c'). $includenulls;
        case 'last30days':
            return $title . ' ' . get_string('occurredafter', 'totara_reportbuilder') . ' ' .
                userdate(time() - 60*60*24*30, '%c') . get_string('and', 'totara_reportbuilder') .
                get_string('occurredbefore', 'totara_reportbuilder') . userdate(time(), '%c') .
                $includenulls;

        case 'next30days':
            return $title . ' ' . get_string('occurredafter', 'totara_reportbuilder') . ' ' .
                userdate(time(), '%c') . get_string('and', 'totara_reportbuilder') .
                get_string('occurredbefore', 'totara_reportbuilder') .
                userdate(time() + 60*60*24*30, '%c') . $includenulls;
        case 'currentfinancial':
            return $title . ' ' . get_string('occurredthisfinancialyear', 'totara_reportbuilder') .
                $includenulls;
        case 'lastfinancial':
            return $title . ' ' . get_string('occurredprevfinancialyear', 'totara_reportbuilder') .
                $includenulls;
        default:
            return 'Error with date content restriction';
        }
    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        // get current settings
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $when = reportbuilder::get_setting($reportid, $type, 'when');
        $incnulls = reportbuilder::get_setting($reportid, $type, 'incnulls');

        $mform->addElement('header', 'date_header', get_string('showbyx',
            'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('date_header');
        $mform->addElement('checkbox', 'date_enable', '',
            get_string('showbasedonx', 'totara_reportbuilder',
            lcfirst($title)));
        $mform->setDefault('date_enable', $enable);
        $mform->disabledIf('date_enable', 'contentenabled', 'eq', 0);
        $radiogroup = array();
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('thepast', 'totara_reportbuilder'), 'past');
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('thefuture', 'totara_reportbuilder'), 'future');
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('last30days', 'totara_reportbuilder'), 'last30days');
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('next30days', 'totara_reportbuilder'), 'next30days');
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('currentfinancial', 'totara_reportbuilder'), 'currentfinancial');
        $radiogroup[] =& $mform->createElement('radio', 'date_when', '',
            get_string('lastfinancial', 'totara_reportbuilder'), 'lastfinancial');
        $mform->addGroup($radiogroup, 'date_when_group',
            get_string('includerecordsfrom', 'totara_reportbuilder'), html_writer::empty_tag('br'), false);
        $mform->setDefault('date_when', $when);
        $mform->disabledIf('date_when_group', 'contentenabled', 'eq', 0);
        $mform->disabledIf('date_when_group', 'date_enable', 'notchecked');
        $mform->addHelpButton('date_header', 'reportbuilderdate', 'totara_reportbuilder');

        $mform->addElement('checkbox', 'date_incnulls',
            get_string('includeemptydates', 'totara_reportbuilder'));
        $mform->setDefault('date_incnulls', $incnulls);
        $mform->disabledIf('date_incnulls', 'date_enable', 'notchecked');
        $mform->disabledIf('date_incnulls', 'contentenabled', 'eq', 0);
    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        $status = true;
        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->date_enable) &&
            $fromform->date_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // when radio option
        $when = isset($fromform->date_when) ?
            $fromform->date_when : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'when', $when);

        // include nulls checkbox option
        $incnulls = (isset($fromform->date_incnulls) &&
            $fromform->date_incnulls) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'incnulls', $incnulls);

        return $status;
    }
}


/*
 * Restrict content by offical tags
 *
 * Pass in a column that contains a pipe '|' separated list of official tag ids
 */
class rb_tag_content extends rb_base_content {
    /**
     * Generate the SQL to apply this content restriction
     *
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);

        $include_sql = array();
        $exclude_sql = array();

        // get arrays of included and excluded tags
        $settings = reportbuilder::get_all_settings($reportid, $type);
        $itags = ($settings['included']) ?
            explode('|', $settings['included']) : array();
        $etags = ($settings['excluded']) ?
            explode('|', $settings['excluded']) : array();
        $include_logic = (isset($settings['include_logic']) &&
            $settings['include_logic'] == 0) ? ' AND ' : ' OR ';
        $exclude_logic = (isset($settings['exclude_logic']) &&
            $settings['exclude_logic'] == 0) ? ' OR ' : ' AND ';

        // loop through current official tags
        $tags = $DB->get_records('tag', array('isstandard' => 1), 'name');
        $params = array();
        $count = 1;
        foreach ($tags as $tag) {
            // if found, add the SQL
            // we can't just use LIKE '%tag%' because we might get
            // partial number matches
            if (in_array($tag->id, $itags)) {
                $uniqueparam = rb_unique_param("cctre_{$count}_");
                $elike = $DB->sql_like($field, ":{$uniqueparam}");
                $params[$uniqueparam] = $DB->sql_like_escape($tag->id);

                $uniqueparam = rb_unique_param("cctrew_{$count}_");
                $ewlike = $DB->sql_like($field, ":{$uniqueparam}");
                $params[$uniqueparam] = $DB->sql_like_escape($tag->id).'|%';

                $uniqueparam = rb_unique_param("cctrsw_{$count}_");
                $swlike = $DB->sql_like($field, ":{$uniqueparam}");
                $params[$uniqueparam] = '%|'.$DB->sql_like_escape($tag->id);

                $uniqueparam = rb_unique_param("cctrsc_{$count}_");
                $clike = $DB->sql_like($field, ":{$uniqueparam}");
                $params[$uniqueparam] = '%|'.$DB->sql_like_escape($tag->id).'|%';

                $include_sql[] = "({$elike} OR
                {$ewlike} OR
                {$swlike} OR
                {$clike})\n";

                $count++;
            }
            if (in_array($tag->id, $etags)) {
                $uniqueparam = rb_unique_param("cctre_{$count}_");
                $enotlike = $DB->sql_like($field, ":{$uniqueparam}", true, true, true);
                $params[$uniqueparam] = $DB->sql_like_escape($tag->id);

                $uniqueparam = rb_unique_param("cctrew_{$count}_");
                $ewnotlike = $DB->sql_like($field, ":{$uniqueparam}", true, true, true);
                $params[$uniqueparam] = $DB->sql_like_escape($tag->id).'|%';

                $uniqueparam = rb_unique_param("cctrsw_{$count}_");
                $swnotlike = $DB->sql_like($field, ":{$uniqueparam}", true, true, true);
                $params[$uniqueparam] = '%|'.$DB->sql_like_escape($tag->id);

                $uniqueparam = rb_unique_param("cctrsc_{$count}_");
                $cnotlike = $DB->sql_like($field, ":{$uniqueparam}", true, true, true);
                $params[$uniqueparam] = '%|'.$DB->sql_like_escape($tag->id).'|%';

                $include_sql[] = "({$enotlike} AND
                {$ewnotlike} AND
                {$swnotlike} AND
                {$cnotlike})\n";

                $count++;
            }
        }

        // merge the include and exclude strings separately
        $includestr = implode($include_logic, $include_sql);
        $excludestr = implode($exclude_logic, $exclude_sql);

        // now merge together
        if ($includestr && $excludestr) {
            return array(" ($includestr AND $excludestr) ", $params);
        } else if ($includestr) {
            return array(" $includestr ", $params);
        } else if ($excludestr) {
            return array(" $excludestr ", $params);
        } else {
            // using 1=0 instead of FALSE for MSSQL support
            return array('1=0', $params);
        }
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $settings = reportbuilder::get_all_settings($reportid, $type);

        $include_text = array();
        $exclude_text = array();

        $itags = ($settings['included']) ?
            explode('|', $settings['included']) : array();
        $etags = ($settings['excluded']) ?
            explode('|', $settings['excluded']) : array();
        $include_logic = (isset($settings['include_logic']) &&
            $settings['include_logic'] == 0) ? 'and' : 'or';
        $exclude_logic = (isset($settings['exclude_logic']) &&
            $settings['exclude_logic'] == 0) ? 'and' : 'or';

        $tags = $DB->get_records('tag', array('isstandard' => 1), 'name');
        foreach ($tags as $tag) {
            if (in_array($tag->id, $itags)) {
                $include_text[] = '"' . $tag->name . '"';
            }
            if (in_array($tag->id, $etags)) {
                $exclude_text[] = '"' . $tag->name . '"';
            }
        }

        if (count($include_text) > 0) {
            $includestr = $title . ' ' . get_string('istaggedwith', 'totara_reportbuilder') .
                ' ' . implode(get_string($include_logic, 'totara_reportbuilder'), $include_text);
        } else {
            $includestr = '';
        }
        if (count($exclude_text) > 0) {
            $excludestr = $title . ' ' . get_string('isnttaggedwith', 'totara_reportbuilder') .
                ' ' . implode(get_string($exclude_logic, 'totara_reportbuilder'), $exclude_text);
        } else {
            $excludestr = '';
        }

        if ($includestr && $excludestr) {
            return $includestr . get_string('and', 'totara_reportbuilder') . $excludestr;
        } else if ($includestr) {
            return $includestr;
        } else if ($excludestr) {
            return $excludestr;
        } else {
            return '';
        }

    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        global $DB;

        // remove rb_ from start of classname
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');
        $include_logic = reportbuilder::get_setting($reportid, $type, 'include_logic');
        $exclude_logic = reportbuilder::get_setting($reportid, $type, 'exclude_logic');
        $activeincludes = explode('|',
            reportbuilder::get_setting($reportid, $type, 'included'));
        $activeexcludes = explode('|',
            reportbuilder::get_setting($reportid, $type, 'excluded'));

        $mform->addElement('header', 'tag_header',
            get_string('showbytag', 'totara_reportbuilder'));
        $mform->setExpanded('tag_header');
        $mform->addHelpButton('tag_header', 'reportbuildertag', 'totara_reportbuilder');

        $mform->addElement('checkbox', 'tag_enable', '',
            get_string('tagenable', 'totara_reportbuilder'));
        $mform->setDefault('tag_enable', $enable);
        $mform->disabledIf('tag_enable', 'contentenabled', 'eq', 0);

        $mform->addElement('html', html_writer::empty_tag('br'));

        // include the following tags
        $tags = $DB->get_records('tag', array('isstandard' => 1), 'name');
        if (!empty($tags)) {
            $checkgroup = array();
            $opts = array(1 => get_string('anyofthefollowing', 'totara_reportbuilder'),
                          0 => get_string('allofthefollowing', 'totara_reportbuilder'));
            $mform->addElement('select', 'tag_include_logic', get_string('includetags', 'totara_reportbuilder'), $opts);
            $mform->setDefault('tag_include_logic', $include_logic);
            $mform->disabledIf('tag_enable', 'contentenabled', 'eq', 0);
            foreach ($tags as $tag) {
                $checkgroup[] =& $mform->createElement('checkbox',
                    'tag_include_option_' . $tag->id, '', $tag->name, 1);
                $mform->disabledIf('tag_include_option_' . $tag->id,
                    'tag_exclude_option_' . $tag->id, 'checked');
                if (in_array($tag->id, $activeincludes)) {
                    $mform->setDefault('tag_include_option_' . $tag->id, 1);
                }
            }
            $mform->addGroup($checkgroup, 'tag_include_group', '', html_writer::empty_tag('br'), false);
        }
        $mform->disabledIf('tag_include_group', 'contentenabled', 'eq', 0);
        $mform->disabledIf('tag_include_group', 'tag_enable',
            'notchecked');

        $mform->addElement('html', str_repeat(html_writer::empty_tag('br'), 2));

        // exclude the following tags
        if (!empty($tags)) {
            $checkgroup = array();
            $opts = array(1 => get_string('anyofthefollowing', 'totara_reportbuilder'),
                          0 => get_string('allofthefollowing', 'totara_reportbuilder'));
            $mform->addElement('select', 'tag_exclude_logic', get_string('excludetags', 'totara_reportbuilder'), $opts);
            $mform->setDefault('tag_exclude_logic', $exclude_logic);
            $mform->disabledIf('tag_enable', 'contentenabled', 'eq', 0);
            foreach ($tags as $tag) {
                $checkgroup[] =& $mform->createElement('checkbox',
                    'tag_exclude_option_' . $tag->id, '', $tag->name, 1);
                $mform->disabledIf('tag_exclude_option_' . $tag->id,
                    'tag_include_option_' . $tag->id, 'checked');
                if (in_array($tag->id, $activeexcludes)) {
                    $mform->setDefault('tag_exclude_option_' . $tag->id, 1);
                }
            }
            $mform->addGroup($checkgroup, 'tag_exclude_group', '', html_writer::empty_tag('br'), false);
        }
        $mform->disabledIf('tag_exclude_group', 'contentenabled', 'eq', 0);
        $mform->disabledIf('tag_exclude_group', 'tag_enable',
            'notchecked');

    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        global $DB;

        $status = true;
        // remove the rb_ from class
        $type = substr(get_class($this), 3);

        // enable checkbox option
        $enable = (isset($fromform->tag_enable) &&
            $fromform->tag_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        // include with any or all
        $includelogic = (isset($fromform->tag_include_logic) &&
            $fromform->tag_include_logic) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'include_logic', $includelogic);

        // exclude with any or all
        $excludelogic = (isset($fromform->tag_exclude_logic) &&
            $fromform->tag_exclude_logic) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'exclude_logic', $excludelogic);

        // tag settings
        $tags = $DB->get_records('tag', array('isstandard' => 1));
        if (!empty($tags)) {
            $activeincludes = array();
            $activeexcludes = array();
            foreach ($tags as $tag) {
                $includename = 'tag_include_option_' . $tag->id;
                $excludename = 'tag_exclude_option_' . $tag->id;

                // included tags
                if (isset($fromform->$includename)) {
                    if ($fromform->$includename == 1) {
                        $activeincludes[] = $tag->id;
                    }
                }

                // excluded tags
                if (isset($fromform->$excludename)) {
                    if ($fromform->$excludename == 1) {
                        $activeexcludes[] = $tag->id;
                    }
                }

            }

            // implode into string and update setting
            $status = $status && reportbuilder::update_setting($reportid,
                $type, 'included', implode('|', $activeincludes));

            // implode into string and update setting
            $status = $status && reportbuilder::update_setting($reportid,
                $type, 'excluded', implode('|', $activeexcludes));
        }
        return $status;
    }
}

/*
 * Restrict content by availability
 *
 * Pass in a column that contains a pipe '|' separated list of official tag ids
 *
 * @deprecated Since Totara 12.0
 */
class rb_prog_availability_content extends rb_base_content {
    /**
     * Generate the SQL to apply this content restriction
     *
     * @deprecated Since Totara 12.0
     * @param string $field SQL field to apply the restriction against
     * @param integer $reportid ID of the report
     *
     * @return array containing SQL snippet to be used in a WHERE clause, as well as array of SQL params
     */
    public function sql_restriction($field, $reportid) {
        debugging('rb_prog_availability_content::sql_restriction has been deprecated since Totara 12.0', DEBUG_DEVELOPER);

        // The restriction snippet based on the available fields was moved to totara_visibility_where.
        // So no restriction for programs or certifications.
        $restriction = " 1=1 ";

        return array($restriction, array());
    }

    /**
     * Generate a human-readable text string describing the restriction
     *
     * @deprecated Since Totara 12.0
     * @param string $title Name of the field being restricted
     * @param integer $reportid ID of the report
     *
     * @return string Human readable description of the restriction
     */
    public function text_restriction($title, $reportid) {
        debugging('rb_prog_availability_content::text_restriction has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        return get_string('contentavailability', 'totara_program');
    }


    /**
     * Adds form elements required for this content restriction's settings page
     *
     * @deprecated Since Totara 12.0
     * @param object &$mform Moodle form object to modify (passed by reference)
     * @param integer $reportid ID of the report being adjusted
     * @param string $title Name of the field the restriction is acting on
     */
    public function form_template(&$mform, $reportid, $title) {
        debugging('rb_prog_availability_content::form_template has been deprecated since Totara 12.0', DEBUG_DEVELOPER);

        global $DB;

        // Get current settings and
        // remove rb_ from start of classname.
        $type = substr(get_class($this), 3);
        $enable = reportbuilder::get_setting($reportid, $type, 'enable');

        $mform->addElement('header', 'prog_availability_header',
            get_string('showbyx', 'totara_reportbuilder', lcfirst($title)));
        $mform->setExpanded('prog_availability_header');
        $mform->addElement('checkbox', 'prog_availability_enable', '',
            get_string('contentavailability', 'totara_program'));
        $mform->setDefault('prog_availability_enable', $enable);
        $mform->disabledIf('prog_availability_enable', 'contentenabled', 'eq', 0);
        $mform->addHelpButton('prog_availability_header', 'contentavailability', 'totara_program');

    }


    /**
     * Processes the form elements created by {@link form_template()}
     *
     * @deprecated Since Totara 12.0
     * @param integer $reportid ID of the report to process
     * @param object $fromform Moodle form data received via form submission
     *
     * @return boolean True if form was successfully processed
     */
    public function form_process($reportid, $fromform) {
        debugging('rb_prog_availability_content::form_process has been deprecated since Totara 12.0', DEBUG_DEVELOPER);

        global $DB;

        $status = true;
        // Remove rb_ from start of classname.
        $type = substr(get_class($this), 3);

        // Enable checkbox option.
        $enable = (isset($fromform->prog_availability_enable) &&
            $fromform->prog_availability_enable) ? 1 : 0;
        $status = $status && reportbuilder::update_setting($reportid, $type,
            'enable', $enable);

        return $status;

    }
}

// Include trainer content restriction
include_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_trainer_content.php');
// Include session roles content restriction.
include_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_session_roles_content.php');
// Include report access content restriction.
include_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_report_access_content.php');
// Include saved search access content restriction.
include_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_saved_search_access_content.php');
// Include audience restriction.
include_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_audience_content.php');
