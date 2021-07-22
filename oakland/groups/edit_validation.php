<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

function isValidGroupName() {
    global $DB;

    $name = $_GET['groupName'];

    $courses = $DB->get_records('course',array('shortname'=>$name));
    return empty($courses);
}

echo isValidGroupName();