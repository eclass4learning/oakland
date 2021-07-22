<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/5/15
 * Time: 10:07 AM
 * To change this template use File | Settings | File Templates.
 */

defined('MOODLE_INTERNAL') or die();

require_once "$CFG->libdir/adminlib.php";

$configs   = array();

$configs[] = new admin_setting_configtext('block_forum/maxpostsshown', new lang_string('maxpostsshown', 'block_forum'),
    new lang_string('maxpostsshown', 'block_forum'), 10, PARAM_INT);

// Define the config plugin so it is saved to
// the config_plugin table then add to the settings page
foreach ($configs as $config) {
    $config->plugin = 'blocks/forum';
    $settings->add($config);
}