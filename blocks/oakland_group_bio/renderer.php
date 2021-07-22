<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 4:35 PM
 */

class block_oakland_group_bio_renderer extends plugin_renderer_base {

    /**
     * Construct contents of oakland_group_bio block
     * @param $group
     * @return string
     */
    public function oakland_group_bio($group) {
        global $DB, $CFG;
        $html = '';

        /**
         * Get the Group Image, if any
         */

        $context = context_system::instance();
        $fs = get_file_storage();
        $filename = 'f1.png';
        $imagevalue = '';
        $hasuploadedpicture = $fs->file_exists($context->id, 'oakland_groups', 'grouplogo', $group->id, '/', $filename);
        if (!empty($group->logo) && $hasuploadedpicture) {
            global $CFG;
            $component = 'oakland_groups';
            $filearea = 'grouplogo';
            if($file_record = $DB->get_record('files',array('id'=>$group->logo))){
                $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/$context->id/$component/$filearea/$file_record->itemid".'/'.$filename);
                $imagevalue = html_writer::img($url,'',array('style'=>'float:left; margin:10px; margin-right:12px;'));
            }
        }
        $html .= $imagevalue;
        /**
         * Display Description and Purpose
         */
        $html .= html_writer::tag('h4',get_string('name','block_oakland_group_bio'));
        $html .= html_writer::tag('p',$group->name);
        $html .= html_writer::tag('h4',get_string('description','block_oakland_group_bio'));
        $html .= html_writer::tag('p',$group->description);
        $html .= html_writer::tag('h4',get_string('purpose','block_oakland_group_bio'));
        $html .= html_writer::tag('p',$group->purpose);
        return $html;
    }

    public function no_group_selected(){
        $html = '';
        $html .= html_writer::tag('h4',get_string('nogroupselected','block_oakland_group_bio'));
        $html .= html_writer::tag('p',get_string('editblockfix','block_oakland_group_bio'));
        return $html;
    }
}