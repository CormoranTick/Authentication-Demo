<?php

$error_reporting = true; // Control PHP error reporting (Set to false for production environments)

// Database configuration values
$db_host = 'localhost';
$db_user = 'user';
$db_pass = 'pass';
$db_name = 'login_demo';

$tbl_users = 'users'; // Name of table containing user data

// Specific column names for required columns
//    - Leave blank if turning off functionality
$fld_id = 'id'; // Requires auto increment INT
$fld_username = 'username'; // Requires VARCHAR($max_username_length)
$fld_password = 'password'; // Requires VARCHAR(64)
$fld_sid = 'sid'; // Requires VARCHAR(64)
$fld_lastip = 'lastip'; // Requires VARCHAR(15) if $session_hijack is true
$fld_enabled = 'enabled'; // Requires BOOLEAN or TINYINT(1) if $disable_users is true

// Control functionality
$session_name = 'LGINDMO'; // Name assigned to PHP session and prefacing cookie names
$max_username_length = 32;
$session_hijack = true; // Store and check user's IP address to prevent session hijacking ($fld_lastip MUST BE SET IF TRUE)
$disable_users = true; // Support enabling and disabling user accounts ($fld_enabled MUST BE SET IF TRUE)

$salt = '43tERGherth45hgr4tEht54htrhTYREIJKTUhe6yhERTHW64hurthrtyujUJWRJKOLikikiuk';

?>