<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 5/22/15
 * Time: 1:30 PM
 * To change this template use File | Settings | File Templates.
 */
$capabilities = array(
    'block/oakland_group_list:addinstance' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    'block/oakland_group_list:myaddinstance' => array(
        'riskbitmask'  => RISK_PERSONAL,
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => array(
            'user' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
);