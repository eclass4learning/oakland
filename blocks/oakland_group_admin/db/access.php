<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/18/15
 * Time: 9:47 AM
 * To change this template use File | Settings | File Templates.
 */
$capabilities = array(
    'block/oakland_group_admin:addinstance' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    'block/oakland_group_admin:myaddinstance' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => array(
            'user' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
    'block/oakland_group_admin:configuregroup' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:approvemembers' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:invitemembers' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:removemembers' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:delegatemembers' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:messagegroup' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:viewcourse' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
    'block/oakland_group_admin:removeself' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager'=> CAP_ALLOW,
        )
    ),
);
