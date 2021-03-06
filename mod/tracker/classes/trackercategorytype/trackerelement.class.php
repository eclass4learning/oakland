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

defined('MOODLE_INTERNAL') || die();

/**
 * @package mod_tracker
 * @category mod
 * @author Clifford Tham, Valery Fremaux > 1.8
 * @date 02/12/2007
 */

/**
 * A generic class for collecting all that is common to all elements
 */

abstract class trackerelement {
    var $id;
    var $course;
    var $usedid;
    var $name;
    var $description;
    var $format;
    var $type;
    var $sortorder;
    var $maxorder;
    var $value;
    var $options;
    var $tracker;
    var $active;
    var $private;
    var $canbemodifiedby;
    var $context;

    function __construct(&$tracker, $elementid = null, $used = false) {
        global $DB;

        $this->id = $elementid;

        if ($elementid && $used) {
            $elmusedrec = $DB->get_record('tracker_elementused', array('id' => $elementid));
            $this->usedid = $elementid;
            $elementid = $elmusedrec->elementid;
            $this->active = $elmusedrec->active;
            $this->mandatory = $elmusedrec->mandatory;
            $this->private = $elmusedrec->private;
            $this->sortorder = $elmusedrec->sortorder;
            $this->canbemodifiedby = $elmusedrec->canbemodifiedby;
        }

        if ($elementid) {
            $elmrec = $DB->get_record('tracker_element', array('id' => $elementid));
            $this->id = $elmrec->id;
            $this->name = $elmrec->name;
            $this->description = $elmrec->description;
            $this->course = $elmrec->course;
            $this->type = $elmrec->type;
        }

        $this->options = null;
        $this->value = null;
        $this->tracker = $tracker;
    }

    /**
     * If true, element is like a select or a radio box array
     * and has suboptions to define
     */
    function type_has_options() {
        return false;
    }

    /**
     * Tells if options are defined for thsi instance
     */
    function hasoptions() {
        return $this->options !== null;
    }

    /** 
     * Get an option value
     */
    function getoption($optionid) {
        return $this->options[$optionid];
    }

    /** 
     * Sets the option list
     */
    function setoptions($options) {
        $this->options = $options;
    }

    /**
     * If true, this element can be told to be mandatory.
     */
    function has_mandatory_option() {
        return true;
    }

    /**
     * If true, this element can be told to be private.
     * A private element can be edited by the ticket operators,
     * but is not seen by ticket owners.
     */
    function has_private_option() {
        return true;
    }

    function setcontext(&$context) {
        $this->context = $context;
    }

    /**
     * in case we have options (such as checkboxes or radio lists, get options from db.
     * this is backcalled by specific type constructors after core construction.
     */
    function setoptionsfromdb() {
        global $DB;

        if (isset($this->id)) {
            $this->options = $DB->get_records_select('tracker_elementitem', " elementid = ? AND active = 1 ORDER BY sortorder", array($this->id));
            if ($this->options) {
                foreach ($this->options as $option) {
                    $this->maxorder = max($option->sortorder, $this->maxorder);
                }
            } else {
                $this->maxorder = 0;
            }
        } else {
            print_error ('errorinvalidelementID', 'tracker');
        }
    }

    /**
     * Gets the current value for this element instance
     * in an issue
     */
    function getvalue($issueid) {
        global $CFG, $DB;

        if (!$issueid) {
            return '';
        }

        $sql = "
            SELECT
                elementitemid
            FROM
                {tracker_issueattribute}
            WHERE
                elementid = {$this->id} AND
                issueid = {$issueid}
        ";
        $this->value = $DB->get_field_sql($sql);
        return($this->value);
    }

    function getname() {
        return $this->name;
    }

