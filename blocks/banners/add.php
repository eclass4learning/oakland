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

/**
 * Manage files in folder in private area.
 *
 * @package   moodle
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
            
require('../../config.php');
require_once("add_form.php");
require_once("lib.php");
require_once("$CFG->dirroot/repository/lib.php");

global $OUTPUT;
$blockid = optional_param('id', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);
require_login();
if (isguestuser()) {
    die(); 
}
$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
if (empty($returnurl)) {
    $returnurl = new moodle_url('add.php');
}else{
    $returnurl = new moodle_url($returnurl);
    $returnurl->params(array('id'=>$courseid));
}
$context = context_block::instance($blockid);

$title = get_string('addbanner','block_banners');
$PAGE->set_url('/blocks/banners/add.php');
$PAGE->set_context($context);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('mydashboard');

$data = new stdClass();
$data->block_id = $blockid;
$data->courseid = $courseid;
$data->returnurl = $returnurl;
$data->contextid = $context->id;
$options = array('subdirs'=>false, 'maxbytes'=>$CFG->userquota, 'maxfiles'=>-1, 'accepted_types'=> array('*.gif', '*.jpg'), 'return_types'=>FILE_INTERNAL);

$fs = get_file_storage();

// process any incoming delete request         
$delete=optional_param('delete',0,PARAM_INT);
$msg='';
if($delete){
    deletebanner($fs,$context->id,$delete);
    $msg = get_string('bannerdeleted','block_banners'); 
}

// grab the block config data - width/height/speed etc..
if ($configdata = $DB->get_field('block_instances', 'configdata', array('id' => $blockid))) {
            $config = unserialize(base64_decode($configdata));
}else{
   $config=new stdClass();  
   $config->height= 400;
   $config->width = 822;
   $config->maxday = 10;
   $config->permission = 1;
   $config->speed = 5;
   $config->title = '';
    
}

$mform = new banner_upload_form(null, array('data'=>$data, 'options'=>$options, 'config'=>$config));
$newid = -1;
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($formdata = $mform->get_data()) {
    $draftitemid = $formdata->files;
    $valid=false;    
    $files = file_get_drafarea_files($draftitemid);
    if (count($files->list) == 1) {
        if ($draftitemid) {
            // move file from draft area
            file_save_draft_area_files($draftitemid, $context->id, 'block_banners', 'content', $draftitemid );
            $files = $fs->get_area_files($context->id, 'block_banners', 'content', $draftitemid);
            foreach($files as $f){
               if($f->get_filesize()>0){
                   // check the image meets specification - otherwise delete it
                   $x=$f->get_imageinfo();  
                   if($x['width'] <= $config->width && $x['height'] <= $config->height && ($x['mimetype'] == 'image/jpeg' || $x['mimetype'] == 'image/gif' || $x['mimetype'] == 'image/png') ){
                       $valid=true;
                       $newid=$f->get_id(); 
                   }else{
                      $f->delete(); 
                   }
               }
            }
        }
    }
    if($valid){
        $new = new stdClass();
        $new->fileid = $newid;
        $new->url = $formdata->urllink;
        $new->timeinput=date("Ymd", $formdata->timeavailablefrom);
        $new->displayuntil = date("Ymd",$formdata->timeavailabletill);
        $DB->insert_record('block_banners',$new,false);
        redirect($returnurl);
    }else{
        $a = new stdClass();
        $a->height = $config->height;
        $a->width = $config->width;
        $msg = $OUTPUT->box(get_string('badimage','block_banners', $a),'errorbox',null);
    }
}
 
echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox');
$can_add_banner =  ($config->permission>0)
                || has_capability('moodle/block:edit', $context, $USER->id);
if($can_add_banner){
    if($msg){
        echo $msg;
    }
    $mform->display();
}else{
    echo html_writer::tag('h2',get_string('nopermission','block_banners'));
    
}

// display users banners - or all banners for editor
// allow delete - purge banners greater than two years old
$admin = has_capability('moodle/site:config', $context, $USER->id);

echo '<hr/>' . ($admin?get_string('adminbannerreview','block_banners'):get_string('bannerreview','block_banners'));
$deleteicon='<img src="'.$OUTPUT->pix_url('t/delete') . '" class="icon" alt="delete banner" />';

$files = $fs->get_area_files($context->id, 'block_banners', 'content');        
if($files){
    foreach($files as $file){
        if($file->get_filesize()>0){
            if($admin||$USER->id==$file->get_userid()){
                $fid=$file->get_id();
                $banner=$DB->get_record('block_banners',array('fileid'=>$fid));
                $delete_old_images = get_config('block_banners','deleteyearoldimages');
                // if $delete_old_images is true, purge banners after more than 1 yr passed
                // [this keeps file available for re-download and re-posting for at least an academic year]
                // but doesn't keep the file for ever

                if($delete_old_images && substr($banner->displayuntil,0,6)<date('Ym')-200){
                    deletebanner($fs,$context->id,$file->get_itemid());
                }else{
                    $url = "{$CFG->wwwroot}/pluginfile.php/{$file->get_contextid()}/block_banners/content/";
                    $filename = $file->get_filename();
                    $path = $url.$file->get_filepath().$file->get_itemid().'/'.$filename;
                    echo html_writer::tag('img','',array('src'=>$path));   
                    if($admin){
                        $poster=$DB->get_record("user",array('id'=>$file->get_userid()));
                        $name = fullname($poster);
                    }else{
                        $name = fullname($USER);
                    }
                    $timeinput=date('d/m/Y', strtotime($banner->timeinput));
                    $displayuntil=date('d/m/Y', strtotime($banner->displayuntil));
                    echo "<br/>$name  {$timeinput}-{$displayuntil} <a href='{$FULLSCRIPT}?id={$blockid}&returnurl={$returnurl}&delete=" . $file->get_itemid() . "'>$deleteicon</a><br/>{$banner->url}<hr/>";
                }
            }
        }
    }
}else{
        print_string('nobanners','block_banners');
}

echo $OUTPUT->box_end();
echo $OUTPUT->footer();

function deletebanner($fs,$context,$delete){
    global $DB;
    $files = $fs->get_area_files($context, 'block_banners', 'content', $delete);
    foreach($files as $f){
       if($f->get_filesize()>0){
        $del = $f->get_id();
       }
       $f->delete();
    }
    if($del){
        $DB->delete_records('block_banners',array('fileid'=>$del));
    }
}

?>
