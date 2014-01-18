<?php

function checksession($cfg, $db){
	if (!isset($_COOKIE['SESSION']) || empty($_COOKIE['SESSION'])){
		return array(false, false);
	}
	$pub_sid = urldecode($_COOKIE['SESSION']);
	
	if (!preg_match('/[a-zA-Z0-9]+:[0-9]+[R]?/', $pub_sid)){
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	list($pub_sid, $id) = explode(':', $pub_sid);
	
	if (preg_match('/[0-9]+[R]/', $id)){
		$rememberme = true;
	} elseif (preg_match('/[0-9]+/', $id)){
		$rememberme = false;
	} else {
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	preg_replace('/[^0-9]+/', '', $id);
	
	if (empty($id) || strlen($id) < 5){
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	
	$key = substr($id, strlen($id)-4, 4);
	$uid = substr($id, 0, strlen($id)-4);
	
	$sid = hash('SHA256', $cfg['salt'].$pub_sid.$key);
	
	$sql = 'SELECT `id`,`uid`,`ipaddress` FROM `sessions` WHERE ';
	$sql .= "`id` = '".$sid."'";
	
	$res = $db->query($sql);
	if (!$res || $res->num_rows != 1){
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	
	$session = $res->fetch_assoc();
	
	if ($session['uid'] != $uid || $session['ipaddress'] != $_SERVER['REMOTE_ADDR']){
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	
	$sql = 'SELECT `id`,`username`,`firstname`,`lastname`,`position`,`enabled` ';
	$sql .= 'FROM `users` ';
	$sql .= 'WHERE `id` = '.$session['uid'].' ';
	$sql .= 'AND `enabled` = 1';
	
	$res = $db->query($sql);
	if (!$res || $res->num_rows != 1){
		setcookie('SESSION', '', time()-1);
		return array(false, false);
	}
	
	$user = $res->fetch_assoc();
	$user['sid'] = $session['id'];
	
	$raw_sid = $user['id'].$user['username'].$key;
	$pub_sid = md5($cfg['salt'].$raw_sid);
	
	$sql = 'UPDATE `sessions` SET ';
	$sql .= '`lastactivity` = NOW() ';
	$sql .= "WHERE `id` = '".$session['id']."'";
	
	$pub_sid .= ':'.$user['id'].$key;
	if ($rememberme){
		$pub_sid .= 'R';
		$expire_time = time()+31557600; // One year
	} else {
		$expire_time = time()+7200; // Two hours
	}
	
	setcookie('SESSION', $pub_sid, $expire_time);
	
	return array(true, $user);
}

?>