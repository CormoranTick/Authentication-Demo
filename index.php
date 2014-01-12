<?php
require_once('init.php');
require('loginfunctions.php');

list($loggedin, $user) = loggedin($cfg, $db);

if ($loggedin){
	echo "Login success! Session check success!<br>";
} else {
	header('Location:login.php');
	exit();
}


?>