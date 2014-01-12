<?php

require_once('configuration.php');

foreach ($GLOBALS['GLOBALS'] as $key => $value){
	$cfg[$key] = $value;
}

$db = new MySQLi($db_host, $db_user, $db_pass, $db_name);
if ($db->connect_errno){
    if ($error_reporting){
        die('Database connection ERROR: ('.$db->connect_errno.') '.$db->connect_error);
	} else {
		die('There was a problem connecting to the database!');
	}
}

?>