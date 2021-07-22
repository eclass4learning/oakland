<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 4:34 PM
 */

class block_oakland_group_bio extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_oakland_group_bio');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function applicable_formats() {
        return array(                            //TODO: ?
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true
        );
    }

    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_oakland_group_bio');
        } else {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';

        $output = $this->page->get_renderer('block_oakland_group_bio');
        $instance = $this->instance;
        $groupbioblock = $DB->get_record('block_oakland_groups_bio', array('blockinstanceid'=>$instance->id));
        $group = null;
        if ($groupbioblock != null) {
            $group = $DB->get_record('oakland_groups',array('id'=>$groupbioblock->oaklandgroupid));
        }
        if ($group != null) {
            $this->content->text = $output->oakland_group_bio($group);
        }else{
            $this->content->text = $output->no_group_selected();
        }

        return $this->content;
    }
}
