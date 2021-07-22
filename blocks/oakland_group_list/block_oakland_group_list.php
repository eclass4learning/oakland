<?php

class block_oakland_group_list extends block_base {

    public function get_content() {
        global $DB;

        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';

        $output = $this->page->get_renderer('block_oakland_group_list');
        $this->content->text = $output->oakland_group_list();
    }
    public function init() {
        $this->title = get_string('pluginname', 'block_oakland_group_list');
    }
}