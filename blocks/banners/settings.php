<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 3/19/15
 * Time: 9:59 AM

 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configcheckbox('block_banners/deleteyearoldimages', get_string('deleteyearoldimages', 'block_banners'),
        get_string('deleteyearoldimages_desc', 'block_banners'), 1,0));

}