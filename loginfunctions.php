<?php

function loggedin($cfg, $db){
	if (!isset($_COOKIE[$cfg['session_name']]) || empty($_COOKIE[$cfg['session_name']])){
		return array(false, false);
	}
	
	$osid = urldecode($_COOKIE[$cfg['session_name']]);
	
	if (!preg_match('/[a-zA-Z0-9]+:[0-9]+[rR]?/', $osid)){
		setcookie($cfg['session_name'], '', time()-1);
		return array(false, false);
	}
	
	list($sid, $id) = explode(':', $osid);
	
	if (preg_match('/[0-9]+[rR]/', $id)){
		$rememberme = true;
	} else {
		$rememberme = false;
	}
	
	preg_replace('/[^0-9]+/', '', $id);
	
	if (empty($id) || strlen($id) < 4 || !is_numeric($id)){
		setcookie($cfg['session_name'], '', time()-1);
		return array(false, false);
	}
	
	$key = substr($id, strlen($id)-3, 3);
	$uid = substr($id, 0, strlen($id)-3);
	
	$sql = 'SELECT `'.$cfg['fld_id'].'`,`'.$cfg['fld_username'].'`,`'.$cfg['fld_sid'];
	if (isset($cfg['session_hijack']) && $cfg['session_hijack']){
		$sql .= '`,`'.$cfg['fld_lastip'];
	}
	if (isset($cfg['disable_users']) && $cfg['disable_users']){
		$sql .= '`,`'.$cfg['fld_enabled'];
	}
	
	$sql .= '` FROM `'.$cfg['tbl_users'].'` WHERE `';
	$sql .= $cfg['fld_sid']."` = '".hash('SHA256', $cfg['salt'].$sid.$key)."'";
	if (isset($cfg['session_hijack']) && $cfg['session_hijack']){
		$sql .= ' AND `'.$cfg['fld_lastip']."` = '".$_SERVER['REMOTE_ADDR']."'";
	}
	if (isset($cfg['disable_users']) && $cfg['disable_users']){
		$sql .= ' AND `'.$cfg['fld_enabled'].'` = 1';
	}
	$sql .= ' AND `'.$cfg['fld_id'].'` = '.preg_replace('/[^0-9]+/', '', $uid);
	
	$res = $db->query($sql);
	if (!$res || $res->num_rows != 1){
		setcookie($cfg['session_name'], '', time()-1);
		return array(false, false);
	}
	
	$user = $res->fetch_assoc();
	
	$raw_session = $user['id'].$user['username'].$key;
	
	$nsid = md5($cfg['salt'].$raw_session);
	
	if ($nsid != $sid){
		setcookie($cfg['session_name'], '', time()-1);
		return array(false, false);
	}
	
	$sid .= ':'.$user['id'].$key;
	
	if ($rememberme){
		$cookietime = time()+999999999;
		$sid .= 'R';
	} else {
		$cookietime = time()+7200;
	}
	
	setcookie($cfg['session_name'], $sid, $cookietime);
	return array(true, $user);
}

?>