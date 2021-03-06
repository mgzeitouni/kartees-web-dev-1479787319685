<?php
error_reporting(0);
require_once("database.php");
session_start();

if( isset($_SESSION["auth"]) && $_SESSION["auth"] && $_GET['Logout'] ){
         unset( $_SESSION );
        session_destroy();
        deleteSessionId();
		header('Location: index.php');
    }
    

$userName = isset($_POST["user"]) ? $_POST["user"] : null;
        $userPass = isset($_POST["pass"]) ? $_POST["pass"] : null;
        if ($userName && $userPass ){
            $query = "SELECT * FROM login WHERE user = '$userName'";// AND password = $userPass";
            $result = run($query);
            if(!$result){
                echo "<div>";
                echo "No existing user";
                echo "</div>";
            }
            else {
                $hash = $result['pass'];
                $pass = crypt($userPass, $hash);
                $query = "SELECT ID FROM login WHERE user = '$userName' AND pass = '$pass'";// AND password = $userPass";
                $finalresult = run($query);
                if(!$finalresult){
                    echo "Wrong Password";
                } else {
                    $loggedIn = True;
                    putSessionId($finalresult['ID']);
                    header('Location: myaccount.php');
                }
            }
        }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Welcom to Kartees</title>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="shortcut icon" href="flat-ui/images/favicon.ico">
        <link rel="stylesheet" href="flat-ui/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="flat-ui/css/flat-ui.css">
        <!-- Using only with Flat-UI (free)-->
        <link rel="stylesheet" href="common-files/css/icon-font.css">
        <!-- end -->
        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
<?php
	//include("header.php");
?>
<div class="container-fluid">
  <div class="row">
<?php

    $loggedIn = false;

    if( isset($_SESSION["auth"]) && $_SESSION["auth"] && !$_GET['Logout'] )
    {

        if($id = getSessionId($_SESSION['auth'])){
			$loggedIn = true;
		} else {
			 $loggedIn = false;
		}
        echo "You are already logged in, ".getNameFromId($id)."!";
        echo "<a href='login.php?Logout=1'>Logout</a>";
        //unset( $_SESSION );
        //session_destroy();

        // *** The empty quotes do nothing
        // exit("");
        exit;
    }  


    if ( !$loggedIn )
    {
        
        
        echo '
                <div class="container">

      <form class="form-signin" action="login.php" method="post" class="login-screen" style="margin-top: 20%; width: 70%; margin-left: 15%;">
	  <div class="login-icon" style="display: inline-block; width: 10%; margin-right: 5%;left:0px; top:0px; position:relative">
            <img src="img/logo@2x.png" alt="Welcome to Mail App">
            <h4>Welcome back to <small>Kartees</small></h4>
          </div>
<div class="login-form" style="width: 80%; display: inline-block">
            <div class="form-group">
              <input type="text" name="user" class="form-control login-field" value="" placeholder="Enter your name" id="login-name">
              <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
              <input type="password" name="pass" class="form-control login-field" value="" placeholder="Password" id="login-pass">
              <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>

            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Login" />
            <a class="login-link" href="#">Lost your password?</a>
          </div>
      </form>

    </div>
            ';
    }
    else{
        echo "<div>";
        echo "You have been logged in as $userName!";
        echo "</div>";
        $_SESSION["name"] = $userName;
    }
    

?>
  </div></div>
</body>
</html>
