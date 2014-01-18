<?php
require_once('init.php');
require('includes/functions.php');

list($loggedin, $user) = checksession($cfg, $db);

if ($loggedin){
	setcookie('SESSION', '', time()-1);
	$sql = 'DELETE FROM `sessions` WHERE ';
	$sql .= "`id` = '".$user['sid']."'";
	$db->query($sql);
}
header('Location:login.php');
?>