<?php
require_once('init.php');
require('includes/functions.php');

list($loggedin, $user) = checksession($cfg, $db);

if ($loggedin){
	header('Location:index.php');
	exit();
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        <title>Please Login</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/login.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <form class="form-login" role="form" action="dologin.php" method="post">
            <h2 class="form-login-heading">Please login</h2>
<?php if (isset($_GET['loginfailed']) && $_GET['loginfailed'] == '1'){ ?>            <div class="alert alert-danger">
                <strong>Login Failed</strong> Please try again.
            </div>
<?php } ?>                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <label class="checkbox">
                    <input type="checkbox" name="remember-me" value="true"> Remember me
                </label>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
            </form>
        </div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>