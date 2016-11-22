<?php
error_reporting(0);
require "database.php";
session_start();

if( isset($_SESSION["auth"]) && $_SESSION["auth"] && $_GET['Logout'] ){
         unset( $_SESSION );
        session_destroy();
        deleteSessionId();
		header('Location: index.php');
}
    

if($_POST['user'] && $_POST['pass']){	
		 foreach($_POST as $key=>$value) {
			  if(empty($_POST[$key])) {
			  $message = "1";
			  break;
			  }
		 }
		 if($_POST['pass'] != $_POST['cpass']){ 
			  $message = "2"; 
		 }
			  
		 if(!isset($message)) {
				  if (!filter_var($_POST["user"], FILTER_VALIDATE_EMAIL)) {
						   $message = "3";
				  }
		 }
		 $fname = $_POST['name'];
		 $lname = $_POST['lname'];
		 $email = $_POST["user"];
		 $password = $_POST['pass'];
		 if(isset($message)){
				  header('Location: signup.php?message='.$message);
		 } else {
				  signup($fname, $lname, $email, $password);
		 }
}
	
	
function login($userName = null, $userPass = null){

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
}


	
function signup($userFName = null,$userLName = null,$userEmail = null, $userPass = null){

        if ($userEmail && $userPass){
            $query = "SELECT * FROM login WHERE user = '$userEmail'";// AND password = $userPass";
            $result = run($query);
			$userPassC = crypt($userPass);
            if(!$result){
                $query = "INSERT INTO login (Fname, Lname, user, pass)
						   VALUES ('$userFName', '$userLName', '$userEmail', '$userPassC')";
                $finalresult = run($query);
				login($userEmail, $userPass);
            }
            else {
                header("Location: signup.php?message=4");		
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
        
        if($_GET['message']){
				  switch($_GET['message']){
						   case "1":
									$message = "Please Fill Out All Fields";
									break;
						   case "2":
									$message = "Passwords Do Not Match";
									break;
						   case "3":
									$message = "Invalid Email";
									break;
						   case "4":
									$message = "User Exists";
									break;
									
				  }
		}
        echo '
                <div class="container">
      <form class="form-signin" action="signup.php" method="post" class="login-screen" name="signup" style="margin-top: 20%; width: 70%; margin-left: 15%;">
	  
	  <div class="login-icon" style="display: inline-block; width: 12%; margin-right: 5%;left:0px; top:0px; position:relative">
            <img src="img/logo@2x.png" alt="Welcome to Mail App">
            <h4>Welcome to <small>Kartees</small> We hope you\'ll love it!</h4>
          </div>
<div class="login-form" style="width: 80%; display: inline-block">';
		if($message) echo '<span style="border: 1px solid red; padding: 5px; display:block">'.$message.'</span>	';
		 echo ' <div class="form-group">
              <input type="name" name="name" class="form-control login-field" value="" placeholder="Name" id="login-name">
              <label class="login-field-icon fui-user" for="login-name"></label>
            </div>
			<div class="form-group">
              <input type="name" name="lname" class="form-control login-field" value="" placeholder="Last Name" id="login-lname">
              <label class="login-field-icon fui-user" for="login-lname"></label>
            </div>
            <div class="form-group">
              <input type="email" name="user" class="form-control login-field" value="" placeholder="Email" id="login-user">
              <label class="login-field-icon fui-user" for="login-user"></label>
            </div>

            <div class="form-group">
              <input type="password" name="pass" class="form-control login-field" value="" placeholder="Password" id="login-pass">
              <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>
			
			<div class="form-group">
              <input type="password" name="cpass" class="form-control login-field" value="" placeholder="Confirm Password" id="login-cpass">
              <label class="login-field-icon fui-lock" for="login-cpass"></label>
            </div>

            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Signup" />
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
