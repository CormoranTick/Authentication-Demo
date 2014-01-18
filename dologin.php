<?php
require_once('init.php');
require('includes/functions.php');

list($loggedin, $user) = checksession($cfg, $db);

if ($loggedin){
	header('Location:index.php');
	exit();
}

if (!isset($_POST['username']) || empty($_POST['username'])){
	header('Location:login.php?loginfailed=1');
	exit();
}	
$username = preg_replace('/[^a-zA-Z0-9\.-]/', '', $_POST['username']);
if(strlen($username) > 32){
	$username = substr($username, 0, 32);
}
	
if (!isset($_POST['password']) || empty($_POST['password'])){
	header('Location:login.php?loginfailed=1');
	exit();
}
$password = hash('SHA256', $cfg['salt'].$_POST['password']);

$sql = 'SELECT `id`,`username`,`firstname`,`lastname`,`position`,`enabled` ';
$sql .= 'FROM `users` ';
$sql .= "WHERE `username` = '".$db->real_escape_string($username)."' ";
$sql .= "AND `password` = '".$password."' ";
$sql .= 'AND `enabled` = 1';

$res = $db->query($sql);
if (!$res || $res->num_rows != 1){
	header('Location:login.php?loginfailed=1');
	exit();
}

$user = $res->fetch_assoc();

$key = rand(1000,9999);
$raw_sid = $user['id'].$user['username'].$key;
$pub_sid = md5($cfg['salt'].$raw_sid);
$sid = hash('SHA256', $cfg['salt'].$pub_sid.$key);

$sql = 'INSERT INTO `sessions` (`id`,`uid`,`lastactivity`,`ipaddress`) ';
$sql .= "VALUES ('".$sid."',".$user['id'].",NOW(),'".$_SERVER['REMOTE_ADDR']."')";

$db->query($sql);
if ($db->errno){
	header('Location:login.php?loginfailed=1');
	exit();
}

$pub_sid .= ':'.$user['id'].$key;
if (isset($_POST['remember-me'])){
	$pub_sid .= 'R';
	$expire_time = time()+31557600; // One year
} else {
	$expire_time = time()+7200; // Two hours
}

setcookie('SESSION', $pub_sid, $expire_time);

header('Location:index.php');
?>