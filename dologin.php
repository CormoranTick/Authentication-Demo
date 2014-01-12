<?php
require_once('init.php');

if (!isset($_POST['username']) || empty($_POST['username'])){
	header('Location:login.php?loginfailed=1');
	exit();
}
	
$username = $_POST['username'];
	
if (!isset($_POST['password']) || empty($_POST['password'])){
	header('Location:login.php?loginfailed=1');
	exit();
}
	
$password = hash('SHA256', $_POST['password']);

$sql = 'SELECT `'.$fld_id.'`,`'.$fld_username;
$sql .= '` FROM `'.$tbl_users.'` WHERE `';
$sql .= $fld_username."` = '".$db->real_escape_string($username)."'";

$res = $db->query($sql);
if (!$res || $res->num_rows != 1){
	header('Location:login.php?loginfailed=1');
	exit();
}

$sql .= ' AND `'.$fld_password."` = '".$password."'";

$res = $db->query($sql);
if (!$res || $res->num_rows != 1){
	header('Location:login.php?loginfailed=1');
	exit();
}

if (isset($disable_users) && $disable_users){
	$sql .= ' AND `'.$fld_enabled.'` = 1';
}

$res = $db->query($sql);
if (!$res || $res->num_rows != 1){
	header('Location:login.php?loginfailed=1');
	exit();
}

$user = $res->fetch_assoc();

$key = rand(100,999);
$raw_session = $user['id'].$user['username'].$key;

$sid = md5($salt.$raw_session);

$sql = 'UPDATE `'.$tbl_users.'` SET `'.$fld_sid."` = '".hash('SHA256', $salt.$sid.$key)."'";
if (isset($session_hijack) && $session_hijack){
	$sql .= ', `'.$fld_lastip."` = '".$_SERVER['REMOTE_ADDR']."'";
}
$sql .= ' WHERE `'.$fld_id.'` = '.$user['id'];
$db->query($sql);
if ($db->errno != 0){
	header('Location:login.php?loginfailed=1');
	exit();
}

$sid .= ':'.$user['id'].$key;

setcookie($session_name, $sid, time()+7200);

header('Location:index.php');

?>