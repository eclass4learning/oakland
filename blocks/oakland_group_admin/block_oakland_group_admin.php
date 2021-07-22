<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/18/15
 * Time: 9:43 AM
 * To change this template use File | Settings | File Templates.
 */
class block_oakland_group_admin extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_oakland_group_admin');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function get_content() {
        global $DB;
        require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
        $this->content = new stdClass();
        $this->content->text = '';
        $output = $this->page->get_renderer('block_oakland_group_admin');
        $id = optional_param('id',0,PARAM_INT);
        $dashboard = $DB->get_record('totara_dashboard', array('id'=>$id));
        if ($dashboard != null) {
            $group = $DB->get_record('oakland_groups', array('id'=>$dashboard->oaklandgroupid));
            if ($group != null) {
                $this->content->text = $output->display_admin_block($group);
            }
            else
            {
                $this->content->text = 'No group to display';
            }
        }
        else
        {
            $this->content->text = 'No group to display';
        }

    }
}
