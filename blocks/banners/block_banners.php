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
 * Form for editing banners block instances.
 *
 * @package   block_banners
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.banners GNU GPL v3 or later
 */

class block_banners extends block_base {

    function init() {
        global $PAGE;
        $this->title = get_string('pluginname', 'block_banners');
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : ''; 
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $PAGE,$CFG;
        require_once($CFG->libdir . '/filelib.php');
        
        if(!isset($this->config))$this->config = new stdClass();
        $this->config->height= (isset($this->config->height)) ? $this->config->height : 400;
        $this->config->width = (isset($this->config->width)) ? $this->config->width : 822;
        $this->config->maxday = (isset($this->config->maxday)) ? $this->config->maxday : 10;
        $this->config->permission = (isset($this->config->permission)) ? $this->config->permission : 1;
        $this->config->speed = (isset($this->config->speed)) ? $this->config->speed : 5;
        $this->config->title = (isset($this->config->title)) ? $this->config->title : '';

        if ($this->content !== NULL) {
            return $this->content;
        }
        
        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = $this->genBannerFrontend();
        return $this->content;
    }
    
    function genBannerFrontend(){
    	global $CFG,$DB,$PAGE,$COURSE,$FULLSCRIPT;
    	require_once(dirname(__FILE__).'/lib.php');
    	$return_url = $FULLSCRIPT;
 	           
        $context = context_block::instance($this->instance->id);
        $admin_context = context_course::instance($COURSE->id);  //get_context_instance(CONTEXT_COURSE, $COURSE->id);
        
        // everyone has permissions or is it a course editor?
        $can_add_banner = $this->config->permission>0 
                       || has_capability('moodle/block:edit', $context, $_SESSION['USER']->id, false) || has_capability('moodle/site:config', $admin_context);
        $addlink = '<a href="'.$CFG->wwwroot.'/blocks/banners/add.php?courseid='.$COURSE->id .'&id=' . $this->instance->id . '&returnurl=' . $return_url .'">
                    <img src="'.$CFG->wwwroot.'/blocks/banners/images/add.png" title="Manage Banner"></a>';                       
        
        if($file_arr = get_banner_images($this->context->id)){
            $h = $this->config->height;
            $w = $this->config->width;
            if($can_add_banner){
                $add='<span style="right:-20px;top:-22px;z-index:10;position:relative;float:right;"><a href="'.$CFG->wwwroot.'/blocks/banners/add.php?courseid=' . $COURSE->id . '&id=' . $this->instance->id . '&returnurl=' . $return_url .'">' . $addlink . '</span>';
            }
            $img_counter = 0;
            for($i=0;$i<sizeof($file_arr);$i++){
                if(bannerWithinShowPeriod($file_arr[$i][3],$this->config->maxday)){
                    $banner_url = getBannerURL($file_arr[$i][0]);
                    
                    $banner = '<div class="item';
                    if ($i == 0 ) { $banner .= ' active'; }
                    $banner .= '">';
                    if($banner_url!='')
                        $banner.='<a href="'.$banner_url.'">';
                    $banner.='<img class="img-responsive" src="'.$file_arr[$i][1].'" />';
                    if($banner_url!='')
                        $banner.='</a>';
                    $banner.='</div>';
                    $banners[]=$banner;
                    $img_counter++;
                }
            }

            $speed = $this->config->speed==''?5000:$this->config->speed*1000;
                
    	    $element_id = 'container_'.$this->instance->id;

            $html = '<div id="myCarousel" class="carousel slide">
                <ol class="carousel-indicators">';
            
            for ($x = 0; $x < $img_counter; $x++){
                $html .= '<li data-target="#myCarousel" data-slide-to="'.$x.'"';
                if ( $x == 0 ) { $html .= ' class="active"'; }
                $html .= '></li>';
            }


            $html .= '</ol>
                <!-- Carousel items -->
                <div class="carousel-inner">'.implode( '', $banners ).
                '</div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev"><img src="'.$CFG->wwwroot.'/blocks/banners/images/left.png" id="left_banner_'.$this->instance->id.'"></a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next"><img src="'.$CFG->wwwroot.'/blocks/banners/images/right.png" id="right_banner_'.$this->instance->id.'"></a>
  <a class="carousel-control pause" href="#myCarousel" data-slide="pause"><img src="'.$CFG->wwwroot.'/blocks/banners/images/pause.png" id="pause_banner_'.$this->instance->id.'"></a>';
  if ($can_add_banner) { 
    $html .= '<a class="carousel-control right addBanner" data-slide="add" href="'.$CFG->wwwroot.'/blocks/banners/add.php?courseid='.$COURSE->id .'&id=' . $this->instance->id . '&returnurl=' . $return_url .'">
                    <img src="'.$CFG->wwwroot.'/blocks/banners/images/add.png" title="Manage Banner"></a>'; 
  }
  
  $html .= '</div>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script>
    $("#myCarousel").carousel({
        interval: '.$speed.'
    });
    $(".pause").click( function(){
        $("#myCarousel").carousel("pause");
    });
</script>';


        }else{
           // no banners - just supply post link
            if($can_add_banner){
                 $html = '<div style="min-height:40px;margin:0px auto;padding:0px;overflow:hidden;">' 
                    . $addlink .'</div>';
            }else{
                $html = '';
            }
        }
        
    	return $html;
    }


    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        parent::instance_config_save($config, $nolongerused);
    }

    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_banners');
        return true;
    }

    public function instance_can_be_docked() {
    	return false;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }
}
