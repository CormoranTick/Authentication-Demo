<?php

require_once('init.php');
require('includes/functions.php');

list($loggedin, $user) = checksession($cfg, $db);

if (!$loggedin){
	header('Location:login.php');
	exit();
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        <title>Home</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <h1>Login Successful!</h1>
            <p class="text-center"><a href="logout.php">Logout</a></p>
        </div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>