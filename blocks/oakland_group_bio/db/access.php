<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/12/15
 * Time: 4:36 PM
 */

$capabilities = array(
    'block/oakland_group_bio:addinstance' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    'block/oakland_group_bio:myaddinstance' => array(
        'riskbitmask'  => RISK_PERSONAL,
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => array(
            'user' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
);