    function optionlistview($cm) {
        global $CFG, $COURSE, $OUTPUT;

        $strname = get_string('name');
        $strdescription = get_string('description');
        $strsortorder = get_string('sortorder', 'tracker');
        $straction = get_string('action');
        $table = new html_table();
        $table->width = "90%";
        $table->size = array('15%', '15%', '50%', '30%');
        $table->head = array('', "<b>$strname</b>","<b>$strdescription</b>","<b>$straction</b>");
        if (!empty($this->options)) {
            foreach ($this->options as $option) {
                $params = array('id' => $cm->id, 'view' => 'admin', 'what' => 'editelementoption', 'optionid' => $option->id, 'elementid' => $option->elementid);
                $editoptionurl = new moodle_url('/mod/tracker/view.php', $params);
                $actions  = '<a href="'.$editoptionurl.'" title="'.get_string('edit').'"><img src="'.$OUTPUT->pix_url('/t/edit', 'core').'" /></a>&nbsp;';

                $img = ($option->sortorder > 1) ? 'up' : 'up_shadow';
                $params = array('id' => $cm->id, 'view' => 'admin', 'what' => 'moveelementoptionup', 'optionid' => $option->id, 'elementid' => $option->elementid);
                $moveurl = new moodle_url('/mod/tracker/view.php', $params);
                $actions .= '<a href="'.$moveurl.'" title="'.get_string('up').'"><img src="'.$OUTPUT->pix_url("{$img}", 'mod_tracker').'"></a>&nbsp;';

                $img = ($option->sortorder < $this->maxorder) ? 'down' : 'down_shadow' ;
                $params = array('id' => $cm->id, 'view' => 'admin', 'what' => 'moveelementoptiondown', 'optionid' => $option->id, 'elementid' => $option->elementid);
                $moveurl = new moodle_url('/mod/tracker/view.php', $params);
                $actions .= '<a href="'.$moveurl.'" title="'.get_string('down').'"><img src="'.$OUTPUT->pix_url("{$img}", 'mod_tracker').'"></a>&nbsp;';

                $params = array('id' => $cm->id, 'view' => 'admin', 'what' => 'deleteelementoption', 'optionid' => $option->id, 'elementid' => $option->elementid);
                $deleteurl = new moodle_url('/mod/tracker/view.php', $params);
                $actions .= '<a href="'.$deleteurl.'" title="'.get_string('delete').'"><img src="'.$OUTPUT->pix_url('/t/delete', 'core').'"></a>';
                $table->data[] = array('<b> '.get_string('option', 'tracker').' '.$option->sortorder.':</b>',$option->name, format_string($option->description, true, $COURSE->id), $actions);
            }
        }
        return html_writer::table($table);
    }

    function viewsearch() {
        $this->edit();
    }

    function viewquery() {
        $this->view(true);
    }

    /**
     * given a tracker and an element form key in a static context,
     * build a suitable trackerelement object that represents it.
     */
    static function find_instance(&$tracker, $elementkey) {
        global $DB;

        $elmname = preg_replace('/^element/', '', $elementkey);

        $sql = "
            SELECT
                e.*,
                eu.id as usedid
            FROM
                {tracker_element} e,
                {tracker_elementused} eu
            WHERE
                e.id = eu.elementid AND
                eu.trackerid = ? AND
                e.name = ?
        ";

        if ($element = $DB->get_record_sql($sql, array($tracker->id, $elmname))) {

            $eltypeconstuctor = $element->type.'element';
            $instance = new $eltypeconstuctor($tracker, $element->id);
            return $element;
        }

        return null;
    }

    /**
     * Get the element view when the ticket is being edited
     */
    abstract function edit($issueid = 0);

    /**
     * Get the element view when the ticket is being displayed
     */
    abstract function view($issueid = 0);

    /**
     * Provides the form element when building a new element instance
     */
    abstract function add_form_element(&$mform);

    abstract function formprocess(&$data);

    /**
     * given a tracker and an id of a used element in a static context,
     * build a suitable trackerelement object that represents it.
     * what we need to knwo is the type of the element to call the adequate
     * constructor.
     */
    static function find_instance_by_usedid(&$tracker, $usedid) {
        global $DB, $CFG;

        $sql = "
            SELECT
                eu.id,
                e.type
            FROM
                {tracker_element} e,
                {tracker_elementused} eu
            WHERE
                e.id = eu.elementid AND
                eu.id = ?
        ";

        if ($element = $DB->get_record_sql($sql, array($usedid))) {

            $eltypeconstructor = $element->type.'element';
            include_once($CFG->dirroot.'/mod/tracker/classes/trackercategorytype/'.$element->type.'/'.$element->type.'.class.php');
            $instance = new $eltypeconstructor($tracker, $usedid, true);
            return $instance;
        }

        return null;
    }

    /**
     * given a tracker and an id of a used element in a static context,
     * build a suitable trackerelement object that represents it.
     * what we need to knwo is the type of the element to call the adequate
     * constructor.
     */
    static function find_instance_by_id(&$tracker, $id) {
        global $DB, $CFG;

        if ($element = $DB->get_record('tracker_element', array('id' => $id), 'id, type', 'id')) {
            $eltypeconstructor = $element->type.'element';
            include_once($CFG->dirroot.'/mod/tracker/classes/trackercategorytype/'.$element->type.'/'.$element->type.'.class.php');
            $instance = new $eltypeconstructor($tracker, $id, false);
            return $instance;
        }

        return null;
    }
}
