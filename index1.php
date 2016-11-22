<?php session_start();
require "database.php"?>
<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/style.css" />

</head>
<body>
	<?php include("header.php"); ?>
	<?php
		//print_r(getTable("login"));
		//echo $_SESSION['auth'].'<br>';
		//echo crypt("mark", $_SESSION['auth']);
		echo date('m.d.Y');
		if($id = getSessionId($_SESSION['auth'])){
			echo "Logged in as ".getNameFromId($id, True);
		} else {
			echo "<div>
				<a href=\"login.php\">Login</a>
				<a href=\"signup.php\">Signup</a>
			</div>";
		}
	?>
</body>
</html>
