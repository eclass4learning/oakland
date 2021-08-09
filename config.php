<?php  // Totara configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
# $CFG->dbhost    = '172.31.56.199';
$CFG->dbhost    = 'rds-pgsql-oakland.cjn38es2ruxd.us-west-2.rds.amazonaws.com';
$CFG->dbname    = 'miplace';
$CFG->dbuser    = 'miplace';
$CFG->dbpass    = '5QVdAfSc4RaX';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
);
# $CFG->wwwroot   = 'http://www.miplacek12.org';
$CFG->wwwroot   = 'https://oakland.eclass4learning.com';
$CFG->dataroot  = '/opt/data';
$CFG->admin     = 'admin';

#$CFG->reverseproxy = true;
$CFG->sslproxy = true;

$CFG->directorypermissions = 0777;

$CFG->debug = E_ALL | E_STRICT;
$CFG->debugdisplay = 1;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
