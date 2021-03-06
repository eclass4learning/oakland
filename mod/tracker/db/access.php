<?php
//
// Capability definitions for the tracker module.
//
// The capabilities are loaded into the database table when the module is
// installed or updated. Whenever the capability definitions are updated,
// the module version number should be bumped up.
//
// The system has four possible values for a capability:
// CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).
//
//
// CAPABILITY NAMING CONVENTION
//
// It is important that capability names are unique. The naming convention
// for capabilities that are specific to modules and blocks is as follows:
//   [mod/block]/<component_name>:<capabilityname>
//
// component_name should be the same as the directory name of the mod or block.
//
// Core moodle capabilities are defined thus:
//    moodle/<capabilityclass>:<capabilityname>
//
// Examples: mod/forum:viewpost
//           block/recent_activity:view
//           moodle/site:deleteuser
//
// The variable name for the capability definitions array follows the format
//   $<componenttype>_<component_name>_capabilities
//
// For the core capabilities, the variable is $moodle_capabilities.


$capabilities = array(

    'mod/tracker:addinstance' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:seeissues' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:viewallissues' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:managepriority' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:viewpriority' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:managewatches' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:canbecced' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:resolve' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:report' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:develop' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:comment' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'mod/tracker:configure' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        )
    ),

    'mod/tracker:viewreports' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        )
    ),

    'mod/tracker:configurenetwork' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
        )
    ),

    'mod/tracker:shareelements' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
        )
    ),

);
