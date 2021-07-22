<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/4/15
 * Time: 10:29 AM
 * To change this template use File | Settings | File Templates.
 */

class block_forum extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_forum');
    }

    function has_config() {
        return true;
    }

    function instance_allow_multiple() {
        return true;
    }

    function specialization() {
        if($this->config){
            if ($this->config->forumtodisplay == 'Got It') {
                $this->title = get_string('gotit', 'block_forum');
            } else if ($this->config->forumtodisplay == 'Want It') {
                $this->title = get_string('wantit', 'block_forum');
            }
        }
    }

    public function get_content() {
        global $DB;

        // use block configuration if it exists
        if (isset($this->config->forumtodisplay)) {
            $forum_to_display = $this->config->forumtodisplay;
        } else {
            $forum_to_display = 'Group';
        }

        $output = $this->page->get_renderer('block_forum');

        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';

        if(isset($_REQUEST['id'])){
            $dashboard = $DB->get_record('totara_dashboard', array('id'=>$_REQUEST['id']));
        }else{
            $dashboard = false;
        }
        if ($dashboard) {
            $maxpostsshown = get_config('blocks/forum','maxpostsshown');

            if (isset($dashboard->oaklandgroupid) && $dashboard->oaklandgroupid != 0) {
                $course = $DB->get_record('course', array('oaklandgroupid'=>$dashboard->oaklandgroupid));
                $forums = array();
                if ($forum_to_display == 'Group') {
                    $forums = $DB->get_records('forum', array('course'=>$course->id));
                } else if ($forum_to_display == 'Got It') {
                    $forums = $DB->get_records('forum', array('course'=>$course->id,'name'=>'Got It'));
                } else if ($forum_to_display == 'Want It') {
                    $forums = $DB->get_records('forum', array('course'=>$course->id,'name'=>'Want It'));
                }
                if (empty($forums)) {
                    $this->content->text = get_string('noforumtodisplay', 'block_forum');
                } else if (sizeof($forums) != 1) {
                    $this->content->text = get_string('morethanoneforum', 'block_forum');
                } else {
                    foreach ($forums as $forum) {
                        $discussions = $DB->get_records_select('forum_discussions', 'forum = ' . $forum->id . ' order by timemodified desc limit ' . $maxpostsshown);
                        $this->content->text = $output->forum_list($discussions, $forum, $course, $forum_to_display);
                    }
                }
            } else {
                $this->content->text = get_string('noforumtodisplay', 'block_forum');
            }
        } else {
            $this->content->text = get_string('noforumtodisplay', 'block_forum');
        }
    }

    function applicable_formats() {
        return array('all' => true, 'mod' => false, 'my' => true, 'admin' => false,
            'tag' => false, 'course-view' => false);
    }
}