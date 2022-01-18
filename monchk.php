<?php
    $db_handle = pg_connect("host=rds-pgsql-oakland.cjn38es2ruxd.us-west-2.rds.amazonaws.com dbname=miplace user=miplace password=5QVdAfSc4RaX");
    if ($db_handle) {
        echo 'check';
    } else {
        echo 'fail';
    }
    pg_close($db_handle);
?>
