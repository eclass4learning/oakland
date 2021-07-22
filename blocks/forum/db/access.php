<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/4/15
 * Time: 10:26 AM
 * To change this template use File | Settings | File Templates.
 */

$capabilities = array(
    'block/forum:addinstance' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    'block/forum:myaddinstance' => array(
        'riskbitmask'  => RISK_PERSONAL,
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => array(
            'user' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
);