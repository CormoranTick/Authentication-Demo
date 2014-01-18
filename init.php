<?php
require_once('configuration.php');

$db = new MySQLi($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name']);
if ($db->connect_errno){
    if ($cfg['error_reporting']){
        die('Database connection ERROR: ('.$db->connect_errno.') '.$db->connect_error);
	} else {
		die('There was a problem connecting to the database!');
	}
}

$db->query("SET SESSION time_zone = 'America/Toronto'");

if (!$cfg['error_reporting']){
	error_reporting(0);
}

?>